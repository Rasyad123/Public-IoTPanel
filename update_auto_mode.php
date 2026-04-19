<?php
session_start();
include_once "koneksi.php";

if ($_SESSION['log'] != "login") {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (isset($_POST['status'])) {
    $status = ($_POST['status'] == "1") ? "1" : "0";

    // Update kolom auto_mode untuk id esp32_01
    $sql = "UPDATE esp32_table_dht11_leds_update SET auto_mode = $status WHERE id = 'esp32_01'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(['success' => true, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing status']);
}
