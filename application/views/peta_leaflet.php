<div class="content" style="margin: 20px; background-color: #f8f9fa; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
      <div class="header-section" style="background-color: #0275d8; padding: 2em; border-radius: 15px 15px 0 0;">
          <h1 style="font-family: 'Arial', sans-serif; color: white; font-size: 2.5em; font-weight: 600; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); text-align: center;">Rumah Sakit di Bekasi Utara</h1>
          <p style="text-align: center; color: white;">klik marker untuk melihat detail rumah sakit</p>
      </div>
      <div id="map" class="map"></div>
      <div class="data main-content">
          <div class="download-section">
              <a href="<?=base_url()?>assets/hospital.geojson" download="hospital.geojson">
                  <button class="btn btn-success btn-lg"><i class="fas fa-download"></i> Download GeoJSON</button>
              </a>
          </div>
          <div class="table-responsive">
              <table id="data-container" class="table table-hover table-bordered">
              </table>
          </div>
      </div>
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
                              <a href="${feature.properties.web}" class=""><button class="btn btn-primary btn-sm"><i class="fas fa-globe"></i>
                              Website
                              
                              </button> </a>
                              <a href="${feature.properties.route}" class="btn btn-warning btn-sm"><i class="fas fa-route"></i> Rute</a>
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

          var polygon = L.polygon([
              [-6.1651102, 106.9818729],
              [-6.1651102, 107.0418729],
              [-6.2251102, 107.0418729],
              [-6.2251102, 106.9818729],
          ], {
              color: '#3498db',
              fillColor: '#3498db',
              fillOpacity: 0.2,
              weight: 2
          }).addTo(map);

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

          // Add table data
          const container = document.getElementById("data-container");
          container.innerHTML = `
          <thead class="table-primary">
              <tr>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Latitude</th>
                  <th>Longitude</th>
                  <th>Gambar</th>
                  <th>Website</th>
                  <th>Rute</th>
              </tr>
          </thead>
          `;

          data.features.forEach(feature => {
              const row = document.createElement("tr");
              row.innerHTML = `
                  <td class="align-middle">${feature.properties.name}</td>
                  <td class="align-middle">${feature.properties.address}</td>
                  <td class="align-middle">${feature.geometry.coordinates[1]}</td>
                  <td class="align-middle">${feature.geometry.coordinates[0]}</td>
                  <td class="align-middle"><img src="${feature.properties.image}" class="img-thumbnail"></td>
                  <td class="align-middle"><a href="${feature.properties.web}" class="btn btn-primary btn-sm"><i class="fas fa-globe"></i> Website</a></td>
                  <td class="align-middle"><a href="${feature.properties.route}" class="btn btn-warning btn-sm"><i class="fas fa-route"></i> Rute</a></td>
              `;
              container.appendChild(row);
          });
      })
</script>

<style>
      .data {
          display: flex;
          flex-direction: column;
          gap: 2em;
          margin: 20px;
      }

      img {
          width: 150px;
          height: 80px;
          object-fit: cover;
          border-radius: 8px;
          transition: transform 0.3s ease;
      }

      img:hover {
          transform: scale(1.1);
      }

      a {
          text-decoration: none;
      }

      .download-section {
          margin: 2em 0;
          text-align: center;
      }

      .main-content {
          margin: 20px;
          padding: 2em;
          background-color: white;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0,0,0,0.05);
      }

      .table {
          background-color: white;
      }

      .table th {
          background-color: #0275d8;
          color: white;
          font-weight: 600;
      }

      .table td {
          vertical-align: middle;
      }

      .btn {
          transition: transform 0.2s ease;
      }

      .btn:hover {
          transform: translateY(-2px);
      }

      .map {
          width: 100%;
          margin: auto;
          top: 20px;
          bottom: 80px;
          height: 80vh;
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

      @media screen and (max-width: 768px) {
          .main-content {
              padding: 1em;
          }

          .table-responsive {
              overflow-x: auto;
          }
      }
</style>
