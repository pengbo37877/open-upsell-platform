<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpsellRockSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsell_rock_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->default('Get extras for your product');
            $table->string('add_to_cart')->default('Add');
            $table->string('added_to_cart')->default('Added');
            $table->string('upgrade')->default('Upgrade');
            $table->string('upgraded')->default('Upgraded');
            $table->string('proceed_to_cart')->default('Continue');
            $table->string('back')->default('Back');
            $table->string('cart_discount_note')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->unsignedInteger('max_popup_session_views')->default(0);
            $table->string('continue_action')->nullable();
            $table->string('close_action')->nullable();
            $table->string('primary_color')->default('#333333');
            $table->string('layout')->default('layout1');
            $table->boolean('auto_conversion')->default(false);
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
        Schema::dropIfExists('upsell_rock_settings');
    }
}
