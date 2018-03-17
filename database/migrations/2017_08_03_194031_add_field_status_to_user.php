<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStatusToUser extends Migration
{
    protected $table_basket = 'users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Добавляем поля
        Schema::table($this->table_basket, function (Blueprint $table)
        {
            $table->tinyInteger('status')->nullable();
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
            $table->dropColumn('status');
        });
    }
}
