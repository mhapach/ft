<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientsAddFieldCLISONLINE extends Migration
{
    protected $table_basket = 'Clients';
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
            $table->timestamp('CREATED_AT')->nullable();
            $table->tinyInteger('CL_ISONLINE')->nullable();
            $table->index('CL_ISONLINE');
            $table->index('cl_mail');
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
            $table->dropIndex('clients_cl_isonline_index');
            $table->dropIndex('clients_cl_mail_index');
            $table->dropColumn('CL_ISONLINE');
            $table->dropColumn('CREATED_AT');
        });

    }
}
