<template>
    <div class="rating-statistics mt-2">
        <div class="d-flex flex-nowrap align-center"  v-for="interval in ratingIntervals" :key="interval">
             <div :class="['vrw-star-progress-left-text eeao-rank-text d-flex justify-end progress-col eeao-rank-text', progressOrderClass('RankText')]" v-if="progressShowRankText">
                    {{ progressGetRankText(interval) }}
            </div>
            <div :class="['vrw-star-progress progress-col progress-bar' , progressOrderClass('ProgressBar')]">
                    <v-progress-linear
                        :value="getRatingStatisticsAvg(interval)"
                        :color="progressColor"
                        :background-color="progressBgColor"
                        :height="progressHeight"
                        :background-opacity="progressOpacity"
                        :rounded="progressRounded"
                        :striped="progressStriped"
                    ></v-progress-linear>
            </div>
            <div v-if="isShowStar && $vuetify.breakpoint.smAndUp" :class="['progress-col', progressOrderClass('RankStar')]">
                <VCRating 
                    :value="interval"
                    :color="activeColor"
                    :background-color="inActiveColor"
                    :empty-icon="emptyIcon"
                    :full-icon="fullIcon"
                    :half-icon="halfIcon"
                    :half-increments="halfIncrements"
                    :size="progressStarSize"
                    :length="length"
                    :dense="dense"
                    :hover="false"
                    :readonly="true"
                ></VCRating>
            </div>
            <div v-if="progressShowVotesNumberText" :class="['vrw-star-progress-right-text eeao-votes-text progress-col', progressOrderClass('VotesText')]">
                {{ progressGetVotesText(interval) }}
            </div>
            <div v-if="progressShowVotesPercentage" :class="['vrw-star-progress-right-percentage-text eeao-votes-percentage progress-col', progressOrderClass('VotesPercentage')]">
                {{ progressGetRatingPercentage(interval) + '%' }}
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapState} from 'vuex';
    import { validationMixin } from 'vuelidate'
    import VCRating from "@/components/Rating.vue";
    import validationResultMixin from '@/mixins/validationResultMixin.js';
    import { ratingsStatisticsValidations } from '@/validations';
    
    export default {
        name: "RatingsStatistics",
        props: {
            isPopover: {
                type: Boolean,
                default: false
            }
        },
        mixins: [validationMixin, validationResultMixin],
        components: { VCRating },
        data: () => {
            return {
                ratingIntervals: [5, 4, 3, 2, 1],
            }
        },
        validations(){
            if(this.showRatingStatistics){
                return ratingsStatisticsValidations;
            }
            return {};
        },
        computed: {
            ...mapGetters(['getRatingStatisticsAvg', 'progressGetVotesText', 'progressGetRankText', 'progressGetRatingPercentage']),
            ...mapState(['progressShowRankText', 'progressShowVotesNumberText', 'showRatingStatistics','progressColor', 'progressBgColor', 'progressHeight',
                'progressOpacity', 'progressRounded', 'progressStriped', 'progressShowRankStars', 'progressShowVotesPercentage', 'progressStarSize', 'progressColumnOrdering',
            ]),
            ...mapState([
                'activeColor', 'inActiveColor', 'popoverTriangle', 'popoverPosition',
                'rtl', 'starSize',
                'dense', 'halfIncrements', 'length',
                'rtl', 'starSize',
                'halfIncrements', 'length',
                'iconBorder', 'iconBorderColor', 'iconBorderWidth',
                'currentRating',
                'rootElement'
            ]),
            ...mapGetters([
                'emptyIcon', 'fullIcon'
            ]),
            halfIcon () {
                return this.$store.getters.halfIcon(this.currentRating);
            },
            progressOrderClass: (state) => (col) => {
                return 'order-' + state.progressColumnOrdering[col]
            },
            isShowStar(){
                return this.isPopover ? this.$store.state.popoverShowRankStars : this.$store.state.progressShowRankStars
            }
        },
    }
</script>

<style lang="scss" scoped>
    .progress-col{
        min-width: 35px;
        text-align: right;
    }
    .progress-bar{
        min-width: 140px;
    }
    .eeao-rank-text, .eeao-votes-text, .eeao-votes-percentage{
        overflow-x: auto;
    }
    @media only screen and (max-width: #{map-get($grid-breakpoints, 'sm') - 1}){
        .eeao-rank-text, .eeao-votes-text, .eeao-votes-percentage{
            font-size: 85%;
        }
    }
</style>