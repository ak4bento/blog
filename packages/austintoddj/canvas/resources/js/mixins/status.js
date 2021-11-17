import isEmpty from 'lodash/isEmpty';

export default {
    methods: {
        isDraft(date) {
            return isEmpty(date) || this.isScheduled(date);
        },

        isScheduled(date) {
            return !isEmpty(date) && new Date(date) > new Date();
        },

        isPublished(date) {
            return !isEmpty(date) && new Date(date) < new Date();
        },
    },
};
