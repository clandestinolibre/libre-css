<?php
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: no-referrer-when-downgrade");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	http_response_code(405);
	echo json_encode(["success" => false, "error" => "Method not allowed"]);
	exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !is_array($input)) {
	http_response_code(400);
	echo json_encode([
		"success" => false,
		"error" => "Ungültiges Anfrageformat (JSON erwartet).",
	]);
	exit();
}

$name = trim($input["name"] ?? "");
$email = trim($input["email"] ?? "");
$phone = trim($input["phone"] ?? "");
$message = trim($input["message"] ?? "");
$privacy = trim($input["privacy"] ?? "");
$turnstile = trim($input["turnstile"] ?? "");

$errors = [];

if (strlen($name) < 2) {
	$errors["name"] = "Bitte einen gültigen Namen eingeben.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors["email"] = "Bitte eine gültige E-Mail-Adresse angeben.";
}

if (strlen($message) < 10) {
	$errors["message"] = "Die Nachricht muss mindestens 10 Zeichen enthalten.";
}

if ($privacy !== "yes") {
	$errors["privacy"] = "Bitte akzeptieren Sie die Datenschutzerklärung.";
}

if (!$turnstile) {
	$errors["turnstile"] = "Ungültige Captcha-Bestätigung.";
}

if (!empty($errors)) {
	http_response_code(400);
	echo json_encode(["success" => false, "errors" => $errors]);
	exit();
}


$secretKey = "HIER_DEIN_TURNSTILE_SECRET_KEY_EINTRAGEN";

if ($secretKey === "HIER_DEIN_TURNSTILE_SECRET_KEY_EINTRAGEN") {
	echo json_encode([
		"success" => false,
		"error" => "Turnstile Secret Key fehlt.",
	]);
	exit();
}

$verify = http_build_query([
	"secret" => $secretKey,
	"response" => $turnstile,
	"remoteip" => $_SERVER["REMOTE_ADDR"] ?? "",
]);

$context = stream_context_create([
	"http" => [
		"method" => "POST",
		"header" => "Content-Type: application/x-www-form-urlencoded",
		"content" => $verify,
	],
]);

$verifyResponse = file_get_contents(
	"https://challenges.cloudflare.com/turnstile/v0/siteverify",
	false,
	$context,
);

$verification = json_decode($verifyResponse, true);

if (!($verification["success"] ?? false)) {
	http_response_code(400);
	echo json_encode([
		"success" => false,
		"error" => "Captcha-Verifikation fehlgeschlagen.",
		"details" => $verification,
	]);
	exit();
}

require_once __DIR__ . "/phpmailer/PHPMailer.php";
require_once __DIR__ . "/phpmailer/SMTP.php";
require_once __DIR__ . "/phpmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

	$mail->isSMTP();
	$mail->Host = "smtp.DEINE-DOMAIN.de";
	$mail->SMTPAuth = true;
	$mail->Username = "[email protected]";
	$mail->Password = "SMTP_PASSWORT";
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;

	$mail->setFrom("[email protected]", "Website Kontaktformular");
	$mail->addAddress("[email protected]", "Admin");
	$mail->addReplyTo($email, $name);


	$htmlMessage =
		'
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial, sans-serif; background:#f7f7f7; padding:20px;">
      <tr><td align="center">
        <table width="600" cellpadding="20" cellspacing="0" style="background:white; border-radius:8px;">

          <tr>
            <td style="text-align:center; background:#0f172a; color:white; border-radius:8px 8px 0 0;">
              <h2 style="margin:0; padding:10px 0;">Neue Kontaktanfrage</h2>
            </td>
          </tr>

          <tr>
            <td style="color:#333; font-size:16px;">
              <p><strong>Name:</strong> ' .
		$name .
		'</p>
              <p><strong>E-Mail:</strong> ' .
		$email .
		'</p>
              <p><strong>Telefon:</strong> ' .
		$phone .
		'</p>
              <p><strong>Nachricht:</strong><br><br>' .
		nl2br(htmlspecialchars($message)) .
		'</p>
            </td>
          </tr>

          <tr>
            <td style="font-size:12px; color:#666; text-align:center;">
              Diese E-Mail wurde automatisch über das Kontaktformular deiner Website gesendet.
            </td>
          </tr>

        </table>
      </td></tr>
    </table>';

	$mail->isHTML(true);
	$mail->Subject = "Neue Kontaktanfrage von $name";
	$mail->Body = $htmlMessage;

	$mail->send();

	echo json_encode(["success" => true]);
	exit();
} catch (Exception $e) {

	http_response_code(500);
	echo json_encode([
		"success" => false,
		"error" => "Mailversand fehlgeschlagen.",
		"details" => $mail->ErrorInfo,
	]);
	exit();
}
