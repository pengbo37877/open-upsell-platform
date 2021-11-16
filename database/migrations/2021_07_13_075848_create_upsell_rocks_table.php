<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpsellRocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsell_rocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // 需要upsell的product (在app中设置)
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('display_for_type')->default('all-products');
            $table->unsignedBigInteger('shopify_product_id')->nullable()->comment('upsell的product');
            $table->unsignedBigInteger('shopify_product_variant_id')->nullable()->comment('upsell的product_variant');
            $table->string('shopify_product_handle')->nullable()->comment('upsell商品handle');
            $table->text('shopify_product_variants')->nullable()->comment('upsell商品variants');
            // for app created products(service)
            $table->string('icon')->nullable();
            $table->string('product_name')->nullable()->comment('显示在购物车里的名称');
            $table->string('headline')->nullable()->comment('显示在popup中的名称');
            $table->string('description')->nullable()->comment('用户点击custom service的headline/icon时显示的文案');
            $table->unsignedInteger('price')->nullable()->comment('服务的价格');
            // settings
            $table->boolean('active')->default(true)->comment('是否启用');
            $table->unsignedInteger('order')->default(5)->comment('upsell默认显示的顺序');
            $table->string('triggered_on')->default('add-to-cart')->comment('触发动作');
            $table->string('short_description')->default('')->comment('用户添加的简短说明');
            $table->boolean('show_note_field')->default(false)->comment('给买家填写的输入框');
            $table->boolean('hide_upsell_product_already_in_cart')->default(false)->comment('字面意思');
            $table->boolean('match_parent_quantity')->default(true)->comment('true:和parent产品数量一致');
            $table->boolean('enable_quantity_selector')->default(true)->comment('数量选择器');
            $table->string('price_type')->default('all')->comment('all, range');
            $table->string('minimum_price')->nullable()->comment('when price_type is range,当商品价格大于该值显示upsell');
            $table->string('maximum_price')->nullable()->comment('when price_type is range,当商品价格小于该值显示upsell');
            $table->boolean('apply_discount')->default(true)->comment('是否使用打折券');
            $table->string('amount_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('discount_code')->nullable();
            $table->unsignedBigInteger('shopify_price_rule_id')->nullable();
            $table->boolean('is_upgrade')->default(false)->comment('Remove parent product when upsell product is added');
            $table->boolean('remove_upsell_product_when_parent_product_is_removed')->default(false)->comment('和用户添加的商品一起删除');
            $table->boolean('remove_parent_product_when_upsell_product_is_added')->default(false)->comment('upgrade');
            $table->boolean('use_recommended_products')->default(false)->comment('是否使用shopify推荐的商品');
            $table->unsignedInteger('recommended_product_count')->default(1)->comment('使用shopify推荐的商品数量');

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
        Schema::dropIfExists('upsell_rocks');
    }
}
