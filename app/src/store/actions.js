import starsApi from '@/api/stars';
const SHOW_LOADING_TIME = 5000;
export default {
    setRating({ commit, state, dispatch }, {currentRating}){
        return new Promise((resolve, reject) => {
            commit('startSetRating', { currentRating });
            starsApi.setRating(state.adminAjaxUrl, state.ajaxConfig, 
                {currentRating, postId: state.postId, widgetId: state.widgetId}
            ).then(response => {
                  let payload =  response.data;
                  commit('updateRating', payload)  
                  setTimeout(() => {
                    commit('updateLoadingMessage', '')
                  }, SHOW_LOADING_TIME);
                  commit('setLoading', false);
                  resolve(payload);
            }).catch(err => {
                dispatch('setRatingError', {message: 'Error setting ratings'})
                commit('setLoading', false);
                reject(err);
            })
        });
    },
    setRatingError({ commit, state, dispatch }, { message }){
        commit('updateLoadingMessage', message)
        setTimeout(() => {
            commit('updateLoadingMessage', '')
        }, SHOW_LOADING_TIME);
    }
}