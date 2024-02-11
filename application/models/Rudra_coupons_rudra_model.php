<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rudra_coupons_rudra_model extends CI_Model
{
	public function __construct()
	{

		parent::__construct();

		$this->bdp = $this->db->dbprefix;
		$this->full_table = 'rudra_coupons';
	}
	public function get_table_data($limit, $start, $order, $dir, $filter_data, $tbl_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT TBL.id, TBL.code, TBL.offered, TBL.expiry, TBL.status, TBL.is_deleted";
		$query .= "  FROM $table ";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.code LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.offered LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.expiry LIKE '%" . $filter_data['general_search'] . "%'";
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
		$where .=  "  OR  TBL.code LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.offered LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.expiry LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		$query = $query . $where;
		$res = $this->db->query($query)->row();
		return $res->cntrows;
	}
}
