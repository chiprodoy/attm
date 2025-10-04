<?php

use App\Models\CheckLogStatus;
use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_check_log_statuses', function (Blueprint $table) {
            $table->id();
            $table->dateTime('checklog_date');
            $table->foreignIdFor(Employee::class);
            $table->string('checklog_status'); // App\Models\CheckLogStatus
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_check_log_statuses');
    }
};
