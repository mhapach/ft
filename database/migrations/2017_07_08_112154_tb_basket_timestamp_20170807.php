<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TbBasketTimestamp20170807 extends Migration
{
    protected $table_basket = 'tb_basket';
    protected $table_basket_item = 'tb_basket_items';
    protected $table_basket_itemservices = 'tb_touristservices';
    protected $table_basket_itemtourists = 'tb_baskettourists';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Добавляем поля cteated_at И updated_at
        Schema::table($this->table_basket, function (Blueprint $table)
        {
            $table->timestamps();
        });
        Schema::table($this->table_basket_item, function (Blueprint $table)
        {
            $table->timestamps();
        });
        Schema::table($this->table_basket_itemservices, function (Blueprint $table)
        {
            $table->string('name', 1024)->nullable();
            $table->integer('nmen')->nullable();
            $table->integer('cnkey')->nullable();
            $table->integer('ctkey')->nullable();
            $table->timestamps();
        });
        Schema::table($this->table_basket_itemtourists, function (Blueprint $table)
        {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table_basket, function (Blueprint $table) {
            $table->dropTimestamps();
        });
        Schema::table($this->table_basket_item, function (Blueprint $table)
        {
            $table->dropTimestamps();
        });
        Schema::table($this->table_basket_itemservices, function (Blueprint $table)
        {
            $table->dropColumn('name');
            $table->dropColumn('nmen');
            $table->dropColumn('cnkey');
            $table->dropColumn('ctkey');
            $table->dropTimestamps();
        });
        Schema::table($this->table_basket_itemtourists, function (Blueprint $table)
        {
            $table->dropTimestamps();
        });
    }
}
