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
		"error" => "Invalid request format (JSON expected).",
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
	$errors["name"] = "Please enter a valid name.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors["email"] = "Please enter a valid email address.";
}

if (strlen($message) < 10) {
	$errors["message"] = "The message must contain at least 10 characters.";
}

if ($privacy !== "yes") {
	$errors["privacy"] = "Please accept the privacy policy.";
}

if (!$turnstile) {
	$errors["turnstile"] = "Invalid captcha verification.";
}

if (!empty($errors)) {
	http_response_code(400);
	echo json_encode(["success" => false, "errors" => $errors]);
	exit();
}


$secretKey = "ENTER_YOUR_TURNSTILE_SECRET_KEY_HERE";

if ($secretKey === "ENTER_YOUR_TURNSTILE_SECRET_KEY_HERE") {
	echo json_encode([
		"success" => false,
		"error" => "Turnstile Secret Key is missing.",
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
		"error" => "Captcha verification failed.",
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
	$mail->Host = "smtp.YOUR-DOMAIN.com";
	$mail->SMTPAuth = true;
	$mail->Username = "[email protected]";
	$mail->Password = "SMTP_PASSWORD";
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;

	$mail->setFrom("[email protected]", "Website Contact Form");
	$mail->addAddress("[email protected]", "Admin");
	$mail->addReplyTo($email, $name);


	$htmlMessage =
		'
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial, sans-serif; background:#f7f7f7; padding:20px;">
      <tr><td align="center">
        <table width="600" cellpadding="20" cellspacing="0" style="background:white; border-radius:8px;">

          <tr>
            <td style="text-align:center; background:#0f172a; color:white; border-radius:8px 8px 0 0;">
              <h2 style="margin:0; padding:10px 0;">New website contact message</h2>
            </td>
          </tr>

          <tr>
            <td style="color:#333; font-size:16px;">
              <p><strong>Name:</strong> ' .
		$name .
		'</p>
              <p><strong>email:</strong> ' .
		$email .
		'</p>
              <p><strong>Phone:</strong> ' .
		$phone .
		'</p>
              <p><strong>Message:</strong><br><br>' .
		nl2br(htmlspecialchars($message)) .
		'</p>
            </td>
          </tr>

          <tr>
            <td style="font-size:12px; color:#666; text-align:center;">
		This email was sent automatically via the contact form on your website.
		</td>
          </tr>

        </table>
      </td></tr>
    </table>';

	$mail->isHTML(true);
	$mail->Subject = "New conact request from $name";
	$mail->Body = $htmlMessage;

	$mail->send();

	echo json_encode(["success" => true]);
	exit();
} catch (Exception $e) {

	http_response_code(500);
	echo json_encode([
		"success" => false,
		"error" => "Email delivery failed.",
		"details" => $mail->ErrorInfo,
	]);
	exit();
}
