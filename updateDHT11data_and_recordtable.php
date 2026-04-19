<?php
require 'database.php';

if (!empty($_POST)) {
  // Keep track POST values
  $id = $_POST['id'];
  $temperature = $_POST['temperature'];
  $humidity = $_POST['humidity'];
  $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];
  $led_01 = $_POST['led_01'];
  $led_02 = $_POST['led_02'];
  $led_03 = $_POST['led_03'];
  $soil_moisture = $_POST['soil_moisture'];
  $status_soil = $_POST['status_soil'];
  $light_level = $_POST['light_level'];
  $status_light = $_POST['status_light'];

  // Get time & date
  date_default_timezone_set("Asia/Jakarta"); // Look here for your timezone : https://www.php.net/manual/en/timezones.php
  $tm = date("H:i:s");
  $dt = date("Y-m-d");

  // Update data ke esp32_table_dht11_leds_update
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_update'.
  // This table is used to store DHT11 sensor data updated by ESP32. 
  // This table is also used to store the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
  // This table is operated with the "UPDATE" command, so this table will only contain one row.

  $sql = "UPDATE esp32_table_dht11_leds_update SET 
              temperature = ?, 
              humidity = ?, 
              status_read_sensor_dht11 = ?, 
              soil_moisture = ?, 
              status_soil = ?, 
              light_level = ?, 
              status_light = ?, 
              LED_01 = ?, 
              LED_02 = ?, 
              LED_03 = ?,
              time = ?, 
              date = ? 
            WHERE id = ?";
  $q = $pdo->prepare($sql);
  $q->execute(array(
    $temperature,
    $humidity,
    $status_read_sensor_dht11,
    $soil_moisture,
    $status_soil,
    $light_level,
    $status_light,
    $led_01,
    $led_02,
    $led_03,
    $tm,
    $dt,
    $id
  ));
  Database::disconnect();

  // Generate ID untuk record
  $id_key;
  $board = $_POST['id'];
  $found_empty = false;
  $pdo = Database::connect();

  //:::::::: Process to check if "id" is already in use.
  while (!$found_empty) {
    $id_key = generate_string_id(10);
    // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_record'.
    // This table is used to store and record DHT11 sensor data updated by ESP32. 
    // This table is also used to store and record the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
    // This table is operated with the "INSERT" command, so this table will contain many rows.
    // Before saving and recording data in this table, the "id" will be checked first, to ensure that the "id" that has been created has not been used in the table.

    $sql = 'SELECT * FROM esp32_table_dht11_leds_record WHERE id="' . $id_key . '"';
    $q = $pdo->prepare($sql);
    $q->execute();
    if (!$q->fetch()) {
      $found_empty = true;
    }
  }

  // Insert data ke record table
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_record'.
  // This table is used to store and record DHT11 sensor data updated by ESP32. 
  // This table is also used to store and record the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
  // This table is operated with the "INSERT" command, so this table will contain many rows.

  $sql = "INSERT INTO esp32_table_dht11_leds_record 
              (id, board, temperature, humidity, status_read_sensor_dht11, soil_moisture, status_soil, light_level, status_light, LED_01, LED_02, LED_03, time, date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $q = $pdo->prepare($sql);
  $q->execute(array(
    $id_key,
    $board,
    $temperature,
    $humidity,
    $status_read_sensor_dht11,
    $soil_moisture,
    $status_soil,
    $light_level,
    $status_light,
    $led_01,
    $led_02,
    $led_03,
    $tm,
    $dt
  ));
  Database::disconnect();
}


//---------------------------------------- Function to create "id" based on numbers and characters.
function generate_string_id($strength = 16)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $input_length = strlen($permitted_chars);
  $random_string = '';
  for ($i = 0; $i < $strength; $i++) {
    $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
    $random_string .= $random_character;
  }
  return $random_string;
}
