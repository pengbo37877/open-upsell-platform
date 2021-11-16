<template>
  <div>
    <div
      class="flex flex-col items-center py-6 text-center border-r border-gray-300"
      v-if="plan_id==='' || plan_id===null || plan_id === 6"
    >
      <div class="text-2xl font-medium">{{ sessions.length }} / {{getPlanSessionCount()}}</div>
      <div class="font-light text-gray-500">Sessions usage</div>
      <div
        class="w-auto px-3 py-2 mt-1 text-sm font-light text-gray-600 bg-gray-300 rounded-sm"
      >Your plan</div>
    </div>
    <div class="flex flex-col items-center py-6 text-center border-r border-gray-300" v-else>
      <div class="text-2xl font-medium">200 sessions</div>
      <div class="font-light text-gray-500">FREE</div>
      <a
        :href="`/billing/6?shop=${user.name}`"
        class="px-3 py-2 mt-1 text-sm font-light text-white bg-gray-600 border border-gray-700 rounded-sm hover:bg-gray-700"
      >Downgrade</a>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: ["plan_id", "sessions"],
  computed: {
    ...mapState({
      user: state => state.upsellrock.user
    })
  },
  methods: {
    getPlanSessionCount() {
      if (this.plan_id == "" || this.plan_id == null || this.plan_id == 6) {
        return 200;
      } else if (this.plan_id === 7) {
        return 2000;
      } else if (this.plan_id === 8) {
        return 5000;
      } else if (this.plan_id === 9) {
        return "Unlimited";
      }
    }
  }
};
</script>
