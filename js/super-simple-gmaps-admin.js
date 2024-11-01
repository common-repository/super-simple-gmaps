jQuery(document).ready(function ($) {


    tinymce.PluginManager.add('simple_gmaps_button', function (editor, url) {
        editor.addButton('simple_gmaps_button', {
            title: 'Add Google Maps',
            icon: 'icon dashicons-admin-site',
            onclick: function () {
                editor.windowManager.open({
                    title: 'Google Maps',
                    body: [
                        {type: 'textbox', name: 'title', label: 'Titel', value: 'Google Maps'},
                        {type: 'textbox', name: 'lat', label: 'Latitude', value: '0'},
                        {type: 'textbox', name: 'long', label: 'Longitude', value: '0'}],
                    onsubmit: function (e) {
                        editor.insertContent('[google-maps gname="' + e.data.title + '" lat="' + e.data.lat + '" long="' + e.data.long + '"/]');
                    }
                });
            }
        });
    });
});
  