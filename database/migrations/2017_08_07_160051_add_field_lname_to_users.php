<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldLnameToUsers extends Migration
{
    protected $table_basket = 'users';

    public function up()
    {
        //Добавляем поля
        Schema::table($this->table_basket, function (Blueprint $table)
        {
            $table->string('lname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Удаляем поля
        Schema::table($this->table_basket, function (Blueprint $table)
        {
            $table->dropColumn('lname');
        });
    }
}
