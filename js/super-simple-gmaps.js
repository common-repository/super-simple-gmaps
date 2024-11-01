jQuery(document).ready(function ($) {
    function init_map() {

        if (typeof jsparams != 'undefined' && typeof jsparams.lat != 'undefined' && typeof jsparams.long != 'undefined') {
            var var_location = new google.maps.LatLng(jsparams.lat, jsparams.long);
            var var_mapoptions = {
                center: var_location,
                zoom: 14
            };
            var var_marker = new google.maps.Marker({
                position: var_location,
                map: var_map,
                title: jsparams.gname});


            var var_map = new google.maps.Map(document.getElementById("map-container"), var_mapoptions);
            var_marker.setMap(var_map);
        }


    }
    google.maps.event.addDomListener(window, 'load', init_map);

});