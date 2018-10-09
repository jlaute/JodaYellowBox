//{namespace name=backend/index/view/widgets}

Ext.define('Shopware.apps.Index.jodaTicketsWidget.view.Main', {
    /**
     * Extend the base widget view
     */
    extend: 'Shopware.apps.Index.view.widgets.Base',

    /**
     * Set alias so the widget can be identified per widget name
     */
    alias: 'widget.joda-tickets',

    /**
     * Set the south handle so the widget height can be resized.
     */
    resizable: {
        handles: 's'
    },

    /**
     * Minimum / Default height of the widget
     */
    minHeight: 250,

    /**
     * Maximum height that the widget can have
     */
    maxHeight: 600,

    /**
     * Initializes the widget.
     * Creates the account store and the Grid for showing the newest registrations.
     * Adds a refresh button to the header to manually refresh the grid.
     *
     * @public
     * @return void
     */
    initComponent: function() {
        var me = this;

        me.accountStore = Ext.create('Shopware.apps.Index.jodaTicketsWidget.store.Ticket');

        me.items = [
            me.createTicketGrid()
        ];

        me.tools = [{
            type: 'refresh',
            scope: me,
            handler: me.refreshView
        }];

        me.callParent(arguments);
    },

    /**
     * Creates the main Widget grid and its columns
     *
     * @returns { Ext.grid.Panel }
     */
    createTicketGrid: function() {
        var me = this;

        return Ext.create('Ext.grid.Panel', {
            border: 0,
            store: me.accountStore,
            columns: me.createColumns()
        });
    },

    /**
     * Helper method which creates the columns for the
     * grid panel in this widget.
     *
     * @return { Array } - generated columns
     */
    createColumns: function() {
        var me = this;

        return [{
            dataIndex: 'number',
            header: '{s name=joda-tickets/number}{/s}',
            width: 90,
            renderer: function (value, metaData, record) {
                if (record.data.state === 'approved') {
                    value = '<span style="color: greenyellow">'+value+'</span>';
                } else if (record.data.state === 'rejected') {
                    value = '<span style="color: red">'+value+'</span>';
                }

                return value;
            }
        }, {
            dataIndex: 'name',
            header: '{s name=joda-tickets/name}{/s}',
            flex: 1,
            renderer: function (value, metaData, record) {
                if (record.data.state === 'approved') {
                    value = '<span style="color: greenyellow">'+value+'</span>';
                } else if (record.data.state === 'rejected') {
                    value = '<span style="color: red">'+value+'</span>';
                }

                return value;
            }
        }, {
            xtype: 'actioncolumn',
            width: 80,
            items: [{
                tooltip: '{s name=joda-tickets/approve}{/s}',
                getClass: function(v, meta, rec) {
                    var transitions = rec.get('possible_transitions');
                    return me.getCorrectClassName(transitions, 'approve');
                },
                handler: function(view, rowIndex, colIndex, item, event, record) {
                    me.callTransition('approve');
                }
            }, {
                tooltip: '{s name=joda-tickets/reject}{/s}',
                getClass: function(v, meta, rec) {
                    var transitions = rec.get('possible_transitions');
                    return me.getCorrectClassName(transitions, 'reject');
                },
                handler: function(view, rowIndex, colIndex, item, event, record) {
                    me.callTransition('reject');
                }
            }, {
                tooltip: '{s name=joda-tickets/reopen}{/s}',
                getClass: function(v, meta, rec) {
                    var transitions = rec.get('possible_transitions');
                    return me.getCorrectClassName(transitions, 'reopen');
                },
                handler: function(view, rowIndex, colIndex, item, event, record) {
                    me.callTransition('reopen');
                }
            }]
        }]
    },

    /**
     * Refresh the account store if its available
     */
    refreshView: function() {
        var me = this;

        if(!me.accountStore) {
            return;
        }

        me.accountStore.reload();
    },

    callTransition: function(transition) {
        var me = this;

        Ext.Ajax.request({
            url: '{url controller=JodaTicketsWidget action=transition}',
            method: 'POST',
            params: {
                id: record.get('id'),
                transition: 'reopen'
            },
            success: function (response, operation) {
                me.refreshView();
            }
        })
    },

    getCorrectClassName: function (transitions, action) {
        if (typeof transitions === 'object') {
            transitions = Object.values(transitions);
        }
        if (Ext.isArray(transitions) && Ext.Array.contains(transitions, action)) {
            if (action === 'reopen') {
                return 'sprite-pencil';
            }
            if (action === 'reject') {
                return 'sprite-cross';
            }
            if (action === 'approve') {
                return 'sprite-tick';
            }
        }
        return 'x-hide-display';
    }
});
