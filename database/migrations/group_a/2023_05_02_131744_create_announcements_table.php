<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->text('summary')->nullable();
			$table->longText('description')->nullable();
			$table->UnsignedBiginteger('company_id')->nullable();
			$table->UnsignedBiginteger('department_id')->nullable();
			$table->string('added_by',40)->nullable();
			$table->tinyInteger('is_notify')->nullable();

			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
			$table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

			$table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign('announcements_company_id_foreign');
            $table->dropForeign('announcements_department_id_foreign');
            $table->dropIfExists('announcements');
        });
    }
}
