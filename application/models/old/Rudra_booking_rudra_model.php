<?php
                
defined('BASEPATH') or exit('No direct script access allowed');

                
class Rudra_booking_rudra_model extends CI_Model
                
{
                   
                    
	public function __construct()
                    
	{
                    
		parent::__construct();
                
                    $this->bdp = $this->db->dbprefix;
                    $this->full_table = 'rudra_booking';
                        
                    }
	public function get_table_data($limit,$start,$order,$dir,$filter_data,$tbl_data,$table_name)
	{
		$table = $this->full_table .' TBL';
	$query = " SELECT TBL.id, TBL.user_id, TBL.zipcode, TBL.latitude, TBL.longitude, TBL.address, TBL.fk_car_reg_id, TBL.wash_id, TBL.extra_wash_type_ids, TBL.wash_date, TBL.wash_time, TBL.notes, TBL.wash_status, TBL.status, TBL.is_deleted" ;
	$query .= "  FROM $table "; 
	$where = " WHERE 1 = 1 ";
	 $where .= " AND ( ";
	$where .=  "  TBL.id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.user_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.zipcode LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.latitude LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.longitude LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.address LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.fk_car_reg_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.extra_wash_type_ids LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_date LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.notes LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.is_deleted LIKE '%".$filter_data['general_search']."%'";
	 $where .= " ) ";
	$order_by = ($order == '' ? '' : ' ORDER BY '.$order." ".$dir);
	 $limit = " LIMIT ".$start." , " .$limit;
	$query = $query.$where.$order_by.$limit;
	 $res = $this->db->query($query)->result();
	 return $res;
	}


	public function count_table_data($filter_data,$table_name)
		{
		$table = $this->full_table .' TBL';
	$query = " SELECT COUNT(id) as cntrows" ;
	$query .= "  FROM $table "; 
	$where = " WHERE 1 = 1 ";
	 $where .= " AND ( ";
	$where .=  "  TBL.id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.user_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.zipcode LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.latitude LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.longitude LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.address LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.fk_car_reg_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.extra_wash_type_ids LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_date LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.notes LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.wash_status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.status LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.is_deleted LIKE '%".$filter_data['general_search']."%'";
	 $where .= " ) ";
	$query = $query.$where;
	 $res = $this->db->query($query)->row();
	 return $res->cntrows;
		}

}