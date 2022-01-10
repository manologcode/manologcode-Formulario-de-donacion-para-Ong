<?php
# modetest=1
$modetest=$_POST['mode_test'] ? $_POST['mode_test'] :0;
define('MODETEST',$modetest);

if(MODETEST){
	echo "MODO TEST ACTIVADO\n";
}

require("config.php");

function valida_email($email){
	$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
	if(!preg_match($email_exp,$email)) {
	   return false;
 	}else{
	  	return true;
	}
}

function logger($logData=""){
    file_put_contents('email.log',date("j.n.Y h:i:s")." || $logData \n", FILE_APPEND);
}
function saveData($data=""){
    file_put_contents('data.txt',date("j.n.Y h:i:s")."  $data \n", FILE_APPEND);
}

$validar=false;

if (in_array($_POST['origen'], $domains)) {


	$cadenas = $grupos[$_POST['origen']];			   

	// recoger valores de post
	for ($i = 0; $i < count($cadenas); $i++) {
		$campo[$cadenas[$i]]=nl2br(htmlspecialchars(trim($_POST[$cadenas[$i]])));
		if ($cadenas[$i]=="email"){
			$validar=valida_email($campo[$cadenas[$i]]);
			if(MODETEST){
			echo "validar email -".$campo[$cadenas[$i]].":".$validar."<br>";
			}
		}
	}
	saveData(json_encode($campo));			   

	$mensaje_html = '<html><body bgcolor="#ffb380"><center><h1>Mensaje recibido de '.$_POST['origen'].'</h1>
					 <table width="90%" border="0" bgcolor="#FFFFFF" cellpadding="4" style="font: 13px Arial; color: #97AEBE; border: 1px solid #BBBBBB;">';
					for ($i = 0; $i < count($cadenas); $i++) {
						if ($campo[$cadenas[$i]] !=""){
							$mensaje_html.= ' <tr><td width="25%"><b>'.$cadenas[$i].':</b></td><td>'.$campo[$cadenas[$i]].'</td></tr>';
						}
					}
	$mensaje_html.= '</table></center></body></html>';
	$validar=true;

}else{
	if(MODETEST){
		echo "error dominio no permitido".$_POST['origen'];
	}
}

if($validar){
	if(MODETEST){
		echo "entra en enviar";
	}
	require("class.phpmailer.php");
	//ENVIAR MAIL AL CLIENTE
	$remite_mail=$campo['email'];
	$remite_nombre=$campo['nombre'];//name
	$titulo="WEB donaciones ".$_POST['origen']."-".$remite_mail;  //subject

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->SMTPDebug  = $mail_SMTPDebug;
	$mail->SMTPSecure= $mail_SMTPSecure;
	$mail->Port = $mail_Port;
	$mail->CharSet = $mail_CharSet;
	$mail->SMTPAuth = $mail_SMTPAuth;
	$mail->Timeout = 15;
	$mail->Host =$host;
	$mail->Username = $username;
	$mail->Password = $password;

	//email de remitente desde donde se envía el correo.
	//*****************************
	$mail->From=$username;
	$mail->FromName=$destino_nombre;
	//$mail->Sender=$remite_nombre; // direccion de envio
	$mail->AddReplyTo($remite_mail, $remite_nombre); // Responder a
	//*****************************
	//email de destinatario a donde se envía el correo.
	//*****************************
	$mail->AddAddress($destino_mail,$destino_nombre);//destinatario que va a recibir el correo
	//$mail->AddCC($remite_mail, 'copia');//envía una copia del correo a la dirección especificada

	$mail->Subject=$titulo;
	//$mail->AltBody = $mensaje_text;//cuerpo con texto plano
	//$mail->MsgHTML('Mensaje con HTML');//cuerpo con html
	$mail->Body = $mensaje_html;
	// Envio del mensaje

	if(!$mail->Send()) {//finalmente enviamos el email
		$estado="ERROR AL ENVIAL";
		if(MODETEST){
				$estado.=$mail->ErrorInfo;
		}
	} else {
		$estado='Mensaje enviado OK.';
	}

}else{
 	$estado="ERROR: al enviar";
}
logger($estado);
echo $estado;
?>
