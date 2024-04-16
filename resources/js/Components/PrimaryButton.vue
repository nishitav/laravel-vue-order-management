<template>
  <span :class="badgeClasses">
    <slot />
  </span>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: 'gray',
    },
    rounded: {
      type: Boolean,
      default: false,
    },
    pill: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    badgeClasses() {
      return [
        'inline-block px-2 py-1 text-xs font-semibold leading-none',
        `bg-${this.color}-200 text-${this.color}-800`,
        {
          'rounded-full': this.pill,
          'rounded-md': this.rounded && !this.pill,
        },
      ];
    },
  },
};
</script>
