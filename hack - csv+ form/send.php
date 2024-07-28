<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    // Check if the "email" field is set in the form submission
    if (isset($_POST["email"])) {
        // Initialize PHPMailer for the first email
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

            // Initialize PHPMailer for the confirmation email
            $mail->clearAddresses();
            $mail->addAddress($_POST["email"]); // Add the user's email address
            $mail->Subject = "Thank you for contacting us!";
            $mail->Body = "Dear " . $_POST["name"] . ",<br><br>"
                . "Thank you for getting in touch with us. We have received your details and will get back to you shortly.<br><br>"
                . "Best regards,<br>"
                . "The CentOS Migration Team";

            $mail->send();

            // Store the entered data in a CSV file
            $file = 'form_data.csv';
            $data = [
                $_POST["name"],
                $_POST["email"],
                $_POST["telephone"], // Changed from 'phone' to 'telephone'
                $_POST["company"],
                $_POST["role"],
                $_POST["country"]
            ];

            $file_exists = file_exists($file);

            $handle = fopen($file, 'a');
            if (!$file_exists) {
                fputcsv($handle, ['Name', 'Email', 'Phone', 'Company', 'Role', 'Country']);
            }
            fputcsv($handle, $data);
            fclose($handle);

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
