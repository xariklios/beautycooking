function soapery_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof SOAPERY_STORAGE['googlemap_init_obj'] == 'undefined') soapery_googlemap_init_styles();
	SOAPERY_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		SOAPERY_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: SOAPERY_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		soapery_googlemap_create(id);

	} catch (e) {
		
		dcl(SOAPERY_STORAGE['strings']['googlemap_not_avail']);

	};
}

function soapery_googlemap_create(id) {
	"use strict";

	// Create map
	SOAPERY_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(SOAPERY_STORAGE['googlemap_init_obj'][id].dom, SOAPERY_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in SOAPERY_STORAGE['googlemap_init_obj'][id].markers)
		SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	soapery_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (SOAPERY_STORAGE['googlemap_init_obj'][id].map)
			SOAPERY_STORAGE['googlemap_init_obj'][id].map.setCenter(SOAPERY_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function soapery_googlemap_add_markers(id) {
	"use strict";
	for (var i in SOAPERY_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (SOAPERY_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (SOAPERY_STORAGE['googlemap_init_obj'].geocoder == '') SOAPERY_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			SOAPERY_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			SOAPERY_STORAGE['googlemap_init_obj'].geocoder.geocode({address: SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = SOAPERY_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						SOAPERY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						SOAPERY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					SOAPERY_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						soapery_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(SOAPERY_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: SOAPERY_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].title;
			SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (SOAPERY_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				SOAPERY_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				SOAPERY_STORAGE['googlemap_init_obj'][id].map.setCenter(SOAPERY_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in SOAPERY_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								SOAPERY_STORAGE['googlemap_init_obj'][id].map,
								SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			SOAPERY_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function soapery_googlemap_refresh() {
	"use strict";
	for (id in SOAPERY_STORAGE['googlemap_init_obj']) {
		soapery_googlemap_create(id);
	}
}

function soapery_googlemap_init_styles() {
	// Init Google map
	SOAPERY_STORAGE['googlemap_init_obj'] = {};
	SOAPERY_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.soapery_theme_googlemap_styles!==undefined)
		SOAPERY_STORAGE['googlemap_styles'] = soapery_theme_googlemap_styles(SOAPERY_STORAGE['googlemap_styles']);
}