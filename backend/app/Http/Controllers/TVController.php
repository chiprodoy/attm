<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TVController extends Controller
{
    //
        public function index()
    {
        // Data dummy bisa diganti dengan dari DB
        $lateEmployees = [
            ['nip' => '198721', 'name' => 'Andi Saputra', 'dept' => 'IT Support', 'company' => 'PERTAMARINE', 'time' => '08:23'],
            ['nip' => '199882', 'name' => 'Siti Nurhaliza', 'dept' => 'Logistik', 'company' => 'PERTAMARINE', 'time' => '08:32'],
            ['nip' => '197003', 'name' => 'Bambang S', 'dept' => 'Operasional', 'company' => 'PERTAMARINE', 'time' => '08:27'],
        ];

        $earlyEmployees = [
            ['nip' => '198331', 'name' => 'Rina Amelia', 'dept' => 'HRD', 'company' => 'PERTAMARINE', 'time' => '15:25'],
            ['nip' => '199112', 'name' => 'Yusuf Maulana', 'dept' => 'Maintenance', 'company' => 'PERTAMARINE', 'time' => '15:18'],
        ];

        $checkingEmployees = [
            ['nip' => '198712', 'name' => 'Dian Aulia', 'dept' => 'Keuangan', 'company' => 'PERTAMARINE', 'time' => '08:15'],
        ];

        return Inertia::render('TV/Index', [
            'lateEmployees' => $lateEmployees,
            'earlyEmployees' => $earlyEmployees,
            'checkingEmployees' => $checkingEmployees
        ]);
    }
}
