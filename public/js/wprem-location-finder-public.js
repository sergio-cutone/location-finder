(function($) {
    $(function() {

        /* WPML LANGUAGE SETTINGS */
        var text = [];
        text['directions'] = 'Get Directions';
        text['details'] = 'View Details';
        text['emaillocation'] = 'Email Location';

        if ($("html").attr("lang") === 'fr-CA'){
            text['directions'] = "Obtenir un itinéraire";
            text['details'] = "Voir les détails";
            text['emaillocation'] = 'Enovyer un courriel';
        }

        /* end - WPML LANGUAGE SETTINGS */

        if ($("#wprem-location-finder-map").length == 0) {
            return false;
        }

        var theRadius;

        function returnGEO(address) {
            map1 = "https://geocode.xyz/" + address + "?json=1";
            //map3 = "https://nominatim.openstreetmap.org/search/" + address + "?format=json&addressdetails=1&limit=1";
            map2 = "https://geogratis.gc.ca/services/geolocation/en/locate?q=" + address;
            var live_map = 2;
            var kms = parseInt($("#radiusSelect").val() * 1000);
            if (theRadius != undefined) {
                map.removeLayer(theRadius);
            };

            if (live_map == 1) {
                $.getJSON(map1, function(data) {}).done(function(data) {
                    if (data.latt != '0.00000' || data.longt != '0.00000') {
                        color = ($("#wprem-location-finder-map").attr("data-radiuscolor").length > 0) ? $("#wprem-location-finder-map").attr("data-radiuscolor") : '#CC0000';
                        if ($("#wprem-location-finder-map").attr("data-radiusshow") == 'y') {
                            theRadius = L.circle([data.latt, data.longt], { radius: kms, color: color }).addTo(map);
                        }
                        locationChecker(data.latt, data.longt);
                    } else {
                        $(".wprem-locations-error").show();
                    }
                });
            } else {
                $.getJSON(map2, function(data) {}).done(function(data) {
                    var lat = data[0].geometry.coordinates[0];
                    var lon = data[0].geometry.coordinates[1];
                    //console.log(lon+" : "+lat);
                    if (lon != '0.00000' || lat != '0.00000') {
                        color = ($("#wprem-location-finder-map").attr("data-radiuscolor").length > 0) ? $("#wprem-location-finder-map").attr("data-radiuscolor") : '#CC0000';
                        if ($("#wprem-location-finder-map").attr("data-radiusshow") == 'y') {
                            theRadius = L.circle([lon, lat], { radius: kms, color: color }).addTo(map);
                        }
                        locationChecker(lon, lat);
                    } else {
                        $(".wprem-locations-error").show();
                    }
                });
            }
        }

        // #-#-#-#-# FOR PROXIMITY / REGION SEARCH
        function returnRegion(v) {
            $(".wprem-location-container").show();
            var found = [];
            var newMarkers = [];
            var hit = false;
            var defaultlocation = $("#wprem-location-finder").data("default");
            $(".wprem-location-container").each(function() {
                var codes = new Array();
                var c = $(".wprem-locations-gm-marker", this).data("index");
                if ($(this).data("region")) {
                    regions = $(this).data("region").replace(/\s/g, '');
                    codes = regions.split(",");
                    if (codes.indexOf(v.toLowerCase()) >= 0) {
                        found.push(c);
                        newMarkers.push(markers[c]);
                        map.addLayer(markers[c]);
                        $(this).show();
                        hit = true;
                    }else{
                        map.removeLayer(markers[c]);
                        $(this).hide();
                    }
                } else {
                    $(this).hide();
                    map.removeLayer(markers[c]);
                }
            });
            if (!hit){
                $(".wprem-location-container").each(function(){
                    if ($(this).data("locationid") === defaultlocation ){
                        $(this).show();
                        map.addLayer(markers[$(".wprem-locations-gm-marker", this).data("index")]);
                        newMarkers.push(markers[$(".wprem-locations-gm-marker", this).data("index")]);
                    }
                })
            }
            group = new L.featureGroup(newMarkers);
            mapCentre();
        }
        // #-#-#-#-# end - FOR PROXIMITY / REGION SEARCH

        function locationChecker(lat, lng) {
            var c = 0;
            var f = 1;
            var found = false;
            var newMarkers = [];
            var locationArrNew = [];
            $("#wprem-locations").html("");
            markers.forEach(function(location) {
                distanceTo = (markers[c]._latlng.distanceTo([lat, lng]) / 1000);
                //console.log("distance: " + distanceTo + " :: " + parseInt($("#radiusSelect").val()) + " | " + markers[c]._latlng + " ::: " + markers[c]._latlng.distanceTo([lat, lng]));
                if (parseFloat(distanceTo) > parseInt($("#radiusSelect").val())) {
                    map.removeLayer(markers[c]);
                    //map.removeLayer(pop[c]);
                } else {
                    found = true;
                    locationArr[c][13] = parseInt(distanceTo);
                    map.addLayer(markers[c]);
                    locals[c].push();
                    //map.addLayer(pop[c]);
                    newMarkers.push(markers[c]);
                    locationArrNew.push(locationArr[c]);
                }
                c++;
            });

            // #-#-#-#-# SORT THE LOCATIONS BASED ON DISTANCE
            if (found == true) {
                group = new L.featureGroup(newMarkers);
                mapCentre();
                locationOut(locationArrNew.sort(compareSecondColumn));
                // Open popup for closest location
                markers[$(".wprem-locations-gm-marker").eq(0).attr("data-index")].openPopup();
            } else {
                $("#wprem-locations").append("<p>Sorry no results found, please enter a different address</p>").show();
            }
        }

        // #-#-#-#-#- CLICK FUNCTIONS -#-#-#-#-# //

        // #-#-#-#-#- SEARCH
        $(document).on("click", "#searchButton", function() {
            doSearch();
        });
        $(document).keypress(function (e) {
            var key = e.which;
            if(key == 13) {
                if ($("#addressInput").is(":focus")) {
                    doSearch();
                }
            }
        });

        function doSearch(){
            $(".wprem-locations-error").hide();
            userAddress = $("#addressInput").val();
            if (userAddress.length < 3) {
                $(".wprem-locations-error").show();
            } else {
                $("#wprem-locations-error").hide();
                if ($("#wprem-location-finder").attr("data-region") === "0") {
                    returnGEO(userAddress);
                } else {
                    returnRegion($("#addressInput").val());
                }
            }
        }

        // #-#-#-#-#- FILTER
        $(document).on("click", ".wprem-location-filter-cat", function() {
            $("#wprem-locations").html("");
            c = 0;
            catname = $(this).attr("data-name");
            filterLocationsArr = [];
            if (theRadius) {
                map.removeLayer(theRadius);
            }

            markers.forEach(function(location) {
                map.removeLayer(markers[c]);
                if (locationArr[c][17] === catname) {
                    map.addLayer(markers[c]);
                    filterLocationsArr.push(locationArr[c]);
                }
                c++;
            });
            locationOut(filterLocationsArr);
            $(".wprem-kms", document).html('');
            group = new L.featureGroup(markers);
            mapCentre();
        });

        // #-#-#-#-#- REFRESH BUTTON
        $(document).on("click", "#refreshButton", function() {
            $("#wprem-locations").html("");
            c = 0;
            if (theRadius) {
                map.removeLayer(theRadius);
            }
            markers.forEach(function(location) {
                map.addLayer(markers[c]);
                c++;
            });
            locationOut(locationArr);
            $(".wprem-kms", document).html('');
            group = new L.featureGroup(markers);
            mapCentre();
        });

        // #-#-#-#-#- SCROLL AFTER PIN CLICK wprem-locations-gm-marker
        function pin_scroll() {
            header = $(".fl-theme-builder-header-shrink").length ? $(".fl-theme-builder-header-shrink").outerHeight() : 0;
            scrolloffset = parseInt($("#wprem-location-finder").attr("data-scroll"));
            if (!$(".fl-theme-builder-header-sticky").length) {
                scrolloffset = 0;
            }
            if ($("#wpadminbar").length) {
                scrolloffset = parseInt(scrolloffset + $("#wpadminbar").outerHeight());
            }
            $('html, body').animate({
                scrollTop: $("#wprem-location-finder").offset().top - scrolloffset - 10
            }, 500, 'swing');
        }

        // #-#-#-#-#- MARKER
        $(document).on("click", ".wprem-locations-gm-marker", function() {
            markers[$(this).attr("data-index")].openPopup();
            pin_scroll();
        });

        // #-#-#-#-# end CLICK FUNCTIONS -#-#-#-#-# //

        // #-#-#-#-# COMPARE -#-#-#-#-# //
        function compareSecondColumn(a, b) {
            if (a[13] === b[13]) {
                return 0;
            } else {
                return (a[13] < b[13]) ? -1 : 1;
            }
        }

        // #-#-#-#-# OUTPUT OF LOCATIONS LIST #-#-#-#-# //
        function locationOut(locationArr) {
            //console.log("LocARR: "+locationArr);
            var f = 1;
            var detail_options = $(".wprem-locations-container").attr("data-details");
            var see_details_main = $(".wprem-locations-container").attr("data-seedetailsmain");
            if (detail_options) {
                var ob = JSON.parse(detail_options);
            }
            for (var a = 0; a < locationArr.length; a++) {
                if (locationArr[a][16]) {
                    var getIcon = locationArr[a][16];
                } else {
                    var getIcon = $("#defaultMarker").val() ? $("#defaultMarker").val() : '/wp-content/plugins/wprem-location-finder/public/img/marker.png';
                }
                if ($(".wprem-locations-container").attr("data-titlelink") == 1) {
                    titlelink = '<a href="' + locationArr[a][15] + '">' + locationArr[a][0] + '</a>';
                } else {
                    titlelink = '<span class="wprem-locations-gm-marker" data-index="' + locationArr[a][14] + '">' + locationArr[a][0] + '</span>';
                }

                var addresswrap = new Array('', '');
                if (ob.address == "1" && ob.unit == "1") {
                    addresswrap = new Array('<div class="full_address">', '</div>');
                }

                var location_details = '';
                if (ob.title == "1") location_details = '<span class="wprem-title">' + titlelink + '</span>';
                if (ob.address == "1") location_details = location_details + addresswrap[0] + '<span class="address">' + locationArr[a][7] + "</span>";
                if (ob.unit == "1") location_details = location_details + ' <span class="unit">' + locationArr[a][8] + '</span>' + addresswrap[1];
                if (ob.city == "1") location_details = location_details + '<span class="city">' + locationArr[a][9] + ' </span>';
                if (ob.prostate == "1") location_details = location_details + '<span class="prostate">' + locationArr[a][20] + "</span>";
                if (ob.postal == "1") location_details = location_details + '<span class="postal">' + locationArr[a][10] + "</span>";
                if (ob.country == "1") location_details = location_details + '<span class="country">' + locationArr[a][19] + "</span>";
                if (ob.tel == "1" && locationArr[a][4]) location_details = location_details + '<span class="tel"><span class="tel_title">Tel: </span><a href="tel:' + locationArr[a][4] + '">' + locationArr[a][4] + "</a></span>";
                if (ob.toll == "1" && locationArr[a][5]) location_details = location_details + '<span class="toll"><span class="toll_title">Toll Free: </span><a href="tel:' + locationArr[a][5] + '">' + locationArr[a][5] + "</a></span>";
                if (ob.fax == "1" && locationArr[a][6]) location_details = location_details + '<span class="fax"><span class="fax_title">Fax: </span>' + locationArr[a][6] + "</span>";
                if (ob.email == "1" && locationArr[a][3]) location_details = location_details + '<span class="email"><a href="mailto:' + locationArr[a][3] + '">' + locationArr[a][3] + "</a></span>";
                if (see_details_main == 1 && locationArr[a][23] != 'on') location_details = location_details + '<span class="seedetails"><a href="' + locationArr[a][18] + '">'+text['details']+'</a></span>';

                /*title = '<div class="wprem-title">'+titlelink+'</div>';
                address = '<div>' + locationArr[a][7] + locationArr[a][8] + '<br/>' + locationArr[a][9] + '<br/>' + locationArr[a][10] + '</div>';
                telephone = locationArr[a][4] ? '<div class="wprem-telephone">Tel: <a href="tel:' + locationArr[a][4] + '">' + locationArr[a][4] + '</a></div>' : '';
                email = locationArr[a][3] ? '<div class="wprem-email"><a href="mailto:'+locationArr[a][3]+'">'+locationArr[a][3]+'</a></div>' : '';
                tollfree = locationArr[a][5] ? '<div class="wprem-tollfree">TollFree: <a href="tel:'+locationArr[a][5]+'">'+locationArr[a][5]+'</a></div>' : '';
                fax = locationArr[a][6] ? '<div class="wprem-fax">Fax: <a href="tel:'+locationArr[a][6]+'">'+locationArr[a][6]+'</a></div>' : '';
                */
                download = locationArr[a][12] ? locationArr[a][12] : 'File';
                file = locationArr[a][11] ? '<div><a href="' + locationArr[a][11] + '" target="_blank">' + download + '</a></div>' : '';
                kms = (locationArr[a][13] > 0) ? '<span class="wprem-kms" style="font-size:14px; line-height:12px">' + locationArr[a][13] + '<br/>kms</span>' : '';
                cat = (locationArr[a][17] && ($(".wprem-locations-container").attr("data-cat") == 1)) ? '<div class="wprem-location-cat wprem-location-cat_' + locationArr[a][17].toLowerCase() + '">' + locationArr[a][17] + '</div>' : '';

                postalregion = locationArr[a][21] ? 'data-region="' + locationArr[a][21].toString().toLowerCase() + '"' : '';

                // #-#-#-#-# APPEND TO SCREEN #-#-#-#-# /
                if ($(".wprem-locations-container").attr("data-columns") === "1") {
                    $("#wprem-locations").append('<div class="col-xs-6 col-md-' + $(".wprem-locations-container").attr("data-columnsize") + ' wprem-location-container wprem-locations-column wprem-locations-gm-marker" data-index="' + locationArr[a][14] + '" ' + postalregion + ' data-locationid="'+locationArr[a][22]+'"">' +
                        '<div class="wprem-locations-gm-marker-container">' +
                        '<span class="wprem-locations-gm-marker-position">' + (a + 1) + '</span>' +
                        '<img src="' + getIcon + '" /><br/>' + kms +
                        '</div>' +
                        '<div class="wprem-location-details">' + location_details + file + cat + '</div>' +
                        '</div>');
                } else {
                    $("#wprem-locations").append('<div class="wprem-location-container wprem-locations-single-column" ' + postalregion + ' data-locationid="'+locationArr[a][22]+'">' +
                        '<div class="wprem-locations-gm-marker wprem-locations-gm-marker-container" data-index="' + locationArr[a][14] + '">' +
                        '<span class="wprem-locations-gm-marker-position">' + (a + 1) + '</span>' +
                        '<img src="' + getIcon + '" /><br/>' + kms +
                        '</div>' +
                        '<div class="wprem-location-details">' + location_details + file + cat + '</div>' +
                        '</div>');
                }
                $(document).find("[data-markerid='" + locationArr[a][14] + "']").html((a + 1));
            }
        }

        function getPin(i, q) {
            out = '';
            $("#marker-cats span").each(function() {
                if ($(this).attr("data-id") == i && q == 'pin') {
                    out = $(this).html();
                }
                if ($(this).attr("data-id") == i && q == 'cat') {
                    out = $(this).attr("data-name");
                }
            });
            return out;
        }

        function mapInit(cat) {
            locationArr = [];
            locals = [];
            var c = 0;
            var locationObj;
            var orderby = $(".wprem-locations-container").attr("data-orderby");
            var order = $(".wprem-locations-container").attr("data-order");
            var detaulLocation = $(".wprem-locations-container").data("default");
            var baseURL = $(".wprem-locations-container").attr("data-baseurl");
            window.locations = $.getJSON(baseURL+'/wp-json/wp/v2/wprem_locations/?per_page=100&orderby=' + orderby + '&order=' + order, function(data) {
                window.items = data;
                var first_load = window.items.filter(function(index) {
                    var local = [];
                    filtercat = cat ? true : false;
                    singleid = $(".wprem-locations-container").data("singleid") ? $(".wprem-locations-container").data("singleid") : 0;

                    if (!singleid || index['id'] == singleid){
                    if (!filtercat || getPin(index['wprem_locations_category'], 'cat') == cat) {
                        locationArr.push(new Array(
                            index['location_data']['_data_title'][0], // 0
                            parseFloat(index['location_data']['_data_lat']), // 1
                            parseFloat(index['location_data']['_data_lng']), // 2
                            index['location_data']['_data_email_address'][0], // 3
                            index['location_data']['_data_telephone'][0], // 4
                            index['location_data']['_data_tollfree'][0], // 5
                            index['location_data']['_data_fax'][0], // 6
                            index['location_data']['_data_address'][0], // 7
                            index['location_data']['_data_unit'][0], // 8
                            index['location_data']['_data_city'][0], // 9
                            index['location_data']['_data_postalcode'][0], // 10
                            index['location_data']['_data_file'], // 11
                            index['location_data']['_data_filelabel'], // 12
                            0, // 13
                            c++, // 14
                            index['link'], // 15
                            getPin(index['wprem_locations_category'], 'pin'), // 16
                            getPin(index['wprem_locations_category'], 'cat'), // 17
                            index['link'], // 18
                            index['location_data']['_data_country'], // 19
                            index['location_data']['_data_prostate'], // 20
                            index['location_data']['proximity_postal'], // 21
                            index['id'], // 22
                            index['location_data']['_data_hidedetails'], // 23
                        ));
                    }
                    }
                    // LOCATION INFO
                    local.push('<div class="wprem-title">' + index['location_data']['_data_title'][0] + '</div>');
                    locals.push(local);
                });
            }).success(function() {
                // Remove spinner
                $("#location-loader").remove();
                // Show output
                $("#location-finder-container").css("opacity", 1);

                //locationArr = locationObj;

                // #-#-#-#-# SET DEFAULT LOCATION TO FIRST ON LIST #-#-#-#-# //
                if ($("#wprem-location-finder").data("defaultfirst") === 1){
                    var setFirst;
                    for (a = 0; a < locationArr.length; a++) {
                        // Roll through Location Array and pick out the default location based on ID
                        if (detaulLocation === locationArr[a][22]){
                            setFirst = locationArr.splice(a,1);
                        }
                    }
                    locationArr.unshift(setFirst[0]);

                    for (a = 0; a < locationArr.length; a++) {
                        locationArr[a][14] = a;
                    }
                }
                // #-#-#-#-# end - SET DEFAULT LOCATION TO FIRST ON LIST #-#-#-#-# // 


                locationOut(locationArr);

                latlng = [];
                for (a = 0; a < locationArr.length; a++) {
                    latlng[a] = [locationArr[a][1], locationArr[a][2]];
                }

                // #-#-#-#-# SET MAP #-#-#-#-# //
                bounds = new L.LatLngBounds(latlng);
                map = L.map('wprem-location-finder-map').setView([43.6534399, -79.3840901], 8);
                maptile = $("#defaultMapStyle").val() ? $("#defaultMapStyle").val() : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';

                if ($(".wprem-locations-container").attr("data-bw") == 1) {
                    L.TileLayer.Grayscale = L.TileLayer.extend({
                        options: {
                            quotaRed: 21,
                            quotaGreen: 71,
                            quotaBlue: 8,
                            quotaDividerTune: 0,
                            quotaDivider: function() {
                                return this.quotaRed + this.quotaGreen + this.quotaBlue + this.quotaDividerTune;
                            }
                        },

                        initialize: function(url, options) {
                            options.crossOrigin = true;
                            L.TileLayer.prototype.initialize.call(this, url, options);

                            this.on('tileload', function(e) {
                                this._makeGrayscale(e.tile);
                            });
                        },

                        _createTile: function() {
                            var tile = L.TileLayer.prototype._createTile.call(this);
                            tile.crossOrigin = "Anonymous";
                            return tile;
                        },

                        _makeGrayscale: function(img) {
                            if (img.getAttribute('data-grayscaled'))
                                return;

                            img.crossOrigin = '';
                            var canvas = document.createElement("canvas");
                            canvas.width = img.width;
                            canvas.height = img.height;
                            var ctx = canvas.getContext("2d");
                            ctx.drawImage(img, 0, 0);

                            var imgd = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            var pix = imgd.data;
                            for (var i = 0, n = pix.length; i < n; i += 4) {
                                pix[i] = pix[i + 1] = pix[i + 2] = (this.options.quotaRed * pix[i] + this.options.quotaGreen * pix[i + 1] + this.options.quotaBlue * pix[i + 2]) / this.options.quotaDivider();
                            }
                            ctx.putImageData(imgd, 0, 0);
                            img.setAttribute('data-grayscaled', true);
                            img.src = canvas.toDataURL();
                        }
                    });

                    L.tileLayer.grayscale = function(url, options) {
                        return new L.TileLayer.Grayscale(url, options);
                    };

                    L.tileLayer.grayscale(maptile, {
                        maxZoom: 18,
                        padding: 80,
                        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                    }).addTo(map);
                } else {
                    L.tileLayer(maptile, {
                        maxZoom: 18,
                        padding: 80,
                        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                    }).addTo(map);
                }

                //L.tileLayer(
                //    mapStyle, {
                //        attribution: '&copy; ' + mapLink,
                //        maxZoom: 18,
                //        padding: 80,
                //    }).addTo(map);



                // #-#-#-#-# end SET MAP #-#-#-#-# //
                // #-#-#-#-# ICON #-#-#-#-# //
                getIcon = $("#defaultMarker").val() ? $("#defaultMarker").val() : '/wp-content/plugins/wprem-location-finder/public/img/marker.png';
                WPIcon = L.Icon.extend({
                    options: {
                        iconUrl: getIcon,
                        iconSize: [50, 50],
                        iconAnchor: [(50 / 2), 50],
                        popupAnchor:  [0, ((50 / 2) * -1)]
                    }
                });
                customIcon = new WPIcon();

                //L.icon = function(options) { return new L.Icon(options); };
                // #-#-#-#-# end ICON #-#-#-#-# //
                // #-#-#-#-# MARKERS #-#-#-#-# //
                markers = [];
                pop = [];

                var pintel = $(".wprem-locations-container").attr("data-pintel");
                var pintoll = $(".wprem-locations-container").attr("data-pintoll");
                var pinemail = $(".wprem-locations-container").attr("data-pinemail");
                for (var i = 0; i < locationArr.length; i++) {
                    if (locationArr[i][16]) {
                        getIcon = locationArr[i][16];
                    } else {
                        getIcon = $("#defaultMarker").val() ? $("#defaultMarker").val() : '/wp-content/plugins/wprem-location-finder/public/img/marker.png';
                    }
                    pinnum = '';
                    if ($(".wprem-locations-container").attr("data-num") == 1) {
                        pinnum = '<span class="marker-id" data-markerid="' + i + '">' + (i + 1) + '</span>';
                    }

                    var numberIcon = L.divIcon({
                        className: "number-icon",
                        iconSize: [45, 45],
                        iconAnchor: [22.5, 45],
                        popupAnchor:  [0, (22.5 * -1)],
                        html: '<img src="' + getIcon + '" style="width:100%">' + pinnum
                    });
                    fulladdress = locationArr[i][7] + " " + locationArr[i][9] + " " + locationArr[i][10];

                    var details = ($(".wprem-locations-container").attr("data-seedetails") == 1 && locationArr[i][23] != 'on') ? '<br/><a href="' + locationArr[i][18] + '" class="see-details">'+text['details']+'</a>' : '';
                    var infMap = [];
                    infMap["title"] = (locationArr[i][0].length != 0) ? '<div><strong>' + locationArr[i][0] + '</strong></div>' : '';
                    infMap["address"] = (locationArr[i][7].length != 0) ? '<div>' + locationArr[i][7] + '</div>' : '';
                    infMap["unit"] = (locationArr[i][8].length != 0) ? '<div>' + locationArr[i][8] + '</div>' : '';
                    infMap["postal"] = (locationArr[i][10].length != 0) ? '<div>' + locationArr[i][10] +'</div>' : '';

                    if (locationArr[i][9].length != 0 && locationArr[i][20].length != 0){
                        infMap["cityprovince"] = '<div>'+ locationArr[i][9] + ', '+locationArr[i][20]+'</div>';
                    }else{
                        infMap["city"] = (locationArr[i][9].length != 0) ? '<div>' + locationArr[i][9] +'</div>' : '';
                        infMap["province"] = (locationArr[i][20].length != 0) ? '<div>' + locationArr[i][20] +'</div>' : '';
                        infMap["cityprovince"] = infMap["city"] + infMap["province"];
                    }

                    infMap["tel"] = (locationArr[i][4].length != 0 && pintel == 1) ? '<div>Tel: <a href="tel:'+locationArr[i][4].replace(/[^A-Za-z0-9]/g,"")+'">'+locationArr[i][4]+'</a></div>' : '';
                    infMap["toll"] = (locationArr[i][5].length != 0 && pintoll == 1) ? '<div>Toll Free: <a href="tel:'+locationArr[i][5].replace(/[^A-Za-z0-9]/g,"")+'">'+locationArr[i][5]+'</a></div>' : '';
                    infMap["email"] = (locationArr[i][3].length != 0 && pinemail == 1) ? '<div><a href="mailto:'+locationArr[i][3]+'">'+text['emaillocation']+'</a></div>' : '';

                    infMapFull = infMap["title"] + infMap["address"] + infMap["unit"] + infMap["cityprovince"] + infMap["postal"] + infMap["tel"] + infMap["toll"] + infMap["email"];
                    marker = new L.marker([locationArr[i][1], locationArr[i][2]], { icon: numberIcon }).bindPopup(infMapFull + "<a href='https://www.google.ca/maps/dir/&nbsp;/" + fulladdress + "' target='_blank'>"+text['directions']+"</a>" + details, { autoClose: true, closeOnClick: false }).addTo(map);
                    bounds.extend(marker.getLatLng());
                    markers.push(marker);
                }
                group = new L.featureGroup(markers);
                mapCentre();
                // #-#-#-#-# end MARKERS #-#-#-#-# //
                // #-#-#-#-# TOGGLE ZOOM #-#-#-#-# //
                map.scrollWheelZoom.disable();
                map.on('click', function() {
                    if (map.scrollWheelZoom.disable()) {
                        map.scrollWheelZoom.enable();
                    }
                });
                // #-#-#-#-# end TOGGLE ZOOM #-#-#-#-# //

                if ($("#addressInput").val()) {
                    $("#searchButton").trigger('click');
                }

                //$(".leaflet-marker-pane div").html("2");

            });
        }
        mapInit();

        // #-#-#-#-# CENTRE MAP #-#-#-#-# //
        function mapCentre() {
            map.fitBounds(group.getBounds(), { padding: [100, 100], maxZoom:14 });
        }
        // #-#-#-#-# end CENTRE MAP #-#-#-#-# //
        // #-#-#-#-# CENTRE MAP ON RESIZE #-#-#-#-# //
        window.onresize = function(event) {
            mapCentre();
        };
        // #-#-#-#-# end CENTRE MAP ON RESIZE  #-#-#-#-# //
    });
})(jQuery);