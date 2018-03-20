<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificationController extends Controller
{
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
        return view('certifications_edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => false,
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
        Auth::user()->profile->certifications()->save($certification);

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

        return back()
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