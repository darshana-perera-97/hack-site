<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    // Check if the "email" field is set in the form submission
    if (isset($_POST["email"])) {
        // Verify reCAPTCHA
        $recaptchaSecretKey = '6LeRj_onAAAAACfF1VKNIaCv0z8WaDwdlxBg1X19';
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => $recaptchaSecretKey,
            'response' => $recaptchaResponse,
        ];
        $recaptchaOptions = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($recaptchaData),
            ],
        ];
        $recaptchaContext = stream_context_create($recaptchaOptions);
        $recaptchaResult = file_get_contents($recaptchaUrl, false, $recaptchaContext);
        $recaptchaSuccess = json_decode($recaptchaResult);

        // Check if reCAPTCHA was successful
        if (!$recaptchaSuccess->success) {
            echo '<script>document.getElementById("recaptcha-warning").style.display = "block";</script>';
            exit; // Prevent further execution
        }

        // If reCAPTCHA verification is successful, proceed with sending the email
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ds.perera.test@gmail.com';
        $mail->Password = 'ycfdgqfhinumrzjx';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('centos-migrate@hsenid.com');
        $mail->addAddress('darshana.saluka.pc@gmail.com'); // Add recipient's email address here
        $mail->isHTML(true);

        $mail->Subject = "Migrate CentOS - " . $_POST["name"];
        $mail->Body = "Name: " . $_POST["name"] . "<br>"
            . "Email: " . $_POST["email"] . "<br>"
            . "Phone: " . $_POST["telephone"] . "<br>" // Changed from 'phone' to 'telephone'
            . "Company: " . $_POST["company"] . "<br>"
            . "Role: " . $_POST["role"] . "<br>"
            . "Country: " . $_POST["country"];

        try {
            $mail->send();
            echo "
            <script>
            document.location.href = 'thankyou.html';
            </script>";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email field not found in the form submission.";
    }
}
