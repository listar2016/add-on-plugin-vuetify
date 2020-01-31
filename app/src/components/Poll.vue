<template>
  <div>{{widgetTitle}}</div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
export default {
  name: "poll",
  data() {
    return {};
  },
  computed: {
    widgetTitle: {
      get() {
        return this.$store.state.widgetTitle;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
