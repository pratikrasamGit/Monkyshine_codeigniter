<?php
                
defined('BASEPATH') or exit('No direct script access allowed');

                
class Rudra_slots_rudra_model extends CI_Model
                
{
                   
                    
	public function __construct()
                    
	{
                    
		parent::__construct();
                
                    $this->bdp = $this->db->dbprefix;
                    $this->full_table = 'rudra_slots';
                        
                    }
	public function get_table_data($limit,$start,$order,$dir,$filter_data,$tbl_data,$table_name)
	{
		$table = $this->full_table .' TBL';
	$query = " SELECT TBL.id, TBL.slot_date, TBL.start_time, TBL.end_time, TBL.no_of_service, TBL.is_deleted" ;
	$query .= "  FROM $table "; 
	$where = " WHERE 1 = 1 ";
	 $where .= " AND ( ";
	$where .=  "  TBL.id LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.slot_date LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.start_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.end_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.no_of_service LIKE '%".$filter_data['general_search']."%'";
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
	$where .=  "  OR  TBL.slot_date LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.start_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.end_time LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.no_of_service LIKE '%".$filter_data['general_search']."%'";
	$where .=  "  OR  TBL.is_deleted LIKE '%".$filter_data['general_search']."%'";
	 $where .= " ) ";
	$query = $query.$where;
	 $res = $this->db->query($query)->row();
	 return $res->cntrows;
		}

		public function get_no_of_services($date){

			// $query = " SELECT COUNT(id) as cntrows from rudra_booking where wash_date=".$date ;
			$table = 'rudra_booking';
			$query = " SELECT COUNT(id) as cntrows" ;
			$query .= "  FROM $table "; 
			$where = " WHERE 1 = 1 ";
			//  $where .= " AND ( ";
			$where .=  " and wash_date = '".$date."' ";
			//  $where .= " ) ";
			$query = $query.$where;
			$res = $this->db->query($query)->row();
			// return $this->db->last_query();
			return $res->cntrows;
		}

		public function get_no_of_services_by_time($date,$start_time,$end_time){

			$table = 'rudra_booking';
			$query = " SELECT COUNT(id) as cntrows" ;
			$query .= "  FROM $table "; 
			$where = " WHERE 1 = 1 ";
			//  $where .= " AND ( ";
			$where .=  " and wash_date = '".$date."' and wash_time>='".$start_time."' and wash_time<'".$end_time."' ";
			//  $where .= " ) ";
			$query = $query.$where;
			$res = $this->db->query($query)->row();
			return $res->cntrows;
		}

		public function get_no_of_maxservices($date,$start_time,$end_time){
			$table = 'rudra_slots';
			$query = " SELECT *" ;
			$query .= "  FROM $table "; 
			$where = " WHERE 1 = 1 ";
			//  $where .= " AND ( ";
			$where .=  " and slot_date = '".$date."' and start_time='".$start_time."' and end_time='".$end_time."' ";
			//  $where .= " ) ";
			$query = $query.$where;
			$res = $this->db->query($query)->row();
			if(!empty($res)){
				return $res->no_of_service;
			}else{
				return 0;
			}
		}
}