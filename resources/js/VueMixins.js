/**
 * Vue Mixins
 */

import Vue from "vue";

Vue.mixin({
    methods: {
        /**
         * Create display message using "toast" bootstrap-vue component.
         */
        toast: function (toaster, variant, title, message) {
            this.$bvToast.toast(message, {
                title: title,
                variant: variant,
                toaster: toaster,
                solid: true,
                appendToast: true,
            })
        },
        /**
         * Format the date of the article.
         */
        dateAbbreviation: function (date) {
            let dateTimestamp = new Date(date);

            let day   = dateTimestamp.getDate();
            let month = dateTimestamp.getMonth();
            let year  = dateTimestamp.getFullYear();

            switch (month) {
                case 0:
                    month = 'Ian';
                    break;
                case 1:
                    month = 'Feb';
                    break;
                case 2:
                    month = 'Mart';
                    break;
                case 3:
                    month = 'Apr';
                    break;
                case 4:
                    month = 'Mai';
                    break;
                case 5:
                    month = 'Iun';
                    break;
                case 6:
                    month = 'Iul';
                    break;
                case 7:
                    month = 'Aug';
                    break;
                case 8:
                    month = 'Sept';
                    break;
                case 9:
                    month = 'Oct';
                    break;
                case 10:
                    month = 'Nov';
                    break;
                case 11:
                    month = 'Dec';
                    break;
            }

            return month + ' ' + day + ', ' + year;
        }
    }
})
