<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shoppe | About Us</title>
<?php
  include 'inc/head.inc.php';
?>
</head>
<body>
<?php
  include 'inc/nav.inc.php';
?>


<div class="container">

  <div class="content mt-5">
    <h1>About Little Haven Shoppe</h1>

    <div class="interactive-section">
      <h2>Our Story</h2>
      <p class="interactive-content">Write a captivating story about Little Haven Shoppe's journey, from its inception to its current standing. Highlight the inspiration behind the shoppe, the challenges you've overcome, and your vision for the future.</p>
      <i class="fas fa-angle-down"></i> </div>

    <div class="interactive-section">
      <h2>Our Mission & Values</h2>
      <p class="interactive-content">Clearly articulate Little Haven Shoppe's mission statement and core values. Explain how these principles guide your everyday operations and how they benefit your customers.</p>
      <i class="fas fa-angle-down"></i> </div>

    <div class="interactive-section">
      <h2>What Makes Us Unique</h2>
      <p class="interactive-content">Emphasize the distinctive qualities that set Little Haven Shoppe apart from competitors. Mention your product selection, customer service approach, or any special initiatives that make you stand out.</p>
      <i class="fas fa-angle-down"></i> </div>

    <div class="interactive-section">
      <h2>Meet Our Team</h2>
      <p class="interactive-content">Showcase your team members with short bios, pictures, and (optional) links to their social media profiles. Let your customers connect with the passionate individuals behind Little Haven Shoppe.</p>
      <i class="fas fa-angle-down"></i> </div>

    <img src="images/company_image1.jpg" class="img-fluid mb-3" alt="Company Image 1">
    <img src="images/company_image2.jpg" class="img-fluid mb-3" alt="Company Image 2">

    <h2>Connect with Us</h2>
    <div class="social-media">
      <a href="https://www.facebook.com/YourCompanyGraz/events/"><i class="fab fa-facebook-f"></i></a>
      <a href="https://twitter.com/CompanyYouKeep"><i class="fab fa-twitter"></i></a>
      <a href="https://www.instagram.com/company/"><i class="fab fa-instagram"></i></a>
    </div>
  </div>

</div>

<?php
  include 'inc/footer.inc.php';
?>
<script src="https://kit.fontawesome.com/your-fontawesome-kit-code.js" crossorigin="anonymous"></script> <script>
  // Add JavaScript for interactive elements
  const interactiveSections = document.querySelectorAll('.interactive-section');
  interactiveSections.forEach(section => {
    section.addEventListener('click', () => {
      const content = section.nextElementSibling;
      content.classList.toggle('interactive-content');
      section.querySelector('i').classList.toggle('fa-angle-down');
      section.querySelector('i').classList.toggle('fa-angle-up');
    });
  });
</script>
</body>
</html>
