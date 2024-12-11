<?php
// Load configuration
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Verify reCAPTCHA
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_secret = $config['recaptcha_secret'];

    $response = file_get_contents($recaptcha_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $response_keys = json_decode($response, true);

    if (!$response_keys["success"]) {
        die("Captcha verificatie mislukt. Probeer opnieuw.");
    }

    // Email verzenden
    $to = "website@cortenlabs.nl";
    $subject = "Nieuw bericht van $name";
    $headers = "From: $email\r\nReply-To: $email\r\n";
    $body = "Naam: $name\nE-mail: $email\n\nBericht:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        echo "Bericht succesvol verstuurd!";
    } else {
        echo "Er is een fout opgetreden bij het verzenden van uw bericht.";
    }
}
?>
