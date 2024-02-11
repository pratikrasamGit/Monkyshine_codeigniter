<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rudra_user_rudra_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->bdp = $this->db->dbprefix;
		$this->full_table = 'rudra_user';
		$this->registration_number_table = 'rudra_user_registered_car_numbers';
		$this->notification_table = "rudra_notifications";
		$this->chat_table = "rudra_chat";
		$this->upload_table = "rudra_chat_uploads";
	}

	public function get_table_data($limit, $start, $order, $dir, $filter_data, $tbl_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT TBL.id, TBL.user_type, TBL.login_type, TBL.social_id, TBL.email, TBL.password, TBL.forgot_code, TBL.forgot_time, TBL.name, TBL.surname, TBL.contact_email, TBL.contact_number, TBL.lat, TBL.lon, TBL.address, TBL.zipcode, TBL.city, TBL.device_token, TBL.app_version, TBL.device_type, TBL.device_company, TBL.device_version, TBL.device_location, TBL.device_lat, TBL.device_lang, TBL.device_city, TBL.device_country, TBL.status, TBL.is_deleted";
		$query .= "  FROM $table ";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.user_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.login_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.social_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.email LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.password LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.forgot_code LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.forgot_time LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.name LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.surname LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.contact_email LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.contact_number LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.lat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.lon LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.address LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.zipcode LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.city LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_token LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.app_version LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_company LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_version LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_location LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_lat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_lang LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_city LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_country LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		if (isset($filter_data['user_type']) && $filter_data['user_type'] != "") {
			$where .= " AND TBL.user_type = '" . $filter_data['user_type'] . "' ";
		}
		$order_by = ($order == '' ? '' : ' ORDER BY ' . $order . " " . $dir);
		$limit = " LIMIT " . $start . " , " . $limit;
		$query = $query . $where . $order_by . $limit;
		$res = $this->db->query($query)->result();
		return $res;
	}

	public function count_table_data($filter_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT COUNT(id) as cntrows";
		$query .= "  FROM $table ";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.user_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.login_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.social_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.email LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.password LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.forgot_code LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.forgot_time LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.name LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.surname LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.contact_email LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.contact_number LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.lat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.lon LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.address LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.zipcode LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.city LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_token LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.app_version LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_type LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_company LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_version LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_location LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_lat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_lang LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_city LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.device_country LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		if (isset($filter_data['user_type']) && $filter_data['user_type'] != "") {
			$where .= " AND TBL.user_type = '" . $filter_data['user_type'] . "' ";
		}
		$query = $query . $where;
		$res = $this->db->query($query)->row();
		return $res->cntrows;
	}

	public function checkNormalUserExists($email = "", $phone_number = "")
	{
		$where = "WHERE `is_deleted` = '0' ";
		if ($email != "" && $phone_number != "") {
			$where .= " AND (`email` = '" . $email . "' OR `contact_number` = '" . $phone_number . "')";
		} elseif ($email != "" && $phone_number == "") {
			$where .= " AND (`email` = '" . $email . "')";
		} elseif ($email == "" && $phone_number != "") {
			$where .= " AND (`contact_number` = '" . $phone_number . "')";
		}

		$return = [];
		// $sql = "SELECT * FROM `" . $this->full_table . "` " . $where . "  AND `login_type` = '0'; ";
		$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " ";
		$check_user_exists = $this->db->query($sql);
		if ($check_user_exists->num_rows() > 0) {
			$return = $check_user_exists->row();
		}

		return $return;
	}

	public function checkSocialUserExists($social_id = "", $identifier = "")
	{
		$return = [];
		if ($social_id != "" && $identifier == "") {
			$where = "WHERE `is_deleted` = '0' AND `social_id` = '" . $social_id . "' ";

			// $sql = "SELECT * FROM `" . $this->full_table . "` " . $where . "  AND `login_type` != '0'; ";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}else if($identifier != ''){
			$where = "WHERE `is_deleted` = '0' AND `apple_identifier` = '" . $identifier . "' ";

			// $sql = "SELECT * FROM `" . $this->full_table . "` " . $where . "  AND `login_type` != '0'; ";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}

		return $return;
	}

	public function checkUserExists($email = "", $password = "")
	{
		$return = [];
		if ($email != "" && $password != "") {
			$where = "WHERE `is_deleted` = '0' AND `status` = '1' AND `email` = '" . $email . "' AND `password` = '" . sha1($password) . "' ";

			// $sql = "SELECT * FROM `" . $this->full_table . "` " . $where . "  AND `login_type` = '0'; ";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}

		return $return;
	}

	public function checkIfEMailExists($user_id = "", $email = "")
	{
		$return = [];
		if ($user_id != "" && $email != "") {
			$where = "WHERE `email` = '" . $email . "' AND `id` NOT IN ('" . $user_id . "')";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}

		return $return;
	}

	public function checkIfPhoneExists($user_id = "", $phone = "")
	{
		$return = [];
		if ($user_id != "" && $phone != "") {
			$where = "WHERE `contact_number` = '" . $phone . "' AND `id` NOT IN ('" . $user_id . "')";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}

		return $return;
	}

	public function checkIfUserExists($user_id = "")
	{
		$return = [];
		if ($user_id != "") {
			$where = "WHERE `id` = '" . $user_id . "'";
			$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->row();
			}
		}

		return $return;
	}

	public function checkNumberHasRegistered($user_id = "", $registration_number = "")
	{
		$return = [];
		if ($user_id != "" && $registration_number != "") {
			$where = "WHERE `registration_number` = '" . $registration_number . "' AND `fk_user_id` = '" . $user_id . "'";
			$sql = "SELECT * FROM `" . $this->registration_number_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_num_exists = $this->db->query($sql);
			if ($check_num_exists->num_rows() > 0) {
				$return = $check_num_exists->row();
			}
		}

		return $return;
	}

	public function checkIfCarRegistrationNumberExists($user_id = "", $reg_no_id = "", $registration_number = "")
	{
		$return = [];
		if ($user_id != "" && $reg_no_id != "") {
			$where = "WHERE `registration_number` = '" . $registration_number . "' AND `fk_user_id` = '" . $user_id . "' AND `id` NOT IN ('" . $reg_no_id . "')";
			$sql = "SELECT * FROM `" . $this->registration_number_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_number_exists = $this->db->query($sql);
			if ($check_number_exists->num_rows() > 0) {
				$return = $check_number_exists->row();
			}
		}

		return $return;
	}

	public function getUserRegisteredCarNumbers($user_id = "", $id = "")
	{
		$return = [];
		if ($user_id != "") {
			$where = "WHERE `fk_user_id` = '" . $user_id . "' ";
			if ($id != "") $where .= " AND `id` = '" . $id . "' ";
			$sql = "SELECT * FROM `" . $this->registration_number_table . "` " . $where . " AND `is_deleted` = '0' AND `status` = '1'; ";
			$check_user_exists = $this->db->query($sql);
			if ($check_user_exists->num_rows() > 0) {
				$return = $check_user_exists->result();
			}
		}

		return $return;
	}

	public function userProfileInfo($user_id = "", $page = "1")
	{
		$where = "WHERE `is_deleted` = '0' AND `status` = '1' ";
		if ($user_id != "") $where .= " AND `id` = '" . $user_id . "'";

		$limit = 10;
		$start = "0";
		if ($page > 1) $start = ($page - 1) * $limit;

		$sql = "SELECT * FROM `" . $this->full_table . "` " . $where . " LIMIT $start, $limit;";
		$check_user_exists = $this->db->query($sql);

		$user_data = [];
		if ($check_user_exists->num_rows() > 0) {
			$user = $check_user_exists->result();
			foreach ($user as $key => $u) {
				$user_info['user_id'] = (isset($u->id) && $u->id != "") ? $u->id : "";
				$user_info['user_type'] = (isset($u->user_type) && $u->user_type != "") ? $u->user_type : "";
				$user_info['login_type'] = (isset($u->login_type) && $u->login_type != "") ? $u->login_type : "";
				$user_info['social_id'] = (isset($u->social_id) && $u->social_id != "") ? $u->social_id : "";
				$user_info['email'] = (isset($u->email) && $u->email != "") ? $u->email : "";
				$user_info['name'] = (isset($u->name) && $u->name != "") ? $u->name : "";
				$user_info['surname'] = (isset($u->surname) && $u->surname != "") ? $u->surname : "";
				$user_info['contact_email'] = (isset($u->contact_email) && $u->contact_email != "") ? $u->contact_email : "";
				$user_info['contact_number'] = (isset($u->contact_number) && $u->contact_number != "") ? $u->contact_number : "";
				$user_info['lat'] = (isset($u->lat) && $u->lat != "") ? $u->lat : "";
				$user_info['lon'] = (isset($u->lon) && $u->lon != "") ? $u->lon : "";
				$user_info['address'] = (isset($u->address) && $u->address != "") ? $u->address : "";
				$user_info['zipcode'] = (isset($u->zipcode) && $u->zipcode != "") ? $u->zipcode : "";
				$user_info['city'] = (isset($u->city) && $u->city != "") ? $u->city : "";
				$user_info['device_token'] = (isset($u->device_token) && $u->device_token != "") ? $u->device_token : "";
				$user_info['app_version'] = (isset($u->app_version) && $u->app_version != "") ? $u->app_version : "";
				$user_info['device_type'] = (isset($u->device_type) && $u->device_type != "") ? $u->device_type : "";
				$user_info['device_company'] = (isset($u->device_company) && $u->device_company != "") ? $u->device_company : "";
				$user_info['device_version'] = (isset($u->device_version) && $u->device_version != "") ? $u->device_version : "";
				$user_info['device_location'] = (isset($u->device_location) && $u->device_location != "") ? $u->device_location : "";
				$user_info['device_lat'] = (isset($u->device_lat) && $u->device_lat != "") ? $u->device_lat : "";
				$user_info['device_lang'] = (isset($u->device_lang) && $u->device_lang != "") ? $u->device_lang : "";
				$user_info['device_city'] = (isset($u->device_city) && $u->device_city != "") ? $u->device_city : "";
				$user_info['device_country'] = (isset($u->device_country) && $u->device_country != "") ? $u->device_country : "";
				$user_info['status'] = (isset($u->status) && $u->status != "") ? $u->status : "";
				$user_info['fcm_token'] = (isset($u->fcm_token) && $u->fcm_token != "") ? $u->fcm_token : "";
				$user_info['notification_flag'] = (isset($u->notification_status) && $u->notification_status != "") ? $u->notification_status : "";

				/* $user_info['is_deleted'] = (isset($u->is_deleted) && $u->is_deleted != "") ? $u->is_deleted : "";
				$user_info['created_at'] = (isset($u->created_at) && $u->created_at != "") ? $u->created_at : "";
				$user_info['updated_at'] = (isset($u->updated_at) && $u->updated_at != "") ? $u->updated_at : ""; */

				$user_info['profile_flag'] = "0";
				if (
					$user_info['name'] != "" && $user_info['surname'] != "" &&
					$user_info['email'] != "" && $user_info['contact_number'] != "" &&
					$user_info['address'] != "" && $user_info['zipcode'] != "" && $user_info['city'] != ""
				) {
					$user_info['profile_flag'] = "1";
				}

				$user_data[] = $user_info;
			}
		}

		return $user_data;
	}

	public function chatData($user_id, $page = "1", $chat_id = "", $order = "DESC")
	{
		$limit = "10";
		$start = "0";
		if ($page > 1) $start = ($page - 1) * $limit;

		$chat = "";
		if ($chat_id != "") $chat = " AND `id` = '" . $chat_id . "' ";

		$query = "SELECT * FROM " . $this->chat_table . " WHERE (fk_from_id = '" . $user_id . "' OR `fk_to_id` = '" . $user_id . "') AND `status` = '1' AND `is_deleted` = '0' " . $chat . " ORDER BY `created_at` " . $order . " LIMIT $start , $limit";
		$result = $this->db->query($query);

		/* getting page count */
		$query1 = "SELECT * FROM " . $this->chat_table . " WHERE (fk_from_id = '" . $user_id . "' OR `fk_to_id` = '" . $user_id . "') AND `status` = '1' AND `is_deleted` = '0' " . $chat . " ORDER BY `created_at` " . $order . ";";
		$result1 = $this->db->query($query1);
		$total_result_count = $result1->num_rows();
		/* getting page count */

		/* getting user info */
		$this->db->where(['id' => $user_id]);
		$user_info = $this->db->get($this->full_table);
		$user_name = "User";
		if ($user_info->num_rows() > 0) {
			$user = $user_info->row();
			$user_name = $user->name;
		}
		/* getting user info */

		$list['chat'] = [];
		if ($result->num_rows() > 0) {
			foreach ($result->result() as $res) {
				$chat_array["chat_id"] = $res->id;
				/* from data */
				if($res->from_admin == '1'){
						$chat_array["from_id"] = $res->fk_from_id;
						$chat_array["from_name"] = "admin";
						$chat_array["from_logo"] = base_url('app_assets/images/admin/admin_icon.png');

				}else{
					if ($res->fk_from_id == $user_id) {
						// user
						$chat_array["from_id"] = $res->fk_from_id;
						$chat_array["from_name"] = $user_name;
						$chat_array["from_logo"] = base_url('app_assets/images/admin/user_icon.png');
					} else {
						// admin
						$chat_array["from_id"] = $res->fk_from_id;
						$chat_array["from_name"] = "admin";
						$chat_array["from_logo"] = base_url('app_assets/images/admin/admin_icon.png');
					}
				}
				/* from data */

				/* to data */
				if($res->from_admin == '0'){
						$chat_array["to_id"] = $res->fk_to_id;
						$chat_array["to_name"] = $user_name;
						$chat_array["to_logo"] = base_url('app_assets/images/admin/user_icon.png');
				}else{
					if ($res->fk_to_id == $user_id) {
						// user
						$chat_array["to_id"] = $res->fk_to_id;
						$chat_array["to_name"] = $user_name;
						$chat_array["to_logo"] = base_url('app_assets/images/admin/user_icon.png');
					} else {
						// admin
						$chat_array["to_id"] = $res->fk_to_id;
						$chat_array["to_name"] = "admin";
						$chat_array["to_logo"] = base_url('app_assets/images/admin/admin_icon.png');
					}
				}
				/* to data */
				$chat_array["message"] = (isset($res->message) && $res->message != "") ? $res->message : "";
				$chat_array["created_at"] = (isset($res->created_at)) ? date('d-m-Y h:i A', strtotime($res->created_at)) : "";

				/* getting user info */
				$uploads = [];
				$this->db->where(['fk_chat_id' => $res->id, 'status' => '1', 'is_deleted' => "0"]);
				$upload_info = $this->db->get($this->upload_table);
				if ($upload_info->num_rows() > 0) {
					foreach ($upload_info->result() as $key => $cu) {
						$uploads[] = base_url('app_assets/images/chat/' . $cu->file_name);
					}
				}

				$chat_array["msg_type"] = (empty($uploads)) ? "0" : "1";
				$chat_array["msg_type_definition"] = ($chat_array["msg_type"] == "0") ? "Only text" : "Has images";
				$chat_array["uploads"] = $uploads;
				/* getting user info */

				$chat_array["from_admin"] = $res->from_admin;
				$list['chat'][] = $chat_array;
			}
		}
		$total_pages = ceil($total_result_count / $limit);
		$list['total_pages_available'] =  strval($total_pages);
		$list['current_page'] = $page;
		$list['results_per_page'] = $limit;

		return $list;
	}

	public function checkBookingExists($id)
	{
		$this->db->where(['id' => $id, 'wash_status' => '1', 'status' => '1', 'is_deleted' => '0']);
		$bookings = $this->db->get('rudra_booking');

		return ($bookings->num_rows() > 0) ? $bookings->row() : [];
	}

	public function checkWashAccepted($id, $washer_id)
	{
		$this->db->where(['id' => $id, 'washer_id' => $washer_id, 'wash_status' => "2", 'status' => '1', 'is_deleted' => '0']);
		$bookings = $this->db->get('rudra_booking');

		return ($bookings->num_rows() > 0) ? $bookings->row() : [];
	}

	public function updateNotifications($user_id, $message, $message_dn)
	{
		$update_array = array('fk_user_id' => $user_id, 'messge' => $message, 'messge_dn' => $message_dn);
		$new_id = $this->db->insert($this->notification_table, $update_array);
	}

	public function vat_percentage()
	{

		$this->db->where(['label' => 'vat_percentage', 'status' => '1', 'is_deleted' => '0']);
		$query = $this->db->get('rudra_threshold');

		return $query->row()->value;
	}
}
