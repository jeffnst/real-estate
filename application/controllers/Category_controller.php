<?php

/**
 * Created by Khang Nguyen.
 * Email: khang.nguyen@banvien.com
 * Date: 7/19/2017
 * Time: 10:15 PM
 */
class Category_controller extends CI_Controller
{
	public function index() {
		$this->db->where("ParentID IS NULL and Active = 1");
		$query = $this->db->get("category");

		$data['records'] = $query->result();
		//print_r($data);
		$child = [];
		foreach ($data as $key=>$value){
			foreach ($data[$key] as $k=>$v){
				$categoryId = $v->CategoryID;
				if($categoryId != null){
					$this->db->where("ParentID = ". $categoryId);
					$query = $this->db->get("category");
					$child[$categoryId] = $query->result();
				}
			}
		}
		$data['child'] = $child;

		$this->load->helper('url');
		$this->load->view('Stud_view', $data);
	}
}