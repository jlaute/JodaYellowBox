

Ext.define('Shopware.apps.Index.jodaTicketsWidget.model.Ticket', {

    extend: 'Ext.data.Model',

    fields: [
        'id',
        'number',
        'name',
        'created',
        'possible_transitions'
    ]
});
