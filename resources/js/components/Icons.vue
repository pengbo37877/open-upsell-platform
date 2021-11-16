<template>
  <div class="mt-4 grid grid-cols-6 gap-6">
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('badge-check')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/badge-check.svg"
        alt="badge-check"
        :class="`${cIcon==='badge-check'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div
      class="flex justify-center items-center cursor-pointer"
      @click="chooseIcon('cursor-click')"
    >
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/cursor-click.svg"
        alt="cursor-click"
        :class="`${cIcon==='cursor-click'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('gift')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/gift.svg"
        alt="gift"
        :class="`${cIcon==='gift'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('plus')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/plus.svg"
        alt="plus"
        :class="`${cIcon==='plus'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('star')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/star.svg"
        alt="star"
        :class="`${cIcon==='star'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('mail-open')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/mail-open.svg"
        alt="mail-open"
        :class="`${cIcon==='mail-open'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div
      class="flex justify-center items-center cursor-pointer"
      @click="chooseIcon('shield-check')"
    >
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/shield-check.svg"
        alt="shield-check"
        :class="`${cIcon==='shield-check'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('sparkles')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/sparkles.svg"
        alt="sparkles"
        :class="`${cIcon==='sparkles'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('chat')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/chat.svg"
        alt="chat"
        :class="`${cIcon==='chat'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('key')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/key.svg"
        alt="key"
        :class="`${cIcon==='key'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('heart')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/heart.svg"
        alt="heart"
        :class="`${cIcon==='heart'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('thumb-up')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/thumb-up.svg"
        alt="thumb-up"
        :class="`${cIcon==='thumb-up'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('clock')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/clock.svg"
        alt="clock"
        :class="`${cIcon==='clock'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div
      class="flex justify-center items-center cursor-pointer"
      @click="chooseIcon('question-mark-circle')"
    >
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/question-mark-circle.svg"
        alt="question-mark-circle"
        :class="`${cIcon==='question-mark-circle'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('emoji-happy')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/emoji-happy.svg"
        alt="emoji-happy"
        :class="`${cIcon==='emoji-happy'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('eye-off')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/eye-off.svg"
        alt="eye-off"
        :class="`${cIcon==='eye-off'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('fire')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/fire.svg"
        alt="fire"
        :class="`${cIcon==='fire'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('truck')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/truck.svg"
        alt="truck"
        :class="`${cIcon==='truck'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('camera')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/camera.svg"
        alt="camera"
        :class="`${cIcon==='camera'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div
      class="flex justify-center items-center cursor-pointer"
      @click="chooseIcon('lightning-bolt')"
    >
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/lightning-bolt.svg"
        alt="lightning-bolt"
        :class="`${cIcon==='lightning-bolt'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('bell')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/bell.svg"
        alt="bell"
        :class="`${cIcon==='bell'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div
      class="flex justify-center items-center cursor-pointer"
      @click="chooseIcon('color-swatch')"
    >
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/color-swatch.svg"
        alt="color-swatch"
        :class="`${cIcon==='color-swatch'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('collection')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/collection.svg"
        alt="collection"
        :class="`${cIcon==='collection'?'opacity-80':'opacity-40'}`"
      />
    </div>
    <div class="flex justify-center items-center cursor-pointer" @click="chooseIcon('cake')">
      <img
        src="https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/cake.svg"
        alt="cake"
        :class="`${cIcon==='cake'?'opacity-80':'opacity-40'}`"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["icon"],
  data() {
    return {
      cIcon: ""
    };
  },
  mounted() {
    console.log("icons mounted");
    this.cIcon = this.icon;
  },
  methods: {
    chooseIcon(icon) {
      console.log("chooseIcon:" + icon);
      this.cIcon = icon;
      this.$emit("update:icon", icon);
    }
  }
};
</script>
