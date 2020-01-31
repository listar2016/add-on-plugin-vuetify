import Vue from 'vue'
import Vuex from 'vuex'
import cloneDeep from 'lodash.clonedeep'
import actions from '@/store/actions'
import mutations from '@/store/mutations'
import getters from '@/store/getters'
import state from '@/store/state'
import { parseSetting, updateCSSColor } from '@/utils/common';

Vue.use(Vuex)

/**
 * @param {Object} setting Elementor widget setting data
 */
export default function createStore(setting){

  let initState = cloneDeep(Object.assign(state, parseSetting(setting)))
    if(initState.widgetType =='star-rating' ){
        updateCSSColor(initState.rootElement, initState.activeColor, initState.inActiveColor);
    }
  return new Vuex.Store({
        state: initState,
        mutations,
        actions,
        getters
  });
}
