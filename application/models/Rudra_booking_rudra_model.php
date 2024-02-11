<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_booking_rudra_model extends CI_Model
{
	public function __construct()
	{

		parent::__construct();

		$this->bdp = $this->db->dbprefix;
		$this->full_table = 'rudra_booking';
	}

	public function get_table_data($limit, $start, $order, $dir, $filter_data, $tbl_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT TBL.id, TBL.user_id, TBL.is_new, TBL.booking_id, TBL.zipcode, TBL.latitude, TBL.longitude, TBL.address, TBL.fk_car_reg_id, TBL.wash_id, TBL.extra_wash_type_ids, TBL.wash_date, TBL.wash_time, TBL.notes, TBL.washer_id, TBL.wash_status, TBL.vat_percentage, TBL.vat, TBL.extra_charges, TBL.wash_type_json, TBL.wash_amount, TBL.extra_wash_type_json, TBL.extra_wash_amount, TBL.total_pay, TBL.transaction, TBL.pay_method, TBL.status, TBL.is_deleted, TBL.cancellation, TBL.refunded_status";
		$query .= "  FROM $table ";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.booking_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.zipcode LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.latitude LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.longitude LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.address LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_car_reg_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_type_ids LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_date LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_time LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.notes LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.washer_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.vat_percentage LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.vat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_charges LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_type_json LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_amount LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_type_json LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_amount LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.total_pay LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.transaction LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.pay_method LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		// $where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		if ((isset($filter_data['start_date']) && $filter_data['start_date'] != "") && (isset($filter_data['end_date']) && $filter_data['end_date'] != "")) {
			$where .= " AND TBL.wash_date >= '" . $filter_data['start_date'] . "' AND TBL.wash_date <= '" . $filter_data['end_date'] . "'";
		}

		if (isset($filter_data['user_id']) && $filter_data['user_id'] != "") {
			$where .= " AND TBL.user_id = '" . $filter_data['user_id'] . "' ";
		}
		if (isset($filter_data['washer_id']) && $filter_data['washer_id'] != "") {
			$where .= " AND TBL.washer_id = '" . $filter_data['washer_id'] . "' ";
		}
		if (isset($filter_data['wash_status']) && $filter_data['wash_status'] != "") {
			$where .= " AND TBL.wash_status = '" . $filter_data['wash_status'] . "' ";
		}

		if (isset($filter_data['searchtext']) && $filter_data['searchtext'] != "") {
			$where .= " AND (TBL.booking_id LIKE '%" . $filter_data['searchtext'] . "%') ";
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
		$where .=  "  OR  TBL.user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.booking_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.zipcode LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.latitude LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.longitude LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.address LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_car_reg_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_type_ids LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_date LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_time LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.notes LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.washer_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.vat_percentage LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.vat LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_charges LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_type_json LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.wash_amount LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_type_json LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.extra_wash_amount LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.total_pay LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.transaction LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.pay_method LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		// $where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		if ((isset($filter_data['start_date']) && $filter_data['start_date'] != "") && (isset($filter_data['end_date']) && $filter_data['end_date'] != "")) {
			$where .= " AND TBL.wash_date >= '" . $filter_data['start_date'] . "' AND TBL.wash_date <= '" . $filter_data['end_date'] . "'";
		}
		if (isset($filter_data['user_id']) && $filter_data['user_id'] != "") {
			$where .= " AND TBL.user_id = '" . $filter_data['user_id'] . "' ";
		}
		if (isset($filter_data['washer_id']) && $filter_data['washer_id'] != "") {
			$where .= " AND TBL.washer_id = '" . $filter_data['washer_id'] . "' ";
		}
		if (isset($filter_data['wash_status']) && $filter_data['wash_status'] != "") {
			$where .= " AND TBL.wash_status = '" . $filter_data['wash_status'] . "' ";
		}
		if (isset($filter_data['searchtext']) && $filter_data['searchtext'] != "") {
			$where .= " AND (TBL.booking_id LIKE '%" . $filter_data['searchtext'] . "%') ";
		}
		$query = $query . $where;
		$res = $this->db->query($query)->row();
		return $res->cntrows;
	}
}
