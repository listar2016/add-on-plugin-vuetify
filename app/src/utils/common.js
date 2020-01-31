const updateCSSColor = (rootElement, activeColor, bgColor) => {
  let el = document.querySelector(rootElement);
  if (!el) {
    return;
  }
  el.style.setProperty("--rating-active-color", activeColor);
  el.style.setProperty("--rating-bg-color", bgColor);
};
const setBorderIcon = ({
  rootElement,
  iconBorder,
  iconBorderWidth,
  iconBorderColor
}) => {
  if (iconBorder) {
    let container = document.querySelector(rootElement);
    if (!container) {
      return;
    }
    const starMatches = container.querySelectorAll(".v-rating .v-icon");
    starMatches.forEach(function(starIconEl) {
      starIconEl.style.webkitTextStrokeWidth = `${iconBorderWidth}px`;
      starIconEl.style.webkitTextStrokeColor = iconBorderColor;
    });
  }
};
const parseSetting = setting => {
  let data = {};
  let rating = isNaN(setting.rating) ? 0 : parseFloat(setting.rating);
  data.widgetType = setting.widgetType;
  if (data.widgetType == "star-rating") {
    (data.showRatingWidget = setting.showRatingWidget === "yes"),
      (data.showRatingStatistics = setting.showRatingStatistics === "yes"),
      (data.showIconRankOnHover = setting.showIconRankOnHover === "yes"),
      (data.previousRating = rating);
    data.showRatingStatisticsOnHover =   setting.showRatingStatisticsOnHover === "yes";
    data.showRatingStatisticsOnBelow =   setting.showRatingStatisticsOnBelow === "yes";
    data.popoverCloseDelay = setting.popoverCloseDelay ? parseInt(setting.popoverCloseDelay) * 1000 : 2000
    data.popoverTriangle = setting.popoverTriangle === "yes"
    data.popoverPosition = setting.popoverPosition
    data.popoverShowRankStars = setting.popoverShowRankStars === "yes"
    //data.popoverMinWidth = setting.popoverMinWidth && setting.popoverMinWidth.size;
    //data.popoverMaxWidth = setting.popoverMaxWidth && setting.popoverMaxWidth.size;
    data.popoverNudgeLeft =  setting.popoverNudgeLeft && setting.popoverNudgeLeft.size
    data.currentRating = rating;
    data.ratingMessage = setting.ratingText;
    data.ratingsStatistics = setting.statistics ? setting.statistics : [];
    data.showRatingText = setting.showRatingText === "yes";
    data.ratingTextPosition = setting.ratingTextPosition || 'after';
    data.ratingString = setting.ratingString;
    data.noRatingsString = setting.noRatingsString;
    data.ratingsCount = setting.ratingsCount;
    data.activeColor = setting.starColor;
    data.inActiveColor = setting.inactiveStarColor;
    data.loaderColor = setting.loaderColor;
    data.loaderBgColor = setting.loaderBgColor;
    data.loaderRounded = setting.loaderRounded === "yes";
    data.loaderHeight = setting.loaderHeight && setting.loaderHeight.size;
    data.fullIcon = Array.isArray(setting.fullIcon)
      ? setting.fullIcon[0]
      : setting.fullIcon;
    data.iconBorder = setting.iconBorder === "yes";
    data.iconBorderColor = setting.iconBorderColor;
    data.iconBorderWidth =
      setting.iconBorderWidth && setting.iconBorderWidth.size;

    data.starSize = setting.starSize && Math.round(setting.starSize.size);
    data.starPadding = setting.starPadding && Math.round(setting.starPadding.size);

    data.rtl = setting.rtl === "yes";
    data.readonly = setting.readonly === "yes";
    data.dense = setting.dense === "yes";
    data.halfIncrements = setting.halfIncrements === "yes";

    data.ajaxConfig = {
      headers: {
        "X-WP-Nonce": window.postRatingSettings.ajax_nonce
      }
    };
    data.adminAjaxUrl = window.postRatingSettings.ajaxUrl;
    data.noRatingsYetString = window.postRatingSettings.no_ratings_yet_string;

    data.progressColor = setting.progressColor;
    data.progressBgColor = setting.progressBgColor;
    data.progressHeight =
      setting.progressHeight && parseInt(setting.progressHeight.size);
    data.progressOpacity =
      setting.progressOpacity && parseFloat(setting.progressOpacity.size);
    data.progressRounded = setting.progressRounded === "yes";
    data.progressStriped = setting.progressStriped === "yes";
    data.progressRankText = setting.progressRankText
      ? setting.progressRankText
      : [];
    data.progressVotesText = setting.progressVotesText
      ? setting.progressVotesText
      : [];
    data.progressShowRankText = setting.progressShowRankText === "yes";
    data.progressShowVotesNumberText =
      setting.progressShowVotesNumberText === "yes";
    data.progressShowRankStars = setting.progressShowRankStars === "yes";
    data.progressShowVotesPercentage = setting.progressShowVotesPercentage === "yes";
    data.progressStarSize = setting.progressStarSize && parseFloat(setting.progressStarSize.size);
    data.progressColumnOrdering = setting.progressColumnOrdering;

    data.postId = parseInt(setting.postId);
    data.rootElement = setting.rootElement;
    data.widgetId = setting.eeaoWidgetId ? setting.eeaoWidgetId : 1
    console.log(setting);
  }

  if (data.widgetType == "poll" || data.widgetType == "live-data-table") {
    console.log(setting);
    data.widgetTitle = setting.widgetTitle;
  }

  if (data.widgetType == "live-data-table") {
    data.liveDataTableFields = setting.eeaoLiveDataTableFields;
    data.liveDataTableHeaders = typeof setting.eeaoLiveDataTableHeaders !== 'undefined' ? setting.eeaoLiveDataTableHeaders : [];
    data.liveDataTableItems = typeof setting.eeaoLiveDataTableItems !== 'undefined' ? setting.eeaoLiveDataTableItems : [];

  }

  return data;
};

export { parseSetting, updateCSSColor, setBorderIcon };
