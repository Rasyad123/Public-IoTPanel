<?php
session_start();
include_once "koneksi.php";
if ($_SESSION['log'] != "login") {
  header('location:login.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Smart Farming Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
  <style>
    /* Basic Styles */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f5f5;
      color: #2c3e50;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for body */
    }

    .container {
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      margin-top: 40px;
      width: 90%;
      max-width: 1000px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
      text-align: center;
      position: relative;
      transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition */
    }

    h1 {
      font-size: 2em;
      margin-bottom: 5px;
    }

    .subtitle {
      color: gray;
      margin-bottom: 30px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .card {
      background: white;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
    }

    .card-icon {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 1.2em;
      margin-bottom: 5px;
    }

    .card-value {
      font-size: 2em;
      font-weight: bold;
      color: #27ae60;
    }

    .device-control {
      background: white;
      border-radius: 16px;
      padding: 24px;
      max-width: 800px;
      margin: 0 auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
    }

    .control-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .control-box {
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      padding: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
    }

    .control-label {
      font-weight: 500;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 44px;
      height: 24px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      background-color: #ccc;
      border-radius: 34px;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      transition: .4s;
    }

    .slider:before {
      content: "";
      position: absolute;
      height: 16px;
      width: 16px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #2ecc71;
    }

    input:checked + .slider:before {
      transform: translateX(20px);
    }

    .slider.round {
      border-radius: 34px;
    }

    .footer {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .footer span {
      font-size: 0.9em;
    }

    .footer .buttons {
      display: flex;
      gap: 10px;
    }

    .circle-button {
      background-color: #fff;
      border: none;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      font-size: 18px;
      cursor: pointer;
      transition: background-color 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .circle-button:hover {
      background-color: #e0e0e0;
    }

    .theme-toggle {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 44px;
      height: 44px;
      background-color: #fff;
      border: none;
      border-radius: 50%;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition */
    }

    /* Dark Mode */
    .dark-mode {
      background-color: #121212;
      color: #f0f0f0;
    }

    .dark-mode .container,
    .dark-mode .card,
    .dark-mode .device-control,
    .dark-mode .control-box {
      background-color: #1e1e1e;
      color: #f0f0f0;
      border: 1px solid rgba(255,255,255,0.06);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .dark-mode .circle-button,
    .dark-mode .theme-toggle {
      background-color: #2c2c2c;
      color: #f0f0f0;
      border: 1px solid rgba(255,255,255,0.08);
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .dark-mode .circle-button:hover,
    .dark-mode .theme-toggle:hover {
      background-color: #3a3a3a;
    }

    /* Smooth transition for icon change in theme toggle */
    .dark-mode .theme-toggle i {
      transform: rotate(180deg);
      transition: transform 0.3s ease;
    }

  </style>
</head>
<body>
  <style>
    .toggle-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .toggle-text {
      font-weight: bold;
      font-size: 16px;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 24px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    .hidden {
      display: none;
    }
  </style>

  <div class="toggle-wrapper">
    <label class="switch" id="switchLabel">
      <input type="checkbox" id="modeToggle" onchange="toggleMode()">
      <span class="slider round"></span>
    </label>
    <span id="modeText" class="toggle-text">Mode Otomatis: OFF</span>
  </div>

  <script>
    function toggleMode() {
      const toggle = document.getElementById("modeToggle");
      const text = document.getElementById("modeText");
      const switchLabel = document.getElementById("switchLabel");

      if (toggle.checked) {
        switchLabel.classList.add("hidden");
        text.textContent = "Mode Otomatis: ON";
      } else {
        switchLabel.classList.remove("hidden");
        text.textContent = "Mode Otomatis: OFF";
      }
    }
  </script>


  <div class="container">
    <button class="theme-toggle" title="Toggle Dark Mode"><i class="bi bi-moon-fill"></i></button>

    <h1>Smart Farming Dashboard</h1>
    <div class="subtitle">Real-time monitoring system</div>

    <div class="grid">
      <div class="card">
        <div class="card-icon">🌡️</div>
        <div class="card-title">Temperature</div>
        <div class="card-value"><span id="ESP32_01_Temp"></span> &deg;C</span></div>
      </div>
      <div class="card">
        <div class="card-icon">💧</div>
        <div class="card-title">Humidity</div>
        <div class="card-value"><span id="ESP32_01_Humd"></span> &percnt;</span></div>
      </div>
      <div class="card">
        <div class="card-icon">🌱</div>
        <div class="card-title">Soil Moisture</div>
        <div class="card-value"><span id="ESP32_01_TempSOIL"></span>&percnt;</span></div>
      </div>
      <div class="card">
        <div class="card-icon">🔆</div>
        <div class="card-title">LDR (Light)</div>
        <div class="card-value"><span id="ESP32_01_TempLDR"></span>&percnt;</span></div>
      </div>
    </div>

    <div class="device-control">
      <h2>Controls</h2>
      <div class="control-grid">
        <div class="control-box">
          <span class="control-label">Lamp</span>
          <label class="switch">
            <input type="checkbox" id="lampSwitch" checked>
            <span class="slider round"></span>
          </label>
        </div>
        <div class="control-box">
          <span class="control-label">Fan</span>
          <label class="switch">
            <input type="checkbox" id="fanSwitch">
            <span class="slider round"></span>
          </label>
        </div>
        <div class="control-box">
          <span class="control-label">Water Pump</span>
          <label class="switch">
            <input type="checkbox" id="pumpSwitch">
            <span class="slider round"></span>
          </label>
        </div>
      </div>
    </div>

    <div class="footer">
      <span>Last updated: <span id="ESP32_01_LTRD"></span></span>
      <div class="buttons">
      <button class="circle-button" title="informasi"><i class="bi bi-info"></i></button>
        <button class="circle-button" title="Calendar">📅</button>
        
        <a href="logout.php" class="circle-button" title="Logout">
  <i class="bi bi-box-arrow-right logout-icon"></i>
</a>

      </div>
    </div>
  </div>

  <script>
    
    const body = document.body;

// Cek localStorage saat halaman dimuat

const themeToggle = document.querySelector('.theme-toggle');


if (localStorage.getItem('theme') === 'dark') {
  body.classList.add('dark-mode');
  themeToggle.innerHTML = '<i class="bi bi-brightness-low-fill"></i>';
} else {
  body.classList.remove('dark-mode');
  themeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
}

// Fungsi toggle tema dan simpan ke localStorage
themeToggle.addEventListener('click', () => {
  body.classList.toggle('dark-mode');
  const isDark = body.classList.contains('dark-mode');

  // Ganti ikon
  themeToggle.innerHTML = isDark
    ? '<i class="bi bi-brightness-low-fill"></i>'
    : '<i class="bi bi-moon-fill"></i>';

  // Simpan status ke localStorage
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
});



  </script>

  <script>

     // Example of data update logic
    document.getElementById("ESP32_01_Temp").innerHTML = "0"; 
    document.getElementById("ESP32_01_Humd").innerHTML = "0";
    document.getElementById("ESP32_01_TempSOIL").innerHTML = "0";
    document.getElementById("ESP32_01_TempLDR").innerHTML = "0";
    document.getElementById("ESP32_01_LTRD").innerHTML = "Not Ready";
  

    Get_Data("esp32_01");
      
      setInterval(myTimer, 5000);
      
      //------------------------------------------------------------
      function myTimer() {
        Get_Data("esp32_01");
      }

   

    //------------------------------------------------------------
    //document.getElementById("lastUpdated").textContent = new Date().toLocaleTimeString();

    function Get_Data(id) {
				if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "esp32_01") {
              document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
              document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
              document.getElementById("ESP32_01_TempSOIL").innerHTML = myObj.soil_moisture;
              document.getElementById("ESP32_01_TempLDR").innerHTML = myObj.light_level;
              document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + "";
              if (myObj.LED_01 == "ON") {
                document.getElementById("ESP32_01_TogLED_01").checked = true;
              } else if (myObj.LED_01 == "OFF") {
                document.getElementById("ESP32_01_TogLED_01").checked = false;
              }
              if (myObj.LED_02 == "ON") {
                document.getElementById("ESP32_01_TogLED_02").checked = true;
              } else if (myObj.LED_02 == "OFF") {
                document.getElementById("ESP32_01_TogLED_02").checked = false;
              }
            }
          }
        };
        xmlhttp.open("POST","getdata.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
			}

      
      //------------------------------------------------------------
      function GetTogBtnLEDState(togbtnid) {
        if (togbtnid == "ESP32_01_TogLED_01") {
          var togbtnchecked = document.getElementById(togbtnid).checked;
          var togbtncheckedsend = "";
          if (togbtnchecked == true) togbtncheckedsend = "ON";
          if (togbtnchecked == false) togbtncheckedsend = "OFF";
          Update_LEDs("esp32_01","LED_01",togbtncheckedsend);
        }
        if (togbtnid == "ESP32_01_TogLED_02") {
          var togbtnchecked = document.getElementById(togbtnid).checked;
          var togbtncheckedsend = "";
          if (togbtnchecked == true) togbtncheckedsend = "ON";
          if (togbtnchecked == false) togbtncheckedsend = "OFF";
          Update_LEDs("esp32_01","LED_02",togbtncheckedsend);
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Update_LEDs(id,lednum,ledstate) {
				if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("demo").innerHTML = this.responseText;
          }
        }
        xmlhttp.open("POST","updateLEDs.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id+"&lednum="+lednum+"&ledstate="+ledstate);
			}



      //------------------------------------------------------------
  </script>

</body>
</html>
