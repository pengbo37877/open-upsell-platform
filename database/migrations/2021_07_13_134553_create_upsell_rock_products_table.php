<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpsellRockProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsell_rock_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shopify_product_id')->nullable();
            $table->unsignedBigInteger('shopify_collection_id')->nullabel();
            $table->text('shopify_product_variants')->nullabel();
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('handle')->nullable();
            $table->text('image')->nullable();
            $table->text('shopify_response')->nullable();
            $table->unsignedInteger('times')->default(0);
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
        Schema::dropIfExists('upsell_rock_products');
    }
}
