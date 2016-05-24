<?php

require "handler.class.php";

class StartMD_Help extends StartMD_DB {

	public function getTicketThread($id)
	{

		if (!is_numeric($id)) return "Invalid Ticket ID";

		$threads=$this->safeQuery("SELECT * FROM `helper_threads` WHERE `ticket_id`=? ORDER BY `number` ASC",array("i",$id),false);

		foreach ($threads as $key=>$t) {

			$threads[$key]['attachments']=array();

			$attachments=$this->safeQuery("SELECT * FROM `helper_files` WHERE `thread_num`=?",array("i",$t['number']),false);

			if (isset($attachments[0])) $threads[$key]['attachments']=$attachments;
		}

		$ticket=$this->safeQuery("SELECT * FROM `helper_ticket` WHERE `id`=?",array("i",$id),false)[0];

		$ticket['custom']=json_decode($ticket['custom'],true);

		$ticket['threads']=$threads;

		return $ticket;

	}

	public function addToThread($ticket,$user,$post)
	{

		$post=$this->cleanVar($post);

		if ($user!="user" && $user!="admin") return "Invalid User Type.";

		if (!is_numeric($ticket)) return "Invalid Ticket ID.";

		$status=$this->retrieveAttribute("status","helper_ticket",array("id",$ticket),"i");

		if ($status==0) return "Sorry, this thread has been closed.";

		$try=$this->safeQuery("INSERT INTO `helper_threads`(`ticket_id`,`message`,`posted_by`,`time`) VALUES(?,?,?,".time().")",array("iss",$ticket,$post,$user),true);

		return $this->DB->insert_id;

	}

	public function openTicket(array $details)
	{

		foreach ($details as $key=>$val) {

			$details[$key]=$this->cleanVar($val);

		}

		$customs=$this->loadCustomFields();

		$custom_values=array();

		foreach ($customs as $custom) {

			$custom_values[$custom['name']]=$_POST[$custom['name']];

		}

		$insert_customs="";

		if (isset($customs[0]['name'])) $insert_customs=json_encode($custom_values);

		$insert=$this->safeQuery("INSERT INTO `helper_ticket`(`name`,`email`,`website`,`urgency`,`custom`,`time`) VALUES(?,?,?,?,?,".time().")",array("sssss",$details['first_name']." ".$details['last_name'],$details['email'],$details['website'],$details['urgency'],$insert_customs),true);
	
		$id=$this->DB->insert_id;

		$insert=$this->safeQuery("INSERT INTO `helper_threads`(`ticket_id`,`message`,`posted_by`,`time`) VALUES($id,?,'user',".time().")",array("s",$details['message']),true);

		return $id;

	}

	public function checkTicket($id,$email)
	{

		$try=$this->safeQuery("SELECT `id` FROM `helper_ticket` WHERE `id`=? AND `email`=?",array("is",$id,$email),false);

		if  (count($try)>0) return true;

		else false;

	}

	public function closeTicket($id)
	{

		if (!is_numeric($id)) return "Invalid ID.";

		$do=$this->safeQuery("UPDATE `helper_ticket` SET `status`=0 WHERE `id`=?",array("i",$id),true);

		return true;

	}

	public function editThread($msg,$id)
	{

		if (!is_numeric($id)) return "Invalid Thread Post ID.";

		$msg=$this->cleanVar($msg);

		if ($this->retrieveAttribute("posted_by","helper_threads",array("number",$id),"i")!=$_COOKIE['user']) return "You  are not authorised for this change.";

		$update=$this->safeQuery("UPDATE `helper_threads` SET `message`=? , `last_change`=".time()." WHERE `number`=?",array("si",$msg,$id),true);

		return true;

	}

	public function deleteThread($id)
	{

		if (!is_numeric($id)) return "Invalid Thread Post ID.";

		if ($this->retrieveAttribute("posted_by","helper_threads",array("number",$id),"i")!=$_COOKIE['user']) return "You  are not authorised for this change.";

		$update=$this->safeQuery("DELETE FROM `helper_threads` WHERE `number`=?",array("i",$id),true);

		return true;

	}

	public function uploadFiles($files,$do,$base="")
	{

		if (!isset($files['name'][0])) return true;

		foreach ($files['name'] as $key=>$filename) {

			$location=$base."uploads/{$filename}";

			$i=1;

			while(file_exists($location)) {

				$filename=$i."_".$filename;

				$location=$base."uploads/{$filename}";

				$i++;

			}

			move_uploaded_file($files['tmp_name'][$key], $location);

			if ($files['type'][$key]=="image/jpeg" || $files['type'][$key]=="image/gif" || $files['type'][$key]=="image/png" && getimagesize($location)) {

				$type="image";

				require_once($base."libs/piczy.php");

				$piczy=new Piczy;

				$piczy->load($location);

				$piczy->crop(100,100);

				$piczy->save($base."uploads/th_{$filename}");

				$piczy->close();

			}

			else $type="file";

			$adding=$this->addFileToThread($filename,$do,$type,$base);

			if (!is_numeric($adding)) return $adding;

		}

		return true;

	}

	public function removeAttachment($id,$base="")
	{
		
		if (!is_numeric($id)) return "Invalid ID.";

		$file=$this->retrieveAttribute("filename","helper_files",array("id",$id),"i");

		$type=$this->retrieveAttribute("type","helper_files",array("id",$id),"i");

		@unlink($base."uploads/".$file);

		if ($type=="image") @unlink($base."uploads/th_".$file);

		$delete=$this->safeQuery("DELETE FROM `helper_files` WHERE `id`=?",array("i",$id),true);

		return true;

	}

	public function addFileToThread($filename,$id,$type="file",$base="")
	{

		if (!is_numeric($id)) return "Invalid Thread Post ID.";

		if (!file_exists($base."uploads/{$filename}")) return "File not found.";

		$add=$this->safeQuery("INSERT INTO `helper_files`(`thread_num`,`filename`,`type`) VALUES(?,?,?)",array("iss",$id,$filename,$type),true);

		if ($this->DB->error) return $this->DB->error;

		return $this->DB->insert_id;

	}

	public function formatMail($msg,$array)
	{

		include "configs.php";

		$new=str_ireplace("{{url}}", $url, str_ireplace("{{sitename}}",$sitename,$msg));

		foreach ($array as $search=>$replace) {

			$new=str_ireplace('{{'.$search.'}}', $replace, $new);

		}

		return $new;
	}

	public function getTickets($status=1)
	{
		
		$tickets=$this->retrieve("helper_ticket");

		$return=array();

		foreach ($tickets as $ticket) {

			if ($ticket['status']==$status) {

				$return[]=$this->getTicketThread($ticket['id']);

			}

		}

		return $return;

	}

	public function loadCustomFields()
	{

		$customs=$this->retrieve("helper_customs");

		foreach ($customs as $key=>$custom) {

			if (!empty($custom['values'])) {
				
				$customs[$key]['values']=json_decode($custom['values'],true);
			
			}
		
		}

		return $customs;

	}

	public function addCustomField(array $details)
	{

		foreach ($details as $key=>$val) {

			if (empty($val)) return "All fields are necessary.";

			if ($key!="customs") $details[$key]=$this->cleanVar($val);

		}

		if (is_null(json_decode($details['customs']))) return "You need JSON encoded string for this type.";

		$add=$this->safeQuery("INSERT INTO `helper_customs`(`label`,`name`,`type`,`values`) VALUES(?,?,?,?)",array("ssss",$details['label'],$details['name'],$details['type'],$details['customs']),true);

		return true;

	}

	public function deleteCustomField($name)
	{

		$do=$this->safeQuery("DELETE FROM `helper_customs` WHERE `name`=?",array("s",$name),true);

		return true;

	}

	public function isEmail($user) 
    {
        
         if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i", $user)) {
          
          return true;
         
         }
         else {

          return false;

         }

    }

	public function cleanVar($var) 
	{
		return addslashes(str_ireplace('"',"&quot;",str_ireplace("'","&#39;",htmlentities($var))));
	}

}