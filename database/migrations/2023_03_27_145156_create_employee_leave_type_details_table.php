<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveTypeDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_leave_type_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->longText('leave_type_detail')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('employee_leave_type_details', function (Blueprint $table) {
            $table->dropForeign('employee_leave_type_details_employee_id_foreign');
            $table->dropIfExists('employee_leave_type_details');
        });
        // Schema::dropIfExists('employee_leave_type_details');
    }
}
