<?php
    session_start();
?>
<?php
$latitude = 1.3778101623320074; // latitude
$longitude = 103.84886987401006; //longitude

$api_key = 'AIzaSyBVgoKLVNax1NGb2nb8zU8HP2XOOXJMKtw'; //Google Maps API key
?>

<!DOCTYPE html>
<html>
<title>Find Us - Little Haven Shoppe </title>
  <style>
    #map {
      height: 600px;
      width: 100%;
    }
  </style>
<body>
<?php
  include 'inc/head.inc.php';
?>
</head>
<body>
<?php
  include 'inc/nav.inc.php';
?>

  <h1>Find Us - Little Haven Shoppe </h1>
  <div id="map"></div>
  <script>
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?> }
      });

      var marker = new google.maps.Marker({
        position: { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?> },
        map: map,
        infoWindow: new google.maps.InfoWindow({
          content: '172A Ang Mo Kio Avenue 8, Singapore 567739 <br> Phone:+65 65921189 <br> Open hours: Monday to Friday 0700-1700'
        })
      });

      marker.addListener('click', function() {
        this.infoWindow.open(map, this);
      });
    }
  </script>
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&callback=initMap">
  </script>
  <p>172A Ang Mo Kio Avenue 8, Singapore 567739</p>
  <p>Phone:+65 65921189</p>
  <p>Open hours: Monday to Friday 0700-1700</p>
</body>
</html>
