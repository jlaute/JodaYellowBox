;(function($) {
    'use strict';

    var $confirmation = null,
        ignoreTransitions = [
            'reopen'
        ],
        getConfirmYesButton = function () {
            return $confirmation.find('.confirm-yes');
        },
        getConfirmNoButton = function () {
            return $confirmation.find('.confirm-no');
        },
        hideConfirmation = function () {
            return $confirmation.hide();
        },
        showConfirmation = function () {
            return $confirmation.show();
        };

    /**
     * Enables and shows the confirmation in yellowbox
     */
    $.subscribe('plugin/jodaYellowBox/canTransition', function (event, me, data) {
        if ($.inArray(data.ticketTransition, ignoreTransitions) !== -1) {
            return me.canTransition = true;
        }

        $confirmation = $('#confirmation');

        getConfirmYesButton().on('click', function (e) {
            $(this).off(e.type);
            hideConfirmation();

            me.executeTransition(data);
        });

        getConfirmNoButton().on('click', function (e) {
            $(this).off(e.type);
            hideConfirmation();
        });

        showConfirmation();
        me.canTransition = false;
    });
})(jQuery);
