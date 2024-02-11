<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rudra_user_registered_car_numbers_rudra_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->bdp = $this->db->dbprefix;
		$this->full_table = 'rudra_user_registered_car_numbers';
	}

	public function get_table_data($limit, $start, $order, $dir, $filter_data, $tbl_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT TBL.id, TBL.car_size, TBL.fk_user_id, TBL.registration_number, TBL.name, TBL.brand, TBL.model, TBL.color, TBL.description, TBL.status, TBL.is_deleted, u.name u_name";
		$query .= "  FROM $table ";
		$query .= "  JOIN rudra_user u ON TBL.fk_user_id = u.id ";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  u.name LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.car_size LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.registration_number LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.name LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.brand LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.model LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.color LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.description LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
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
		$where .=  "  OR  TBL.car_size LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.registration_number LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.name LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.brand LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.model LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.color LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.description LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		$query = $query . $where;
		$res = $this->db->query($query)->row();
		return $res->cntrows;
	}
}
