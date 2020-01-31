export default {
    setLoading(state, loading){
        state.isLoading = loading;
    },
    startSetRating(state, { currentRating }){
        state.currentRating = currentRating;
        state.isLoading = true;
        state.loadingMessage = "Loading...";
    },
    updateCurrentRating(state, currentRating){
        state.currentRating = currentRating;
    },
    updateRating(state, payload){
        if( !payload.success ){
            state.currentRating = state.previousRating;
          } else {
            state.previousRating = state.currentRating;
          }
          state.ratingsCount = payload.ratingsCount;
          state.currentRating = parseFloat( payload.ratings.rating );
          state.ratingsCount = payload.ratings.count;
          state.ratingsStatistics = payload.ratings.statistics;
          state.loadingMessage = payload.message;
    },
    updateLoadingMessage(state, loadingMessage){
        state.loadingMessage = loadingMessage;
    }
}