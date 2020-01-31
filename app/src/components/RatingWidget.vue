<template>
  <div class="eeao-rating-widget">
     <RatingsStatistics v-if="showRatingStatisticsOnBelow" />
    <div :class="ratingContainerClass">
      <div class="vrw-star-box" id="vrw-star-box" v-if="showRatingWidget">
        <div class="d-flex align-center">
            <VCRating            
              v-model="currentRating"
              :color="activeColor"
              :background-color="inActiveColor"             
              :empty-icon="emptyIcon"
              :full-icon="fullIcon"
              :half-icon="halfIcon"
              :half-increments="halfIncrements"
              :length="length"
              :size="starSize"
              @input="setRating"
              @onMouseEnterIcon="onMouseEnterIcon"
              @onMouseLeaveIcon="onMouseLeaveIcon"
              :dense="dense"
              :hover="hover"
              :readonly="readonly"
            ></VCRating>
          <div v-if="showIconRankOnHover" class="icon-rank-text px-1">{{ hoverIndex }}</div>
        </div>
      </div>
      <div v-if="showRatingWidget" class="d-flex align-start">
         

        <v-menu
          v-model="showPopover"
          :open-on-hover="true"
          :close-on-content-click="false"
          :top="popoverPosition=='top'"
          :bottom="popoverPosition=='bottom'"
          offset-y
          :max-width="popoverMenuMaxWidth"
          :nudge-left="nudgeLeft"
          :content-class="popoverPosition=='top' ? 'eeao-popover eeao-popover-top' : 'eeao-popover eeao-popover-bottom'"
          :close-delay="popoverCloseDelay"
          :attach="true"
          :z-index="1000"
        >
          <template v-slot:activator="{ on }">
            <div
              :class="ratingMessageClass"
              v-if="showRatingText"
              v-on="showRatingStatisticsOnHover||showRatingStatisticsOnBelow && on ? on : {}"
              ref="ratingText"
            >
              <RatingMessage />
            </div>
          </template> 
          <RatingsStatisticsCard
            :closePopover="closePopover"
          />
        </v-menu>
      </div>
    </div>
    <Loader v-if="showRatingWidget"/>    
    <RatingsStatistics v-if="showRatingStatisticsOnHover" />
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { validationMixin } from "vuelidate";
import validationResultMixin from "@/mixins/validationResultMixin.js";
import { ratingWidgetValidations } from "@/validations";
import Loader from "@/components/Loader.vue";
import RatingsStatistics from "@/components/RatingsStatistics.vue";
import RatingsStatisticsCard from "@/components/RatingsStatisticsCard.vue";
import RatingMessage from "@/components/RatingMessage.vue";
import VCRating from "@/components/Rating.vue";
import { setBorderIcon } from "@/utils/common";

export default {
  name: "rating-widget",
  mixins: [validationMixin, validationResultMixin],
  components: {
    VCRating,
    Loader,
    RatingMessage,
    RatingsStatistics,
    RatingsStatisticsCard
  },
  mounted() {
    setBorderIcon({
      rootElement: this.rootElement,
      iconBorder: this.iconBorder,
      iconBorderWidth: this.iconBorderWidth,
      iconBorderColor: this.iconBorderColor
    });
  },
  data: () => {
    return {
      showPopover: false,
      hoverIndex: ""
    };
  },
  methods: {
    closePopover(){
      this.showPopover = !this.showPopover
    },
    setRating(currentRating) {
      if (!currentRating || typeof currentRating != "number") {
        this.$store.dispatch("setRatingError", {
          message: "Invalid rating selected"
        });
        return;
      }
      this.$store.dispatch("setRating", { currentRating }).catch(err => {
        console.error(err);
      });
    },
    onMouseEnterIcon(hoverIndex) {
      this.hoverIndex = hoverIndex;
    },
    onMouseLeaveIcon() {
      this.hoverIndex = "";
    }
  },
  validations() {
    if (this.showRatingWidget) {
      return ratingWidgetValidations;
    }
    return {};
  },
  computed: {
    currentRating: {
      get() {
        return this.$store.state.currentRating;
      },
      set(newValue) {
        this.$store.commit("updateCurrentRating", newValue);
      }
    },
    ...mapState([
      "showRatingWidget",
      "showRatingStatistics",
      "showRatingStatisticsOnHover",
      "showRatingStatisticsOnBelow",
      "popoverCloseDelay",
      "popoverPosition",
      "popoverMinWidth",
      "popoverMaxWidth",
      "showIconRankOnHover",
      "increment",
      "showRating",
      "showRatingText",
      "ratingTextPosition",
      "activeColor",
      "inActiveColor",
      "starSize",
      "rtl",
      "hover",
      "dense",
      "readonly",
      "halfIncrements",
      "length",
      "isLoading",
      "iconBorder",
      "iconBorderColor",
      "iconBorderWidth",
      "rootElement"
    ]),
    ...mapGetters([ "emptyIcon", "fullIcon" ]),
    ratingContainerClass(){
      const ratingContainerClass = {
        'after': 'd-flex flex-column flex-sm-row align-sm-center',
        'before': 'd-flex flex-column-reverse flex-sm-row-reverse align-xs-start align-sm-center justify-end',
        'below': 'd-flex flex-column',
        'above': 'd-flex flex-column-reverse',
      }
      return ratingContainerClass[this.ratingTextPosition];
    },
    ratingMessageClass(){
      return {
        'post-rating-text': true, 
        'flex-grow-1': true,
        'pl-3': this.ratingTextPosition=='after', 
        'pr-3': this.ratingTextPosition=='before' 
      }
    },
    halfIcon() {
      return this.$store.getters.halfIcon(this.currentRating);
    },
    nudgeLeft() {
      switch (this.$vuetify.breakpoint.name) {
          case 'xs': return 15
          default: 
            return this.$store.state.popoverNudgeLeft
        }
       
    },
    popoverMenuMaxWidth() {
      switch (this.$vuetify.breakpoint.name) {
          case 'xs': return this.$vuetify.breakpoint.width - 30
          default: 
            return 'auto'
        }
    }
  }
};
</script>

<style lang="scss">
// :root{
//   --rating-active-color: #ffd055;
//   --rating-bg-color: #d8d8d8;
// }
.vrw-icon-half:hover::before {
  background: linear-gradient(
    to right,
    var(--rating-active-color) 0%,
    var(--rating-active-color) 50%,
    var(--rating-bg-color) 50%,
    var(--rating-bg-color) 100%
  ) !important;
  -webkit-background-clip: text !important;
  background-clip: text !important;
  -webkit-text-fill-color: transparent !important;
}

$sizes: (
  10: 15,
  20: 20,
  30: 30,
  40: 40,
  50: 50,
  60: 60,
  70: 70,
  80: 80,
  90: 85
);
@each $key, $size in $sizes {
  .vrw-icon-w-#{$key}:before {
    background: linear-gradient(
      to right,
      var(--rating-active-color) 0%,
      var(--rating-active-color) #{$size + "%"},
      var(--rating-bg-color) #{$size + "%"},
      var(--rating-bg-color) 100%
    );
    -webkit-background-clip: text !important;
    background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
  }
}

.danger-bg {
  background: red;
}
.disabled-bg,
.disabled-bg:disabled {
  background: grey;
  cursor: not-allowed;
}
.vrw-star-box{
  margin: 10px 5px;
}
.reset-rating {
  margin-top: 5px;
  border-radius: 3px;
  border: 1px solid grey;
  padding: 15px;
}
.eeao-rating-widget .v-menu>.v-menu__content {
    border: none;
    background: transparent;
    box-shadow: none;
}
</style>
