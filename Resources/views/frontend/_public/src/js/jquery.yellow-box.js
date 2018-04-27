;(function($, window) {
    'use strict';

    $.plugin('jodaYellowBox', {
        defaults: {
            minimizeClass: 'minimized',

            transitionUrl: null,

            transitionButtonSelector: '.entry--actions .btn',

            boxContentSelector: '.box--content',

            commentFormSelector: '.comment-form',

            abortButtonSelector: '.abort',

            closeButtonSelector: '.close',

            rejectTransition: 'reject'
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
            me.$commentForm = me.$el.find(me.opts.commentFormSelector);
            me.$abortButton = me.$commentForm.find(me.opts.abortButtonSelector);
            me.$commentSubmitButton = me.$commentForm.find("button[type='submit']");
            me.$closeButton = me.$el.find(me.opts.closeButtonSelector);

            me._on(me.$closeButton, 'click', $.proxy(me.onCloseClick, me));
            me._on(me.$el, 'click', $.proxy(me.onElementClick, me));
            me._on(me.$transitionButtons, 'click', $.proxy(me.onTransitionButtonClick, me));
            me._on(me.$abortButton, 'click', $.proxy(me.toggleCommentForm, me));
            me._on(me.$commentSubmitButton, 'click', $.proxy(me.onSubmitCommentForm, me))
        },

        /**
         * Calls when user clicks on close button in yellow box
         */
        onCloseClick: function (event) {
            var me = this;

            event.preventDefault();
            event.stopPropagation();

            me.$el.toggleClass(me.opts.minimizeClass);

            $.publish('plugin/jodaYellowBox/onCloseClick', [ me ]);
        },

        /**
         * Call only when element is minimized
         *
         * @param event
         */
        onElementClick: function(event) {
            var me = this;

            if (!me.$el.hasClass(me.opts.minimizeClass)) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            me.$el.toggleClass(me.opts.minimizeClass);

            $.publish('plugin/jodaYellowBox/onElementClick', [ me ]);
        },

        /**
         * Calls when user clicks on transition button in yellow box
         * @param event
         */
        onTransitionButtonClick: function (event) {
            var me = this,
                $button = $(event.target).is('i') ? $(event.target).parent() : $(event.target), //sometimes the target is the underlying 'i' element. Makes no sense, but the console doesn`t lie
                ticketId = $button.closest('li').data('ticket-id'),
                transition = $button.data('ticket-transition');

            event.preventDefault();
            event.stopPropagation();

            if ($button.hasClass(me.opts.rejectTransition)) {
                me.onRejectButtonClick(ticketId, $button);
                return;
            }

            var data = {
                ticketId: ticketId,
                ticketTransition: transition
            };
            me.callTransition(data);

            $.publish('plugin/jodaYellowBox/onTransitionButtonClick', [ me, event ]);
        },

        /**
         *
         * @param ticketId
         * @param $button
         */
        onRejectButtonClick: function (ticketId, $button) {
            var me = this;

            me.$commentForm.find("input[name='ticketId']").val(ticketId);
            me.$commentForm.find("input[name='ticketTransition']").val(me.opts.rejectTransition);
            me.$commentForm.find(".ticketnr").html($button.data('ticket-name'));

            me.toggleCommentForm();
        },

        toggleCommentForm: function() {
            var me = this,
                $boxContent = me.$el.find(me.opts.boxContentSelector);

            $boxContent.toggle();
            me.$commentForm.toggle();
        },

        /**
         * @param event
         */
        onSubmitCommentForm: function(event) {
            var me = this;

            event.preventDefault();
            event.stopPropagation();

            var data = me.$commentForm.serialize();
            me.callTransition(data);
        },

        /**
         * Send the transition to the server!
         *
         * @param data
         */
        callTransition: function (data) {
            var me = this;

            $.loadingIndicator.open({
                closeOnClick: false,
                renderElement: me.$el
            });

            $.ajax({
                'url': me.opts.transitionUrl,
                'data': data,
                'success': function (content) {
                    $.loadingIndicator.close(function () {
                        me._setContent(content);
                        me._removeEvents();
                        me.registerEvents();
                    });

                    $.publish('plugin/jodaYellowBox/onTransitionSuccess', [ me, content ]);
                }
            });
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
