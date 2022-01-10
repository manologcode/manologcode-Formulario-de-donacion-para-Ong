<?php
	$host = "xxxxxxx";
	$username = "xxxxxx@xxxxxx.org";
    $password = "passsssss";

	$mail_SMTPDebug  = 1;
	$mail_SMTPSecure='ssl';
	$mail_Port = 465;
	$mail_CharSet="UTF-8";
	$mail_SMTPAuth = true;

	//*****************************

	$destino_nombre="Donaciones";
	$destino_mail="xxxxxxx@xxxxxxxxxx.org.es";


	$domains = ["1_asociate_email", "2_asociate", "1_donacion_email", "2_donacion", "0_contacta"];

	$grupos =array (
		"1_asociate_email" => array ("donacion","periodo","email"),
		"2_asociate" => array ("donacion","periodo","email","tipo","r_social","nombre","nif","iban","telefono","provincia"),
		"1_donacion_email" => array ("donacion","email","periodica","periodo"),
		"2_donacion" => array ("donacion","periodo","email","tipo","r_social","nombre","nif","telefono","provincia"),
		"0_contacta" => array ("name","email","message")
   );
