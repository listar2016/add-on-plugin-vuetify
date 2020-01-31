<template>
  <div id="app">
    <v-app>
      <v-content>
        <v-container fluid pl-0>
          <rating-widget v-if="widgetType=='star-rating'"></rating-widget>
          <poll v-if="widgetType=='poll'"></poll>
          <live-data-table v-if="widgetType=='live-data-table'"></live-data-table>
        </v-container>
      </v-content>
    </v-app>
  </div>
</template>

<script>
import RatingWidget from "./components/RatingWidget.vue";
import Poll from "./components/Poll.vue";
import LiveDataTable from "./components/LiveDataTable.vue";
export default {
  name: "app",
  components: { RatingWidget, Poll , LiveDataTable},
  computed: {
    widgetType: {
      get() {
        return this.$store.state.widgetType;
      }
    }
  }
};
</script>
<style lang="scss">
.theme--light.v-application {
  background: transparent;
}

.v-application--wrap {
  min-height: auto !important;
}
</style>
