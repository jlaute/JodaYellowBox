//

Ext.define('Shopware.apps.YellowBox.store.Release', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'YellowBox'
        };
    },

    model: 'Shopware.apps.YellowBox.model.Release'
});
