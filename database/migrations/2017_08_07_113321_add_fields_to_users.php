<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsers extends Migration
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
            $table->dateTime('birth_date')->nullable();
            $table->dateTime('passport_issue')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('passport')->nullable();
            $table->tinyInteger('is_mailing_agree')->nullable();
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
            $table->dropColumn('birth_date');
            $table->dropColumn('passport_issue');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('passport');
            $table->dropColumn('is_mailing_agree');
        });
    }
}
