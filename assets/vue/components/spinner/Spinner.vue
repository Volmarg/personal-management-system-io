<!-- Template -->
<template>
  <!-- This top wrapper must be never effected by vue else SpinnerService breaks -->
  <section id="spinnerTopWrapper" class="d-none">
    <section class="spinner-wrapper w-100 h-100"
             id="spinnerWrapper"
             :class="lowOpacityClass"
    >

      <div class="text-wrapper" v-if="text">
        <h3 class="additional-text text-center">{{ text }}</h3>
      </div>

      <div class="semipolar-spinner">
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
      </div>
    </section>
  </section>
</template>

<!-- Script -->
<script>
/**
 * @see SpinnerService
 */
export default {
  props: {
    text: {
      type     : String,
      required : false,
      default  : "",
    }
  },
  computed: {
    lowOpacityClass(){
      return {
        "low-opacity": this.text
      }
    }
  }
}
</script>

<!-- Style -->
<style scoped>
.semipolar-spinner, .semipolar-spinner * {
  box-sizing: border-box;
}
.semipolar-spinner {
  height: 65px;
  width: 65px;
  position: relative;
}
.semipolar-spinner .ring {
  border-radius: 50%;
  position: absolute;
  border: calc(65px * 0.05) solid transparent;
  border-top-color: #1ea1a5;
  border-left-color: #1ea1a5;
  animation: semipolar-spinner-animation 2s infinite;
}
.semipolar-spinner .ring:nth-child(1) {
  height: calc(65px - 65px * 0.2 * 0);
  width: calc(65px - 65px * 0.2 * 0);
  top: calc(65px * 0.1 * 0);
  left: calc(65px * 0.1 * 0);
  animation-delay: calc(2000ms * 0.1 * 4);
  z-index: 5;
}
.semipolar-spinner .ring:nth-child(2) {
  height: calc(65px - 65px * 0.2 * 1);
  width: calc(65px - 65px * 0.2 * 1);
  top: calc(65px * 0.1 * 1);
  left: calc(65px * 0.1 * 1);
  animation-delay: calc(2000ms * 0.1 * 3);
  z-index: 4;
}
.semipolar-spinner .ring:nth-child(3) {
  height: calc(65px - 65px * 0.2 * 2);
  width: calc(65px - 65px * 0.2 * 2);
  top: calc(65px * 0.1 * 2);
  left: calc(65px * 0.1 * 2);
  animation-delay: calc(2000ms * 0.1 * 2);
  z-index: 3;
}
.semipolar-spinner .ring:nth-child(4) {
  height: calc(65px - 65px * 0.2 * 3);
  width: calc(65px - 65px * 0.2 * 3);
  top: calc(65px * 0.1 * 3);
  left: calc(65px * 0.1 * 3);
  animation-delay: calc(2000ms * 0.1 * 1);
  z-index: 2;
}
.semipolar-spinner .ring:nth-child(5) {
  height: calc(65px - 65px * 0.2 * 4);
  width: calc(65px - 65px * 0.2 * 4);
  top: calc(65px * 0.1 * 4);
  left: calc(65px * 0.1 * 4);
  animation-delay: calc(2000ms * 0.1 * 0);
  z-index: 1;
}
@keyframes semipolar-spinner-animation {
  50% {
    transform: rotate(360deg) scale(0.7);
  }
}
/* Customization */
.spinner-wrapper {
  position: absolute;
  z-index: 1;
  background-color: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
}

.semipolar-spinner {
  align-self: center;
  position: relative;
}

.text-wrapper {
  align-self: center;
  position: absolute;
  width: 100%;
  display: flex;
  justify-content: center;
}

.additional-text {
  text-align: center;

  position: absolute;
  display: inline-block;
  bottom: 80px;

  background-color: rgba(255, 255, 255, 0.9);
  padding: 7px;
  border-bottom: 3px solid #1ea1a5;
}

.low-opacity {
  background-color: rgba(255, 255, 255, 0.85);
}
</style>