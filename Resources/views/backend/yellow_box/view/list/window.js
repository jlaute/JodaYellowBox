//

Ext.define('Shopware.apps.YellowBox.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.release-list-window',
    height: 450,
    title : '{s name=window_title}Release listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.YellowBox.view.list.Release',
            listingStore: 'Shopware.apps.YellowBox.store.Release'
        };
    }
});
