<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('firstName', 45);
            $table->string('middleName', 45);
            $table->string('lastName', 45);
            $table->text('street')->nullable();
            $table->text('brgy')->nullable();
            $table->text('city');
            $table->string('contact', 30);
            $table->string('email', 45)->nullable();
            $table->string('card', 45)->nullable();
            $table->unique(['firstName', 'middleName','lastName']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
