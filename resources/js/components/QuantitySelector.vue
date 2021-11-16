<template>
    <imask-input
        v-model="numberModel"
        :mask="Number"
        radix="."
        :unmask="false"
        @accept="onAccept"
        placeholder="Enter number here"
    />
</template>

<script>
import { IMaskComponent } from "vue-imask";
export default {
    components: {
        "imask-input": IMaskComponent
    },
    props: ["quantity"],
    data() {
        return {
            numberModel: this.quantity + ""
        };
    },
    methods: {
        onAccept(value) {
            console.log(value);
            this.$emit("update:quantity");
        }
    }
};
</script>
