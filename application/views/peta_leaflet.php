<div class="content" style="margin: 10px;"> 
      <div style="margin: auto; padding-left: 5em; padding-top:2em;">
          <h1 style="font-family: 'Arial', sans-serif; color: #2c3e50; font-size: 2.5em; font-weight: 600; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); border-bottom: 3px solid #3498db; padding-bottom: 10px; display: inline-block;">Rumah Sakit di Bekasi Utara</h1>
          <p style="margin-bottom: -1em;">click the marker to see hospital details</p>
      </div>      <div id="map" class="map"></div> 
</div> 

<script> 
      var hospital = new L.layerGroup();
      $.getJSON("<?=base_url()?>assets/hospital.geojson", function(data) {
          var ratIcon = L.icon({
              iconUrl: '<?=base_url()?>assets/marker.png',
              iconSize: [48, 48]
          });

          L.geoJson(data, {
              pointToLayer: function(feature, latlng) {
                  var marker = L.marker(latlng, {icon: ratIcon});
                  marker.bindPopup(`
                      <div style="min-width: 80px; padding: 8px;">
                          <h3 style="margin: 0 0 8px 0; color: #333; font-size: 14px; font-weight:bold;">${feature.properties.name}</h3>
                          <p style="font-size: 12px;">${feature.properties.address}</p>
                          <div style="display: flex; justify-content: center;">
                              <img src="${feature.properties.image}" style="width: 80%; height:100px; margin-bottom: 8px;">
                          </div>
                          <div class="button-container">
                                                <a href="${feature.properties.web}" style="text-decoration: none;">
                                                    <button class="btn button" style="margin-right: 5px;">Website</button>
                                                </a>
                                                <a href="${feature.properties.route}" target="__blank">
                                                    <button class="btn button" style="margin-right: 5px;">Route</button>
                                                </a>
                          </div>
                      </div>
                  `, {
                      closeButton: false,
                      className: 'custom-popup'
                  });
                  return marker;
              }
          }).addTo(hospital);
        
          var map = L.map('map', {
              center: [-6.1951102, 107.0118729], 
              zoom: 12, 
              zoomControl: false,
              layers: [hospital],
              fadeAnimation: true,
              zoomAnimation: true
          }); 

          var GoogleMaps = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
              maxZoom: 22,
              opacity: 1.0,
              attribution: 'Latihan Web GIS'
          }).addTo(map);
        
          var GoogleSatelliteHybrid = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { 
              maxZoom: 22, 
              attribution: 'Latihan Web SIG' 
          }); 

          var Esri_NatGeoWorldMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', {
              attribution: 'Tiles © Esri — National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC',
              maxZoom: 16
          });

          var GoogleRoads = L.tileLayer('https://mt1.google.com/vt/lyrs=h&x={x}&y={y}&z={z}', {
              maxZoom: 22,
              opacity: 1.0,
              attribution: 'Latihan Web GIS'
          });

          var baseLayers = {
              'Google Maps': GoogleMaps,
              'Google Satellite Hybrid': GoogleSatelliteHybrid,
              'Esri NatGeoWorldMap': Esri_NatGeoWorldMap,
              'Google Roads': GoogleRoads
          };

          var groupedOverlays = {
              "Peta Dasar": {
                  'Rumah Sakit': hospital
              }
          };
            
          L.control.groupedLayers(baseLayers, groupedOverlays).addTo(map);

          var miniMapTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
              minZoom: 0,
              maxZoom: 13,
              attribution: 'Map data © OpenStreetMap contributors'
          });

          var miniMap = new L.Control.MiniMap(miniMapTile, {
              toggleDisplay: true, 
              position: "bottomright",
              aimingRectOptions: {color: "#ff1100", weight: 3}, 
              shadowRectOptions: {color: "#0000AA", weight: 1, opacity: 0, fillOpacity: 0}
          }).addTo(map);
        
          L.Control.geocoder({
              position: "topleft",
              collapsed: true
          }).addTo(map);

          var locateControl = L.control.locate({
              position: "topleft",
              drawCircle: true,
              follow: true,
              setView: true,
              keepCurrentZoomLevel: true,
              markerStyle: {
                  weight: 1,
                  opacity: 1.0,
                  fillOpacity: 0.8
              },
              circleStyle: {
                  weight: 1,
                  clickable: false
              },
              icon: "fa fa-location-arrow",
              metric: true,
              strings: {
                  title: "My location",
                  popup: "You are within {distance} {unit} from this point",
                  outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
              },
              locateOptions: {
                  maxZoom: 18,
                  watch: true,
                  enableHighAccuracy: true,
                  maximumAge: 10000,
                  timeout: 10000
              }
          }).addTo(map);

          var zoom_bar = new L.Control.ZoomBar({position: 'bottomleft'}).addTo(map);

          L.control.coordinates({
              position: "bottomleft",
              decimals: 2,
              decimalSeperator: ",",
              labelTemplateLat: "Latitude: {y}",
              labelTemplateLng: "Longitude: {x}"
          }).addTo(map);

          L.control.scale({
              metric: true,
              position: "bottomleft"
          }).addTo(map);

          window.addEventListener('resize', function() {
              map.invalidateSize();
          });
      })
</script>

<style>
      a{
            text-decoration: none;
            
      }
      .button-container {
          display: flex;
          flex-direction: row;
          gap: 2em;
          justify-content: center;
          align-items: center;
          margin-top: 20px;
      }
      .button {
            height: 3em;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 8em;
            background-color: white;
           
            border: 1px solid #ccc;
            border-color: blue;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            padding: 0 1.5em;
      }

      .button:hover {
            background-color: blue;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            color:white;
      }      .map {
          width: 90%;
          margin: auto;
          top: 20px;
          bottom: 80px;
          height: 60vh;
          min-height: 500px;
          border-radius: 10px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .custom-popup .leaflet-popup-content-wrapper {
          background: rgba(255, 255, 255, 0.95);
          border-radius: 8px;
          box-shadow: 0 3px 14px rgba(0,0,0,0.2);
      }

      .custom-popup .leaflet-popup-tip {
          background: rgba(255, 255, 255, 0.95);
      }

      .leaflet-control {
          background: rgba(255, 255, 255, 0.9);
          border-radius: 6px !important;
          backdrop-filter: blur(5px);
      }

      .leaflet-touch .leaflet-control-layers, 
      .leaflet-touch .leaflet-bar {
          border: none;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
</style>
