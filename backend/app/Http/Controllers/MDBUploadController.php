<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Inertia\Inertia;

class MDBUploadController extends Controller
{
    public function create(){
        return Inertia::render('MDBUpload/import');

    }
    //
    public function store(Request $request)
    {
        $request->validate([
            'mdb_file' => 'required|file|mimes:mdb|max:10240', // max 10MB
            'table' => 'required|string',
        ]);

        $path = $request->file('mdb_file')->store('mdb_uploads');

        // Jalankan command import
        $process = new Process([
            'php',
            base_path('artisan'),
            'import:access',
            storage_path('app/' . $path),
            'userinfo',
        ]);

        $process->start();

        $process = new Process([
            'php',
            base_path('artisan'),
            'import:access',
            storage_path('app/' . $path),
            'user_of_run',
        ]);

        $process->start();


        return response()->json([
            'success' => true,
            'message' => 'Upload berhasil! Proses import sedang berjalan.',
            'file' => $path,
        ]);
    }
}
