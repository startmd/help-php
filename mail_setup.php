<?php

$mail = new PHPMailer;

$mail->isSMTP();

$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';

$mail->Host = $host;

$mail->Port = $port;

$mail->SMTPSecure = $security;

$mail->SMTPAuth=true;

$mail->Username=$mail_username;

$mail->Password=$mail_password;

$mail->setFrom($mail_username,$username);

$mail->addReplyTo($mail_username,$username);

