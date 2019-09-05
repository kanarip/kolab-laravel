<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user',
            function (Blueprint $table) {
                $table->bigInteger('uuid');
                $table->string('name', 128)->nullable();
                $table->string('login', 128)->nullable();
                $table->string('email');
                $table->string('locale', 2);
                $table->string('country', 3);
                $table->string('currency', 3);
                $table->string('timezone', 64);
                $table->string('password');
                $table->string('remember_token')->nullable();
                $table->datetimeTz('email_verified_at')->nullable();
                $table->timestamps();
            }
        );

        Schema::table(
            'user',
            function (Blueprint $table) {
                $table->primary('uuid');
                $table->unique('email');
                $table->unique('login');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
