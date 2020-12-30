(function($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function() {

        if (!$("#wprem-location-finder-map").length) {
            //return false;
        }

        // - - - - - - - - - - Element Functions - - - - - - - - - - //
        $(document).on("click", ".wprem-locations-gm-marker", function() {
            google.maps.event.trigger(markers[$(".wprem-locations-gm-marker").index($(this))], 'click');
        });

        $(document).on("click", ".wp-directions", function(e) {
            e.preventDefault();
            window.open("https://www.google.ca/maps/dir/" + $("#addressInput").val() + "/" + $(this).attr("data-address"));
        });

        $(document).on("click", ".wprem-locations-pagination", function(e) {
            e.preventDefault();
            var disable = $(this).hasClass("disable");
            if (disable)
                return false;
            if ($(this).index(".wprem-locations-pagination") == 1) {
                page = parseInt(page + 3);
            } else {
                page = parseInt(page - 3);
            }
            search_locations();
        });
        // - - - - - - - - - - end - Element Functions - - - - - - - - - - //

        var map, markers = [],
            marker, infoWindow, locationSelect, bounds, closest = [],
            distance, labelIndex = 1,
            lat, lng, page = 0;

        function compare(a, b) {
            if (a.dist < b.dist)
                return -1;
            if (a.dist > b.dist)
                return 1;
            return 0;
        }

        function search_locations_near(center) {
            clearLocations();
            bounds = new google.maps.LatLngBounds();
            var closest = [],
                c = 1,
                tel_raw, tel, email, address, city, prostate, postal, title, img, flyer, directions, loc, ok, a, latlng, navi, km;
            ok = window.items.filter(function(index) {
                distance = getDistanceFromLatLonInKm(center.lat(), center.lng(), index['location_data']['_data_lat'], index['location_data']['_data_lng']);
                if (distance < $("#radiusSelect").val()) {
                    index.dist = distance;
                    closest.push(index);
                }
            });

            closest.sort(compare);
            navi = closest.length;
            // Uncomment for pagination
            //closest = closest.slice(page, parseInt(page + 3)); // start, end and don't include


            for (a = 0; a < closest.length; a++) {
                lat = closest[a]['location_data']['_data_lat'] ? closest[a]['location_data']['_data_lat'] : '';
                lng = closest[a]['location_data']['_data_lng'] ? closest[a]['location_data']['_data_lng'] : '';
                if (lat.length && lng.length) {
                    latlng = new google.maps.LatLng(
                        parseFloat(lat),
                        parseFloat(lng)
                    );
                    console.log("e: "+closest[a]['location_data']['_data_email_address']);
                    km = '<span class="float-right">' + (Math.round(closest[a]["dist"] * 100) / 100) + '<br/>km</span>';
                    tel_raw = String(closest[a]['location_data']['_data_telephone']).replace(/[^0-9]/g, '');
                    tel = tel_raw ? '<div class="wprem-telephone"><a href="tel:' + tel_raw + '">' + closest[a]['location_data']['_data_telephone'] + '</a></div>' : '';
                    email = (closest[a]['location_data']['_data_email_address'] != "") ? '<span class="wprem-email"><a href="mailto:' + closest[a]['location_data']['_data_email_address'] + '">Email</a></span> | ' : '';
                    address = closest[a]['location_data']['_data_address'] ? closest[a]['location_data']['_data_address'] : '';
                    city = closest[a]['location_data']['_data_city'] ? closest[a]['location_data']['_data_city'] : '';
                    prostate = closest[a]['location_data']['_data_prostate'] ? closest[a]['location_data']['_data_prostate'] : '';
                    postal = closest[a]['location_data']['_data_postalcode'] ? closest[a]['location_data']['_data_postalcode'] : '';
                    title = closest[a]['location_data']['_data_title'] ? '<div class="wprem-title"><a href="' + closest[a]['link'] + '">' + closest[a]['location_data']['_data_title'] + '</a></div>' : '';

                    img = '<div class="wprem-locations-gm-marker" href="#" style="font-size:10px; float:left; position:relative; text-align:center; color:#000"><span style="position:absolute; top:3px; text-align:center; width:100%; font-size:16px; left: 0;">' + c + '</span><img src="/wp-content/plugins/wprem-location-finder/public/img/marker.png" style="width:30px; float:left;"/><br/>' + km + '</div>';
                    flyer = closest[a]['location_data']['_data_file'] ? '<a href="' + closest[a]['location_data']['_data_file'] + '" target="_blank">File</a>' : '';
                    directions = '<a href="#" class="wp-directions" data-address="' + address + ' ' + city + ' ' + prostate + ' ' + postal + '">Directions</a> | ';

                    // - - - - - Setup Output - - - - - //
                    loc = '<div style="border-bottom:1px solid #CCC; margin-bottom:20px; padding-bottom:10px;">' +
                        img + '<div style="line-height: 18px; display: inline-block; margin-left:10px">' + title +
                        '<div class="wp-adddress">' + address + '<br/>' + city + ' ' + prostate + '<br/>' + postal + '</div>' + tel + email + directions + flyer + '</div></div>';
                    $("#wprem-locations").append(loc);
                    createMarker(latlng, title, address);
                    bounds.extend(latlng);
                    c++;
                }
            }
            // Uncomment for pagination
            //$("#wprem-locations").append('<div id="wprem-locations-paginate"><a class="wprem-locations-pagination" data-disable="0">PREV</a><a class="wprem-locations-pagination" data-disable="0">NEXT</a></div>');
            map.fitBounds(bounds);
            map.panBy(1, 1);


            if (page == '0') {
                $(".wprem-locations-pagination", document).eq(0).css("opacity", ".5").addClass("disable");
            }

            if (parseInt(navi - page) <= 3) {
                $(".wprem-locations-pagination", document).eq(1).css("opacity", ".5").addClass("disable");
            }

        }

        function search_locations(x) {
            if (x === 'init') {
                var address = document.getElementById("defaultLocation").value;
                $("#addressInput").val(address);
            } else {
                var address = document.getElementById("addressInput").value;
            }
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    search_locations_near(results[0].geometry.location);
                } else {
                    alert(address + ' not found');
                }
            });
        }

        // Load and store the data once!
        window.initMap = function() {
            var searchButton = document.getElementById("searchButton").onclick = function() {
                page = 0;
                search_locations();
            }
            map = new google.maps.Map(document.getElementById('wprem-location-finder-map'), {
                mapTypeId: 'roadmap',
                maxZoom: 16,
                styles: [{
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#000000"
                        }]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "weight": "4"
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.icon",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "saturation": "-100"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 20
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#000000"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.locality",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },
                    {
                        "featureType": "administrative.neighborhood",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "all",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "lightness": "80"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{
                                "visibility": "simplified"
                            },
                            {
                                "color": "#797979"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },
                    {
                        "featureType": "landscape.natural.landcover",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },
                    {
                        "featureType": "landscape.natural.terrain",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#dfdfdf"
                            },
                            {
                                "lightness": 21
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#fed41c"
                            },
                            {
                                "visibility": "on"
                            },
                            {
                                "weight": "3.00"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#fed41c"
                            },
                            {
                                "gamma": "0.6"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#fed41c"
                            },
                            {
                                "weight": "4.00"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "weight": "1"
                            },
                            {
                                "gamma": "0.6"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#aeaeae"
                            },
                            {
                                "lightness": 18
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                            "color": "#b6b6b6"
                        }]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "all",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#656565"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#c6c6c6"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#b1b1b1"
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#bdbdbd"
                            },
                            {
                                "lightness": 19
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#c0d8e3"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    }
                ]
            });
            infoWindow = new google.maps.InfoWindow();
            bounds = new google.maps.LatLngBounds();

            window.locations = $.getJSON('/wp-json/wp/v2/wprem_locations/', function(data) {
                window.items = data;
                var first_load = window.items.filter(function(index) {
                    var latlng = new google.maps.LatLng(
                        parseFloat(index['location_data']['_data_lat']),
                        parseFloat(index['location_data']['_data_lng'])
                    );

                    if (latlng) {
                        createMarker(latlng, index['location_data']['_data_title'], index['location_data']['_data_address']);
                        bounds.extend(latlng);
                    }
                });
                map.fitBounds(bounds);
            });

            google.maps.event.addDomListener(window, 'resize', function() {
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
                map.fitBounds(bounds);
            });

            map.addListener('bounds_changed', function() {
                for (var i = 0; i < markers.length; i++) {
                    if (check_is_in_or_out(markers[i])) {
                        //console.log(markers[i]);
                    }
                }
            });

            google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
                search_locations('init');
                $("#location-finder-container").animate({
                    opacity: 1
                }, 500);
                $("#location-loader").remove();
            });

        }

        function check_is_in_or_out(marker) {
            return map.getBounds().contains(marker.getPosition());
        }

        function clearLocations() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
                console.log("clear: " + i);
            }
            markers = [];
            labelIndex = 1;
            $("#wprem-locations").html('');
            infoWindow.close();
        }

        function createMarker(latlng, name, address) {
            var html = "<b>" + name + "</b> <br/>" + address;
            var image = { labelOrigin: new google.maps.Point(15, 15), url: '/wp-content/plugins/wprem-location-finder/public/img/marker.png', scaledSize: new google.maps.Size(30, 43), origin: new google.maps.Point(0, 0), anchor: new google.maps.Point(0, 43) };
            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                label: String(labelIndex++),
                icon: image
            });
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
            markers.push(marker);
        }

        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2 - lat1); // deg2rad below
            var dLon = deg2rad(lon2 - lon1);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c; // Distance in km
            return d;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180)
        }

        if ($("#wprem-location-finder-map").length) {
            initMap();
        }
    });

})(jQuery);