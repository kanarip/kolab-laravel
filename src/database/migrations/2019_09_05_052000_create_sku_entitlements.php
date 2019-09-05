<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuEntitlements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'entitlement',
            function (Blueprint $table) {
                $table->string('uuid', 36);
                $table->bigInteger('user_uuid');
                $table->string('wallet_uuid', 36);
                $table->string('sku_uuid', 36);
                $table->string('description');
                $table->timestamps();
            }
        );

        Schema::create(
            'sku',
            function (Blueprint $table) {
                $table->string('uuid', 36);
                $table->string('title', 64);
                $table->text('description');
                $table->decimal('cost', 8, 2);
                $table->timestamps();
            }
        );

        Schema::table(
            'entitlement',
            function (Blueprint $table) {
                $table->primary('uuid');
            }
        );

        Schema::table(
            'sku',
            function (Blueprint $table) {
                $table->primary('uuid');
            }
        );

        Schema::table(
            'entitlement',
            function (Blueprint $table) {
                $table->foreign('sku_uuid')
                    ->references('uuid')->on('sku')
                    ->onDelete('cascade');

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
        // TODO: drop foreign keys first
        Schema::dropIfExists('entitlement');
        Schema::dropIfExists('sku');
    }
}
