<?php
// Load configuration
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $honeypot = $_POST['honeypot'];
    $captcha = $_POST['captcha'];

    // Controleer honeypot
    if (!empty($honeypot)) {
        die("Spam gedetecteerd. Bericht niet verzonden.");
    }

    // Controleer CAPTCHA
    if ($captcha !== '7') { // Verwacht resultaat van "4 + 3"
        die("Verkeerde CAPTCHA-oplossing. Probeer opnieuw.");
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
