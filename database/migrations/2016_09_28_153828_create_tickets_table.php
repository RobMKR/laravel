<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->string('name', 255);
                $table->text('description');
                $table->unsignedInteger('department_id');
                $table->unsignedInteger('user_id');
                $table->string('status', 255);
                $table->timestamps();

                $table->index('department_id');
                $table->index('user_id');
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
