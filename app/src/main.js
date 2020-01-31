import Vue from "vue";
import App from "@/App.vue";
import AdminApp from "@/AdminApp";
import createStore from "@/store";
import createVuetify from "./plugins/vuetify";
import PortalVue from "portal-vue";

Vue.config.productionTip = false;
Vue.config.devtools = true;
Vue.use(PortalVue);
// Code to detect which screen we are - not sure we going to use this
//var URLString = window.location.href;
//var urlObj = new URL(URLString);
//var starPostID = urlObj.searchParams.get("post");
//if (starPostID == null) {
//    var starPostID = urlObj.searchParams.get("elementor-preview");
//}
const settings = {};
const stores = {};

const renderApp = $scope => {
  let widget = $scope.find(".eeao-widget-data");
  let renderToId = widget[0].id;
  settings[renderToId] = widget.data("eeao-widget-settings");
  let rtl = settings[renderToId].rtl;
  let selector = "#" + renderToId;
  settings[renderToId].rootElement = ".elementor-element-" + $scope.data("id");
  stores[renderToId] = createStore(settings[renderToId]);
  new Vue({
    store: stores[renderToId],
    vuetify: createVuetify({rtl}),
    render: h => h(App)
  }).$mount(selector);
  console.log(`Mounted to ${selector}`);
  console.log(settings);
};

(function($) {
  $(window).on("elementor/frontend/init", function() {
    ['post-rating', 'poll', 'live-data-table'].map( widgetType =>
        elementorFrontend.hooks.addAction(
            `frontend/element_ready/eeao-${widgetType}.default`,
            renderApp
        )
  );
  });
})(jQuery);

document.addEventListener("DOMContentLoaded", function(event) {
  if(!jQuery("#wpbody-content").length){
    return;
  }
  new Vue({
    render: h => h(AdminApp)
  }).$mount("#wpbody-content");
});
