<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WalletControllers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_accounts',
            function (Blueprint $table) {
                $table->bigIncrements('uuid');
                $table->bigInteger('user_uuid');
                $table->string('wallet_uuid', 36);
                $table->timestamps();
            }
        );

        Schema::table(
            'user_accounts',
            function (Blueprint $table) {
                $table->unique(['user_uuid', 'wallet_uuid']);

                $table->foreign('user_uuid')
                    ->references('uuid')->on('user')
                    ->onDelete('cascade');

                $table->foreign('wallet_uuid')
                    ->references('uuid')->on('wallet')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('user_accounts');
    }
}
