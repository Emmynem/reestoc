<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// class sendEmailClass {
//   public $engineMessage = 0;
//   public $error = 0;
// }

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$data = json_decode(file_get_contents("php://input"), true);

$fullname = $data['fullname'];
$email = $data['email'];
$subject = $data['subject'];
$username = $data['username'];
$message = $data['message'];

try {
    //Server settings
    $mail->SMTPDebug = 2;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'hellobeautifulworld.net';                    // Set the SMTP server to send through
    // $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'noreply@hellobeautifulworld.net';                     // SMTP username
    $mail->Password   = 'dJ2p8kSj4Mo';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 25;                                  // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@hellobeautifulworld.net', $username);
    $mail->addAddress($email, $fullname);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    $mail->send();
    echo 'Message has been sent';
    // $returnvalue = new sendEmailClass();
    // $returnvalue->engineMessage = 1;
} catch (Exception $e) {
  // $returnvalue = new sendEmailClass();
  // $returnvalue->error = 2;
    echo "Message could not be sent";
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// echo json_encode($returnvalue);
