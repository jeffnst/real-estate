<?php

/**
 * Created by Khang Nguyen.
 * Email: khang.nguyen@banvien.com
 * Date: 7/20/2017
 * Time: 3:42 PM
 */
class Product_Model extends CI_Model
{
	function __construct() {
		parent::__construct();
	}

	public function findById($productId) {
		$this->db->where(array("ProductID" => $productId));
		$query = $this->db->get("product");
		$product = $query->row();
		return $product;
	}

	public function findByUserId($userId) {
		$this->db->order_by('ModifiedDate', 'desc');
		$this->db->where(array("CreatedByID" => $userId));
		$query = $this->db->get("product");
		$product = $query->result();
		return $product;
	}

	public function findByCategoryCode($catCode, $offset=0, $limit) {
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' inner join category ct on ct.categoryid = p.categoryid';
		$sql .= ' where ct.code = \''.$catCode.'\' and p.status = 1';
		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$query = $this->db->query($sql);
		return $query->result();
	}

	public function findByHotProduct(){
		$sql = 'select p.ProductID as ProductID, p.Title as Title, p.Brief as Brief, p.Thumb as Thumb, p.PriceString as PriceString, p.Area as Area, c.CityName as CityName, d.DistrictName as DistrictName from product p';
		$sql .= ' inner join city c on p.CityID = c.CityID';
		$sql .= ' inner join district d on d.DistrictID = p.DistrictID';
		$sql .= ' where p.HotProduct = ' . ACTIVE;
		$products = $this->db->query($sql)->result();
		return $products;
	}

	public function updateViewForProductId($productId){
		$this->db->set('View', 'View + 1', false);
		$this->db->where('ProductID', $productId);
		$this->db->update('product');
	}

	public function pushPostUp($productId){
		$datestring = '%Y-%m-%d %h:%i:%s';
		$time = time();
		$now = mdate($datestring, $time);

		$this->db->set('ModifiedDate', $now);
		$this->db->where('ProductID', $productId);
		$this->db->update('product');
	}

	public function findByIdFetchAll($productId) {
		$sql = 'select * from product p inner join productdetail pd on p.productid = pd.productid';
		$sql .= ' where p.ProductID = '. $productId;
		$query = $this->db->query($sql);
		$product = $query->row();

		// Fetch Brand
		if($product->BrandID != null){
			$this->db->where("BrandID", $product->BrandID);
			$query = $this->db->get("brand");
			$product->Brand = $query->row();
		}

		// Fetch City
		if($product->CityID != null){
			$this->db->where("CityID", $product->CityID);
			$query = $this->db->get("city");
			$product->City = $query->row();
		}

		// Fetch District
		if($product->DistrictID != null){
			$this->db->where("DistrictID", $product->DistrictID);
			$query = $this->db->get("district");
			$product->District = $query->row();
		}

		// Fetch Ward
		if($product->WardID != null){
			$this->db->where("WardID", $product->WardID);
			$query = $this->db->get("ward");
			$product->Ward = $query->row();
		}

		// Fetch Ward
		if($product->UnitID != null){
			$this->db->where("UnitID", $product->UnitID);
			$query = $this->db->get("unit");
			$product->Unit = $query->row();
		}

		// Fetch Direction
		if($product->DirectionID != null){
			$this->db->where("DirectionID", $product->DirectionID);
			$query = $this->db->get("direction");
			$product->Direction = $query->row();
		}

		// Product Assets
		$this->db->where("ProductID", $productId);
		$query = $this->db->get("productasset");
		$product->Assets = $query->result();

		return $product;
	}

	public function findByCatId($catId, $start=null, $limit=null){
		$query = $this->db->order_by('PostDate', 'desc')->get_where('product', array('CategoryID' => $catId, "Status" => 1), $limit, $start);
		$products = $query->result();

		$this->db->where('CategoryID', $catId);
		$total = $this->db->count_all_results('product');

		$data['products'] = $products;
		$data['total'] = $total;
		return $data;
	}

	public function findByCityIdFetchAddress($cityId, $offset=0, $limit){
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' where p.CityID = '.$cityId.' and p.status = 1';
		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$countsql = 'select count(*) as total from product where CityID = '.$cityId.' and Status = 1';

		$products = $this->db->query($sql);
		$total = $this->db->query($countsql);

		$data['products'] = $products->result();
		$total = $total->row();
		$data['total'] = $total->total;
		return $data;
	}

	public function findByBranchIdFetchAddress($branchId, $offset=0, $limit){
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' where p.BrandID = '.$branchId.' and p.status = 1';
		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$countsql = 'select count(*) as total from product where BrandID = '.$branchId.' and Status = 1';

		$products = $this->db->query($sql);
		$total = $this->db->query($countsql);

		$data['products'] = $products->result();
		$total = $total->row();
		$data['total'] = $total->total;
		return $data;
	}

	public function findByCatIdAndCityIdFetchAddress($catId, $cityId, $offset=0, $limit){
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' where p.CategoryID = '.$catId.' and p.CityID = '.$cityId.' and p.status = 1';
		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$countsql = 'select count(*) as total from product where CategoryID = '.$catId.' and CityID = '.$cityId.' and Status = 1';

		$products = $this->db->query($sql);
		$total = $this->db->query($countsql);

		$data['products'] = $products->result();
		$total = $total->row();
		$data['total'] = $total->total;
		return $data;
	}

	public function findByCatIdFetchAddress($catId, $offset=0, $limit){
		// $this->output->enable_profiler(TRUE);
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' where p.categoryid = '.$catId.' and p.status = 1';
		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$countsql = 'select count(*) as total from product where CategoryID = '.$catId.' and Status = 1';

		$products = $this->db->query($sql);
		$total = $this->db->query($countsql);

		$data['products'] = $products->result();
		$total = $total->row();
		$data['total'] = $total->total;
		return $data;
	}

	public function updatePost($data, $assets){
		$productId = $data['productId'];

		$datestring = '%Y-%m-%d %h:%i:%s';
		$time = time();
		$now = mdate($datestring, $time);
		// Get Unit
		$this->db->where("UnitID", $data['unit']);
		$query = $this->db->get("unit");
		$unit = $query->row();

		$updateData = array(
			'Title' => $data['title'],
			'Brief' => substr($data['description'], 0, 400).'...',
			'Price' => $data['price'],
			'PriceString' => $data['price'].' '.$unit->Title,
			'Area' => $data['area'].' m²',
			'ModifiedDate' => $now,
			'CityID' => $data['city'],
			'DistrictID' => $data['district'],
			'WardID' => $data['ward'],
			'Street' => $data['street'],
			'CategoryID' => $data['categoryID'],
			'Status' => INACTIVE,
			'UnitID' => $data['unit'],
			'Address' => $data['address'],
		);

		$newdatadetail = array(
			'Detail' => $data['description'],
			'Floor' => $data['floor'],
			'Room' => $data['room'],
			'Toilet' => $data['toilet'],
			'WidthSize' => $data['width'],
			'LongSize' => $data['long'],
			'Longitude' => $data['longitude'],
			'Latitude' => $data['latitude'],
			'ContactPhone' => $data['contact_phone'],
			'ContactAddress' => $data['contact_address'],
			'ContactEmail' => $data['txt_email'],
			'ContactName' => $data['contact_name']
		);

		if($data['brand'] != null && $data['brand'] > 0){
			$updateData['BrandID'] = $data['brand'];
		}
		if($data['direction'] != null && $data['direction'] > 0){
			$newdatadetail['DirectionID'] = $data['direction'];
		}

		$this->db->where('ProductID', $productId);
		$this->db->update('product', $updateData);

		$this->saveProductAssets($productId, $assets);
		$this->saveProductDetail($productId, $newdatadetail);
		// update
		return $productId;
	}

	public function saveNewPost($data, $assets){
		$datestring = '%Y-%m-%d %h:%i:%s';
		$time = time();
		$now = mdate($datestring, $time);

		// Get Unit
		$this->db->where("UnitID", $data['unit']);
		$query = $this->db->get("unit");
		$unit = $query->row();

		$newdata = array(
			'code' => $data['code'],
			'Title' => $data['title'],
			'Brief' => substr($data['description'], 0, 400).'...',
			'Price' => $data['price'],
			'PriceString' => $data['price'].' '.$unit->Title,
			'Area' => $data['area'].' m²',
			'Thumb' => $data['image'],
			'PostDate' => $now,
			'ModifiedDate' => $now,
			'CityID' => $data['city'],
			'DistrictID' => $data['district'],
			'WardID' => $data['ward'],
			'Street' => $data['street'],
			'CategoryID' => $data['categoryID'],
			'Status' => INACTIVE,
			'View' => 0,
			'CreatedByID' => $data['CreatedByID'],
			'UnitID' => $data['unit'],
			'Address' => $data['address'],
		);
		$newdatadetail = array(
			'Detail' => $data['description'],
			'Floor' => $data['floor'],
			'Room' => $data['room'],
			'Toilet' => $data['toilet'],
			'WidthSize' => $data['width'],
			'LongSize' => $data['long'],
			'Longitude' => $data['longitude'],
			'Latitude' => $data['latitude'],
			'ContactPhone' => $data['contact_phone'],
			'ContactAddress' => $data['contact_address'],
			'ContactEmail' => $data['txt_email'],
			'ContactName' => $data['contact_name']
		);
		if($data['brand'] != null && $data['brand'] > 0){
			$newdata['BrandID'] = $data['brand'];
		}
		if($data['direction'] != null && $data['direction'] > 0){
			$newdatadetail['DirectionID'] = $data['direction'];
		}
		$this->db->insert('product', $newdata);
		$insert_id = $this->db->insert_id();
		$this->saveProductAssets($insert_id, $assets);
		$this->saveProductDetail($insert_id, $newdatadetail);
		return $insert_id;
	}

	public function changeStatusPost($productId, $status){
		$this->db->set('Status', $status);
		$this->db->where('ProductID', $productId);
		return $this->db->update('product');
	}

	public function updateCoordinator($productId, $longitude, $latitude){
		$this->db->set('Longitude', $longitude);
		$this->db->set('Latitude', $latitude);
		$this->db->where('ProductID', $productId);
		$this->db->update('productdetail');
	}

	public function deleteById($productId){
		$this->db->delete('productasset', array('ProductID' => $productId));
		$this->db->delete('productdetail', array('ProductID' => $productId));
		$this->db->delete('product', array('ProductID' => $productId));
	}

	private function saveProductDetail($productId, $data){
		if($productId != null && $productId > 0 && $data != null && count($data) > 0){
			// delete old items
			$this->db->delete('productdetail', array('ProductID' => $productId));
			$data['ProductID'] = $productId;
			$this->db->insert('productdetail', $data);
		}
	}

	private function saveProductAssets($productId, $assets){
		if($productId != null && $productId > 0 && $assets != null && count($assets) > 0){
			// delete old items
			$this->db->delete('productasset', array('ProductID' => $productId));

			// Save assets
			foreach ($assets as $asset){
				$newdata = array(
					'ProductID' => $productId,
					'Url' => trim($asset, "'"),
					'OrgUrl' => trim($asset, "'")
				);
				$this->db->insert('productasset', $newdata);
			}
		}
	}

	public function searchByProperties($catId, $cityId, $districtId, $area, $price, $postDate, $offset, $limit){
		$sql = 'select p.*, c.cityname as city, d.districtname as district from product p';
		$sql .= ' inner join city c on p.cityid = c.cityid';
		$sql .= ' inner join district d on p.districtid = d.districtid';
		$sql .= ' where p.status = '.ACTIVE;
		if(isset($catId) && $catId > -1) {
			$sql .= ' and p.CategoryID = ' . $catId;
		}
		if(isset($cityId) && $cityId > -1) {
			$sql .= ' and p.CityID = ' . $cityId;
		}
		if(isset($districtId) && $districtId > -1) {
			$sql .= ' and p.DistrictID = ' . $districtId;
		}

		$sql .= ' order by p.postdate desc';
		$sql .= ' limit '.$offset.','.$limit;

		$countsql = 'select count(*) as total from product where Status = '.ACTIVE;
		if(isset($catId) && $catId > -1) {
			$countsql .= ' and CategoryID = ' . $catId;
		}
		if(isset($cityId) && $cityId > -1) {
			$countsql .= ' and CityID = ' . $cityId;
		}
		if(isset($districtId) && $districtId > -1) {
			$countsql .= ' and DistrictID = ' . $districtId;
		}

		$products = $this->db->query($sql);
		$total = $this->db->query($countsql);

		$data['products'] = $products->result();
		$total = $total->row();
		$data['total'] = $total->total;
		return $data;
	}

	private function getAreaFromInt($areaInt){

	}
}
