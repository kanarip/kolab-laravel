<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'wallet',
            function (Blueprint $table) {
                $table->string('uuid', 36);
                $table->string('description', 128)->nullable();
                $table->string('currency', 4);
                $table->decimal('balance', 8, 2);
                $table->bigInteger('user_uuid');

                $table->primary('uuid');
                $table->index('user_uuid');
            }
        );

        Schema::table(
            'wallet',
            function (Blueprint $table) {
                $table->foreign('user_uuid')->references('uuid')->on('user');
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
        Schema::dropIfExists('wallet');
    }
}
