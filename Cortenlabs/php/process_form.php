<?php
// Laad configuratie (optioneel als je credentials gebruikt)
require 'config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $berichttype = $_POST['berichttype']; // Honeypot
    $captcha = $_POST['captcha'];

    // Controleer honeypot
    if (!empty($berichttype)) {
        echo json_encode(["status" => "error", "message" => "Spam gedetecteerd. Bericht niet verzonden."]);
        exit;
    }

    // Controleer CAPTCHA
    $expectedDate = date('d-m-Y', strtotime('-2 days')); // Datum van de dag voor gisteren
    if ($captcha !== $expectedDate) {
        echo json_encode(["status" => "error", "message" => "Verkeerde CAPTCHA-oplossing. Probeer opnieuw."]);
        exit;
    }

    // E-mail verzenden
    $to = "website@cortenlabs.nl";
    $subject = "Nieuw bericht van $name";
    $headers = "From: $email\r\nReply-To: $email\r\n";
    $body = "Naam: $name\nE-mail: $email\n\nBericht:\n$message";
    // Voeg een CC toe naar de afzender
    $headers .= "CC: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success", "message" => "Bericht succesvol verstuurd!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Er is een fout opgetreden bij het verzenden van uw bericht."]);
    }
    exit;
}
?>