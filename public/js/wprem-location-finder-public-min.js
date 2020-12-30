!function(t){t(function(){if(0==t("#wprem-location-finder-map").length)return!1;var e;function r(a,e){var r=0,i=!1,s=[],c=[];t("#wprem-locations").html(""),markers.forEach(function(o){distanceTo=markers[r]._latlng.distanceTo([a,e])/1e3,parseFloat(distanceTo)>parseInt(t("#radiusSelect").val())?map.removeLayer(markers[r]):(i=!0,locationArr[r][13]=parseInt(distanceTo),map.addLayer(markers[r]),locals[r].push(),s.push(markers[r]),c.push(locationArr[r])),r++}),1==i?(group=new L.featureGroup(s),l(),n(c.sort(o))):t("#wprem-locations").append("<p>Sorry no results found, please enter a different address</p>").show()}function o(a,t){return a[13]===t[13]?0:a[13]<t[13]?-1:1}function n(a){for(var e=0;e<a.length;e++){if(a[e][16])var r=a[e][16];else r=t("#defaultMarker").val()?t("#defaultMarker").val():"/wp-content/plugins/wprem-location-finder/public/img/marker.png";1==t(".wprem-locations-container").attr("data-titlelink")?titlelink='<a href="'+a[e][15]+'">'+a[e][0]+"</a>":titlelink='<span class="wprem-locations-gm-marker" data-index="'+a[e][14]+'">'+a[e][0]+"</span>",title='<div class="wprem-title">'+titlelink+"</div>",address="<div>"+a[e][7]+a[e][8]+"<br/>"+a[e][9]+"<br/>"+a[e][10]+"</div>",telephone=a[e][4]?'<div class="wprem-telephone">Tel: <a href="tel:'+a[e][4]+'">'+a[e][4]+"</a></div>":"",email=a[e][3]?'<div class="wprem-email"><a href="mailto:'+a[e][3]+'">'+a[e][3]+"</a></div>":"",tollfree=a[e][5]?'<div class="wprem-tollfree">TollFree: <a href="tel:'+a[e][5]+'">'+a[e][5]+"</a></div>":"",fax=a[e][6]?'<div class="wprem-fax">Fax: <a href="tel:'+a[e][6]+'">'+a[e][6]+"</a></div>":"",download=a[e][12]?a[e][12]:"File",file=a[e][11]?'<div><a href="'+a[e][11]+'" target="_blank">'+download+"</a></div>":"",kms=a[e][13]>0?'<span class="wprem-kms" style="font-size:14px; line-height:12px">'+a[e][13]+"<br/>kms</span>":"",cat=a[e][17]&&1==t(".wprem-locations-container").attr("data-cat")?'<div class="wprem-location-cat wprem-location-cat_'+a[e][17].toLowerCase()+'">'+a[e][17]+"</div>":"",t("#wprem-locations").append('<div style="border-bottom:1px solid #CCC; margin-bottom:20px; padding-bottom:10px;"><div class="wprem-locations-gm-marker" data-index="'+a[e][14]+'" href="#" style="font-size:10px; float:left; position:relative; text-align:center; color:#000"><span style="position:absolute; top:3px; text-align:center; width:100%; font-size:16px; left: 0;">'+(e+1)+'</span><img src="'+r+'" style="width:40px;"><br/>'+kms+'</div><div style="line-height: 18px; display: inline-block; margin-left:10px">'+title+address+telephone+tollfree+fax+email+file+cat+"</div></div>"),t(document).find("[data-markerid='"+a[e][14]+"']").html(e+1)}}function i(a,e){return out="",t("#marker-cats span").each(function(){t(this).attr("data-id")==a&&"pin"==e&&(out=t(this).html()),t(this).attr("data-id")==a&&"cat"==e&&(out=t(this).attr("data-name"))}),out}function l(){map.fitBounds(group.getBounds(),{padding:[100,100]})}t(document).on("click","#searchButton",function(){t(".wprem-locations-error").hide(),userAddress=t("#addressInput").val(),userAddress.length<6?t(".wprem-locations-error").html("Please enter full address or postal code.").show():(t("#wprem-locations-error").hide(),function(a){map1="https://geocode.xyz/"+a+"?json=1",map2="https://geogratis.gc.ca/services/geolocation/en/locate?q="+a;var o=parseInt(1e3*t("#radiusSelect").val());void 0!=e&&map.removeLayer(e),t.getJSON(map2,function(a){}).done(function(a){var n=a[0].geometry.coordinates[0],i=a[0].geometry.coordinates[1];console.log(i+" : "+n),"0.00000"!=i||"0.00000"!=n?(color=t("#wprem-location-finder-map").attr("data-radiuscolor").length>0?t("#wprem-location-finder-map").attr("data-radiuscolor"):"#CC0000","y"==t("#wprem-location-finder-map").attr("data-radiusshow")&&(e=L.circle([i,n],{radius:o,color:color}).addTo(map)),r(i,n)):t(".wprem-locations-error").html("Please enter full address or postal code.").show()})}(userAddress))}),t(document).on("click",".wprem-location-filter-cat",function(){t("#wprem-locations").html(""),c=0,catname=t(this).attr("data-name"),filterLocationsArr=[],e&&map.removeLayer(e),markers.forEach(function(a){map.removeLayer(markers[c]),locationArr[c][17]===catname&&(map.addLayer(markers[c]),filterLocationsArr.push(locationArr[c])),c++}),n(filterLocationsArr),t(".wprem-kms",document).html(""),group=new L.featureGroup(markers),l()}),t(document).on("click","#refreshButton",function(){t("#wprem-locations").html(""),c=0,e&&map.removeLayer(e),markers.forEach(function(a){map.addLayer(markers[c]),c++}),n(locationArr),t(".wprem-kms",document).html(""),group=new L.featureGroup(markers),l()}),t(document).on("click",".wprem-locations-gm-marker",function(){markers[t(this).attr("data-index")].openPopup()}),function(e){locationArr=[],locals=[];var r=0;orderby=t(".wprem-locations-container").attr("data-orderby"),order=t(".wprem-locations-container").attr("data-order"),window.locations=t.getJSON("/wp-json/wp/v2/wprem_locations/?per_page=100&orderby="+orderby+"&order="+order,function(a){window.items=a,window.items.filter(function(a){var t=[];filtercat=!1,e&&(filtercat=!0),filtercat&&i(a.wprem_locations_category,"cat")!=e||locationArr.push(new Array(a.location_data._data_title[0],parseFloat(a.location_data._data_lat),parseFloat(a.location_data._data_lng),a.location_data._data_email_address[0],a.location_data._data_telephone[0],a.location_data._data_tollfree[0],a.location_data._data_fax[0],a.location_data._data_address[0],a.location_data._data_unit[0],a.location_data._data_city[0],a.location_data._data_postalcode[0],a.location_data._data_file,a.location_data._data_filelabel,0,r++,a.link,i(a.wprem_locations_category,"pin"),i(a.wprem_locations_category,"cat"),a.link)),t.push('<div class="wprem-title">'+a.location_data._data_title[0]+"</div>"),locals.push(t)})}).success(function(){for(t("#location-loader").remove(),t("#location-finder-container").css("opacity",1),n(locationArr),latlng=[],a=0;a<locationArr.length;a++)latlng[a]=[locationArr[a][1],locationArr[a][2]];bounds=new L.LatLngBounds(latlng),map=L.map("wprem-location-finder-map").setView([43.6534399,-79.3840901],8),maptile=t("#defaultMapStyle").val()?t("#defaultMapStyle").val():"https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",mapLink='<a href="http://openstreetmap.org">OpenStreetMap</a>',"true"==t(".wprem-locations-container").attr("data-bw")?(L.TileLayer.Grayscale=L.TileLayer.extend({options:{quotaRed:21,quotaGreen:71,quotaBlue:8,quotaDividerTune:0,quotaDivider:function(){return this.quotaRed+this.quotaGreen+this.quotaBlue+this.quotaDividerTune}},initialize:function(a,t){t.crossOrigin=!0,L.TileLayer.prototype.initialize.call(this,a,t),this.on("tileload",function(a){this._makeGrayscale(a.tile)})},_createTile:function(){var a=L.TileLayer.prototype._createTile.call(this);return a.crossOrigin="Anonymous",a},_makeGrayscale:function(a){if(!a.getAttribute("data-grayscaled")){a.crossOrigin="";var t=document.createElement("canvas");t.width=a.width,t.height=a.height;var e=t.getContext("2d");e.drawImage(a,0,0);for(var r=e.getImageData(0,0,t.width,t.height),o=r.data,n=0,i=o.length;n<i;n+=4)o[n]=o[n+1]=o[n+2]=(this.options.quotaRed*o[n]+this.options.quotaGreen*o[n+1]+this.options.quotaBlue*o[n+2])/this.options.quotaDivider();e.putImageData(r,0,0),a.setAttribute("data-grayscaled",!0),a.src=t.toDataURL()}}}),L.tileLayer.grayscale=function(a,t){return new L.TileLayer.Grayscale(a,t)},L.tileLayer.grayscale(maptile,{maxZoom:18,padding:80,attribution:'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>'}).addTo(map)):L.tileLayer(maptile,{maxZoom:18,padding:80,attribution:'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>'}).addTo(map),getIcon=t("#defaultMarker").val()?t("#defaultMarker").val():"/wp-content/plugins/wprem-location-finder/public/img/marker.png",WPIcon=L.Icon.extend({options:{iconUrl:getIcon,iconSize:[50,50]}}),customIcon=new WPIcon,markers=[],pop=[];for(var e=0;e<locationArr.length;e++){locationArr[e][16]?getIcon=locationArr[e][16]:getIcon=t("#defaultMarker").val()?t("#defaultMarker").val():"/wp-content/plugins/wprem-location-finder/public/img/marker.png",pinnum="",1==t(".wprem-locations-container").attr("data-num")&&(pinnum='<span class="marker-id" data-markerid="'+e+'">'+(e+1)+"</span>");var r=L.divIcon({className:"number-icon",iconSize:[45,45],html:'<img src="'+getIcon+'" style="width:100%">'+pinnum});fulladdress=locationArr[e][7]+" "+locationArr[e][9]+" "+locationArr[e][10],details=1==t(".wprem-locations-container").attr("data-seedetails")?'<br/><a href="'+locationArr[e][18]+'" class="see-details">See Details</a>':"",marker=new L.marker([locationArr[e][1],locationArr[e][2]],{icon:r}).bindPopup("<strong>"+locationArr[e][0]+"</strong><br/>"+locationArr[e][7]+(locationArr[e][8]?locationArr[e][8]+"<br/>":"")+"<br/>"+locationArr[e][9]+"<br/>"+locationArr[e][10]+"<br/><a href='https://www.google.ca/maps/dir/&nbsp;/"+fulladdress+"' target='_blank'>Get Directions</a>"+details,{autoClose:!0,closeOnClick:!1}).addTo(map),bounds.extend(marker.getLatLng()),markers.push(marker)}group=new L.featureGroup(markers),l(),map.scrollWheelZoom.disable(),map.on("click",function(){map.scrollWheelZoom.disable()&&map.scrollWheelZoom.enable()}),t("#addressInput").val()&&t("#searchButton").trigger("click")})}(),window.onresize=function(a){l()}})}(jQuery);