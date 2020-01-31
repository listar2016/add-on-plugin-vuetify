<template>
    <v-container v-if="loadingMessage || isLoading">
            <v-row align="start" no-gutters>
                <v-col cols="6" v-if="isLoading">
                    <v-progress-linear
                        indeterminate
                        :color="loaderColor"
                        :background-color="loaderBgColor"
                        :rounded="loaderRounded"
                        :height="loaderHeight"
                    ></v-progress-linear>
                </v-col>
                <v-col cols="12">
                    <div v-if="loadingMessage" class="eeao-loading-message">{{loadingMessage}}</div>
                </v-col>
            </v-row>
    </v-container>
</template>

<script>
    
    import { mapState } from 'vuex';
    import { validationMixin } from 'vuelidate'
    import validationResultMixin from '@/mixins/validationResultMixin.js';
    import {loaderValidations} from '@/validations';
    
    export default {
        mixins: [validationMixin, validationResultMixin],
        validations: loaderValidations,
        computed: {
            ...mapState(['loadingMessage','isLoading', 'loaderColor', 'loaderBgColor', 'loaderRounded', 'loaderHeight']),
        }
    };
</script>