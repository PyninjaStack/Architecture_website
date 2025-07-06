<?php include '../../components/header.php'; ?>
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/info.css">

<main class="info-section">
  <?php include '../../components/info-menu.php'; ?>

  
    <h1>CONTACT US</h1>

    <?php
    $success = $error = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      if (!empty($_POST['website'])) {
        $error = "Spam detected.";
      } else {
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $company = htmlspecialchars(trim($_POST["company"]));
        $message = htmlspecialchars(trim($_POST["message"]));

        //TO SEND EMAIL 
        /*if ($name && $email && $phone) {
          $to = "yourcompany@example.com";
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
        }*/

        // TO CHECK TEMPORARY FORM WORKING
        if ($name && $email && $phone) {
          $subject = "Contact Form Submission from $name";
          $body = "Name: $name\nEmail: $email\nPhone: $phone\nCompany: $company\n\nMessage:\n$message";

          // ✅ Save to a local file for testing instead of sending email
          file_put_contents("messages.txt", $body . "\n\n-------------------\n\n", FILE_APPEND);
          $success = "Thank you! Your message has been saved (email simulation).";
        } else {
          $error = "Please fill out all required fields.";
        }

      }
    }
    ?>
    
    <div class="contact-container">

    <?php if ($success): ?>
      <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    
      <form action="" method="POST" class="contact-form" novalidate>
        <input type="text" name="name" placeholder="Full Name *" required>
        <input type="email" name="email" placeholder="Email Address *" required>
        <input type="text" name="phone" placeholder="Phone Number *" required>
        <input type="text" name="company" placeholder="Company Name">
        <textarea name="message" placeholder="Your Message" rows="6"></textarea>

        <!-- Honeypot anti-spam field -->
        <input type="text" name="website" style="display:none">

        <button type="submit">Send</button>
      </form>
    </div>
  
</main>

<?php include '../../components/footer.php'; ?>
