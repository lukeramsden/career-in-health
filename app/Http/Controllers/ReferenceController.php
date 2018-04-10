<?php

namespace App\Http\Controllers;

use App\Reference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    static $validation = [
        'person_name' => 'required|string|max:80',
        'person_company' => 'required|string|max:80',
        'person_relation' => 'required|string|max:80',
        'person_contact' => 'required|string|max:255',
        'work_id' => 'nullable|exists:profile_work_experiences,id'
    ];

    public function edit()
    {
        return view('profile.references.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'isCvBuilder' => false,
            ]);
    }

    public function edit_single(Reference $reference)
    {
        return view('profile.references.edit_single')
            ->with([
                'reference' => $reference,
                'isCvBuilder' => false,
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this::$validation);

        $reference = new Reference();
        $reference->fill($data);
        Auth::user()->profile->references()->save($reference);

        toast()->success('Created!');
        return back();
    }

    public function update(Request $request, Reference $reference)
    {
        $data = $request->validate($this::$validation);

        $reference->fill($data);
        $reference->save();

        if($request->query('isCvBuilder', false))
        {
            toast()->success('Updated!');
            return redirect(route('cv-builder.references'));
        }

        toast()->success('Updated!');
        return redirect(route('profile.references.edit'));
    }

    public function destroy(Reference $reference)
    {
        try {
            $reference->delete();
        } catch (Exception $e) {
            toast()->error('Could not delete!');
            return back()
                ->withInput();
        }

        toast()->success('Deleted!');
        return back();
    }
}
