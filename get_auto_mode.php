<?php
session_start();
include_once "koneksi.php";

if ($_SESSION['log'] != "login") {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$sql = "SELECT auto_mode FROM esp32_table_dht11_leds_update WHERE id = 'esp32_01' LIMIT 1";
$result = mysqli_query($conn, $sql);

$status = 0;
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $status = (int)$row['auto_mode'];
}

echo json_encode(['status' => $status]);
