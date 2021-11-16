<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpsellRockPriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsell_rock_price_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shopify_id')->default(0);
            $table->string('title');
            $table->string('value_type');
            $table->string('value');
            $table->string('target_type');
            $table->text('target_selection');
            $table->text('customer_selection');
            $table->string('starts_at');
            $table->text('shopify_response')->nullable();
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
        Schema::dropIfExists('upsell_rock_price_rules');
    }
}
