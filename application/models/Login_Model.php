<?php
/**
 * Created by Khang Nguyen.
 * Email: khang.nguyen@banvien.com
 * Date: 7/31/2017
 * Time: 11:25 AM
 */

class Login_Model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	//get the username & password from tbl_usrs
	function get_user($usr, $pwd)
	{
		$sql = "select * from us3r where UserName = '" . $usr . "' and Password = '" . md5($pwd) . "' and status = '".ACTIVE."' limit 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_facebooker($usr, $pwd, $fullname){
		$sql = "select * from us3r where UserName = '" . $usr . "' and Password = '" . md5($pwd) . "' and status = '".ACTIVE."' limit 1";
		$query = $this->db->query($sql);
		$result = $query->row();
		if($result == null || !isset($result->Us3rID)){
			$datestring = '%Y-%m-%d %h:%i:%s';
			$time = time();
			$now = mdate($datestring, $time);
			$data = array(
				'UserGroupID' => 2,
				'UserName' => $usr,
				'Password' => md5($pwd),
				'Email' => $usr,
				'CreatedDate' => $now,
				'Status' => ACTIVE,
				'FullName' => $fullname
			);
			$result = $this->db->insert('us3r', $data);
		}
		return $result;
	}


}?>
