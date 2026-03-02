<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('debug_log.txt', "Reached PHP script at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

include('database.inc.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Name = trim(mysqli_real_escape_string($con, $_POST['name'] ?? ''));
    $Email = trim(mysqli_real_escape_string($con, $_POST['email'] ?? ''));
    $Phonenumber = trim(mysqli_real_escape_string($con, $_POST['phone'] ?? ''));
    $Message = trim(mysqli_real_escape_string($con, $_POST['message'] ?? ''));

    $error_msg = "";
    $phone_err = "";

    if (empty($Name)) {
        $error_msg .= '*Name is required* ';
    }
    if (empty($Phonenumber)) {
        $phone_err .= '*Phone number is required* ';
    }
    if (empty($Email)) {
        $error_msg .= '*Email is required* ';
    }

    $cleanedPhone = preg_replace('/[^0-9]/', '', $Phonenumber);
    if (strlen($cleanedPhone) < 10 || strlen($cleanedPhone) > 15) {
        $phone_err .= '*Enter a valid Mobile Number* ';
    } else {
        $Phonenumber = $cleanedPhone;
    }

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    if (!preg_match($email_exp, $Email)) {
        $error_msg .= 'Please Enter a valid Email Address ';
    } else {
        $cleanedEmail = str_replace(' ', '', $Email);
        if (!filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL)) {
            $error_msg .= 'Invalid email address ';
        } else {
            $Email = $cleanedEmail;
        }
    }

    if (empty($error_msg) && empty($phone_err)) {
        $html = "
            Name: $Name <br>
            Phone Number: $Phonenumber <br>
            Email: $Email <br>
            Message: $Message <br>
        ";

        $query = "INSERT INTO kaduhithlu_forms (Name, PhoneNumber, Email, Message) 
                  VALUES ('$Name', '$Phonenumber', '$Email', '$Message')";

        if (mysqli_query($con, $query)) {
            mysqli_close($con);

            require 'phpmailer/src/PHPMailer.php';
            require 'phpmailer/src/SMTP.php';
            require 'phpmailer/src/Exception.php';

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'balaabimanyugnc@gmail.com';
                $mail->Password = 'zicy vgmr gnsc gpyk';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->SMTPDebug = 0;

                $mail->setFrom('balaabimanyugnc@gmail.com', 'kaduhithlu');
                $mail->addAddress('balaabimanyugnc@gmail.com', 'kaduhithlu');
                $mail->isHTML(true);
                $mail->Subject = 'kaduhithlu Inquiry';
                $mail->Body = $html;

                $mail->send();

                $userMail = new PHPMailer(true);
                $userMail->isSMTP();
                $userMail->Host = 'smtp.gmail.com';
                $userMail->SMTPAuth = true;
                $userMail->Username = 'balaabimanyugnc@gmail.com';
                $userMail->Password = 'zicy vgmr gnsc gpyk';
                $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $userMail->Port = 587;

                $userMail->SMTPDebug = 0;

                $userMail->setFrom('balaabimanyugnc@gmail.com', 'kaduhithlu');
                $userMail->addAddress($Email, $Name);
                $userMail->isHTML(true);
                $userMail->Subject = 'Thank You for Contacting Us';
                $userMail->Body = "
                    Hi <b>$Name</b>,<br><br>
                    Thank you for reaching out to <b>kaduhithlu</b>.<br>
                    We have received your inquiry and will get back to you shortly.<br><br>
                    Regards,<br>
                    kaduhithlu Team";

                $userMail->send();

                http_response_code(200);
                $response['status'] = 'success';
                $response['message'] = 'Form Submitted Successfully';
                ob_clean();
                echo json_encode($response);
                exit();
            } catch (Exception $e) {
                $to = 'balaabimanyugnc@gmail.com';
                $subject = 'kaduhithlu Inquiry';
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                $headers .= 'From: balaabimanyugnc@gmail.com' . "\r\n";

                if (mail($to, $subject, $html, $headers)) {
                    http_response_code(200);
                    $response['status'] = 'success';
                    $response['message'] = 'Form Submitted Successfully (via fallback)';
                    ob_clean();
                    echo json_encode($response);
                    exit();
                } else {
                    http_response_code(500);
                    $response['status'] = 'error';
                    $response['message'] = 'Email could not be sent';
                    ob_clean();
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            http_response_code(500);
            $response['message'] = 'Database error: ' . mysqli_error($con);
            ob_clean();
            echo json_encode($response);
            exit();
        }
    } else {
        http_response_code(400);
        $response['errors'] = ['name' => $error_msg, 'tel' => $phone_err];
        ob_clean();
        echo json_encode($response);
        exit();
    }
} else {
    http_response_code(405);
    $response['message'] = 'Invalid Request Method';
    ob_clean();
    echo json_encode($response);
    exit();
}
