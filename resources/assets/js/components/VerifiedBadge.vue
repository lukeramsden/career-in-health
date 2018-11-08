<template>
  <span v-if="show"
        class="oi oi-circle-check verified-badge"></span>
</template>

<script>
export default {
  props: {
    company: Object,
  },
  computed: {
    show()
    {
      return this.company === undefined || this.company.verified;
    },
  },
};
</script>
