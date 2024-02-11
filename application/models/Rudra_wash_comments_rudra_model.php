<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_wash_comments_rudra_model extends CI_Model
{
	public function __construct()
	{

		parent::__construct();

		$this->bdp = $this->db->dbprefix;
		$this->full_table = 'rudra_wash_comments';
	}

	public function get_table_data($limit, $start, $order, $dir, $filter_data, $tbl_data, $table_name)
	{
		$table = $this->full_table . ' TBL';
		$query = " SELECT TBL.id, TBL.fk_user_id, TBL.fk_wash_id, TBL.comment, b.booking_id, b.wash_id b_wash_id";
		$query .= "  FROM $table ";
		$query .= "  JOIN rudra_booking b ON  TBL.fk_wash_id = b.id";
		$where = " WHERE 1 = 1 ";
		$where .= " AND ( ";
		$where .=  "  TBL.id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  b.booking_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_wash_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.comment LIKE '%" . $filter_data['general_search'] . "%'";
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
		$where .=  "  OR  TBL.fk_user_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.fk_wash_id LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.comment LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.status LIKE '%" . $filter_data['general_search'] . "%'";
		$where .=  "  OR  TBL.is_deleted LIKE '%" . $filter_data['general_search'] . "%'";
		$where .= " ) ";
		$query = $query . $where;
		$res = $this->db->query($query)->row();
		return $res->cntrows;
	}
}
