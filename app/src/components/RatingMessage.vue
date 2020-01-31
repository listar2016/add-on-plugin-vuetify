<template>
    <div>{{ratingStringReplaced}}</div>
</template>

<script>
    import { mapGetters, mapState } from 'vuex'
    import { validationMixin } from 'vuelidate'
    import validationResultMixin from '@/mixins/validationResultMixin.js';
    import { ratingMessageValidations } from '@/validations';
    export default {
        name: 'RatingMessage',
        mixins: [validationMixin, validationResultMixin],
        validations: ratingMessageValidations,
        computed: {
            ...mapState(['ratingsCount', 'noRatingsString', 'ratingString', 'currentRating']),
            ...mapGetters(['ratingStringReplaced'])
        },
    };
</script>