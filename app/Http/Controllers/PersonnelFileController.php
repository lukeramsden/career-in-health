<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf as PDFTk;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PersonnelFileController extends Controller
{
    private $filesForDeletion = [];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('only.employee');
    }

    public function __destruct()
    {
        // delete temp files
        foreach($this->filesForDeletion as $del)
            if(isset($del) && realpath($del))
                unlink($del);
    }

    public function view(Request $request) {
        return view('pdf.personnel', ['profile' => Auth::user()->profile, 'download' => false, 'embed' => $request->query('embed', false)]);
    }

    public function download()
    {
        $profile = Auth::user()->profile;

        // path to view-generated PDF
        $viewName = '/tmp/' . PDFtk::TMP_PREFIX . $profile->first_name . $profile->last_name . "_" . md5(time()) . '_personnel_file_view.pdf';
        array_push($this->filesForDeletion, $viewName);

        PDF
            ::loadView('pdf.personnel', ['profile' => $profile, 'download' => true, 'embed' => false])
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 0)
            ->save($viewName, true);

        // get array of files (keys are randomly generated)
        $certFilesArray = [];
        foreach ($profile->user->cv->certifications->toArray() as $certification)
        {
            switch (mime_content_type(storage_path('app/' . $certification['file']))) {
                case 'application/pdf':
                    $path = storage_path('app/' . $certification['file']);
                    break;
                case 'image/png':
                case 'image/jpeg':
                    $tmpPath = '/tmp/'.PDFtk::TMP_PREFIX.$profile->first_name.$profile->last_name.md5($certification['id'].time()).'.pdf';
                    PDF
                        ::loadView('pdf.image', ['src' => storage_path('app/' . $certification['file'])])
                        ->setPaper('a4')
                        ->setOrientation('portrait')
                        ->setOption('margin-top',0)
                        ->setOption('margin-bottom',0)
                        ->setOption('margin-left',0)
                        ->setOption('margin-right',0)
                        ->save($tmpPath);
                    array_push($this->filesForDeletion, $tmpPath);
                    $path = $tmpPath;
                    break;
                default:
                    continue 2;
            }

            $certFilesArray
                // PDFTk only accepts A-Z for some reason
                // MD5 hash id+time, uppercase, then only A-Z
            [preg_replace("/[^A-Z]+/", "", strtoupper(md5($certification['id'].time())))]
                = $path;
        }

        // create PDFTk object with view-generated PDF and cert files
        $appended = new PDFTk(array_merge(['V' => $viewName], $certFilesArray));

        // cat all files
        $appended->cat(null, null, 'V');
        foreach ($certFilesArray as $key => $file)
            $appended->cat(null, null, $key);

        // name of cat'd PDF file
        $appendedName = '/tmp/' . PDFtk::TMP_PREFIX . $profile->first_name . $profile->last_name . "_" . md5(time()) . '_personnel_file_appended.pdf';
        array_push($this->filesForDeletion, $appendedName);

        // Check for errors
        if (!$appended->saveAs($appendedName))
        {
            $error = $appended->getError();
            abort(500);
        }

        // pdf with logo in top right
        // overlayed on appended
        $stampPath = '/tmp/'.PDFtk::TMP_PREFIX.'stamp_'.md5(str_random().time()).'.pdf';
        PDF
            ::loadView('pdf.stamp')
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top',0)
            ->setOption('margin-bottom',0)
            ->setOption('margin-left',0)
            ->setOption('margin-right',0)
            ->save($stampPath);
        array_push($this->filesForDeletion, $stampPath);

        // name of stamped PDF file
        $stampedName = '/tmp/' . PDFtk::TMP_PREFIX . $profile->first_name . $profile->last_name . "_" . md5(time()) . '_personnel_file_stamped.pdf';
        array_push($this->filesForDeletion, $stampedName);

        $stamped = new PDFTk($appended);
        $stamped->stamp($stampPath);

        // Check for errors
        if (!$stamped->saveAs($stampedName))
        {
            $error = $stamped->getError();
            abort(500);
        }

        return $stamped->send($profile->first_name . $profile->last_name . '.pdf', false);
    }
}
