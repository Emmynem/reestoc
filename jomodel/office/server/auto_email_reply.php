<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class sendEmailClass {
  public $engineMessage = 0;
  public $error = 0;
}

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$data = json_decode(file_get_contents("php://input"), true);

$fullname = $data['fullname'];
$email = $data['email'];
$subject = $data['subject'];
$username = $data['username'];
$message = '

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Hello Beautiful World</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<style type="text/css">
			* {
				-ms-text-size-adjust:100%;
				-webkit-text-size-adjust:none;
				-webkit-text-resize:100%;
				text-resize:100%;
			}
			a{
				outline:none;
				color:#40aceb;
				text-decoration:underline;
			}
			a:hover{text-decoration:none !important;}
			.nav a:hover{text-decoration:underline !important;}
			.title a:hover{text-decoration:underline !important;}
			.title-2 a:hover{text-decoration:underline !important;}
			.btn:hover{opacity:0.8;}
			.btn a:hover{text-decoration:none !important;}
			.btn{
				-webkit-transition:all 0.3s ease;
				-moz-transition:all 0.3s ease;
				-ms-transition:all 0.3s ease;
				transition:all 0.3s ease;
			}
			table td {border-collapse: collapse !important;}
			.ExternalClass, .ExternalClass a, .ExternalClass span, .ExternalClass b, .ExternalClass br, .ExternalClass p, .ExternalClass div{line-height:inherit;}
			@media only screen and (max-width:500px) {
				table[class="flexible"]{width:100% !important;}
				table[class="center"]{
					float:none !important;
					margin:0 auto !important;
				}
				*[class="hide"]{
					display:none !important;
					width:0 !important;
					height:0 !important;
					padding:0 !important;
					font-size:0 !important;
					line-height:0 !important;
				}
				td[class="img-flex"] img{
					width:100% !important;
					height:auto !important;
				}
				td[class="aligncenter"]{text-align:center !important;}
				th[class="flex"]{
					display:block !important;
					width:100% !important;
				}
				td[class="wrapper"]{padding:0 !important;}
				td[class="holder"]{padding:30px 15px 20px !important;}
				td[class="nav"]{
					padding:20px 0 0 !important;
					text-align:center !important;
				}
				td[class="h-auto"]{height:auto !important;}
				td[class="description"]{padding:30px 20px !important;}
				td[class="i-120"] img{
					width:120px !important;
					height:auto !important;
				}
				td[class="footer"]{padding:5px 20px 20px !important;}
				td[class="footer"] td[class="aligncenter"]{
					line-height:25px !important;
					padding:20px 0 0 !important;
				}
				tr[class="table-holder"]{
					display:table !important;
					width:100% !important;
				}
				th[class="thead"]{display:table-header-group !important; width:100% !important;}
				th[class="tfoot"]{display:table-footer-group !important; width:100% !important;}
			}
		</style>
	</head>
	<body style="margin:0; padding:0;" bgcolor="#eaeced">
		<table style="min-width:320px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eaeced">
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
				<td class="wrapper" style="padding:0 10px;">
					<!-- module 1 -->
					<table data-module="module-1" data-thumb="thumbnails/01.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding:29px 0 30px;">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<th class="flex" width="200" align="left" style="padding:0;">
														<table class="center" cellpadding="0" cellspacing="0">
															<tr>
																<td style="line-height:0; font:bold 18px/20px Arial, Helvetica, sans-serif; color:#888;">
																	<a target="_blank" style="text-decoration:none;" href="https://www.hellobeautifulworld.net/">
                                    <!-- Hello Beautiful World -->
                                    <td class="img-flex"><img src="https://images.hellobeautifulworld.net/gallery/favicon.png" style="vertical-align:top; background-color: #eaeced" width="130" height="110" alt="" /></td>
                                  </a>
																</td>
															</tr>
														</table>
													</th>
													<th class="flex" align="left" style="padding:0;">
														<table width="100%" cellpadding="0" cellspacing="0">
															<tr>
																<td data-color="text" data-size="size navigation" data-min="10" data-max="22" data-link-style="text-decoration:none; color:#888;" class="nav" align="right" style="font:bold 17px/19px Arial, Helvetica, sans-serif; color:#888;">
																	<a target="_blank" style="text-decoration:none; color:#888;" href="https://www.hellobeautifulworld.net/">Home</a> &nbsp; &nbsp;
                                  <a target="_blank" style="text-decoration:none; color:#888;" href="https://www.hellobeautifulworld.net/blog/">Blog</a> &nbsp; &nbsp;
                                  <a target="_blank" style="text-decoration:none; color:#888;" href="https://www.hellobeautifulworld.net/magazine/">Magazine</a> &nbsp; &nbsp;
                                  <a target="_blank" style="text-decoration:none; color:#888;" href="https://www.hellobeautifulworld.net/events/">Events</a> &nbsp; &nbsp;
																	<a target="_blank" style="text-decoration:none; color:#888;" href="https://www.hellobeautifulworld.net/shop/">Shop</a> &nbsp; &nbsp;
																</td>
															</tr>
														</table>
													</th>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- module 2 -->
					<table data-module="module-2" data-thumb="thumbnails/02.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>
										<td class="img-flex"><img src="https://www.images.emmynem.com/email_svgs/undraw_welcoming_xvuq.png" style="vertical-align:top; background-color: rgb(249, 249,249)" width="600" height="350" alt="" /></td>
									</tr>
									<tr>
										<td data-bgcolor="bg-block" class="holder" style="padding:58px 60px 52px;" bgcolor="#f9f9f9">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td data-color="title" data-size="size title" data-min="25" data-max="45" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="center" style="font:35px/38px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 24px;">
														Hello Beautiful World
													</td>
												</tr>
												<tr>
													<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="center" style="font:bold 16px/25px Arial, Helvetica, sans-serif; color:#888; padding:0 0 23px;">
														Hello Beautiful World is an Online Travel and Lifestyle Magazine for female travelers traversing the world.
                            Our aim is to travel around the world, create and share exciting experiences, and to bring you along for the journey.
                            With our unique skills in connecting people to brands you have the opportunity to reach a wider audience through our community.
													</td>
												</tr>
												<tr>
													<td style="padding:0 0 20px;">
														<table width="134" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
															<tr>
																<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:12px/14px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#7bb84f">
																	<a target="_blank" style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href="https://www.hellobeautifulworld.net/">Visit Us</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="28"></td></tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- module 3 -->
					<table data-module="module-3" data-thumb="thumbnails/03.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>
										<td class="img-flex"><img src="https://www.images.emmynem.com/email_svgs/undraw_celebration_0jvk.png" style="vertical-align:top; background-color: rgb(249, 249,249)" width="600" height="450" alt="" /></td>
									</tr>
									<tr>
										<td data-bgcolor="bg-block" class="holder" style="padding:65px 60px 50px;" bgcolor="#f9f9f9">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td data-color="title" data-size="size title" data-min="20" data-max="40" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="center" style="font:30px/33px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 24px;">
														Hi '.$fullname.'
													</td>
												</tr>
												<tr>
													<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="center" style="font:16px/29px Arial, Helvetica, sans-serif; color:#888; padding:0 0 21px;">
														We have responded to your enquiry, this ticket has been completed. Kindly check your mail for the response.
													</td>
												</tr>
												<tr>
													<td style="padding:0 0 20px;">
														<table width="134" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
															<tr>
																<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:12px/14px Arial, Helvetica, sans-serif; color:#f8f9fb; text-transform:uppercase; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#f5ba1c">
																	<a target="_blank" style="text-decoration:none; color:#f8f9fb; display:block; padding:12px 10px 10px;" href="https://www.hellobeautifulworld.net/blog/">View Blog</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="28"></td></tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- module 6 -->
					<table data-module="module-6" data-thumb="thumbnails/06.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>
										<td class="img-flex"><img src="https://www.images.emmynem.com/email_svgs/undraw_online_ad_mg4t.png" style="vertical-align:top; background-color: rgb(249, 249,249)" width="600" height="350" alt="" /></td>
									</tr>
									<tr>
										<td data-bgcolor="bg-block" class="holder" style="padding:64px 60px 50px;" bgcolor="#f9f9f9">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td data-color="title" data-size="size title" data-min="20" data-max="40" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="center" style="font:30px/33px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 23px;">
														Advertise With Us
													</td>
												</tr>
												<tr>
													<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="center" style="font:16px/29px Arial, Helvetica, sans-serif; color:#888; padding:0 0 21px;">
                            Join thousands of businesses as well as female-owned businesses from all over the world as we help them experience growth.  When it comes to our advice we\'re proud to say we\'re different. We offer something a little more special.
                            A variety of advertising options are available, including, but not limited to, sponsored posts and social media coverage. Seize the opportunity to advertise your brand to our audience of over 10,000 engaged readers across platforms in our online quarterly magazine. All magazines will be promoted via email marketing, social media and on our website.
                            To further discuss your sponsorship options or advertising needs, or to request a more detailed media kit, please contact: <br> Jayme Hoyte @ <a href="mailto:hbwmagazine365@gmail.com">hbwmagazine365@gmail.com</a>
													</td>
												</tr>
												<tr>
													<td style="padding:0 0 20px;">
														<table width="232" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
															<tr>
																<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="20" class="btn" align="center" style="font:bold 16px/18px Arial, Helvetica, sans-serif; color:#f9f9f9; text-transform:uppercase; mso-padding-alt:22px 10px; border-radius:3px;" bgcolor="#e02d74">
																	<a target="_blank" style="text-decoration:none; color:#f9f9f9; display:block; padding:22px 10px;" href="https://docs.google.com/forms/d/e/1FAIpQLSd1-bjKDUPEGYVjRKOVF-rmHpZJYClVE5vkDG1PSPa5BxvllQ/viewform?usp=sf_link">Contact uS</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="28"></td></tr>
								</table>
							</td>
						</tr>
					</table>
					<table data-module="module-6" data-thumb="thumbnails/06.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td data-bgcolor="bg-module" bgcolor="#eaeced">
								<table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
									<tr>
										<td class="img-flex"><img src="https://www.images.emmynem.com/email_svgs/undraw_Social_media_re_w12q.png" style="vertical-align:top; background-color: rgb(249, 249,249)" width="600" height="400" alt="" /></td>
									</tr>
									<tr>
										<td data-bgcolor="bg-block" class="holder" style="padding:64px 60px 50px;" bgcolor="#f9f9f9">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td data-color="title" data-size="size title" data-min="20" data-max="40" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="center" style="font:30px/33px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 23px;">
														Socials
													</td>
												</tr>
												<tr>
													<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="center" style="font:16px/29px Arial, Helvetica, sans-serif; color:#888; padding:0 0 21px;">
														In order to be in the loop, follow us on our various social media platforms to be notified instantly. You won\'t miss a thing !
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="28"></td></tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- module 7 -->
					<table data-module="module-7" data-thumb="thumbnails/07.png" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td style="padding:34px 20px;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding:0 0 8px;">
											<table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">
												<tr>
													<td><a class="active" href="https://www.facebook.com/HBWTravelMagazine1" target="_blank"><img editable="true" src="https://www.images.emmynem.com/gallery/facebook.png" height="70" width="70" style="vertical-align:top;" alt="facebook" /></a></td>
													<td width="16"></td>
													<td><a class="active" href="https://www.instagram.com/hellobeautifulworld365/" target="_blank"><img editable="true" src="https://www.images.emmynem.com/gallery/instagram.png" height="70" width="70" style="vertical-align:top;" alt="instagram" /></a></td>
													<td width="16"></td>
													<td><a class="active" href="https://www.youtube.com/channel/UCBUYKJBpfCDpZXEtwEuEO5A?view_as=subscriber" target="_blank"><img editable="true" src="https://www.images.emmynem.com/gallery/youtube.png" height="70" width="70" style="vertical-align:top;" alt="youtube" /></a></td>
													<td width="16"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" style="font:12px/22px Arial, Helvetica, sans-serif; color:#e8c1c5;">
											Copyright © <script type="text/javascript">document.write(new Date().getFullYear());</script> <a target="_blank" href="https://www.hellobeautifulworld.net/">Hello Beautiful World</a>. All rights reserved.
										</td>
									</tr>
								</table>
							</td>
						</tr>

					</table>
				</td>
			</tr>
			<!-- fix for gmail -->
			<tr>
				<td style="line-height:0;"><div style="display:none; white-space:nowrap; font:15px/1px courier;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div></td>
			</tr>
		</table>
	</body>
</html>
';

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
    // echo 'Message has been sent';
    $returnvalue = new sendEmailClass();
    $returnvalue->engineMessage = 1;
} catch (Exception $e) {
  $returnvalue = new sendEmailClass();
  $returnvalue->error = 2;
    // echo "Message could not be sent";
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

echo json_encode($returnvalue);
