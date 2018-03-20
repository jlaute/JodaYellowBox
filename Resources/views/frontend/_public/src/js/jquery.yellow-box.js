;(function($) {
    'use strict';

    $.plugin('jodaYellowBox', {
        defaults: {},

        /**
         * Initializes the yellow box plugin
         */
        init: function() {
            var me = this;

            me.applyDataAttributes();
            me.registerEvents();

            $.publish('plugin/jodaYellowBox/init', [ me ]);
        },

        /**
         * Registers our yellow box events
         */
        registerEvents: function () {
            var me = this;

            me._on(me.$el, 'click', $.proxy(me.onClick, me));
        },

        /**
         * Calls when someone clicks on yellow box
         */
        onClick: function () {
            var me = this;

            me.$el.toggleClass('minimized');
            $.publish('plugin/jodaYellowBox/onClick', [ me ]);
        }
    });

    $(function () {
        $('*[data-yellow-box="true"]').jodaYellowBox();
    });
})(jQuery);
