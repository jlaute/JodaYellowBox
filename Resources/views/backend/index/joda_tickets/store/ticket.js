

Ext.define('Shopware.apps.Index.jodaTicketsWidget.store.Ticket', {
    /**
     * Extends the default Ext Store
     * @string
     */
    extend: 'Shopware.store.Listing',

    model: 'Shopware.apps.Index.jodaTicketsWidget.model.Ticket',

    remoteSort: true,

    autoLoad: true,

    /**
     * This function is used to override the { @link #displayConfig } object of the statics() object.
     *
     * @returns { Object }
     */
    configure: function() {
        return {
            controller: 'JodaTicketsWidget'
        }
    }
});
