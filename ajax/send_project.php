<?php

$name = $_POST['name'];
$to = $_POST['email'];
$subject = "Hey, thanks for sharing!";
$message = '<div style="border:#AAA 1px solid; font-family:Helvetica, sans-serif; padding:10px; margin:5px; font-size:11px; width:500px">
			<div style="border-bottom:8px solid #666; margin:0px; padding-bottom:10px"><img src="http://www.bryanchua.com/images/bryanchua.png" border="0" width="300" /></div>
			<p>
			Hi <strong>'.$name.'</strong>,
			<p>
			Thanks for sharing. I will contact you shortly.
			<p>
			Project Details:<br>
			'.$_POST['details'].'
			<p>
			Cheers,<br />
			<strong>Bryan Chua</strong>
			</div>';
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'From: '.$name.' <'.$to.'>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: bryan@ronzaro.com' . "\r\n";

// Mail it
mail("me@bryanchua.com", "(".$to.") Project Details", $_POST['details'], $headers);
//mail($to, $subject, $message, $headers);

$return['status'] = "OK";

echo json_encode($return);

?>