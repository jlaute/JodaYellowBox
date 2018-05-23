//

Ext.define('Shopware.apps.YellowBox.view.list.Release', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.release-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.YellowBox.view.detail.Window'
        };
    }
});
