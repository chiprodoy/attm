<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceApiTest extends TestCase
{
     /**
     * Test the /api/late endpoint returns successful response.
     */
    public function test_late_endpoint_with_start_date_parameter_returns_data_successfully()
    {
        $paramDate='2025-05-25';
        $response = $this->getJson('/api/late/?start_date='.$paramDate);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => [
                             'USERID',
                             'checklog_time',
                             'shift_in',
                             'shift_out',
                             'departement_name',
                             'employee'=>['Name']
                         ]
                     ]
                 ]);
    }

    /**
     * Test the /api/late endpoint returns successful response.
     */
    public function test_late_endpoint_returns_data_successfully()
    {
        $response = $this->getJson('/api/late');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                        '*' => [
                             'USERID',
                             'checklog_time',
                             'shift_in',
                             'shift_out',
                             'departement_name',
                             'employee'=>['Name']
                         ]
                     ]
                 ]);
    }

    /**
     * Test the /api/early endpoint returns successful response.
     */
    public function test_early_endpoint_returns_data_successfully()
    {
        $response = $this->getJson('/api/early');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => [
                             'nip',
                             'name',
                             'dept',
                             'company',
                             'time'
                         ]
                     ]
                 ]);
    }

    /**
     * Test invalid method returns 405 for late endpoint.
     */
    public function test_post_to_late_returns_method_not_allowed()
    {
        $response = $this->postJson('/api/late');
        $response->assertStatus(405);
    }

    /**
     * Test invalid method returns 405 for early endpoint.
     */
    public function test_post_to_early_returns_method_not_allowed()
    {
        $response = $this->postJson('/api/early');
        $response->assertStatus(405);
    }
}
