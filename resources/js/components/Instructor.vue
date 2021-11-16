<template>
  <div class="border border-gray-300 border-t-4 rounded w-full flex p-6" v-if="show">
    <div class="text-xl text-green-600">
      <svg
        class="w-6 h-6"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"
        />
      </svg>
    </div>
    <div class="ml-4 flex-1 flex flex-col">
      <div class="text-xl text-gray-800">Almost there, now let's set up your first upsell.</div>
      <div class="mt-4 flex flex-col space-y-2">
        <div class="text-gray-700 text-sm">👉 Step 1 - Select one of the upsell offers below.</div>
        <div class="text-gray-700 text-sm">👉 Step 2 - Fine-tune the details, save and active it.</div>
        <div
          class="text-gray-700 text-sm"
        >👉 Step 3 - To add upsell on the same pop-up simply create a new upsell offer</div>
        <div
          class="text-gray-700 text-sm"
        >👉 Step 4 - Go to your live store and test the pop-up by clicking on the Add to Cart button.</div>
      </div>
      <div class="mt-4 flex text-gray-700 text-sm">
        <span class="font-light italic">
          Don't see the upsell pop-up? Read our
          <a
            class="text-blue-500 underline"
            target="_blank"
            href="/how-to"
          >How-to guide</a>
        </span>
      </div>
    </div>
    <div class="ml-3 text-gray-600 cursor-pointer" @click="close">
      <svg
        class="w-6 h-6"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M6 18L18 6M6 6l12 12"
        />
      </svg>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: true
    };
  },
  mounted() {
    var saved = window.localStorage.getItem("antupsellrockinstroctorshow");
    this.show = saved === null ? true : false;
  },
  methods: {
    close() {
      this.show = false;
      window.localStorage.setItem("antupsellrockinstroctorshow", false);
    }
  }
};
</script>
