<?php

namespace App\Http\Controllers;

use App\Certification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee')->except('download');
    }

    static $validation = [
        'name' => 'required|string|max:80'
    ];

    static $file_validation = [
        'file' => 'required|file|'
            . '' ///TODO: regulate file types
            . '|max:10240' ///TODO: determine a more specific and less arbitrary max file size
    ];

    public function edit()
    {
        return view('profile.certifications.edit')
            ->with([
                'profile' => Auth::user()->profile,
                'isCvBuilder' => false,
            ]);
    }

    public function edit_single(Certification $certification)
    {
        return view('profile.certifications.edit_single')
            ->with([
                'certification' => $certification,
                'isCvBuilder' => false,
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(array_merge($this::$validation, $this::$file_validation));

        $profile = Auth::user()->profile;
        $path = $request->file('file')->storePublicly('certifications');
        $certification = new Certification();
        $certification->file_path = $path;
        $certification->fill($data);
        $profile->certifications()->save($certification);

        return back()
            ->with([
                'status' => 'Created!'
            ]);
    }

    public function update(Request $request, Certification $certification)
    {
        $data = $request->validate($this::$validation);

        $certification->fill($data);
        $certification->save();

        if($request->query('isCvBuilder', false))
        {
            return redirect(route('cv-builder.certifications'))
                ->with([
                    'status' => 'Updated!'
                ]);
        }

        return redirect(route('profile.certifications.edit'))
            ->with([
                'status' => 'Updated!'
            ]);
    }

    public function destroy(Certification $certification)
    {
        try {
            Storage::delete($certification->file_path);
            $certification->delete();
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with([
                    'status' => 'Could not delete!'
                ]);
        }

        return back()
            ->with([
                'status' => 'Deleted!'
            ]);
    }

    public function download(Certification $certification)
    {
        return $certification->download_resp();
    }
}
