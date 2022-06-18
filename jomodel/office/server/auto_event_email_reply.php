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

$event_name = $data['event_name'];
$fullname = $data['fullname'];
$email = $data['email'];
$subject = $data['subject'];
$message = '
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Hello Beauitiful World</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<style type="text/css">
			a{
				outline:none;
				color:#9c609c;
				text-decoration:underline;
			}
			a:hover{text-decoration:none !important;}
			a[x-apple-data-detectors]{color:inherit !important; text-decoration:none !important;}
			p{Margin:0 !important;}
			.active:hover{opacity:0.8;}
			.active{
				-webkit-transition:all 0.3s ease;
				-moz-transition:all 0.3s ease;
				-ms-transition:all 0.3s ease;
				transition:all 0.3s ease;
			}
			a img{border:none;}
			table td{mso-line-height-rule:exactly;}
			.global-link-02 a{text-decoration: none;}
			.global-link-02 a:hover{text-decoration: underline !important;}
			.global-link-01 a:hover{text-decoration: none !important;}
			.global-link-01 a{color: #fff;}
			.btn-01:hover{background:#FFEEF2;}
			.btn-02:hover{background:#851449;}
			.ExternalClass, .ExternalClass a, .ExternalClass span, .ExternalClass b, .ExternalClass br, .ExternalClass p, .ExternalClass div{line-height:inherit;}
			@media only screen and (max-width:500px) {
				/* default style */
				table[class="flexible"]{width:100% !important;}
				table[class="table-center"]{float:none !important; margin:0 auto !important;}
				*[class="hide"]{display:none !important; width:0 !important; height:0 !important; padding:0 !important; font-size:0 !important; line-height:0 !important;}
				span[class="db"]{display:block !important;}
				td[class="img-flex"] img{width:100% !important; height:auto !important;}
				td[class="aligncenter"]{text-align:center !important;}
				tr[class="table-holder"]{display:table !important; width:100% !important;}
				th[class="tcap"]{display:table-caption !important; width:100% !important;}
				th[class="thead"]{display:table-header-group !important; width:100% !important;}
				th[class="trow"]{display:table-row !important; width:100% !important;}
				th[class="tfoot"]{display:table-footer-group !important; width:100% !important;}
				th[class="flex"]{display:block !important; width:100% !important;}
				/* custom style */
				td[class="h-auto"]{height: auto !important;}
				td[class="wrapper"]{padding:0 !important;}
				td[class="indent-price"]{padding:20px 0 15px !important;}
				td[class="indent-null"]{padding:0 !important;}
				td[class="frame"]{padding:20px !important;}
				td[class="btn-holder"]{padding:40px 15px !important;}
				td[class="container"]{padding:10px 20px 20px !important;}
				td[class="indent-bottom"]{padding:0 0 30px !important;}
				td[class="frame-holder"]{padding:30px 20px !important;}
				td[class="title"]{
					padding:0 0 10px !important;
					font-size:18px !important;
					line-height:22px !important;
				}
			}
		</style>
	</head>
	<body style="margin:0; padding: 0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;" bgcolor="#fff">
		<table style="min-width:320px; border-radius: 25px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff">
			<!-- fix for gmail -->
			<tr>
				<td style="line-height:0;"><div style="display:none; white-space:nowrap; font:15px/1px courier;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div></td>
			</tr>
			<tr>
				<td class="indent-null" style="padding:0 10px;">
					<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
						<!-- fix for gmail -->
						<tr>
							<td class="hide">
								<table width="600" cellpadding="0" cellspacing="0" style="width:600px !important;">
									<tr>
										<td style="min-width:600px; font-size:0; line-height:0;">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:43px 0 37px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr class="table-holder">
										<th class="tfoot" align="left" style="vertical-align:top; padding:0;">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td class="aligncenter"><a href="https://www.hellobeautifulworld.net" target="_blank"><img editable="true" src="https://images.hellobeautifulworld.net/gallery/favicon.png" height="100" width="100" style="vertical-align:top;" alt="Hello Beautiful World" /></a></td>
												</tr>
											</table>
										</th>
										<th class="trow" height="20" align="left" style="vertical-align:top; padding:0;"></th>
										<th class="thead" align="left" style="vertical-align:top; padding:0;">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td class="aligncenter" align="right" style="font:14px/16px Arial, Helvetica, sans-serif; color:#e5babe;"><webversion href="#" target="_blank" style="text-decoration:underline; color:#e5babe;">View this email in browser</webversion></td>
												</tr>
											</table>
										</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="img-flex"><img src="https://events.hellobeautifulworld.net/img/hbw_first.png" height="250" width="100%" style="vertical-align:top;" alt="HBW EVENT" /></td>
						</tr>
						<tr class="global-link-01">
							<td class="frame-holder" bgcolor="#700034" style="padding:49px 70px 39px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									
									<tr>
										<td  align="center" style="font:18px/25px Arial, Helvetica, sans-serif; color:#fefefe;"><multiline><div> Hi '.$fullname.' </div></multiline></td>
									</tr>
									<tr>
										<td  align="center" style="font:18px/25px Arial, Helvetica, sans-serif; color:#fefefe;"><multiline><div> We have responded to your enquiry based on the "'.$event_name.'" event, this ticket has been completed. Kindly check your mail for the response </div></multiline></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="img-flex"></td>
						</tr>

						<tr>
						  <td>
						    <repeater>
						    <layout label="txt-holder">
						      <table width="100%" cellpadding="0" cellspacing="0">
						        <tr class="global-link-01">
						          <td>
						            <table width="100%" cellpadding="0" cellspacing="0">
						              <tr>
						                <td class="frame-holder" bgcolor="#990047" style="padding:50px 70px 30px;">
						                  <table width="100%" cellpadding="0" cellspacing="0">
						                    <tr>
						                      <td class="title" align="center" style="padding:0 0 14px; letter-spacing:1px; font:900 22px/26px Arial, Helvetica, sans-serif; color:#fff; text-transform: uppercase;"><multiline><div>About HBW</div></multiline> </td>
						                    </tr>
						                    <tr>
						                      <td align="center" style="padding:0 0 22px; font:18px/25px Arial, Helvetica, sans-serif; color:#fefefe;"><multiline><div>Hello Beautiful World is an Online Travel and Lifestyle Magazine for female travelers traversing the world. Our aim is to travel around the world, create and share exciting experiences, and to bring you along for the journey. With our unique skills in connecting people to brands you have the opportunity to reach a wider audience through our community.</div></multiline></td>
						                    </tr>
						                    <tr>
						                      <td>
						                        <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
						                          <tr>
						                            <td class="btn-02" align="center" style="mso-padding-alt:10px 38px 8px; border: 1px solid #fff; font:bold 14px/16px Arial, Helvetica, sans-serif; color:#fefefe; text-transform: uppercase;"><multiline><div><a href="https://www.hellobeautifulworld.net" target="_blank" style="text-decoration:none; color:#fefefe; display: block; padding: 10px 38px 8px;">visit us</a></div></multiline></td>
						                          </tr>
						                        </table>
						                      </td>
						                    </tr>
						                  </table>
						                </td>
						              </tr>
						              <tr>
						                <td class="hide"></td>
						              </tr>
						            </table>
						          </td>
						        </tr>
						      </table>
						    </layout>
						      <layout label="txt-holder">
						        <table width="100%" cellpadding="0" cellspacing="0">
						          <tr class="global-link-01">
						            <td>
						              <table width="100%" cellpadding="0" cellspacing="0">
						                <tr>
						                  <td class="frame-holder" bgcolor="#700034" style="padding:50px 70px 30px;">
						                    <table width="100%" cellpadding="0" cellspacing="0">
						                      <tr>
						                        <td class="title" align="center" style="padding:0 0 14px; letter-spacing:1px; font:900 22px/26px Arial, Helvetica, sans-serif; color:#fff; text-transform: uppercase;"><multiline><div>Advertise with us</div></multiline> </td>
						                      </tr>
						                      <tr>
						                        <td align="center" style="padding:0 0 22px; font:18px/25px Arial, Helvetica, sans-serif; color:#fefefe;"><multiline><div>Join thousands of businesses as well as female-owned businesses from all over the world as we help them experience growth.  When it comes to our advice we\'re proud to say we\'re different. We offer something a little more special.
						          A variety of advertising options are available, including, but not limited to, sponsored posts and social media coverage. Seize the opportunity to advertise your brand to our audience of over 10,000 engaged readers across platforms in our online quarterly magazine. All magazines will be promoted via email marketing, social media and on our website.
						          To further discuss your sponsorship options or advertising needs, or to request a more detailed media kit, please contact: <br> Jayme Hoyte @ <a href="mailto:hbwmagazine365@gmail.com">hbwmagazine365@gmail.com</a></div></multiline></td>
						                      </tr>
						                      <tr>
						                        <td>
						                          <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
						                            <tr>
						                              <td class="btn-02" align="center" style="mso-padding-alt:10px 38px 8px; border: 1px solid #fff; font:bold 14px/16px Arial, Helvetica, sans-serif; color:#fefefe; text-transform: uppercase;"><multiline><div><a href="https://docs.google.com/forms/d/e/1FAIpQLSd1-bjKDUPEGYVjRKOVF-rmHpZJYClVE5vkDG1PSPa5BxvllQ/viewform?usp=sf_link" target="_blank" style="text-decoration:none; color:#fefefe; display: block; padding: 10px 38px 8px;">get quote</a></div></multiline></td>
						                            </tr>
						                          </table>
						                        </td>
						                      </tr>
						                    </table>
						                  </td>
						                </tr>
						                <tr>
						                  <td class="hide"></td>
						                </tr>
						              </table>
						            </td>
						          </tr>
						        </table>
						      </layout>
						    </repeater>
						  </td>
						</tr>

						<tr>
							<td style="padding:34px 20px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding:0 0 8px;">
											<table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
												<tr>
													<td><a class="active" href="https://www.facebook.com/HBWTravelMagazine1" target="_blank"><img editable="true" src="https://www.images.hellobeautifulworld.net/gallery/facebook.png" height="70" width="70" style="vertical-align:top;" alt="facebook" /></a></td>
													<td width="16"></td>
													<td><a class="active" href="https://www.instagram.com/hellobeautifulworld365/" target="_blank"><img editable="true" src="https://www.images.hellobeautifulworld.net/gallery/instagram.png" height="70" width="70" style="vertical-align:top;" alt="instagram" /></a></td>
													<td width="16"></td>
													<td><a class="active" href="https://www.youtube.com/channel/UCBUYKJBpfCDpZXEtwEuEO5A?view_as=subscriber" target="_blank"><img editable="true" src="https://www.images.hellobeautifulworld.net/gallery/youtube.png" height="70" width="70" style="vertical-align:top;" alt="youtube" /></a></td>
													<td width="16"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" style="font:12px/22px Arial, Helvetica, sans-serif; color:#e8c1c5;">
											Copyright © 2020 <a target="_blank" href="https://www.hellobeautifulworld.net">Hello Beautiful World</a>. All rights reserved.
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>';

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
    $mail->setFrom('noreply@hellobeautifulworld.net', 'Hello Beautiful World');
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
