<?php
$latitude = 1.3778101623320074; // Replace with your desired latitude
$longitude = 103.84886987401006; // Replace with your desired longitude

$api_key = 'AIzaSyBVgoKLVNax1NGb2nb8zU8HP2XOOXJMKtw'; // Replace with your actual Google Maps API key
?>

<!DOCTYPE html>
<html>
<head>
<title>Find Us - [Your Business Name]</title>
  <style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
<body>
<?php
  include 'inc/nav.inc.php';
?>

  <h1>Find Us - [Your Business Name]</h1>
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
          content: '[Your Business Address] <br> Phone: [Your Phone Number] <br> Open hours: [Your Opening Hours]'
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
  <p>[Your Business Address]</p>
  <p>Phone: [Your Phone Number]</p>
  <p>Open hours: [Your Opening Hours]</p>
</body>
</html>
