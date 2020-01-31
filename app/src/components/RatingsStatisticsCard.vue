<template>
<div>
   <div class="arrow" v-if="popoverTriangle && popoverPosition=='bottom'"></div>
   <v-card class="mx-auto pa-2" flat outlined>
       <div class="d-flex align-start pb-5 align-sm-center">
           <div :class="{'d-flex align-center': true, 'flex-column': $vuetify.breakpoint.xs}">
              <div class="mr-2">
                  <VCRating 
                        v-model="currentRating"
                        :color="activeColor"
                        :background-color="inActiveColor"
                        :empty-icon="emptyIcon"
                        :full-icon="fullIcon"
                        :half-icon="halfIcon"
                        :size="starSize"
                        :half-increments="halfIncrements"
                        :length="length"
                        :dense="dense"
                        :hover="false"
                        :readonly="true"
                    ></VCRating>
              </div>
              <div class="pt-1"><RatingMessage/></div>
           </div> 
           <div class="ml-auto">
               <v-btn icon @click="closePopover">
                <v-icon>mdi-close</v-icon>
              </v-btn>
           </div>
       </div>
       <RatingsStatistics id="RatingsStatisticsCard" is-popover/>
    </v-card>
    <div class="arrow" v-if="popoverTriangle && popoverPosition=='top'"></div>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import validationResultMixin from '@/mixins/validationResultMixin.js';
    import { ratingStatsCardValidations } from '@/validations';
    import {mapGetters, mapState} from 'vuex';    
    import VCRating from '@/components/Rating.vue';
    import { setBorderIcon } from '@/utils/common';
    import RatingMessage from "@/components/RatingMessage.vue";
    import RatingsStatistics from '@/components/RatingsStatistics.vue';
    export default {
        name: "RatingsStatisticsCard",
        mixins: [validationMixin, validationResultMixin],
        components: { RatingsStatistics, VCRating, RatingMessage},
        props: {
            closePopover: {
                type: Function,
                required: true
            },
        },
        mounted(){
            setBorderIcon({
                rootElement: this.rootElement,
                iconBorder: this.iconBorder,
                iconBorderWidth: this.iconBorderWidth,
                iconBorderColor: this.iconBorderColor,
            })
        },
        validations(){
            return ratingStatsCardValidations;
        },
        computed: {
        ...mapState([
            'activeColor', 'inActiveColor', 'popoverTriangle', 'popoverPosition',
            'rtl', 'starSize',
            'dense', 'halfIncrements', 'length',
            'rtl', 'starSize','dense','starPadding',
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
        }
    }
}
</script>

<style lang="scss">
.eeao-popover{
  box-shadow: none;
  border: none;
}
.eeao-popover .arrow {
  display: block;
  width: 1rem;
  height: 0.5rem;
  margin: 0 auto;
}

.eeao-popover .arrow::before, .eeao-popover .arrow::after {
  display: block;
  content: "";
  border-color: transparent;
  border-style: solid;
  position: absolute;
  z-index: 2000;
}

.eeao-popover-top  .arrow{
  bottom: calc(-0.5rem - 1px);
}
.eeao-popover-top .arrow::before{
  bottom: 0;
  border-width: 0.5rem 0.5rem 0;
  border-top-color: rgba(0, 0, 0, 0.25);
  position: absolute;
  z-index: 2000;
}
.eeao-popover-top .arrow::after{
  bottom: 1px;
  border-width: 0.5rem 0.5rem 0;
  border-top-color: #fff;
  position: absolute;
  z-index: 2000;
}

.eeao-popover-bottom .arrow{
  top: calc(-0.5rem - 1px);
}

.eeao-popover-bottom .arrow::before{
  top: 0;
  border-width: 0 0.5rem 0.5rem 0.5rem;
  border-bottom-color: rgba(0, 0, 0, 0.25);
  z-index: 2000;
}

.eeao-popover-bottom .arrow::after{
  top: 1px;
  border-width: 0 0.5rem 0.5rem 0.5rem;
  border-bottom-color: #fff;
}
</style>