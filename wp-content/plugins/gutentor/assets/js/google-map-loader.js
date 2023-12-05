const initMapScript = () => {
    let maps = [];
    maps = window.gutentorGoogleMaps;
    if( maps){
        maps.forEach( map => {
            const googleMap = new google.maps.Map( document.getElementById( map.container ), {
                center: {
                    lat: Number( map.attributes.latitude ),
                    lng: Number( map.attributes.longitude )
                },
                gestureHandling: 'cooperative',
                zoom: map.attributes.zoom,
                mapTypeId: map.attributes.type,
                draggable: map.attributes.draggable,
                mapTypeControl: map.attributes.mapTypeControl,
                zoomControl: map.attributes.zoomControl,
                fullscreenControl: map.attributes.fullscreenControl,
                streetViewControl: map.attributes.streetViewControl
            });

            if ( ! map.attributes.id && map.attributes.location ) {
                const request = {
                    query: map.attributes.location,
                    fields: [ 'name', 'geometry' ]
                };

                const service = new google.maps.places.PlacesService( googleMap );

                service.findPlaceFromQuery( request, ( results, status ) => {
                    if ( status === google.maps.places.PlacesServiceStatus.OK ) {
                        if ( 0 < results.length ) {
                            googleMap.setCenter( results[0].geometry.location );
                        }
                    }
                });
            }

            if ( map.attributes.markers && 0 < map.attributes.markers.length ) {
                map.attributes.markers.forEach( marker => {
                    let position;
                    if( marker.hasOwnProperty('e4Lat')){
                        position = new google.maps.LatLng( marker.e4Lat, marker.e4Lon );
                    }
                    else{
                        position = new google.maps.LatLng( marker.latitude, marker.longitude );
                    }
                    const mark = new google.maps.Marker({
                        position,
                        map: googleMap,
                        title: marker.title
                    });

                    if ( marker.title || marker.description ) {
                        if (!window.infoWindow) {
                            window.infoWindow = new google.maps.InfoWindow();
                        }

                        mark.addListener( 'click', () => {
                            if (!window.infoWindow) return;

                            window.infoWindow.setContent( getInfoWindowContent( marker ) );
                            window.infoWindow.open( googleMap, mark );
                        });
                    }
                });
            }
        });
    }
};

function getInfoWindowContent( marker ) {
    return `<div class="gutentor-map-overview"><h6 class="gutentor-map-overview-title">${ marker.title }</h6><div class="gutentor-map-overview-content">${ marker.description ? `<p>${ marker.description }</p>` : '' }</div></div>`;
}

window.initMapScript = initMapScript;