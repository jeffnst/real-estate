<?php

/**
 * Created by Khang Nguyen.
 * Email: khang.nguyen@banvien.com
 * Date: 8/17/2017
 * Time: 10:39 AM
 */
class Ajax_controller extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('District_Model');
		$this->load->model('Ward_Model');
		$this->load->helper("seo_url");
		$this->load->model('Street_Model');
		$this->load->model('Product_Model');
	}

	public function findStreetByName(){
		$streetName = $this->input->get('query');
		$streetNames = $this->Street_Model->findByName($streetName);
		echo json_encode($streetNames);
	}

	public function findDistrictByCityId(){
		$cityId = $this->input->post('cityId');
		$districts = $this->District_Model->findByCityId($cityId);
		echo json_encode($districts);
	}

	public function findWardByDistrictId(){
		$districtId = $this->input->post('districtId');
		$wards = $this->Ward_Model->findByDistrictId($districtId);
		echo json_encode($wards);
	}

	public function updateCoordinator(){
		$productId = $this->input->post('productId');
		$longitude = $this->input->post('lng');
		$latitude = $this->input->post('lat');
		$this->Product_Model->updateCoordinator($productId, $longitude, $latitude);
		echo json_encode('{success: true}');
	}
}
