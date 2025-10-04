<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MCUController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Mcu/Index');
    }

    public function search_employee(Request $request)
    {
        $term = $request->get('term');

        $employee = Employee::where('SSN', $term)
                    ->orWhere('Name', 'like', "%{$term}%")
                    ->first();

        $hasMcu = DB::table('mcu')
                    ->where('USERID',$employee->USERID)
                    ->whereDate('mcu_date',Carbon::now()->toDateString())
                    ->exists();


        if (!$employee) {
            return response()->json(['employee' => null]);
        }

        return response()->json([
            'employee' => [
                'id'=>$employee->USERID,
                'nip' => $employee->SSN ?? '-',
                'name' => $employee->Name,
                'department' => null,
                'company' => null,
                'mcu_status' => $hasMcu
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $qry = DB::table('mcu')
                    ->where('USERID',$request->id)
                    ->whereDate('mcu_date',Carbon::now()->toDateString());
        $hasMCU = $qry->exists();

        if($hasMCU){
            $qry->update(['mcu_date'=>Carbon::now()->toDateTimeString()]);
        }else{
            DB::table('mcu')
                ->insert(['USERID'=>$request->id,'mcu_date'=>Carbon::now()->toDateTimeString()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
