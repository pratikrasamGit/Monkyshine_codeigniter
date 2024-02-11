<?php
                
defined('BASEPATH') or exit('No direct script access allowed');

                
class Rudra_times_rudra_model extends CI_Model
                
{
                   
                    
	public function __construct()
                    
	{
                    
		parent::__construct();
                
                    $this->bdp = $this->db->dbprefix;
                    $this->full_table = 'rudra_times';
                        
                    }
	public function get_table_data($limit,$start,$order,$dir,$filter_data,$tbl_data,$table_name)
	{
		$table = $this->full_table .' TBL';
	$query = " SELECT TBL.id, TBL.day, TBL.time, TBL.total_slots, TBL.slots_taken, TBL.status, TBL.is_deleted" ;
	$query .= "  FROM $table "; 
	$where = " WHERE TBL.day >= '".date('Y-m-d')."' ";
	 $where .= " AND ( ";
	$where .=  "  TBL.id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.day LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.total_slots LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.slots_taken LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.is_deleted LIKE '%".$filter_data['general_search']."%'";
	 $where .= " ) ";
	 $groupby = " ";
	$order_by = ($order == '' ? ' ORDER BY TBL.day asc' : ' ORDER BY '.$order." ".$dir);
	$order_by = ' ORDER BY TBL.day asc';
	//  $limit = " LIMIT ".$start." , " .$limit;
	$query = $query.$where.$groupby.$order_by;
	 $res = $this->db->query($query)->result();
	 return $res;
	}


	public function count_table_data($filter_data,$table_name)
		{
		$table = $this->full_table .' TBL';
	$query = " SELECT COUNT(id) as cntrows" ;
	$query .= "  FROM $table "; 
	$where = " WHERE TBL.day >= '".date('Y-m-d')."' ";
	 $where .= " AND ( ";
	$where .=  "  TBL.id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.day LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.total_slots LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.slots_taken LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.is_deleted LIKE '%".$filter_data['general_search']."%'";
	 $where .= " ) ";
	 $groupby = " GROUP BY TBL.day ";
	$query = $query.$where.$groupby;
	 $res = $this->db->query($query)->row();
	 return $res->cntrows;
		}

}