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


  </style>
</head>

<body>
  <div id="map"></div>
</body>
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
    $data = mysqli_query($conn, "SELECT data.id, data.prov_id, data.prov_name, data.name, data.alt_name, data.uuid, data.color, dataset.id AS dataset_id, dataset.kab_kota, dataset.komposit, dataset.ncpr, dataset.kemiskinan, dataset.pangan, dataset.listrik, dataset.air, dataset.sekolah, dataset.kesehatan, dataset.harapan_hidup, dataset.stunting, dataset.ikp, dataset.ikp_rangking FROM data JOIN dataset ON SUBSTRING_INDEX(dataset.kab_kota, ' - ', -1) = data.name AND SUBSTRING_INDEX(dataset.kab_kota, ' - ', 1) = data.prov_name;");
  ?>

  function popUp(feature, layer) {
    var out = [];

    <?php
    // Reset pointer data
    mysqli_data_seek($data, 0);

    while ($d = mysqli_fetch_array($data)) {
      ?>
      if (feature.properties.id == <?= $d['id'] ?>) {
        out.push('ncpr = ' + <?= $d['ncpr'] ?>);
        out.push('kemiskinan = ' + <?= $d['kemiskinan'] ?>);
        out.push('pangan = ' + <?= $d['pangan'] ?>);
        out.push('listrik = ' + <?= $d['listrik'] ?>);
        out.push('air = ' + <?= $d['air'] ?>);
        out.push('kemiskinan = ' + <?= $d['kemiskinan'] ?>);
        out.push('sekolah = ' + <?= $d['sekolah'] ?>);
        out.push('kesehatan = ' + <?= $d['kesehatan'] ?>);
        out.push('harapan hidup = ' + <?= $d['harapan_hidup'] ?>);
        out.push('stunting = ' + <?= $d['stunting'] ?>);
        out.push('IKP = ' + <?= $d['ikp'] ?>);
        out.push('IKP Ranking = ' + <?= $d['ikp_rangking'] ?>);
      }
      <?php
    }
    ?>

    layer.bindPopup(out.join('<br />'));
  }
  function getColor(composit) {
  return composit === 1 ? '#800026' :
    composit === 2 ? '#BD0026' :
      composit === 3 ? '#E31A1C' :
        composit === 4 ? '#FC4E2A' :
          composit === 5 ? '#FD8D3C' :
            composit === 6 ? '#FEB24C' :
              '#FFEDA0';
}

function style(feature) {
  <?php
  // Reset pointer data
  mysqli_data_seek($data, 0);

  while ($d = mysqli_fetch_array($data)) {
    ?>
    if (feature.properties.id == <?= $d['id'] ?>) {
      return {
        fillColor: getColor(<?= $d['komposit'] ?>),
        weight: 1,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
      };
    }
    <?php
  }
  ?>
}

var legend = L.control({ position: 'topright' });

legend.onAdd = function (map) {
  var div = L.DomUtil.create('div', 'info legend'),
    grades = [1, 2, 3, 4, 5, 6],
    colors = ['#800026', '#BD0026', '#E31A1C', '#FC4E2A', '#FD8D3C', '#FEB24C'];

  div.innerHTML += '<b>Kaitan Warna - Level Composit</b><br>';

  // loop through our composit grades and generate a label with a colored square for each level
  for (var i = 0; i < grades.length; i++) {
    div.innerHTML +=
      '<i style="background:' + colors[i] + '"></i> ' +
      grades[i] +'<br>';
  }

  return div;
};

legend.addTo(map);


  var jsonTest = new L.GeoJSON.AJAX(['assets/geojson/kabupaten.geojson'], {
    onEachFeature: popUp,
    style: style
  }).addTo(map);
</script>

</html>
