<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIG</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <style>
    #map {
      height: 830px;
    }

    .info.legend {
      background: white;
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .leaflet-control .info.legend {
      padding: 6px 8px;
      font: 14px/16px Arial, Helvetica, sans-serif;
      background: white;
      background: rgba(255, 255, 255, 0.8);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      border-radius: 5px;
    }

    #navbar {
      position: absolute;
      top: 10px;
      left: 10px;
      width: 80%;
      z-index: 1000;
      background: white;
      margin: 10px 30px;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      background-color: rgba(255, 165, 0, 0.7);
    }
    #indicatorSelector {
      background-color: orange;
      color: white;
      border: white 1px solid;
      padding: 8px;
      border-radius: 5px;
      cursor: pointer;
    }

    #indicatorSelector option {
      background-color: rgba(255, 165, 0, 0.7);
      color: white;
      border-radius: 3px;
    }
  </style>
</head>

<body>
  <div id="navbar">
    <select id="indicatorSelector">
      <option value="komposit">Komposit1</option>
      <option value="komposit2">Komposit2</option>
      <option value="komposit3">komposit3</option>
      <option value="komposit4">Komposit4</option>
      <option value="komposit5">Komposit5</option>
      <option value="komposit6">Komposit6</option>
      <option value="komposit7">Komposit7</option>
      <option value="komposit8">Komposit8</option>
      <option value="komposit9">Komposit9</option>
      <option value="komposit10">Komposit10</option>
      
    </select>
  </div>
  <div id="map"></div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="assets/js/leaflet.ajax.js"></script>
  <script>
    var map = L.map('map').setView([-2.5489, 118.0149], 5)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution:
        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    <?php
      include 'koneksi.php';
      $data = mysqli_query($conn, "SELECT * FROM data JOIN dataset ON SUBSTRING_INDEX(dataset.kab_kota, ' - ', -1) = data.name AND SUBSTRING_INDEX(dataset.kab_kota, ' - ', 1) = data.prov_name;");
    ?>  

    var data = <?php echo json_encode(mysqli_fetch_all($data, MYSQLI_ASSOC)); ?>;
    var selectedAttribute = 'komposit'; 
    console.log(data);


    function popUp(feature, layer) {
  var currentFeatureId = feature.properties.id;
  var currentFeatureData = data.find(item => item.id == currentFeatureId);

  if (currentFeatureData) {
    var table = '<table>';
    table += '<tr><td colspan="2"><b>' + currentFeatureData.kab_kota.toUpperCase() + '</b></td></tr>';
    table += '<tr><td colspan="2"><b><hr></b></td></tr>';
    table += '<tr><td><b>NCPR</b></td><td>: ' + currentFeatureData.ncpr + '</td></tr>';
    table += '<tr><td><b>Kemiskinan</b></td><td>: ' + currentFeatureData.kemiskinan + '</td></tr>';
    table += '<tr><td><b>Pangan</b></td><td>: ' + currentFeatureData.pangan + '</td></tr>';
    table += '<tr><td><b>Listrik</b></td><td>: '  + currentFeatureData.listrik + '</td></tr>';
    table += '<tr><td><b>Air</b></td><td>: ' + currentFeatureData.air + '</td></tr>';
    table += '<tr><td><b>Sekolah</b></td><td>: ' + currentFeatureData.sekolah + '</td></tr>';
    table += '<tr><td><b>Kesehatan</b></td><td>: ' + currentFeatureData.kesehatan + '</td></tr>';
    table += '<tr><td><b>Harapan Hidup</b></td><td>: ' + currentFeatureData.harapan_hidup + '</td></tr>';
    table += '<tr><td><b>Stunting</b></td><td>: ' + currentFeatureData.stunting + '</td></tr>';
    table += '<tr><td><b>LKP</b></td><td>: ' + currentFeatureData.ikp + '</td></tr>';
    table += '<tr><td><b>LKP Ranting</b></td><td>: ' + currentFeatureData.ikp_rangking + '</td></tr>';
    
    var selectedValue = currentFeatureData[selectedAttribute] !== undefined ? currentFeatureData[selectedAttribute] : 'Data tidak tersedia';
    table += '<tr><td><b>' + selectedAttribute + '</b></td><td>: 1' + selectedValue + '</td></tr>';

    table += '</table>';

    layer.bindPopup(table);
  }
}




function getColor(value) {
  return value == 1 ? '#A32C33' :
         value == 2 ? '#EF585F' :
         value == 3 ? '#EFA8AF' :
         value == 4 ? '#A9F8B9' :
         value == 5 ? '#37F847' :
         value == 6 ? '#235A33' :
         '#EFF8FF';
}


    function style(feature) {
      var currentFeatureId = feature.properties.id;
      var currentFeatureData = data.find(item => item.id == currentFeatureId);

      if (currentFeatureData) {
        return {
          fillColor: getColor(currentFeatureData[selectedAttribute]),
          weight: 1,
          opacity: 1,
          color: 'white',
          dashArray: '3',
          fillOpacity: 0.7
        };
      }

      return {}; 
    }

    var legend = L.control({ position: 'topright' });

legend.onAdd = function (map) {
  var div = L.DomUtil.create('div', 'info legend'),
    grades = [1, 2, 3, 4, 5, 6],
    colors = ['#A32C33', '#EF585F', '#E31A1C', '#A9F8B9', '#37F847', '#235A33'];

  div.innerHTML += '<b>Kaitan Warna - Level Composit</b><br>';

  for (var i = 0; i < grades.length; i++) {
    div.innerHTML +=
      '<div style="background:' + colors[i] + ';width:100px;height:15px;padding:5;color:black;"><p style="padding-left:120px">' +
      (grades[i] + '</p><br>') +
      '</div>';
  }

  return div;
};


    legend.addTo(map);
    var jsonTest = new L.GeoJSON.AJAX(['assets/geojson/kabupaten.geojson'], {
      onEachFeature: popUp,
      style: style
    }).addTo(map);
    document.getElementById('indicatorSelector').addEventListener('change', function (event) {
  selectedAttribute = event.target.value;
  jsonTest.clearLayers(); 
  jsonTest = new L.GeoJSON.AJAX(['assets/geojson/kabupaten.geojson'], {
    onEachFeature: popUp,
    style: style
  }).addTo(map);
  legend.addTo(map); 
});
  </script>

</html>
