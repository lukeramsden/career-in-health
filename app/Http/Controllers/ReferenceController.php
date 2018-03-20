<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{
    static $validation = [
        'person_name' => 'required|string|max:80',
        'person_company' => 'required|string|max:80',
        'person_relation' => 'required|string|max:80',
        'person_contact' => 'required|string|max:255',
        'work_id' => 'nullable|exists:profile_work_experiences,id'
    ];

    public function edit()
    {
        return view('references_edit')
            ->with([
                'profile' => Auth::user()->profile,
                'is_cvbuilder' => false,
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this::$validation);

        $reference = new Reference();
        $reference->fill($data);
        Auth::user()->profile->references()->save($reference);

        return back()
            ->with([
                'status' => 'Created!'
            ]);
    }

    public function update(Request $request, Reference $reference)
    {
        $data = $request->validate($this::$validation);

        $reference->fill($data);
        $reference->save();

        return back()
            ->with([
                'status' => 'Updated!'
            ]);
    }

    public function destroy(Reference $reference)
    {
        try {
            $reference->delete();
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
}