<?php include '../../components/header.php'; ?>
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/info.css">

<main class="info-section">
  <?php include '../../components/info-menu.php'; ?>

  <section class="info-content">
    <h1>Contact Us</h1>

    <?php
    $success = $error = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      // Honeypot spam check
      if (!empty($_POST['website'])) {
        $error = "Spam detected.";
      } else {
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $company = htmlspecialchars(trim($_POST["company"]));
        $message = htmlspecialchars(trim($_POST["message"]));

        if ($name && $email && $message) {
          $to = "yourcompany@example.com"; // Replace with your company email
          $subject = "Contact Form Submission from $name";
          $body = "Name: $name\nEmail: $email\nPhone: $phone\nCompany: $company\n\nMessage:\n$message";
          $headers = "From: $email";

          if (mail($to, $subject, $body, $headers)) {
            $success = "Thank you! Your message has been sent.";
          } else {
            $error = "Something went wrong. Please try again.";
          }
        } else {
          $error = "Please fill out all required fields.";
        }
      }
    }
    ?>

    <?php if ($success): ?>
      <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="contact-form" novalidate>
      <input type="text" name="name" placeholder="Full Name *" required>
      <input type="email" name="email" placeholder="Email Address *" required>
      <input type="text" name="phone" placeholder="Phone Number">
      <input type="text" name="company" placeholder="Company Name">
      <textarea name="message" placeholder="Your Message *" rows="6" required></textarea>

      <!-- Honeypot anti-spam field -->
      <input type="text" name="website" style="display:none">

      <button type="submit">Send Message</button>
    </form>
  </section>
</main>

<?php include '../../components/footer.php'; ?>
