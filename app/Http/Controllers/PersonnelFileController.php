<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mikehaertl\pdftk\Pdf as PDFTk;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PersonnelFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    public function view(Request $request) {
        return view('pdf.personnel', ['profile' => Auth::user()->profile, 'download' => false, 'embed' => $request->query('embed', false)]);
    }

    public function download()
    {
        try
        {
            $profile = Auth::user()->profile;

            // path to view-generated PDF
            $viewName = '/tmp/' . PDFtk::TMP_PREFIX . $profile->first_name . $profile->last_name . "_" . md5(time()) . '_personnel_file_view.pdf';

            PDF::loadView('pdf.personnel', ['profile' => $profile, 'download' => true, 'embed' => false])
                ->setPaper('a4')
                ->setOrientation('portrait')
                ->setOption('margin-bottom', 0)
                ->save($viewName, true);

            // get array of files (keys are randomly generated)
            $certFilesArray = [];
            foreach ($profile->user->cv->certifications->toArray() as $certification)
                $certFilesArray
                    // PDFTk only accpets A-Z for some reason
                    // MD5 hash id+time, uppercase, then only A-Z
                    [preg_replace("/[^A-Z]+/", "", strtoupper(md5($certification['id'].time())))]
                    = storage_path('app/' . $certification['file']);

            // create PDFTk object with view-generated PDF and cert files
            $appended = new PDFTk(array_merge(['V' => $viewName], $certFilesArray));

            // cat all files
            $appended->cat(null, null, 'V');
            foreach ($certFilesArray as $key => $file)
                $appended->cat(null, null, $key);

            // name of cat'd PDF file
            $appendedName = '/tmp/' . PDFtk::TMP_PREFIX . $profile->first_name . $profile->last_name . "_" . md5(time()) . '_personnel_file_appended.pdf';

            // Check for errors
            if (!$appended->saveAs($appendedName))
            {
                $error = $appended->getError();
                return response($error, 500)->header('Content-Type', 'text/plain');
            }

            $appended->send($profile->first_name . $profile->last_name . '.pdf', true);
        } finally {
            // delete temp files
            if(isset($viewName) && realpath($viewName))
                unlink($viewName);

            if(isset($appendedName) && realpath($appendedName))
                unlink($appendedName);
        }
    }
}
