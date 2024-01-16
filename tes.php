<?php
include 'koneksi.php';
$data = mysqli_query($conn, "SELECT data.id, data.prov_id, data.prov_name, data.name, data.alt_name, data.uuid, data.color, dataset.id AS dataset_id, dataset.kab_kota, dataset.komposit, dataset.ncpr, dataset.kemiskinan, dataset.pangan, dataset.listrik, dataset.air, dataset.sekolah, dataset.kesehatan, dataset.harapan_hidup, dataset.stunting, dataset.ikp, dataset.ikp_rangking, dataset.komposit2, dataset.komposit3, dataset.komposit4, dataset.komposit5, dataset.komposit6, dataset.komposit7, dataset.komposit8, dataset.komposit9, dataset.komposit10 FROM data JOIN dataset ON SUBSTRING_INDEX(dataset.kab_kota, ' - ', -1) = data.name AND SUBSTRING_INDEX(dataset.kab_kota, ' - ', 1) = data.prov_name;");

// Fetch all rows
$result = mysqli_fetch_all($data, MYSQLI_ASSOC);

echo json_encode($result);
?>
