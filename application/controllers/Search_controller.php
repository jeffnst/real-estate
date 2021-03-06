<?php

/**
 * Created by Khang Nguyen.
 * Email: khang.nguyen@banvien.com
 * Date: 8/26/2017
 * Time: 5:42 PM
 */
class Search_controller extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Category_Model');
		$this->load->model('Product_Model');
		$this->load->model('City_Model');
		$this->load->model('Brand_Model');
		$this->load->model('District_Model');
		$this->load->helper("seo_url");
		$this->load->helper('text');
		$this->load->helper("my_date");
		$this->load->helper("bootstrap_pagination");
		$this->load->library('pagination');
		$this->load->helper('form');
	}

	public function index($offset=0){
		$data = $this->Category_Model->getCategories();
		$data['footerMenus'] = $this->City_Model->findByTopProductOfCategoryGroupByCity();
		$data['cities'] = $this->City_Model->getAllActive();

		$catId = $this->input->post("cmCatId");
		$cityId = $this->input->post("cmCityId");
		$districtId = $this->input->post("cmDistrictId");
		$area = $this->input->post("cmArea");
		$price = $this->input->post("cmPrice");
		$postDate = $this->input->post("cmPostDate");

		$data['cmCatId'] = $catId;
		$data['cmCityId'] = $cityId;
		$data['cmDistrictId'] = $districtId;
		$data['cmArea'] = $area;
		$data['cmPrice'] = $price;
		$data['cmPostDate'] = $postDate;

		$search_data = $this->Product_Model->searchByProperties($catId, $cityId, $districtId, $area, $price, $postDate, $offset, MAX_PAGE_ITEM);
		$data = array_merge($data, $search_data);
		$config = pagination();
		$config['base_url'] = base_url('tim-kiem.html');
		$config['total_rows'] = $data['total'];
		$config['per_page'] = MAX_PAGE_ITEM;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$this->load->helper('url');
		if($cityId != null && $cityId > -1) {
			$data['districts'] = $this->District_Model->findByCityId($cityId);
		}

		$this->load->view('/search/Search_view', $data);
	}

	public function searchByCity($cityId, $offset=0) {
		$data = $this->Category_Model->getCategories();
		$data['city'] = $this->City_Model->findById($cityId);
		$data['footerMenus'] = $this->City_Model->findByTopProductOfCategoryGroupByCity();
		$data['cities'] = $this->City_Model->getAllActive();
		$search_data = $this->Product_Model->findByCityIdFetchAddress($cityId, $offset, MAX_PAGE_ITEM);
		$data = array_merge($data, $search_data);
		$config = pagination();
		$config['base_url'] = base_url(seo_url($data['city']->CityName).'-ct'.$cityId.'.html');
		$config['total_rows'] = $data['total'];
		$config['per_page'] = MAX_PAGE_ITEM;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$this->load->helper('url');
		$this->load->view('/search/Search_view', $data);
	}

	public function searchByBranch($branchId, $offset=0) {
		$data = $this->Category_Model->getCategories();
		$data['branch'] = $this->Brand_Model->findById($branchId);
		$data['footerMenus'] = $this->City_Model->findByTopProductOfCategoryGroupByCity();
		$data['cities'] = $this->City_Model->getAllActive();
		$search_data = $this->Product_Model->findByBranchIdFetchAddress($branchId, $offset, MAX_PAGE_ITEM);
		$data = array_merge($data, $search_data);
		$config = pagination();
		$config['base_url'] = base_url(seo_url($data['branch']->BrandName).'-b'.$branchId.'.html');
		$config['total_rows'] = $data['total'];
		$config['per_page'] = MAX_PAGE_ITEM;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$this->load->helper('url');
		$this->load->view('/search/Search_view', $data);
	}

	public function searchByCategoryAndCity($catId, $cityId, $offset=0){
		$data = $this->Category_Model->getCategories();
		$city = $this->City_Model->findById($cityId);
		$category = $this->Category_Model->findByNotChildId($catId);
		$data['cat_city'] = $category->CatName.' tại '.$city->CityName;
		$data['footerMenus'] = $this->City_Model->findByTopProductOfCategoryGroupByCity();
		$data['cities'] = $this->City_Model->getAllActive();
		$search_data = $this->Product_Model->findByCatIdAndCityIdFetchAddress($catId, $cityId, $offset, MAX_PAGE_ITEM);
		$data = array_merge($data, $search_data);
		$config = pagination();
		$config['base_url'] = base_url(seo_url($category->CatName).'-'.seo_url($city->CityName).'-cc'.$catId.'-'.$cityId.'.html');
		$config['total_rows'] = $data['total'];
		$config['per_page'] = MAX_PAGE_ITEM;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$this->load->helper('url');
		$this->load->view('/search/Search_view', $data);
	}
}
