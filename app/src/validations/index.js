import isBoolean from '@/validations/custom/isBoolean';
import isColor from '@/validations/custom/isColor';
import isArray from '@/validations/custom/isArray';
import isObject from '@/validations/custom/isObject';
import isString from '@/validations/custom/isString';
import isFunction from '@/validations/custom/isFunction';

const {
    required,
    decimal,
    integer,
    numeric,
    alphaNum
} = require('vuelidate/lib/validators')

const validationMessages = {
    required: 'The {attribute} field is required',
    decimal: 'The {attribute} field contains an invalid decimal value',
    integer: 'The {attribute} field contains an invalid integer value',
    numeric: 'The {attribute} field contains an invalid numeric value',
    isBoolean: 'The {attribute} field contains an invalid boolean value',
    isColor: 'The {attribute} field contains an invalid color value',
    isArray: 'The {attribute} is not a proper array',
    isObject: 'The {attribute} is not a proper object',
    isString: 'The {attribute} field contains an invalid string value',
    isFunction: 'The {attribute} is not a proper function',
  }

const ratingWidgetValidations = {
    increment: {
        required,
        decimal
    },
    showRating: {
        required,
        isBoolean
    },
    showRatingWidget: {
        required,
        isBoolean
    },
    showRatingStatistics: {
        required,
        isBoolean
    },
    showRatingStatisticsOnHover: {
        isBoolean
    },
    showRatingStatisticsOnBelow: {
        isBoolean
    },
    popoverCloseDelay: {
        integer
    },
    popoverPosition: {
        isString
    },
    popoverMinWidth: {
        integer
    },
    popoverMaxWidth: {
        integer
    },
    showRatingText: {
        isBoolean
    },
    ratingTextPosition: {
        isString
    },
    showIconRankOnHover: {
        isBoolean
    },
    activeColor: {
        required,
        isColor
    },
    inActiveColor: {
        required,
        isColor
    },
    fullIcon: {
        required,
        isString
    },
    emptyIcon: {
        required,
        isString
    },
    halfIcon: {
        required,
        isString
    },
    iconBorder: {
        required,
    },
    iconBorderWidth: {
        integer
    },
    iconBorderColor: {
        isColor
    },
    starSize: {
        required,
        integer
    },
    rtl: {
        required,
        isBoolean
    },
    dense: {
        required,
        isBoolean
    },
    readonly: {
        required,
        isBoolean
    },
    halfIncrements: {
        required,
        isBoolean
    },
    length: {
        required,
        integer
    },
    isLoading: {
        required,
        isBoolean
    },
    currentRating: {
        required,
        decimal
    },
    rootElement: {
        required,
        isString
    }
}
const ratingStatsCardValidations = {
    activeColor: {
        required,
        isColor
    },
    inActiveColor: {
        required,
        isColor
    },
    fullIcon: {
        required,
        isString
    },
    emptyIcon: {
        required,
        isString
    },
    halfIcon: {
        required,
        isString
    },
    iconBorder: {
        required,
    },
    iconBorderWidth: {
        integer
    },
    iconBorderColor: {
        isColor
    },
    starSize: {
        required,
        integer
    },
    rtl: {
        required,
        isBoolean
    },
    dense: {
        required,
        isBoolean
    },
    
    halfIncrements: {
        required,
        isBoolean
    },
    length: {
        required,
        integer
    },
    
    currentRating: {
        required,
        decimal
    },
    closePopover: {
        isFunction,
        required
    },
    popoverTriangle: {
        required,
        isBoolean
    },
    popoverPosition: {
        required,
        isString
    },
    rootElement: {
        required,
        isString
    }
}
const loaderValidations = {
    loadingMessage: {
        isString
    },
    isLoading: {
        required,
        isBoolean
    },
    loaderColor: {
        required,
        isColor
    },
    loaderBgColor: {
        required,
        isColor
    },
    loaderRounded: {
        required,
        isBoolean
    },
    loaderHeight: {
        required,
        integer
    }
}
const ratingsStatisticsValidations = {
    ratingIntervals: {
        isArray
    },
    showRatingStatistics: {
        required,
        isBoolean
    },
    getRatingStatisticsAvg: {
        isFunction
    }, 
    progressGetRankText: {
        isFunction
    }, 
    progressGetVotesText: {
        isFunction
    },
    progressColor: {
        required,
        isColor
    },
    progressBgColor: {
        required,
        isColor
    }, 
    progressHeight: {
        required,
        integer        
    },
    progressRounded: {
        required,
        isBoolean        
    },
    progressStriped: {
        required,
        isBoolean        
    },
    progressOrderClass: {
        isFunction,
        required,
    },
    progressGetRatingPercentage: {
        isFunction,
        required,
    },
    progressShowRankStars: {
        isBoolean
    }, 
    progressShowVotesPercentage: {
        isBoolean
    },
    progressStarSize: {
        numeric        
    },
    progressColumnOrdering: {
        isObject
    },
    isPopover: {
        isBoolean
    },
    isShowStar: {
        isBoolean
    }

}
const ratingMessageValidations = {
    ratingsCount: {
        required,
        integer
    },
    noRatingsString: {
        required,
        isString
    },
    ratingString: {
        required,
        isString
    },
    currentRating: {
        required,
        decimal
    }
}

export {
    validationMessages,
    ratingWidgetValidations,
    ratingStatsCardValidations,
    loaderValidations,
    ratingsStatisticsValidations,
    ratingMessageValidations
};