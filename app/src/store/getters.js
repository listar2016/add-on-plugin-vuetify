export default {
  widgetType(state) {
    let { widgetType } = state;
    return widgetType;
  },
  rating(state) {
    let { rating } = state;
    return parseFloat(rating);
  },
  halfIcon: ( state, getters ) => rating => {
    const iconClasses = [state.fullIcon, 'vrw-icon-half', getters.paddingIconSize];
    if (rating < 1) {
      return iconClasses.join(' ');
    }
    let decimalPart = rating - Math.floor(rating);
    let activeWidth = decimalPart.toFixed(2) * 100;
    iconClasses.push('vrw-icon-w-' + activeWidth);
    return iconClasses.join(' ');
  },
  emptyIcon( state, getters ) {
    return [ state.fullIcon, 'vrw-icon-empty', getters.paddingIconSize ].join(' ');
  },
  fullIcon( state, getters ){
    return [ state.fullIcon, getters.paddingIconSize ].join(' ');
  },
  paddingIconSize(state){
    return "pa-" + state.starPadding;
  },
  ratingStringReplaced(state) {
    let {
      ratingsCount,
      noRatingsString,
      ratingString,
      currentRating: ratingsAverage
    } = state;
    return ratingsCount === 0
      ? noRatingsString
      : ratingString
          .replace("{{ratings_average}}", ratingsAverage)
          .replace("{{ratings_count}}", ratingsCount);
  },
  progressGetRankText: state => interval => {
    let { progressRankText } = state;
    return progressRankText[interval];
  },
  progressGetVotesText: state => interval => {
    let { progressVotesText, ratingsStatistics } = state;
    if (progressVotesText[interval]) {
      return progressVotesText[interval].replace(
        "{{vote_number}}",
        ratingsStatistics[interval] ? ratingsStatistics[interval].votes : 0
      );
    }
    return "";
  },
  getRatingStatisticsAvg: state => interval => {
    let { ratingsStatistics } = state;
    if (ratingsStatistics && ratingsStatistics[interval]) {
      return ratingsStatistics[interval].avg;
    }
    return 0;
  },
  progressGetRatingPercentage: state => interval => {
    let { ratingsStatistics } = state;
    if (ratingsStatistics && ratingsStatistics[interval]) {
      return ratingsStatistics[interval].percentage;
    }
    return 0;
  }
  
};
