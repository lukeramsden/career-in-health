<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:employee')->except('show');
    }

    protected function rules()
    {
        return [
            'first_name'    => 'required|string',
            'last_name'     => 'nullable|string',
            'avatar'        => 'nullable|image|max:1024|dimensions:max_width=600,max_height=600,ratio=1|mimes:jpg,jpeg,png',
            'remove_avatar' => 'nullable|boolean',
            'location_id'   => 'required|integer|exists:locations,id',
            'about'         => 'nullable|string|max:500',
        ];
    }

    public function show(Employee $employee)
    {
        return view('employee.profile.show')
            ->with([
                'employee' => $employee,
                'self' => false,
            ]);
    }

    public function showMe()
    {
        return view('employee.profile.show')
            ->with([
                'employee' => Auth::user()->userable,
                'self' => true,
            ]);
    }

    public function edit()
    {
        return view('employee.profile.edit')
            ->with([
                'employee' => Auth::user()->userable,
            ]);
    }

    public function update()
    {
        $data = $this->request->validate(self::rules());
        $employee = Auth::user()->userable;

        if(isset($data['remove_avatar']) && $data['remove_avatar'])
        {
            Storage::delete($employee->avatar);
            $employee->avatar = null;
        } else if($this->request->hasFile('avatar'))
        {
            $path = $this->request->file('avatar')->storePublicly('avatars');
            Storage::delete($employee->avatar);
            $employee->avatar = $path;
        }

        $employee->fill($data);
        $employee->save();

        if(ajax())
            return response()->json(['success' => true, 'model' => $employee], 200);

        toast()->success('Profile updated!');
        return back();
    }
}
