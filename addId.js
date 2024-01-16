const fs = require('fs');

// Nama file GeoJSON
const fileName = 'assets/geojson/kabupaten.geojson';

// Baca isi file GeoJSON
fs.readFile(fileName, 'utf8', (err, data) => {
  if (err) {
    console.error('Error reading GeoJSON file:', err);
    return;
  }

  // Parse GeoJSON
  let geojsonData;
  try {
    geojsonData = JSON.parse(data);
  } catch (parseError) {
    console.error('Error parsing GeoJSON:', parseError);
    return;
  }

  // Tambahkan properti ID ke setiap fitur
  geojsonData.features.forEach((feature, index) => {
    feature.properties.id = index + 1; // ID dimulai dari 1
  });

  // Simpan kembali GeoJSON yang telah dimodifikasi
  const modifiedGeoJSON = JSON.stringify(geojsonData, null, 2);
  fs.writeFile(fileName, modifiedGeoJSON, 'utf8', (writeErr) => {
    if (writeErr) {
      console.error('Error writing modified GeoJSON:', writeErr);
    } else {
      console.log('Properti ID berhasil ditambahkan ke GeoJSON.');
    }
  });
});
