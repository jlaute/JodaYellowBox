//

Ext.define('Shopware.apps.YellowBox', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.YellowBox',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Release',

        'detail.Release',
        'detail.Window'
    ],

    models: [ 'Release' ],
    stores: [ 'Release' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});
