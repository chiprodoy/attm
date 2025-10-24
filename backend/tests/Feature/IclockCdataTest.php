<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class IclockCdataTest extends TestCase
{
    // Kalau ingin database reset setiap test, aktifkan ini:
    // use RefreshDatabase;

    /** @test */
    public function it_receives_attlog_data_successfully()
    {
        // Simulasi payload multipart/form-data seperti dari mesin presensi
        $payload = [
            'SN' => '6427150500086',
            'table' => 'ATTLOG',
            'Stamp' => '9999',
            'data' => "35\t2025-10-23 07:03:00\t0\t1\t0\t0\t0\n186\t2025-10-23 07:03:17\t0\t1\t0\t0\t0\n80\t2025-10-23 07:04:04\t0\t1\t0\t0\t0",
        ];

        // Kirim POST request ke endpoint
        $response = $this->post('/iclock/cdata', $payload);
        // Pastikan respon sukses
        $response->assertStatus(200);

        // Pastikan ada kata "OK" dalam respon
        $response->assertSeeText('OK');

        // // Opsi tambahan: pastikan data log masuk ke database
        // $this->assertDatabaseHas('finger_log', [
        //     'url' => json_encode([
        //         'SN' => '6427150500086',
        //         'table' => 'ATTLOG',
        //         'Stamp' => '9999',
        //     ]),
        // ]);
    }
}
