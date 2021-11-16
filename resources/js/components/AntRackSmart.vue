<template>
    <div>
        <template v-if="shopifyProducts.length > 0">
            <ant-rack-smart-product
                v-for="(product, index) in shopifyProducts"
                :key="`shopify-${index}`"
                :upsell="upsell"
                :product="product"
                from="shopify"
            ></ant-rack-smart-product>
        </template>
        <template v-if="antProducts.length > 0">
            <ant-rack-smart-product
                v-for="(product, index) in antProducts"
                :key="`ant-${index}`"
                :upsell="upsell"
                :product="product"
                from="ant"
            ></ant-rack-smart-product>
        </template>
    </div>
</template>

<script>
import AntRackSmartProduct from "./AntRackSmartProduct.vue";
export default {
    components: { AntRackSmartProduct },
    props: ["upsell"],
    data() {
        return {
            takesProducts: [],
            antProducts: [],
            shopifyProducts: []
        };
    },
    mounted() {
        console.log("AntRackSmart upsell", this.upsell);
        var antRecommendations = JSON.parse(
            document.getElementById("smart-products").innerText
        );
        var antShopifyRecommendations = JSON.parse(
            document.getElementById("ant-shopify-recommendations").innerText
        );
        console.log("antRecommendations", antRecommendations);
        console.log("antShopifyRecommendations", antShopifyRecommendations);
        var leftCount = this.upsell.recommended_product_count;
        if (leftCount > 0) {
            // build shopifyProducts
            if (antShopifyRecommendations.length >= leftCount) {
                this.shopifyProducts = antShopifyRecommendations.slice(
                    0,
                    leftCount
                );
            } else if (antShopifyRecommendations.length > 0) {
                this.shopifyProducts = [...antShopifyRecommendations];
            }
            leftCount = leftCount - this.shopifyProducts.length;
            console.log("leftCount", leftCount);
            // build antProducts
            if (antRecommendations.length >= leftCount) {
                this.antProducts = antRecommendations.slice(0, leftCount);
            } else if (antRecommendations.length > 0) {
                this.antProducts = [...antRecommendations];
            }
        }
        console.log("antProducts length", this.antProducts.length);
        console.log("shopifyProducts length", this.shopifyProducts.length);
    }
};
</script>
