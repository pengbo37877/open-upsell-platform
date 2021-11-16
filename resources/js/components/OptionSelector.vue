<template>
    <div
        class="relative w-full sm:w-auto"
        v-if="variants.length > 1"
        v-on-clickaway="away"
    >
        <button
            type="button"
            :class="[
                disableOptions
                    ? 'bg-gray-100 text-gray-500'
                    : 'bg-white text-gray-800'
            ]"
            class="relative w-full border border-gray-300 shadow-sm pl-[12px] pr-[40px] py-[8px] text-left cursor-default sm:text-[14px] sm:leading-[20px]"
            aria-haspopup="listbox"
            aria-expanded="true"
            aria-labelledby="listbox-label"
            :disabled="disableOptions"
            @click="btnClick"
        >
            <span class="flex items-center">
                <span class="ml-[12px] block truncate">
                    {{ selectedVariant.title }}
                </span>
            </span>
            <span
                class="ml-[12px] absolute inset-y-0 right-0 flex items-center pr-[8px] pointer-events-none"
            >
                <!-- Heroicon name: solid/selector -->
                <svg
                    class="h-[20px] w-[20px] text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </span>
        </button>

        <!--
      Select popover, show/hide based on select state.

      Entering: ""
        From: ""
        To: ""
      Leaving: "transition ease-in duration-100"
        From: "opacity-100"
        To: "opacity-0"
    -->
        <ul
            class="absolute z-10 mt-[4px] w-full bg-white shadow-lg max-h-[224px] py-[4px] text-[16px] leading-[24px] ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-[14px] sm:leading-[20px]"
            tabindex="-1"
            role="listbox"
            aria-labelledby="listbox-label"
            aria-activedescendant="listbox-option-3"
            v-if="show"
        >
            <!--
        Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

        Highlighted: "text-white bg-indigo-600", Not Highlighted: "text-gray-900"
      -->
            <li
                class="
                    text-gray-700 cursor-default select-none relative py-[8px] pl-[12px] pr-[36px]
                "
                id="listbox-option-0"
                role="option"
                v-for="(variant, index) in variants"
                :key="index"
                @click="variantChanged(variant)"
            >
                <div class="flex items-center">
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <span class="font-normal ml-[12px] block truncate">
                        {{ variant.title }}
                    </span>
                </div>

                <!--
          Checkmark, only display for selected option.

          Highlighted: "text-white", Not Highlighted: "text-indigo-600"
        -->
                <span
                    class="absolute inset-y-0 right-0 flex items-center pr-[16px]"
                    :style="{ color: setting.primary_color }"
                    v-if="variant.id == selectedVariantId"
                >
                    <!-- Heroicon name: solid/check -->
                    <svg
                        class="h-[20px] w-[20px]"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";
export default {
    mixins: [clickaway],
    props: ["variants", "selected", "selectedChanged", "disableOptions"],
    data() {
        return {
            show: false,
            selectedVariantId: this.selected.id,
            selectedVariant: this.selected,
            setting: {}
        };
    },
    mounted() {
        console.log("variants", this.variants);
        console.log("selectedVariantId", this.selectedVariantId);
        console.log("selectedVariant", this.selectedVariant);
        this.setting = JSON.parse(document.getElementById("setting").innerText);
    },
    methods: {
        variantChanged(variant) {
            this.show = false;
            this.selectedVariant = variant;
            this.selectedVariantId = variant.id;
            this.selectedChanged(variant);
        },
        btnClick() {
            if (this.disableOptions) return;
            this.show = !this.show;
        },
        away() {
            this.show = false;
        },
        setSelected(selected) {
            this.selectedVariantId = selected.id;
            this.selectedVariant = selected;
        }
    }
};
</script>
