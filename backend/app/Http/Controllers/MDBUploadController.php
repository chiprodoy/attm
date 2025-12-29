<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\Process\Process;

class MDBUploadController extends Controller
{
    protected $requestTable = ['userinfo','user_of_run'];

    public function store(Request $request)
    {
        $request->validate([
            'mdb_file' => 'required|file|mimes:mdb|max:50240', // max 10MB
        ]);

        $path = $request->file('mdb_file')->store('mdb_uploads');

        foreach($this->requestTable as $k =>$v){
            // Jalankan command import
                $process = new Process([
                    'php',
                    base_path('artisan'),
                    'import:access',
                    storage_path('app/' . $path),
                    $request->table,
                ]);

                $process->start();
        }

        return response()->json([
            'success' => true,
            'message' => 'Upload berhasil! Proses import sedang berjalan.',
            'file' => $path,
        ]);
    }
    public function index(){
        return Inertia::render('MDBUpload/Index');
    }
}
