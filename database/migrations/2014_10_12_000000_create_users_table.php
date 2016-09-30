<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['superadmin', 'admin', 'staff', 'user'])->default('user');
                $table->unsignedInteger('in_department')->nullable();
                $table->rememberToken();
                $table->timestamps();

                $table->index('in_department');
                $table->foreign('in_department')->references('id')->on('departments')->onDelete('SET NULL')->onUpdate('SET NULL');
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
        Schema::drop('users');
    }
}
