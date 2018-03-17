<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblDogovorAddFieldDGCLIENTKEY extends Migration
{
    protected $table_basket = 'tbl_dogovor';
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
            $table->integer('DG_CLIENTKEY')->nullable();
            $table->index('DG_CLIENTKEY');
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
            $table->dropIndex('tbl_dogovor_dg_clientkey_index');
            $table->dropColumn('DG_CLIENTKEY');
        });

    }
}
