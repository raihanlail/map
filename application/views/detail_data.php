<div class="content" style="margin: 10px;"> 
      <div style="margin: auto; padding-left: 5em; padding-top:2em;">
          <h1 style="font-family: 'Arial', sans-serif; color: #2c3e50; font-size: 2.5em; font-weight: 600; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); border-bottom: 3px solid #3498db; padding-bottom: 10px; display: inline-block;">Data Details</h1>
      </div>      <div id="map" class="map"></div> 

      <div class="data" style="margin: 20px;">
        <table id="data-container" class="table table-bordered table-striped">

        </table>
      </div>
</div> 

<script>
    fetch('<?=base_url()?>assets/hospital.geojson')
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById("data-container");
        const table = document.createElement("table");

        table.innerHTML = `
        <tr>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Gambar</th>
        <th>Website</th>
        <th>Rute</th>

        </tr>
        
        `;
        data.features.forEach(feature => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${feature.properties.name}</td>
                <td>${feature.properties.address}</td>
                <td>${feature.geometry.coordinates[1]}</td>
                <td>${feature.geometry.coordinates[0]}</td>
                <td><img src="${feature.properties.image}"> </td>
                <td><a href="${feature.properties.web}"><button class="btn btn-primary">website</button></a></td>
                <td><a href="${feature.properties.route}"><button class="btn btn-warning">Rute</button></a></td>
                `;
                table.appendChild(row);
        });
        container.appendChild(table);

        
    })
</script>

<style>
    .data{
        display: flex;
        flex-direction: column;
        gap: 2em;
        margin:20px
    }
    img{
        width: 150px;
        height: 80px;
    }

    a{
        text-decoration: none;
    }

</style>