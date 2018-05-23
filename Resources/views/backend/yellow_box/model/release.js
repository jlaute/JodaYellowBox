
Ext.define('Shopware.apps.YellowBox.model.Release', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'YellowBox',
            detail: 'Shopware.apps.YellowBox.view.detail.Release'
        };
    },

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string' },
        { name : 'releaseDate', type: 'date', useNull: false },
        { name : 'externalId', type: 'string' },
    ]
});
