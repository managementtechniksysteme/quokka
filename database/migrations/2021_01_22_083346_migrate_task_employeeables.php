<?php

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateTaskEmployeeables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        $taskEmployees = DB::table('employee_task')->get();

        foreach ($taskEmployees as $taskEmployee) {
            DB::table('employeeables')->insert([
                'employee_id' => $taskEmployee->employee_id,
                'employeeable_id' => $taskEmployee->task_id,
                'employeeable_type' => Task::class,
                'employee_type' => 'involved',
                'created_at' => $taskEmployee->created_at,
                'updated_at' => $taskEmployee->updated_at,
            ]);
        }

        Schema::dropIfExists('employee_task');

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('employeeables')
            ->where('employeeable_type', Task::class)
            ->where('employeea_type', 'involved')
            ->delete();
    }
}
