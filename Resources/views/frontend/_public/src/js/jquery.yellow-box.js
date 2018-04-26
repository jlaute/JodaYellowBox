;(function($, window) {
    'use strict';

    $.plugin('jodaYellowBox', {
        defaults: {
            minimizeClass: 'minimized',

            transitionUrl: null,

            transitionButtonSelector: '.entry--actions .btn'
        },

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

            me.$transitionButtons = $(me.opts.transitionButtonSelector);

            me._on(me.$el, 'click', $.proxy(me.onClick, me));
            me._on(me.$transitionButtons, 'click', $.proxy(me.onTransitionButtonClick, me));
        },

        /**
         * Calls when user clicks on yellow box
         */
        onClick: function () {
            var me = this;

            me.$el.toggleClass(me.opts.minimizeClass);

            $.publish('plugin/jodaYellowBox/onClick', [ me ]);
        },

        /**
         * Calls when user clicks on transition button in yellow box
         * @param event
         */
        onTransitionButtonClick: function (event) {
            var me = this,
                opts = me.opts,
                $button = $(event.target).is('i') ? $(event.target).parent() : $(event.target), //sometimes the target is the underlying 'i' element. Makes no sense, but the console doesn`t lie
                ticketId = $button.closest('li').data('ticket-id'),
                transition = $button.data('ticket-transition');

            event.preventDefault();
            event.stopPropagation();

            $.loadingIndicator.open({
                closeOnClick: false,
                renderElement: $button
            });

            $.ajax({
                'url': opts.transitionUrl,
                'data': {
                    ticketId: ticketId,
                    ticketTransition: transition
                },
                'success': function (content) {
                    $.loadingIndicator.close(function () {
                        me._setContent(content);
                        me._removeEvents();
                        me.registerEvents();
                    });

                    $.publish('plugin/jodaYellowBox/onLoadYellowBoxSuccess', [ me, content ]);
                }
            });

            $.publish('plugin/jodaYellowBox/onTransitionButtonClick', [ me, event ]);
        },

        /**
         * Sets a new content for yellow box
         * @param content
         * @private
         */
        _setContent: function (content) {
            var me = this;

            me.$el.html(content);

            $.publish('plugin/jodaYellowBox/onSetContent', [ me, content ]);
        },

        /**
         * Removes current registered events
         * @private
         */
        _removeEvents: function () {
            var me = this;

            me._off(me.$el, 'click');
            me._off(me.$transitionButtons, 'click');

            $.publish('plugin/jodaYellowBox/onRemoveEvents', [ me ]);
        }
    });

    $(function () {
        $('*[data-yellow-box="true"]').jodaYellowBox();
    });
})(jQuery, window);
