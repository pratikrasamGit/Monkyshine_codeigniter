<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_booking_crtl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_booking';
        $this->table_wash_types = 'rudra_wash_types';
        $this->table_wash_extra_types = 'rudra_wash_extra_types';
        $this->table_registered_car_numbers = 'rudra_user_registered_car_numbers';
        $this->table_wash_comments = 'rudra_wash_comments';
        $this->table_qa_uploads = 'rudra_qa_uploads';
        $this->table_user = 'rudra_user';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model', 'crd');
        $this->load->model('rudra_booking_rudra_model', 'rudram');
        // Uncomment Following Codes for Session Check and change accordingly
        /*
        if (!$this->session->userdata('rudra_admin_logged_in'))
        {
            $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
            $return_url = uri_string();
            redirect(base_url("admin-login?req_uri=$return_url"), 'refresh');
        }
        else
        {
            $this->admin_id = $this->session->userdata('rudra_admin_id');
            $this->return_data = array('status' => false, 'msg' => 'Working', 'login' => true, 'data' => array());
        }
        */
    }
    /***********
	//Rudra_booking_crtl ROUTES
        $crud_master = $crm . "Rudra_booking_crtl/";
        $route['rudra_booking'] = $crud_master . 'index';
        $route['rudra_booking/index'] = $crud_master . 'index';
        $route['rudra_booking/list'] = $crud_master . 'list';
        $route['rudra_booking/post_actions/(:any)'] = $crud_master.'post_actions/$1';
     **************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Booking';
        $data['page_template'] = '_rudra_booking';
        $data['page_header'] = ' Booking';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }


    public function pending()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Pending Bookings';
        $data['page_template'] = '_rudra_booking_pending';
        $data['page_header'] = ' Pending Bookings';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function assigned()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Accepted Bookings';
        $data['page_template'] = '_rudra_booking_assigned';
        $data['page_header'] = ' Accepted Bookings';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function completed()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Completed Bookings';
        $data['page_template'] = '_rudra_booking_completed';
        $data['page_header'] = ' Completed Bookings';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $orderArray = array('id', 'user_id', 'booking_id', 'zipcode', 'latitude', 'longitude', 'address', 'fk_car_reg_id', 'wash_id', 'extra_wash_type_ids', 'wash_date', 'wash_time', 'notes', 'washer_id', 'wash_status', 'vat_percentage', 'vat', 'extra_charges', 'wash_type_json', 'wash_amount', 'extra_wash_type_json', 'extra_wash_amount', 'total_pay', 'transaction', 'pay_method', 'status', 'is_deleted','is_new');
        $limit = html_escape($this->input->post('length'));
        $start = html_escape($this->input->post('start'));
        $order = '';
        $dir   = $this->input->post('order')[0]['dir'];
        $order   = $this->input->post('order')[0]['column'];
        $orderColumn = $orderArray[$order];

        $input =  $this->input->post();

        $user_id_filter = (isset($input['user']) && $input['user'] != "") ? $input['user'] : "";
        if ($user_id_filter != "") {
            $filter_data['user_id'] = $user_id_filter;
        }
        $washer_id_filter = (isset($input['washer']) && $input['washer'] != "") ? $input['washer'] : "";
        if ($washer_id_filter != "") {
            $filter_data['washer_id'] = $washer_id_filter;
        }
        $status_id_filter = (isset($input['status']) && $input['status'] != "") ? $input['status'] : "";
        if ($status_id_filter != "") {
            $filter_data['wash_status'] = $status_id_filter;
        }

        $searchtext = (isset($input['searchtext']) && $input['searchtext'] != "") ? $input['searchtext'] : "";
        if ($searchtext != "") {
            $filter_data['searchtext'] = $searchtext;
        }

        $start_date = (isset($input['start_date']) && $input['start_date'] != "") ? date('Y-m-d', strtotime($input['start_date'])) : "";
        $end_date = (isset($input['end_date']) && $input['end_date'] != "") ? date('Y-m-d', strtotime($input['end_date'])) : "";
        $filter_data['start_date'] = $filter_data['end_date'] = "";
        if ($start_date != "" && $end_date != "") {
            $filter_data['start_date'] = $start_date;
            $filter_data['end_date'] = $end_date;
        }

        $general_search = $this->input->post('search')['value'];
        $filter_data['general_search'] = $general_search;
        $totalData = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $totalData; //Initailly Total and Filtered count will be same
        $rescheck = '';
        $rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data, $rescheck, $this->full_table);
        $rows_count = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $rows_count;
        if (!empty($rows)) {
            $res_json = array();
            foreach ($rows as $row) {
                $actions_base_url = 'rudra_booking/post_actions';
                $actions_query_string = "?id= $row->id.'&target_table='.$row->id";
                $form_data_url = 'rudra_booking/post_actions';
                $action_url = 'rudra_booking/post_actions';
                $sucess_badge =  "class=\'badge badge-success\'";
                $danger_badge =  "class=\'badge badge-danger\'";
                $info_badge =  "class=\'badge badge-info\'";
                $warning_badge =  "class=\'badge badge-warning\'";

                $actions_button = "<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a> ";
                $actions_button .= "<a href='" . base_url('rudra_booking/view/' . $row->id) . "' class='label label-primary text-white f-12'>View</a> ";

                $row->actions = $actions_button;

                //JOINS LOGIC

                $fk_car_reg_id = $this->db->get_where('rudra_user_registered_car_numbers', array('id' => $row->fk_car_reg_id))->row();
                $row->fk_car_reg_id = (!empty($fk_car_reg_id) ? $fk_car_reg_id->id : '--');

                $user = userList($type = "1", $id = $row->user_id);
                $row->u_name = "";
                if (isset($user['name']))
                    $row->u_name = $user['name'].' '.$user['surname'];

                $row->washer_name = "";
                $washer = userList($type = "2", $id = $row->washer_id);
                if (isset($washer['id']))
                    $row->washer_name = $washer['name'].' '.$washer['surname'];

                $row->wash_status = (isset($row->wash_status) && $row->wash_status != "") ? washStatus($id = $row->wash_status) : "";
                $row->status = (isset($row->status) && $row->status != "") ? statusDropdown($id = $row->status) : "";
                $row->is_deleted = (isset($row->is_deleted) && $row->is_deleted != "") ? isDeletedDropdown($id = $row->is_deleted) : "";
                $row->wash_date = date('F d, Y', strtotime($row->wash_date));

                    if ($row->is_new == '1'){
                        $row->booking_id = "<span style='color:red'>".$row->booking_id."</span>";
                    }else{
                        $row->booking_id = $row->booking_id;
                    }

                $data[] = $row;
            }
        } else {
            $data = array();
        }
        $json_data = array(
            'draw'           => intval($this->input->post('draw')),
            'recordsTotal'    => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data'           => $data
        );
        echo json_encode($json_data);
    }

    public function viewDetail($id)
    {
        $data = $this->bookingInfo($user_id = "", $booking_id = $id, $page = "1", $filter_where = "", $washer_id = "");

        $data['payments_history'] = [];

        $pay = $this->db->where(['fk_booking_id' => $id])->get('rudra_payments');
        if ($pay->num_rows() > 0) {
            $data['payments_history']  = $pay->row_array();
        }
        $data['pageTitle'] = ' View Booking';
        $data['page_template'] = '_rudra_booking_view';
        $data['page_header'] = 'View Booking';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function bookingInfo($user_id = "", $booking_id = "", $page = "1", $filter_where = "", $washer_id = "")
    {
        $limit = "25";
        $start = "0";
        if ($page > 1) $start = ($page - 1) * $limit;

        $where = "WHERE `b`.`status` = '1'  ";

        if ($user_id != "") $where .= " AND `b`.`user_id` = '" . $user_id . "' ";

        if ($washer_id != "") $where .= " AND `b`.`washer_id` = '" . $washer_id . "' ";

        if ($booking_id != "") $where .= " AND `b`.`id` = '" . $booking_id . "' ";

        if ($filter_where != "") $where .= " " . $filter_where . " ";

        $sql = "SELECT `b`.*, `reg_cars`.`registration_number` `registered_car_number`,  `reg_cars`.`name` `car_name`,  `reg_cars`.`brand` `car_brand`,  `reg_cars`.`model` `car_model`, `reg_cars`.`color` `car_color`, `reg_cars`.`car_size`, `reg_cars`.`description` `car_description`, `wt`.`wash_name` `wash_type_name`, u.name, u.surname, u.contact_number FROM `rudra_booking` AS `b` LEFT JOIN `rudra_user_registered_car_numbers` AS `reg_cars` ON `b`.`fk_car_reg_id` = `reg_cars`.`id` LEFT JOIN `rudra_wash_types` AS `wt` ON `b`.`wash_id` = `wt`.`id` LEFT JOIN `rudra_user` `u` ON `b`.`user_id` = `u`.`id` " . $where . " ORDER BY `b`.`created_at` DESC LIMIT $start, $limit";
        $bookings = $this->db->query($sql);

        $sql1 = "SELECT `b`.*, `reg_cars`.`registration_number` `registered_car_number`,  `reg_cars`.`name` `car_name`,  `reg_cars`.`brand` `car_brand`,  `reg_cars`.`model` `car_model`, `reg_cars`.`color` `car_color`, `reg_cars`.`car_size`, `reg_cars`.`description` `car_description`, `wt`.`wash_name` `wash_type_name` FROM `rudra_booking` AS `b` LEFT JOIN `rudra_user_registered_car_numbers` AS `reg_cars` ON `b`.`fk_car_reg_id` = `reg_cars`.`id` LEFT JOIN `rudra_wash_types` AS `wt` ON `b`.`wash_id` = `wt`.`id` LEFT JOIN `rudra_user` `u` ON `b`.`user_id` = `u`.`id` " . $where . " ORDER BY `b`.`created_at` DESC";
        $bookings_count = $this->db->query($sql1);
        $total_result_count = $bookings_count->num_rows();

        $return['bookings'] = [];
        $wash_status = ['1' => 'Pending', '2' => 'Approved', '3' => 'Done'];
        if ($bookings->num_rows() > 0) {
            foreach ($bookings->result() as $key => $book) {
                $info['wash_id'] = (isset($book->id) && $book->id != "") ? $book->id : "";
                $info['user_id'] = (isset($book->user_id) && $book->user_id != "") ? $book->user_id : "";
                $info['user_name'] = (isset($book->name) && $book->name != "") ? $book->name.' '.$book->surname : "";
                $info['phone'] = (isset($book->contact_number) && $book->contact_number != "") ? $book->contact_number : "";
                $info['booking_id'] = (isset($book->booking_id) && $book->booking_id != "") ? "#" . $book->booking_id : "";
                $info['zipcode'] = (isset($book->zipcode) && $book->zipcode != "") ? $book->zipcode : "";
                $info['latitude'] = (isset($book->latitude) && $book->latitude != "") ? $book->latitude : "";
                $info['longitude'] = (isset($book->longitude) && $book->longitude != "") ? $book->longitude : "";
                $info['address'] = (isset($book->address) && $book->address != "") ? $book->address : "";

                /* vehicle info */
                $info['car_id'] = (isset($book->fk_car_reg_id) && $book->fk_car_reg_id != "") ? $book->fk_car_reg_id : "";
                $info['car_number'] = (isset($book->registered_car_number) && $book->registered_car_number != "") ? $book->registered_car_number : "";
                $info['car_name'] = (isset($book->car_name) && $book->car_name != "") ? $book->car_name : "";
                $info['car_brand'] = (isset($book->car_brand) && $book->car_brand != "") ? $book->car_brand : "";
                $info['car_model'] = (isset($book->car_model) && $book->car_model != "") ? $book->car_model : "";
                $info['car_color'] = (isset($book->car_color) && $book->car_color != "") ? $book->car_color : "";
                $info['car_description'] = (isset($book->car_description) && $book->car_description != "") ? $book->car_description : "";
                $info['car_size'] = (isset($book->car_size) && $book->car_size != "") ? $book->car_size : "";
                $info['car_size_definition'] = (isset($book->car_size) && $book->car_size != "") ? CarSizeType($id = $book->car_size) : "";
                // $info['vehicle_info'] = $vehicle_info;
                /* vehicle info */

                $info['wash_type_id'] = (isset($book->wash_id) && $book->wash_id != "") ? $book->wash_id : "";
                $info['wash_type_name'] = (isset($book->wash_type_name) && $book->wash_type_name != "") ? $book->wash_type_name : "";
                $info['extra_wash_type_ids'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? $book->extra_wash_type_ids : "";
                $info['extra_wash_type_ids_array'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? json_decode($book->extra_wash_type_ids) : [];
                $info['extra_wash_type_ids_definition'] = [];
                if (is_array($info['extra_wash_type_ids_array']) && !empty($info['extra_wash_type_ids_array'])) {
                    $this->db->where_in('id', $info['extra_wash_type_ids_array']);
                    $extras = $this->db->get($this->table_wash_extra_types);
                    $added_extras =  [];
                    if ($extras->num_rows() > 0) {
                        foreach ($extras->result() as $key => $e) {
                            $added_extras[] = ['id' => $e->id, 'name' => $e->extra_name, 'amount' => $e->amount];
                        }
                        $info['extra_wash_type_ids_definition'] = $added_extras;
                    }
                }
                $info['wash_date'] = (isset($book->wash_date) && $book->wash_date != "") ? $book->wash_date : "";
                $info['wash_date_a'] = (isset($book->wash_date) && $book->wash_date != "") ? date('F d, Y', strtotime($book->wash_date)) : "";
                $info['wash_time'] = (isset($book->wash_time) && $book->wash_time != "") ? $book->wash_time : "";
                $info['wash_time_a'] = (isset($book->wash_time) && $book->wash_time != "") ? date('h:i A', strtotime($book->wash_time)) : "";
                $info['notes'] = (isset($book->notes) && $book->notes != "") ? $book->notes : "";
                $info['wash_status_id'] = (isset($book->wash_status) && $book->wash_status != "") ? $book->wash_status : "";
                $info['wash_status_definition'] = (isset($wash_status[$book->wash_status]) && $wash_status[$book->wash_status] != "") ? $wash_status[$book->wash_status] : "Pending";
                $info['extra_charges'] = (isset($book->extra_charges) && $book->extra_charges != "") ? $book->extra_charges : "";
                $info['vat'] = (isset($book->vat) && $book->vat != "") ? $book->vat : "";
                $info['vat_percentage'] = (isset($book->vat_percentage) && $book->vat_percentage != "") ? $book->vat_percentage : "";
                // $info['wash_type_json'] = (isset($book->wash_type_json) && $book->wash_type_json != "") ? $book->wash_type_json : "";
                $info['wash_amount'] = (isset($book->wash_amount) && $book->wash_amount != "") ? $book->wash_amount : "";
                // $info['extra_wash_type_json'] = (isset($book->extra_wash_type_json) && $book->extra_wash_type_json != "") ? $book->extra_wash_type_json : "";
                $info['extra_wash_amount'] = (isset($book->extra_wash_amount) && $book->extra_wash_amount != "") ? $book->extra_wash_amount : "";
                $info['total_pay'] = (isset($book->total_pay) && $book->total_pay != "") ? $book->total_pay : "";
                // $info['status'] = (isset($book->status) && $book->status != "") ? $book->status : "";
                // $info['is_deleted'] = (isset($book->is_deleted) && $book->is_deleted != "") ? $book->is_deleted : "";
                $info['created_at'] = (isset($book->created_at) && $book->created_at != "") ? date('F d, Y', strtotime($book->created_at)) : "";
                // $info['updated_at'] = (isset($book->updated_at) && $book->updated_at != "") ? $book->updated_at : "";

                $info['comments'] = [];
                $this->db->where(['status' => '1', 'is_deleted' => '0', 'fk_wash_id' => $info['wash_id']]);
                $wash_comments = $this->db->get($this->table_wash_comments);
                if ($wash_comments->num_rows() > 0) {
                    $comments = [];
                    foreach ($wash_comments->result() as $key => $c) {
                        $commented_by = ($user_id == $c->fk_user_id) ? "You" : "Washer";
                        $comments[] = ['comment_id' => $c->id, 'commented_by' => $commented_by, 'comment' => $c->comment];
                    }
                    $info['comments'] = $comments;
                }


                /* qa_uploads */
                $this->db->where(['fk_booking_id' => $info['wash_id'], 'status' => '1', 'is_deleted' => '0']);
                $qa_uploads = $this->db->get($this->table_qa_uploads);
                $info['qa_uploads'] = [];
                if ($qa_uploads->num_rows() > 0) {
                    foreach ($qa_uploads->result() as $key => $qa) {
                        $info['qa_uploads'][] = base_url($qa->file_path . $qa->file_name);
                    }
                }
                /* qa_uploads */

                $info['washerdata'] = array();
                if($book->washer_id){
                    $info['washerdata'] = $this->db->get_where('rudra_user', array('id' => $book->washer_id))->row();
                }

                $return['bookings'][] = $info;
            }
        }

        $total_pages = ceil($total_result_count / $limit);
        $return['total_pages_available'] =  strval($total_pages);
        $return['current_page'] = $page;
        $return['results_per_page'] = $limit;

        return $return;
    }

    public function post_actions($param1)
    {
        // main index codes goes here
        $action = (isset($_GET['act']) ? $_GET['act'] : $param1);
        $id = (isset($_GET['id']) ? $_GET['id'] : 0);
        $this->return_data['status'] = true;
        $col_json = $this->db->get_where($this->bdp . 'crud_master_tables', array('tbl_name' => $this->full_table))->row();
        $data['col_json'] = $col_json;
        $json_cols = json_decode($data['col_json']->col_strc);
        //Get Data Methods
        if ($action == "get_data") {
            $data['id'] = $id;
            foreach ($json_cols as $ck => $cv) {
                if ($cv->form_type == 'ddl') {
                    $data[$cv->col_name] = $cv->ddl_options;
                }

                //Foreign Key Logics
                if ($cv->f_key) {
                    $ref_table_name = $cv->ref_table;
                    $data[$cv->col_name] = $this->db->get($ref_table_name)->result();
                }
            }

            $data['form_data'] = $this->db->get_where($this->full_table, array('id' => $id))->row();
            $html = $this->load->view("crm/forms/_ajax_rudra_booking_form", $data, TRUE);
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if ($action == "update_data") {
            if (!empty($_POST)) {
                $id = $_POST['id'];

                $washer_id = $this->input->post('washer_id');
                $wash_status = $this->input->post('wash_status');
                $rowdata = $this->db->get_where($this->full_table, array('id' => $id))->row();
                if($washer_id){
                    if($rowdata->washer_id != $washer_id){
                        $wash_status = '2';
                        
                        $messagestr = "Din vask til booking-id #".$rowdata->booking_id." er blevet accepteret";
                        $messagestr2 = "Du accepterede vask til booking-id #".$rowdata->booking_id;
                        
                        sendPushNotifications($sending_ids = [strval($rowdata->user_id)], $message = $messagestr, $userid=$rowdata->user_id);
                        sendPushNotifications($sending_ids = [strval($washer_id)], $message = $messagestr2, $userid=$washer_id);

                    }
                }

                $updateArray =
                    array(
                        'id' => $this->input->post('id', true),
                        // 'user_id' => $this->input->post('user_id', true),
                        // 'booking_id' => $this->input->post('booking_id', true),
                        'zipcode' => $this->input->post('zipcode', true),
                        /* 'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true), */
                        'address' => $this->input->post('address', true),
                        'fk_car_reg_id' => $this->input->post('fk_car_reg_id', true),
                        'wash_id' => $this->input->post('wash_id', true),
                        'extra_wash_type_ids' => $this->input->post('extra_wash_type_ids', true),
                        'wash_date' => $this->input->post('wash_date', true),
                        'wash_time' => $this->input->post('wash_time', true),
                        'notes' => $this->input->post('notes', true),
                        'washer_id' => $this->input->post('washer_id', true),
                        'wash_status' => $wash_status,
                        /* 'vat_percentage' => $this->input->post('vat_percentage', true),
                        'vat' => $this->input->post('vat', true),
                        'extra_charges' => $this->input->post('extra_charges', true),
                        'wash_type_json' => $this->input->post('wash_type_json', true),
                        'wash_amount' => $this->input->post('wash_amount', true),
                        'extra_wash_type_json' => $this->input->post('extra_wash_type_json', true),
                        'extra_wash_amount' => $this->input->post('extra_wash_amount', true),
                        'total_pay' => $this->input->post('total_pay', true),
                        'transaction' => $this->input->post('transaction', true),
                        'pay_method' => $this->input->post('pay_method', true), */
                        'status' => $this->input->post('status', true),
                        'is_deleted' => '0',
                    );

                $this->db->where('id', $id);
                $this->db->update($this->full_table, $updateArray);
            }
        }
        //Insert Method
        if ($action == "insert_data") {
            $id = 0;
            if (!empty($_POST)) {
                $wash_status = $this->input->post('wash_status');
                $washer_id = $this->input->post('washer_id');
                $booking_id = $this->input->post('booking_id');
                $user_id = $this->input->post('user_id');

                if($washer_id){
                    $wash_status='2';
                }
                //Insert Codes goes here 
                $updateArray =
                    array(
                        'id' => $this->input->post('id', true),
                        'user_id' => $this->input->post('user_id', true),
                        'booking_id' => $this->input->post('booking_id', true),
                        'zipcode' => $this->input->post('zipcode', true),
                        'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true),
                        'address' => $this->input->post('address', true),
                        'fk_car_reg_id' => $this->input->post('fk_car_reg_id', true),
                        'wash_id' => $this->input->post('wash_id', true),
                        'extra_wash_type_ids' => $this->input->post('extra_wash_type_ids', true),
                        'wash_date' => $this->input->post('wash_date', true),
                        'wash_time' => $this->input->post('wash_time', true),
                        'notes' => $this->input->post('notes', true),
                        'washer_id' => $this->input->post('washer_id', true),
                        'wash_status' => $wash_status,
                        'vat_percentage' => $this->input->post('vat_percentage', true),
                        'vat' => $this->input->post('vat', true),
                        'extra_charges' => $this->input->post('extra_charges', true),
                        'wash_type_json' => $this->input->post('wash_type_json', true),
                        'wash_amount' => $this->input->post('wash_amount', true),
                        'extra_wash_type_json' => $this->input->post('extra_wash_type_json', true),
                        'extra_wash_amount' => $this->input->post('extra_wash_amount', true),
                        'total_pay' => $this->input->post('total_pay', true),
                        'transaction' => $this->input->post('transaction', true),
                        'pay_method' => $this->input->post('pay_method', true),
                        'status' => $this->input->post('status', true),
                        'is_deleted' => '0',
                    );

                $this->db->insert($this->full_table, $updateArray);
                $id = $this->db->insert_id();

                
                $messagestr1 = "Din reservation #" . $booking_id . "er oprettet";
                sendPushNotifications($sending_ids = [strval($user_id)], $message = $messagestr1, $userid=$user_id);

                if($washer_id){

                        $messagestr = "Din vask til booking-id #".$booking_id." er blevet accepteret";
                        $messagestr2 = "Du accepterede vask til booking-id #".$booking_id;
                        
                        sendPushNotifications($sending_ids = [strval($user_id)], $message = $messagestr, $userid=$user_id);
                        sendPushNotifications($sending_ids = [strval($washer_id)], $message = $messagestr2, $userid=$washer_id);

                }
            }
        }

        // Export CSV Codes 
        if ($action == "export_data") {
            $header = array();
            foreach ($json_cols as $ck => $cv) {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_booking') . '_' . date('d-m-Y') . ".csv";
            $fp = fopen('php://output', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            fputcsv($fp, $header);
            $result_set = $this->db->get($this->full_table)->result();
            foreach ($result_set as $k) {
                $row = array();
                foreach ($json_cols as $ck => $cv) {
                    $cl = $cv->col_name;
                    $row[] = $k->$cl;
                }
                fputcsv($fp, $row);
            }
        }
        echo json_encode($this->return_data);
        exit;
    }

    public function remove_new(){

        $updateArray =
                    array(
                        "is_new" => '0'
                    );
        $this->db->update($this->full_table, $updateArray);

                    echo "success";

    }

    public function get_new_bookings(){

        $query = "SELECT * FROM " . $this->full_table . " WHERE  `is_new`='1' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
		$result = $this->db->query($query)->num_rows();

        echo $result;
    }

}
