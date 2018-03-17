<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TbBasketTbTouristservicesAddFieldIsDeleted extends Migration
{
    protected $table_basket = 'tb_touristservices';
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
            $table->tinyInteger('is_disabled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table_basket, function (Blueprint $table)
        {
            $table->dropColumn('is_disabled');
        });
    }
}
