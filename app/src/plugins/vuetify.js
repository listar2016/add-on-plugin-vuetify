// import '@mdi/font/css/materialdesignicons.css'
import Vue from 'vue';
import Vuetify, {
  VRating, VProgressLinear, VDataTable
} from 'vuetify/lib'

Vue.use(Vuetify, {
  components: {
    VRating, VProgressLinear, VDataTable
  },
});
export default function createVuetify(options = {}){
  return new Vuetify({
    rtl: options.rtl === 'yes',
    icons: {
      iconfont: 'mdi',
    },
  });
}
