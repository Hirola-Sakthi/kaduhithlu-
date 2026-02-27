<?php
$subject = 'New Reservation'; // Subject of your email
$to = 'contact@designesia.com';  //Recipient's E-mail

$emailTo = $_POST['email'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$msg = $_POST['message'];
$date = $_POST['date'];
$room_type1 = $_POST['room_type'];
$room_type2 = $_POST['room_type2'];
$room_type3 = $_POST['room_type3'];
$room_type4 = $_POST['room_type4'];
$room_type5 = $_POST['room_type5'];
$room_type6 = $_POST['room_type6'];
$room_type7 = $_POST['room_type7'];
$room_type8 = $_POST['room_type8'];
$room_type9 = $_POST['room_type9'];
$room_type10 = $_POST['room_type10'];
$adult = $_POST['adult'];
$children = $_POST['children'];
$room_count = $_POST['room_count'];

$email_from = $name. ' ' . '<'.$email.'>';

$headers = "MIME-Version: 1.1";
$headers .= "Content-type: text/html; charset=iso-8859-1";
$headers .= "From: ".$name.'<'.$email.'>'."\r\n"; // Sender's E-mail
$headers .= "Return-Path:"."From:" . $email;

$message .= 'Name : ' . $name . "\n";
$message .= 'Email : ' . $email . "\n";
$message .= 'Phone : ' . $phone . "\n";
$message .= 'Date : ' . $date . "\n";
$message .= 'Room Count : ' . $room_count . "\n";
$message .= 'Adult(s) : ' . $adult . "\n";
$message .= 'Child(s) : ' . $children . "\n";
$message .= 'Room Type 1 : ' . $room_type1 . "\n";
if($room_type2!=''){$message .= 'Room Type 2 : ' . $room_type2 . "\n";}
if($room_type3!=''){$message .= 'Room Type 3 : ' . $room_type3 . "\n";}
if($room_type4!=''){$message .= 'Room Type 4 : ' . $room_type4 . "\n";}
if($room_type5!=''){$message .= 'Room Type 5 : ' . $room_type5 . "\n";}
if($room_type6!=''){$message .= 'Room Type 6 : ' . $room_type5 . "\n";}
if($room_type7!=''){$message .= 'Room Type 7 : ' . $room_type5 . "\n";}
if($room_type8!=''){$message .= 'Room Type 8 : ' . $room_type5 . "\n";}
if($room_type9!=''){$message .= 'Room Type 9 : ' . $room_type5 . "\n";}
if($room_type10!=''){$message .= 'Room Type 10 : ' . $room_type5 . "\n";}
$message .= 'Message : ' . $msg;

if (@mail($to, $subject, $message, $email_from))
{
	// Transfer the value 'sent' to ajax function for showing success message.
	echo 'sent';
}
else
{
	// Transfer the value 'failed' to ajax function for showing error message.
	echo 'failed';
}
?>