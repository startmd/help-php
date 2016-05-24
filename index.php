<?php

error_reporting(E_ALL);

set_time_limit(0);

mb_internal_encoding("UTF-8");

require "libs/helpphp.php";

require "configs.php";

$main=new StartMD_Help($mysql['host'],$mysql['user'],$mysql['password'],$mysql['db']);

$customs=$main->loadCustomFields();

if (isset($_GET['admin'])) {

	if (!isset($_COOKIE['admin'])) {

		if (isset($_POST['username'])) {

			if ($_POST['username']!=$username) {

				$error="Incorrect Credentials.";

			}

			if ($_POST['password']!=$password) {

				$error="Incorrect Credentials.";

			}

			if (!isset($error)) {

				setcookie("admin",$username,time()+3600,"/");

				setcookie("user","admin",time()+3600,"/");

				header("location:{$url}admin/tickets.php");

				exit;

			}

		}

		$title="Support Admin Login - ".$suffix;

		$body_class="login";

		include "display/header.php";

		include "display/login.php";

		include "display/footer.php";

		exit;

	}

	if (isset($_GET['logout'])) {

		setcookie("admin","",time()-3600,"/");

		setcookie("user","",time()-3600,"/");

		header("location:{$url}admin/");

		exit;

	}

	if (isset($_GET['delete'])) {

		$delete=$main->deleteCustomField($_GET['name']);

		$success="The Custom Field Was Deleted.";

	}

	if (isset($_POST['label']) && isset($_POST['edit'])) {

		$delete=$main->deleteCustomField($_POST['name']);

		$do=$main->addCustomField($_POST);

		if ($do===true) $success="Custom Field Editted.";

		else $error=$do;

	}

	if (isset($_POST['label']) && isset($_POST['add'])) {

		$do=$main->addCustomField($_POST);

		if ($do===true) $success="Custom Field Added.";

		else $error=$do;

	}

	$customs=$main->loadCustomFields();
	
	if (isset($_GET['solved'])) {

		include "libs/pagination.class.php";

		$per_page=20;

		$page=1;

		if (isset($_GET['page'])) $page=$_GET['page'];

		$paging=new ap_pagination;

		$tickets=$main->getTickets(0);

		$paging->_setUp($per_page,count($tickets),$page);

		$tickets=$paging->_pageContents($tickets);

		$next=$paging->_next();

    	$prev=$paging->_prev();

    	$total=$paging->TOTAL;

    	$paginator="solved";

		$active="Solved Tickets";

		$body_class="all-tickets";

		$title="Support Tickets - Admin Panel";

		include "display/header.php";

		include "display/tickets.php";

		include "display/footer.php";

		exit;

	}

	if (isset($_GET['tickets'])) {

		include "libs/pagination.class.php";

		$per_page=20;

		$page=1;

		if (isset($_GET['page'])) $page=$_GET['page'];

		$paging=new ap_pagination;

		$tickets=$main->getTickets(1);

		$paging->_setUp($per_page,count($tickets),$page);

		$tickets=$paging->_pageContents($tickets);

		$next=$paging->_next();

    	$prev=$paging->_prev();

    	$total=$paging->TOTAL;

    	$paginator="tickets";

		$active="Unsolved Tickets";

		$body_class="all-tickets";

		$title="Support Tickets - Admin Panel";

		include "display/header.php";

		include "display/tickets.php";

		include "display/footer.php";

		exit;

	}

	include "libs/pagination.class.php";

	$per_page=20;

	$page=1;

	if (isset($_GET['page'])) $page=$_GET['page'];

	$paging=new ap_pagination;

	$tickets=$main->getTickets(1);

	$paging->_setUp($per_page,count($tickets),$page);

	$tickets=$paging->_pageContents($tickets);

	$next=$paging->_next();

    $prev=$paging->_prev();

    $total=$paging->TOTAL;

    $paginator="tickets";

	$active="Unsolved Tickets";

	$body_class="all-tickets";

	$title="Support Tickets - Admin Panel";

	include "display/header.php";

	include "display/tickets.php";

	include "display/footer.php";

	exit;

}

if (isset($_GET['delete_attachment']) && isset($_POST['id'])) {

	$do=$main->removeAttachment($_POST['id']);

	if ($do===true) die("done");

	else die($do);

}

if (isset($_POST['first_name'])) {

	$error_fields=array();

	foreach ($_POST as $key=>$val) {

		if (empty($val)) $error_fields[]=$key;

	}

	if (!empty($error_fields[0])) $error="All fields are required. Please don't leave anything out.";

	if (!$main->isEmail($_POST['email'])) {

		$error_fields[]="email";

		$error="E-Mail ID was invalid.";

	}

	if (!isset($error)) {

		$do=$main->openTicket($_POST);

		require "libs/PHPMailer/PHPMailerAutoload.php";

		require "mail_setup.php";

		$mail->addAddress($_POST['email'], $_POST['first_name']);

		$mail->addAddress($mail_username,$username);

		$mail->Subject = 'New Support Ticket - #'.$do;

		$message=$main->formatMail(file_get_contents("mails/opened.htm"),array("subject"=>'New Support Ticket - #'.$do,"id"=>$do,"message"=>$main->cleanVar($_POST['message']),"urgency"=>$_POST['urgency']));

		$mail->msgHTML($message);

		$mail->AltBody = "You opened a new issue at {$sitename} Support System. Your new reference ticket ID is #$do";

		$mail->send();

		setcookie("user","user",time()+3600*7*24,"/");

		setcookie("email","{$_POST['email']}",time()+3600*7*24,"/");

		header("location:{$url}ticket/{$do}/");

		exit;

	}

}

if (isset($_POST['ticket_id']) && isset($_POST['email'])) {

	if (!$main->isEmail($_POST['email'])) {

		$error="Invalid Email Provided.";

	}

	if (!is_numeric($_POST['ticket_id'])) {

		$error="Invalid Ticket ID Provided.";

	}

	if (!isset($error)) {

		if ($main->checkTicket($_POST['ticket_id'],$_POST['email'])) {

			setcookie("ticket_id",$_POST['ticket_id'],time()+3600*24*7,"/");

			setcookie("user","user",time()+3600*24*7,"/");

			setcookie("email","{$_POST['email']}",time()+3600*7*24,"/");

			header("location:{$url}ticket/{$_POST['ticket_id']}/");

			exit;

		}

		else {

			$error="No combination of the Token ID and Email ID exists.";

		}

	}

}

if (isset($_GET['ticket'])) {

	if (!isset($_COOKIE['user']) && !isset($_COOKIE['admin']) && $_COOKIE['user']!=$main->retrieveAttribute("name","helper_tickets",array("i",$_GET['ticket']))) {

		header("location:{$url}");

		exit;
		
	}

	if (isset($_GET['delete_thread'])) {

		$do=$main->deleteThread($_GET['delete_thread']);

		if ($do===true) $success="The message was deleted.";

		else $error=$do;

	}

	if (isset($_POST['edit-thread'])) {

		if (empty(trim($_POST['msg']))) $error="You cannot leave anything blank.";
	
		else {

			$do=$main->editThread($_POST['msg'],$_POST['number']);

			if ($do!==true) $error=$do;

			else  {

				$upload=$main->uploadFiles($_FILES['files'],$_POST['number']);

				if ($upload===true) $success="Thread was successfully updated.";

				else $error=$upload;

			}

		}
	}	


	if (isset($_GET['close'])) {

		if (isset($_COOKIE['user'])) $closed_by=$_COOKIE['user'];

		else $closed_by=$_COOKIE['admin'];

		if ($closed_by=="user" && $_COOKIE['email']!=$main->retrieveAttribute("email","helper_ticket",array("id",$_GET['ticket']),"i")) $error="You are not authorized for this task.";

		else {

			$close=$main->closeTicket($_GET['ticket']);

			if ($close) {

				require "libs/PHPMailer/PHPMailerAutoload.php";

				require "mail_setup.php";

				$mail->addAddress($main->retrieveAttribute("email","helper_ticket",array("id",$_GET['ticket']),"i"), $main->retrieveAttribute("name","helper_ticket",array("id",$_GET['ticket']),"i"));

				$mail->addAddress($mail_username,$username);

				$mail->Subject = 'Support Ticket #'.$_GET['ticket'].' Resolved';

				$message=$main->formatMail(file_get_contents("mails/closed.htm"),array("subject"=>'Support Ticket #'.$_GET['ticket'].' Resolved',"id"=>$_GET['ticket'],"by"=>$closed_by));

				$mail->msgHTML($message);

				$mail->AltBody = "The ticket with ID #{$_GET['ticket']} has been closed by {$closed_by}";

				$mail->send();

				$success="The thread has been closed by {$closed_by}.";

			}

			else $error=$close;

		}

	}

	if (isset($_POST['post'])) {

		if (isset($_COOKIE['admin'])) $u="admin";

		else if (isset($_COOKIE['user'])) $u="user";

		$do=$main->addToThread($_GET['ticket'],$u,$_POST['post']);
	
		if (is_numeric($do)) {

			$upload=$main->uploadFiles($_FILES['files'],$do);

			if (!$upload) $error=$upload;
			
			else {

				require "libs/PHPMailer/PHPMailerAutoload.php";

				require "mail_setup.php";

				$mail->addAddress($main->retrieveAttribute("email","helper_ticket",array("id",$_GET['ticket']),"i"), $main->retrieveAttribute("name","helper_ticket",array("id",$_GET['ticket']),"i"));

				$mail->addAddress($mail_username,$username);

				$mail->Subject = 'New Message Posted - Ticket #'.$_GET['ticket'];

				$message=$main->formatMail(file_get_contents("mails/reply.htm"),array("subject"=>'New Message Posted - Ticket #'.$_GET['ticket'],"id"=>$_GET['ticket'],"message"=>$main->cleanVar($_POST['post'])));

				$mail->msgHTML($message);

				$mail->AltBody = "A new reply was posted to the Ticket Number #{$_GET['ticket']}";

				$mail->send();

				$success="Your reply was successfully posted.";

			}

		}

		else $error=$do;

	}

	$thread=$main->getTicketThread($_GET['ticket']);
	
	$default="{$url}assets/default-avatar.png";
	
	$user_grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $thread['email'] ) ) ) . "?d=" . urlencode( $default ) . "&s=200";
	
	$admin_grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=200";

	$body_class="thread";

	$title="Support Ticket #{$thread['id']} - ".$suffix;

	include "display/header.php";

	include "display/thread.php";

	include "display/footer.php";

	exit;

}

$body_class="home";

$title="Customer Support System - ".$suffix;

include "display/header.php";

include "display/home.php";

include "display/footer.php";

exit;