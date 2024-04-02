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
  <style>
    #map {
      height: 600px;
      width: 100%;
    }
  </style>
<body>
<?php
  include 'inc/header.inc.php'; 
  include 'inc/head.inc.php';
?>
</head>
<body>
  <?php
    include 'inc/nav.inc.php';
  ?>

  <h1>Find Us - Little Haven Shoppe</h1>
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

  <section class="contact-info">
    <h2>Contact Information</h2>
    <ul>
      <li>
        <i class="fas fa-map-marker-alt"></i>
        <span>172A Ang Mo Kio Avenue 8, Singapore 567739</span>
      </li>
      <li>
        <i class="fas fa-phone"></i>
        <span>Phone: +65 65921189</span>
      </li>
      <li>
        <i class="fas fa-clock"></i>
        <span>Open hours: <br>Monday: 0700-1700 <br>
                           <span>Tuesday: 0700-1700 <br></span>
                           <span>Wednesday: 0700-1700 <br></span>
                           <span>Thursday: 0700-1700 <br></span>
                           <span>Friday: 0700-1700 <br></span>
                           <span>Saturday: 0700-1200 <br></span>
                           <span>Sunday: Closed <br></span>
      </li>
    </ul>
  </section>
  <?php include "inc/footer.inc.php"; ?>
</body>

</html>
