
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_user_crtl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_user';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model', 'crd');
        $this->load->model('rudra_user_rudra_model', 'rudram');
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
	//Rudra_user_crtl ROUTES
        $crud_master = $crm . "Rudra_user_crtl/";
        $route['rudra_user'] = $crud_master . 'index';
        $route['rudra_user/index'] = $crud_master . 'index';
        $route['rudra_user/list'] = $crud_master . 'list';
        $route['rudra_user/post_actions/(:any)'] = $crud_master.'post_actions/$1';
     **************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' User';
        $data['page_template'] = '_rudra_user';
        $data['page_header'] = ' User';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function add_user(){
        $data['pageTitle'] = ' Add User';
        $data['page_template'] = '_rudra_user_add';
        $data['page_header'] = ' Add User';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $orderArray = array('id', 'user_type', 'login_type', 'social_id', 'email', 'password', 'forgot_code', 'forgot_time', 'name', 'surname', 'contact_email', 'contact_number', 'lat', 'lon', 'address', 'zipcode', 'city', 'device_token', 'app_version', 'device_type', 'device_company', 'device_version', 'device_location', 'device_lat', 'device_lang', 'device_city', 'device_country', 'status', 'is_deleted',);
        $limit = html_escape($this->input->post('length'));
        $start = html_escape($this->input->post('start'));
        $order = '';
        $dir   = $this->input->post('order')[0]['dir'];
        $order   = $this->input->post('order')[0]['column'];
        $orderColumn = $orderArray[$order];
        $general_search = $this->input->post('search')['value'];
        $filter_data['general_search'] = $general_search;
        $input =  $this->input->post();
        $user_type_filter = (isset($input['user_type']) && $input['user_type'] != "") ? $input['user_type'] : "";
        if ($user_type_filter != "") {
            $filter_data['user_type'] = $user_type_filter;
        }
        $totalData = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $totalData; //Initailly Total and Filtered count will be same
        $rescheck = '';
        $rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data, $rescheck, $this->full_table);
        $rows_count = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $rows_count;
        if (!empty($rows)) {
            $res_json = array();
            foreach ($rows as $row) {
                $actions_base_url = 'rudra_user/post_actions';
                $actions_query_string = "?id= $row->id.'&target_table='.$row->id";
                $form_data_url = 'rudra_user/post_actions';
                $action_url = 'rudra_user/post_actions';
                $sucess_badge =  "class=\'badge badge-success\'";
                $danger_badge =  "class=\'badge badge-danger\'";
                $info_badge =  "class=\'badge badge-info\'";
                $warning_badge =  "class=\'badge badge-warning\'";

                $actions_button = "<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a>";
                if($row->user_type=='1'){
                    $actions_button .= '<a href="' . base_url('rudra_user/view/' . $row->id . '') . '" id="" class= "label label-primary f-12" href="#" role="button" target="_blank">View</a>';
                }else{
                    $actions_button .= '<a href="' . base_url('washer/view/' . $row->id . '') . '" id="" class= "label label-primary f-12" href="#" role="button" target="_blank">View</a>';   
                }
                $actions_button .= '<a href="' . base_url('rudra_chat/user?id=' . $row->id . '') . '" id="" class= "label label-primary f-12" href="#" role="button" target="_blank">Chat</a>';
                
                $row->actions = $actions_button;
                $row->name = $row->name.' '.$row->surname;
                $row->user_type = (isset($row->user_type) && $row->user_type != "") ? userType($row->user_type) : "NA";
                $row->status = (isset($row->status) && $row->status != "") ? statusDropdown($id = $row->status) : "";
                $row->is_deleted = (isset($row->is_deleted) && $row->is_deleted != "") ? isDeletedDropdown($id = $row->is_deleted) : "";
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
            $html = $this->load->view("crm/forms/_ajax_rudra_user_form", $data, TRUE);
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if ($action == "update_data") {
            if (!empty($_POST)) {
                $id = $_POST['id'];

                $check_email = $this->rudram->checkIfEMailExists($user_id = $id, $email = $this->input->post('email', true));
                if (empty($check_email)) {
                    $updateArray =
                        array(
                            // 'id' => $this->input->post('id', true),
                            'user_type' => $this->input->post('user_type', true),
                            // 'login_type' => $this->input->post('login_type', true),
                            // 'social_id' => $this->input->post('social_id', true),
                            'email' => $this->input->post('email', true),
                            'password' => sha1($this->input->post('password', true)),
                            // 'forgot_code' => $this->input->post('forgot_code', true),
                            // 'forgot_time' => $this->input->post('forgot_time', true),
                            'name' => $this->input->post('name', true),
                            'surname' => $this->input->post('surname', true),
                            'contact_email' => $this->input->post('contact_email', true),
                            'contact_number' => $this->input->post('contact_number', true),
                            // 'lat' => $this->input->post('lat', true),
                            // 'lon' => $this->input->post('lon', true),
                            'address' => $this->input->post('address', true),
                            'zipcode' => $this->input->post('zipcode', true),
                            'city' => $this->input->post('city', true),
                            // 'device_token' => $this->input->post('device_token', true),
                            // 'app_version' => $this->input->post('app_version', true),
                            // 'device_type' => $this->input->post('device_type', true),
                            // 'device_company' => $this->input->post('device_company', true),
                            // 'device_version' => $this->input->post('device_version', true),
                            // 'device_location' => $this->input->post('device_location', true),
                            // 'device_lat' => $this->input->post('device_lat', true),
                            // 'device_lang' => $this->input->post('device_lang', true),
                            // 'device_city' => $this->input->post('device_city', true),
                            // 'device_country' => $this->input->post('device_country', true),
                            'status' => $this->input->post('status', true),
                            'is_deleted' => '0',
                        );

                    $this->db->where('id', $id);
                    $this->db->update($this->full_table, $updateArray);
                } else {
                    $this->return_data['msg'] = "Email already exists.";
                    $this->return_data['status'] = false;
                }
            }
        }
        //Insert Method
        if ($action == "insert_data") {
            $id = 0;
            if (!empty($_POST)) {
                //Insert Codes goes here

                $check_email = $this->rudram->checkIfEMailExists($user_id = "0", $email = $this->input->post('email', true));
                if (empty($check_email)) {
                    $updateArray =
                        array(
                            // 'id' => $this->input->post('id', true),
                            'user_type' => $this->input->post('user_type', true),
                            // 'login_type' => $this->input->post('login_type', true),
                            // 'social_id' => $this->input->post('social_id', true),
                            'email' => $this->input->post('email', true),
                            'password' => sha1($this->input->post('password', true)),
                            // 'forgot_code' => $this->input->post('forgot_code', true),
                            // 'forgot_time' => $this->input->post('forgot_time', true),
                            'name' => $this->input->post('name', true),
                            'surname' => $this->input->post('surname', true),
                            'contact_email' => $this->input->post('contact_email', true),
                            'contact_number' => $this->input->post('contact_number', true),
                            // 'lat' => $this->input->post('lat', true),
                            // 'lon' => $this->input->post('lon', true),
                            'address' => $this->input->post('address', true),
                            'zipcode' => $this->input->post('zipcode', true),
                            'city' => $this->input->post('city', true),
                            // 'device_token' => $this->input->post('device_token', true),
                            // 'app_version' => $this->input->post('app_version', true),
                            // 'device_type' => $this->input->post('device_type', true),
                            // 'device_company' => $this->input->post('device_company', true),
                            // 'device_version' => $this->input->post('device_version', true),
                            // 'device_location' => $this->input->post('device_location', true),
                            // 'device_lat' => $this->input->post('device_lat', true),
                            // 'device_lang' => $this->input->post('device_lang', true),
                            // 'device_city' => $this->input->post('device_city', true),
                            // 'device_country' => $this->input->post('device_country', true),
                            'status' => $this->input->post('status', true),
                            'is_deleted' => '0',
                        );

                    $this->db->insert($this->full_table, $updateArray);
                    $id = $this->db->insert_id();
                } else {
                    $this->return_data['msg'] = "Email already exists.";
                    $this->return_data['status'] = false;
                }
            }
        }

        // Export CSV Codes 
        if ($action == "export_data") {
            $header = array();
            foreach ($json_cols as $ck => $cv) {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_user') . '_' . date('d-m-Y') . ".csv";
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

    public function viewDetail($id){

        $data['form_data'] = $this->db->get_where($this->full_table, array('id' => $id))->row();
        $data['car_data'] = $this->db->get_where('rudra_user_registered_car_numbers', array('fk_user_id' => $id))->result();
        $data['booking_data'] = $this->db->order_by('wash_date', 'desc')->get_where('rudra_booking', array('user_id' => $id))->result();
        $data['chat_data'] = $this->db->order_by('id', 'desc')->get_where('rudra_chat', array('fk_from_id' => $id))->result();

        $data['pageTitle'] = ' View User';
        $data['page_template'] = '_rudra_user_view';
        $data['page_header'] = 'View User';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);

    }


    public function washers(){

        $data['pageTitle'] = ' Washers';
        $data['page_template'] = '_rudra_washer_view';
        $data['page_header'] = ' Washers';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);

    }

    public function viewDetailwasher($id){

        $data['form_data'] = $this->db->get_where($this->full_table, array('id' => $id))->row();
        $data['bank_data'] = $this->db->get_where('rudra_washer_bank_info', array('fk_user_id' => $id))->row();
        $data['booking_data'] = $this->db->order_by('wash_date', 'desc')->get_where('rudra_booking', array('washer_id' => $id))->result();
        $data['chat_data'] = $this->db->order_by('id', 'desc')->get_where('rudra_chat', array('fk_from_id' => $id))->result();

        $data['pageTitle'] = ' View washer';
        $data['page_template'] = '_rudra_washer_viewDetail';
        $data['page_header'] = 'View washer';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);

    }
    
}
