<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crudmasterstatic extends CI_Controller
{
	private $admin_id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('crudmaster_model', 'crd');
		$this->bdp = $this->db->dbprefix;
		$this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
		$this->set_data();
		//Get All Tables and Insert into crudmaster table to genetate the CRUD Script
		$dbname =  $this->db->database;
		$get_tables = "SELECT table_name FROM information_schema.tables  WHERE table_schema ='$dbname'";
		$get_all_tables = $this->db->query($get_tables)->result();

		if (!empty($get_all_tables)) {
			foreach ($get_all_tables as $all_t) {
				$table_name = $all_t->table_name;
				$res_chk = $this->db->get_where('crud_master_tables', array('tbl_name' => $table_name))->row();
				if (empty($res_chk)) {
					$this->db->insert('crud_master_tables', array('tbl_name' => $table_name));
				}
			}
		}

		//$this->admin_id = 0; 
	}

	public function set_data()
	{
		if (!$this->session->userdata('rudra_admin_logged_in')) {
			$this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
			//echo  uri_string(). json_encode($this->return_data);exit;
			$return_url = uri_string();
			redirect(base_url("admin-login?req_uri=$return_url"), 'refresh');
		} else {
			$this->admin_id = $this->session->userdata('rudra_admin_id');
			$this->return_data = array('status' => false, 'msg' => 'Working', 'login' => true, 'data' => array());
		}
	}
	public function index()
	{

		$data['pageTitle'] = "CRUD Master STATIC";
		$data['page_template'] = "_crud_master_index";
		$data['page_header'] = 'Tables';
		$data['load_type'] = 'all';

		$this->load->view('crm/template', $data);
	}
	public function list()
	{
		//print_r($_POST);exit;
		/****** Posted Data From DataTable *************/

		$limit = html_escape($this->input->post('length'));
		$start = html_escape($this->input->post('start'));
		$order = "";
		$dir   = $this->input->post('order')[0]['dir'];
		$general_search = $this->input->post('search')['value'];
		$filter_data['general_search'] = $general_search;


		$totalData = $this->crd->count_all_tables($filter_data);
		$totalFiltered = $totalData; //Initailly Total and Filtered count will be same

		$rows = $this->crd->get_all_tables($limit, $start, $order, $dir, $filter_data);
		//echo $this->db->last_query();
		//print_r($rows);exit;
		$rows_count = $this->crd->count_all_tables($filter_data);
		$totalFiltered = $rows_count;

		if (!empty($rows)) {
			$res_json = array();

			foreach ($rows as $row) {
				//print_r($row);
				// For Confirmation Modal
				$actions_base_url = "crudmaster/post_actions";
				$actions_query_string = "?id=" . $row->id . '&target_table=' . $row->tbl_name;

				//for Form Modal
				$form_data_url = "crudmaster/post_actions";
				$action_url = "crudmaster/post_actions";
				//$action_url = "form_modal('$form_data_url','$action_url','md','Select Agency')";
				// Different Badges 
				$sucess_badge = " class=\'badge badge-success\'";
				$danger_badge = " class=\'badge badge-danger\'";
				$info_badge = " class=\'badge badge-info\'";
				$warning_badge = " class=\'badge badge-warning\'";

				$actions = array(
					'create_json' => array(
						'action' => "confirm_modal('$actions_base_url/create_json$actions_query_string')",
						'unique_id' => $row->id,
						'unique_name' => 'Create Json',
						'label_class' => 'text-danger',
					),
					'create_files' => array(
						'action' => "confirm_modal('$actions_base_url/create_files$actions_query_string')",
						'unique_id' => $row->id,
						'unique_name' => 'Create Files',
						'label_class' => 'text-danger',
					),
					'create_api_file' => array(
						'action' => "confirm_modal('$actions_base_url/create_api$actions_query_string')",
						'unique_id' => $row->id,
						'unique_name' => 'Create API Files',
						'label_class' => 'text-danger',
					),
					//create_api_file

				);
				//print_r($actions);exit;

				$actions_button = $this->get_dropdown_button('dropdown_button', $actions, 'warning');
				/******* Main Action Button Ends *********/

				$res_json['table_name'] = $row->tbl_name;
				$res_json['status'] = ($row->status == 1 ? "<span class='badge badge-danger'>Files Created</span>" : "<span class='badge badge-success'>Files Not Created</span>");


				$res_json['actions'] = $actions_button;

				$data[] = $res_json;
			}
		} else {
			$data = array();
		}

		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}


	public function post_actions($param1)
	{
		$action = (isset($_GET['act']) ? $_GET['act'] : $param1);
		$table_name = (isset($_GET['target_table']) ? $_GET['target_table'] : 0);
		$notid = (isset($_GET['id']) ? $_GET['id'] : 0);
		$this->return_data['status'] = true;
		if ($action == 'enable') {
			$res = $this->gm->flip_flop_status('user_posts', 'status', 'id', $notid);
			$this->return_data['data']['data'] = $res;
			$this->return_data['status'] = true;
			$this->return_data['msg'] = 'Post Status Changed';
		}
		//echo $action;exit;
		if ($action == 'create_files') {
			$rescheck = $this->db->get_where($this->bdp . 'crud_master_tables', array('id' => $notid))->row();
			//print_r($rescheck);exit;
			if (!empty($rescheck)) {
				$this->create_controller_file($table_name, $rescheck);
				$this->create_modal_file($table_name, $rescheck);
				$this->create_index_view($table_name, $rescheck);
				$this->create_add_edit_view($table_name, $rescheck);
				$this->db->where('id', $rescheck->id);
				$this->db->update('crud_master_tables', array('status' => 1));
			}
			$this->return_data['data']['data'] = array();
			$this->return_data['status'] = true;
			$this->return_data['msg'] = 'Post Status Changed';
		}

		if ($action == 'create_json') {
			//$tbl_desc = $this->db->query("DESCRIBE ".$table_name)->result();
			$tbl_desc = $this->db->query("SHOW FULL COLUMNS FROM " . $table_name)->result();
			if (!empty($tbl_desc)) {
				$json_array = array();
				foreach ($tbl_desc as $tbd) {
					$col_type = 'string';
					$form_type = "text";
					$required = '';
					$list = false;
					$p_key = false;
					$f_key = false;
					$small_list = false;
					$ref_table = '';
					$ddl_options = array();
					if (strpos($tbd->Type, 'int') !== false || strpos($tbd->Type, 'bigint') !== false) {
						$form_type = "number";
						if (strtolower($tbd->Field) == "id") {
							$form_type = "hidden";
							$required = "required";
							$p_key = true;
						}
						if (strpos($tbd->Field, 'fk_') !== false && $tbd->Comment != '') {
							$f_key = true;
							$ref_table = $tbd->Comment;
						}
						$col_type = 'int';
						$list = true;
					} elseif (strpos($tbd->Type, 'varchar') !== false || strpos($tbd->Type, 'text') !== false) {
						if ($tbd->Comment == 'file') {
							$col_type = 'varchar';
							$form_type = "file";
							$list = true;
						} else {
							$col_type = 'varchar';
							$form_type = "text";
							$list = true;
						}
					} elseif (strpos($tbd->Type, 'enum') !== false) {
						$col_type = 'enum';
						$form_type = "ddl";
						$list = true;
						$options = str_replace('enum(', '', $tbd->Type);
						$options = str_replace(')', '', $options);
						$options = str_replace("'", '', $options);
						$options = str_replace('"', '', $options);
						$ddl_options = explode(',', $options);
						if ($tbd->Comment == 'list') {
							$small_list = true;
						}
					} elseif (strpos($tbd->Type, 'timestamp') !== false) {
						$col_type = 'ddl';
						$form_type = "datetime";
					} elseif (strpos($tbd->Type, 'date') !== false) {
						$col_type = 'ddl';
						$form_type = "date";
					}

					if ($form_type != 'datetime') {
						$json_array[] = array(
							'col_name' => $tbd->Field,
							'col_type' => $col_type,
							'form_type' => $form_type,
							'required' => $required,
							'list' => $list,
							'list_caption' => ucwords(str_replace('_', ' ', $tbd->Field)),
							'p_key' => $p_key,
							'f_key' => $f_key,
							'ref_table' => $ref_table,
							'join_type' => 'inner',
							'ddl_options' => $ddl_options,
							'small_list' => $small_list,
						);
					}
				}
			}
			$get_tbl = $this->db->get_where($this->bdp . 'crud_master_tables', array('tbl_name' => $table_name))->row();
			if (!empty($get_tbl)) {
				$this->db->where('id', $get_tbl->id);
				$this->db->update($this->bdp . 'crud_master_tables', array('col_strc' => json_encode($json_array)));
			} else {
				$this->db->insert($this->db . 'crud_master_tables', array('col_strc' => json_encode($json_array), 'tbl_name' => $table_name));
			}
			$this->return_data['data']['data'] = $json_array;
		}



		//Create API Files
		if ($action == 'create_api') {
			$rescheck = $this->db->get_where($this->bdp . 'crud_master_tables', array('id' => $notid))->row();
			//print_r($rescheck);exit;
			if (!empty($rescheck)) {
				$this->create_api_file($table_name, $rescheck);
			}
			$this->return_data['data']['data'] = array();
			$this->return_data['status'] = true;
			$this->return_data['msg'] = 'Post Status Changed';
		}


		echo json_encode($this->return_data);
		exit;
	}

	public function create_modal_file($table_name, $rescheck)
	{
		$controller = 'application/models/';
		$cnt_name = $controller . ucfirst($table_name) . '_rudra_model.php';
		$myfile = fopen($cnt_name, "w") or die("Unable to open file!");
		$str = "<?php
                \ndefined('BASEPATH') or exit('No direct script access allowed');

                \nclass " . ucfirst($table_name) . "_rudra_model extends CI_Model
                \n{
                   
                    \n\tpublic function __construct()
                    \n\t{
                    \n\t\tparent::__construct();
                
                    \$this->bdp = \$this->db->dbprefix;
                    \$this->full_table = '" . $table_name . "';
                        
                    }"; // End of Constructor Method

		$str .= "\n\tpublic function get_table_data(\$limit,\$start,\$order,\$dir,\$filter_data,\$tbl_data,\$table_name)";
		$str .= "\n\t{";
		$str .= "\n\t\t\$table = \$this->full_table .' TBL';";
		//$str .= "\n\t\$query = \"SELECT * FROM \$table \" ";
		//$str .= "\n\t\$where = \" WHERE 1 = 1 \";";

		//Where Condition with all Columns

		$cols = json_decode($rescheck->col_strc);
		$strarray = "";
		$strcols = "";
		if (!empty($cols)) {
			$strarray = "\n\t \$where .= \" AND ( \";";
			$sep = '';
			$sep2 = '';
			foreach ($cols as $k => $v) {
				$strcols .= $sep2 . 'TBL.' . $v->col_name;
				$sep2 = ', ';
				$strarray .= "\n\t\$where .=  \" $sep TBL.$v->col_name LIKE '%\".\$filter_data['general_search'].\"%'\";";
				$sep = " OR ";
			}
			$strarray .= "\n\t \$where .= \" ) \";";
		}
		$str .= "\n\t\$query = \" SELECT $strcols\" ;";
		$str .= "\n\t\$query .= \"  FROM \$table \"; ";
		$str .= "\n\t\$where = \" WHERE 1 = 1 \";";
		$str .= $strarray;
		$str .= "\n\t\$order_by = (\$order == '' ? '' : ' ORDER BY '.\$order.\" \".\$dir);";
		$str .= "\n\t \$limit = \" LIMIT \".\$start.\" , \" .\$limit;";
		$str .= "\n\t\$query = \$query.\$where.\$order_by.\$limit;";
		$str .= "\n\t \$res = \$this->db->query(\$query)->result();";
		$str .= "\n\t return \$res;";
		$str .= "\n\t}"; // End of get_table_data Method
		$str .= "\n\n";
		$str .= "\n\tpublic function count_table_data(\$filter_data,\$table_name)";
		$str .= "\n\t\t{";
		$str .= "\n\t\t\$table = \$this->full_table .' TBL';";
		$str .= "\n\t\$query = \" SELECT COUNT(id) as cntrows\" ;";
		$str .= "\n\t\$query .= \"  FROM \$table \"; ";
		$str .= "\n\t\$where = \" WHERE 1 = 1 \";";
		$str .= $strarray;
		$str .= "\n\t\$query = \$query.\$where;";
		$str .= "\n\t \$res = \$this->db->query(\$query)->row();";
		$str .= "\n\t return \$res->cntrows;";
		$str .= "\n\t\t}"; // End of Count Method
		$str .= "\n\n}"; // End Of Class

		fwrite($myfile, $str);
		fclose($myfile);
	}

	public function create_controller_file($table_name, $rescheck)
	{


		$controller = 'application/controllers/crm/';
		$cnt_name = $controller . ucfirst($table_name) . '_crtl.php';
		$old_name = $cnt_name;
		$new_name = $controller . ucfirst($table_name) . '_crtl_' . time() . '.php';

		if (file_exists($old_name)) {
			rename($old_name, $new_name);
		}
		$rout = "/***********\n\t//" . ucfirst($table_name) . "_crtl ROUTES
        \$crud_master = \$crm . \"" . ucfirst($table_name) . "_crtl/\";
        \$route['$table_name'] = \$crud_master . 'index';
        \$route['$table_name/index'] = \$crud_master . 'index';
        \$route['$table_name/list'] = \$crud_master . 'list';
        \$route['$table_name/post_actions/(:any)'] = \$crud_master.'post_actions/$1';\n\t**************/";

		$menu_table = $this->bdp . 'menu';
		$check_menu = $this->db->get_where($menu_table, array('mn_controller' => $table_name))->row();
		//echo $this->db->last_query();exit;
		if (empty($check_menu)) {
			$crt_menu = array(
				'mn_name' => str_replace('_', ' ', ucfirst(str_replace('rudra', '', $table_name))),
				'mn_controller' => $table_name,
				'mn_status' => 'Active'
			);
			$this->db->insert($menu_table, $crt_menu);
		}

		$myfile = fopen($cnt_name, "w") or die("Unable to open file!");
		$list_code = $this->list_create($table_name, $rescheck);
		$edit_form = "crm/forms/_ajax_" . $table_name . "_form";
		$model_name = $table_name . '_rudra_model';
		//Preparing Stattic Insert and Update Array
		$cols = json_decode($rescheck->col_strc);
		$strarray = "";
		$files_upload_methods = "";
		if (!empty($cols)) {
			$strarray .= "\$updateArray = \n\t\t\t\tarray(";
			foreach ($cols as $k => $v) {
				if ($v->form_type != 'file') {
					$strarray .= "\n\t\t\t\t '$v->col_name' => \$this->input->post('$v->col_name',true),";
				}

				if ($v->form_type == 'file') {
					$files_upload_methods .= "if(isset(\$_FILES['$v->col_name']) && \$_FILES['$v->col_name']['name'] != '') 
            {
                \$bannerpath = 'uploads/intro_banner';
                \$thumbpath = 'uploads/intro_banner';
                \$config['upload_path'] = \$bannerpath;
                \$config['allowed_types'] = 'gif|jpg|png|jpeg|PNG|JPG|JPEG';
                \$config['encrypt_name'] = TRUE;
                \$this->load->library('upload', \$config);
                \$this->upload->initialize(\$config);
                if(!\$this->upload->do_upload('$v->col_name'))
                {
                    \$error = array('error' => \$this->upload->display_errors());
                    print_r(\$error);
                    exit('Errors');
                }
                else
                {
                    \$imagedata = array('image_metadata' => \$this->upload->data());
                    \$uploadedImage = \$this->upload->data();
                }
                \$up_array = array(
                                    '$v->col_name' => \$bannerpath . '/' . \$uploadedImage['file_name']
                                );
                \$this->db->where('id', \$id);
                \$this->db->update(\$this->full_table, \$up_array);
            }";
				}
			}
			$strarray .=  "\n\t\t\t\t);\n";
		}
		$str = "
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class " . ucfirst($table_name) . "_crtl extends CI_Controller
{                   
    public function __construct()
    {
        parent::__construct();                
        \$this->bdp = \$this->db->dbprefix;
        \$this->full_table = '" . $table_name . "';
        \$this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //\$this->set_data();
        \$this->base_id = 0;
        \$this->load->model('crudmaster_model','crd');
        \$this->load->model('$model_name','rudram');
        // Uncomment Following Codes for Session Check and change accordingly 
        /*
        if (!\$this->session->userdata('rudra_admin_logged_in'))
        {			
            \$this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
            \$return_url = uri_string();
            redirect(base_url(\"admin-login?req_uri=\$return_url\"), 'refresh');
        }
        else
        {
            \$this->admin_id = \$this->session->userdata('rudra_admin_id');
            \$this->return_data = array('status' => false, 'msg' => 'Working', 'login' => true, 'data' => array());
        }
        */
    }
    $rout

    public function index()
    {
        // main index codes goes here
        \$data['pageTitle'] = '" . ucwords(str_replace('_', ' ', str_replace('rudra', '', $table_name))) . "';                        
        \$data['page_template'] = '_" . $table_name . "';
        \$data['page_header'] = '" . ucwords(str_replace('_', ' ', str_replace('rudra', '', $table_name))) . "';
        \$data['load_type'] = 'all';                
        \$this->load->view('crm/template', \$data);
    }
    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        " . $list_code . "
    }
    public function post_actions(\$param1)
    {
        // main index codes goes here
        \$action = (isset(\$_GET['act']) ? \$_GET['act'] : \$param1);
		\$id = (isset(\$_GET['id']) ? \$_GET['id'] : 0);
		\$this->return_data['status'] = true;
        \$col_json = \$this->db->get_where(\$this->bdp.'crud_master_tables',array('tbl_name'=>\$this->full_table))->row(); 
        \$data['col_json'] = \$col_json;
        \$json_cols = json_decode(\$data['col_json']->col_strc);
        //Get Data Methods
        if(\$action == \"get_data\")
        {
            \$data['id'] = \$id;                           
            foreach(\$json_cols as \$ck => \$cv)
            {
                if(\$cv->form_type == 'ddl')
                {
                    \$data[\$cv->col_name] = \$cv->ddl_options;
                }

                //Foreign Key Logics
                if(\$cv->f_key)
                {
                    \$ref_table_name = \$cv->ref_table;
                    \$data[\$cv->col_name] = \$this->db->get(\$ref_table_name)->result();
                }
            }

            \$data['form_data'] = \$this->db->get_where(\$this->full_table,array('id'=>\$id))->row(); 
            \$html = \$this->load->view(\"$edit_form\", \$data, TRUE);
            \$this->return_data['data']['form_data'] = \$html;
        }
        // Post Methods
        //Update Codes
        if(\$action == \"update_data\")
        {
            if(!empty(\$_POST))
            {
                \$id = \$_POST['id'];
                $strarray
                \$this->db->where('id',\$id);
                \$this->db->update(\$this->full_table,\$updateArray);
            }
            $files_upload_methods
        }
        //Insert Method
        if(\$action == \"insert_data\")
        {
            \$id = 0;
            if(!empty(\$_POST))
            { 
                //Insert Codes goes here 
                $strarray
                \$this->db->insert(\$this->full_table,\$updateArray);
                \$id = \$this->db->insert_id();
            }
            $files_upload_methods
            
            
        }

        // Export CSV Codes 
        if(\$action == \"export_data\")
        {
            \$header = array();
            foreach(\$json_cols as \$ck => \$cv)
            {
                \$header[] = \$cv->list_caption;
            }
            \$filename = strtolower('$table_name').'_'.date('d-m-Y').\".csv\";
            \$fp = fopen('php://output', 'w');                         
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.\$filename);
            fputcsv(\$fp, \$header);
            \$result_set = \$this->db->get(\$this->full_table)->result();
            foreach(\$result_set as \$k)
            {  
                \$row = array();
                foreach(\$json_cols as \$ck => \$cv)
                {
                    \$cl = \$cv->col_name;                                    
                    \$row[] = \$k->\$cl;
                }                              
                fputcsv(\$fp,\$row);
            }
        }
        echo json_encode(\$this->return_data);
		exit;
    }
                    
}";
		fwrite($myfile, $str);
		fclose($myfile);
	}

	public function create_add_edit_view($table_name, $rescheck)
	{

		$file_name = "application/views/crm/forms/_ajax_" . $table_name . "_form.php";
		$cols = json_decode($rescheck->col_strc);

		$str = "";
		$js = "";
		if (!empty($cols)) {

			foreach ($cols as $k => $v) {
				if ($v->form_type != 'hidden' && $v->form_type != 'ddl') {
					if ($v->f_key) {
						$str .= "\n<?php \$options = \$$v->col_name; ?>";
						$str .= "\n<div class=\"form-group col-sm-6\">\n";
						$str .= "\n\t<label>$v->list_caption</label>\n";
						$str .= "\n\t<select $v->required id='$v->col_name'  name=\"$v->col_name\" class=\"form-control\" >";
						$str .= "\n<?php \n foreach(\$options as \$dk => \$dv)";
						$str .= " \n{";
						$str .= "\n\t \$selectop = '';";
						$str .= "\n\t if(!empty(\$form_data) && \$form_data->$v->col_name == \$dv->id)";
						$str .= "\n\t{";
						$str .= "\n\t\t\$selectop = 'selected=\"selected\"';";
						$str .= "  \n\t} ?>";
						$str .= " \n\t<option <?= \$selectop ?> value=\"<?= \$dv->id ?>\"  ><?= \$dv->id?></option>";
						$str .= "\n<?php  } ?>";

						$str .= "\n\t</select>\n</div>\n";
					} else {
						$str .= "<div class=\"form-group col-sm-6\">\n";
						$str .= "\t<label>$v->list_caption</label>\n";
						$str .= " \t<input type=\"$v->form_type\" $v->required name=\"$v->col_name\" class=\"form-control\" value=\"<?= (!empty(\$form_data) ? \$form_data->$v->col_name : ''); ?>\"   placeholder=\"$v->list_caption\"   />";
						$str .= "\n</div>\n";
					}
				} elseif ($v->form_type == 'hidden') {
					$str .= "<input type=\"hidden\" $v->required name=\"$v->col_name\" value=\"<?= (isset(\$id) ? \$id : 0); ?>\">\n";
				} elseif ($v->form_type == 'ddl') {
					$str .= "\n<?php \$options = \$$v->col_name; ?>";
					$str .= "\n<div class=\"form-group col-sm-6\">\n";
					$str .= "\n\t<label>$v->list_caption</label>\n";
					$str .= "\n\t<select $v->required id='$v->col_name'  name=\"$v->col_name\" class=\"form-control\" >";
					$str .= "\n<?php \n foreach(\$options as \$dk => \$dv)";
					$str .= " \n{";
					$str .= "\n\t \$selectop = '';";
					$str .= "\n\t if(!empty(\$form_data) && \$form_data->$v->col_name == \$dv)";
					$str .= "\n\t{";
					$str .= "\n\t\t\$selectop = 'selected=\"selected\"';";
					$str .= "  \n\t} ?>";
					$str .= " \n\t<option <?= \$selectop ?> value=\"<?= \$dv ?>\"  ><?= \$dv?></option>";
					$str .= "\n<?php  } ?>";

					$str .= "\n\t</select>\n</div>\n";
				}
			}
		}
		$str = "\n<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->\n<div class='row'>" . $str . "</div>\n<!--Uncomment if Scroll Required div -->\n";

		$str .=  $js;
		$myfile = fopen($file_name, "w") or die("Unable to open file!");
		fwrite($myfile, $str);
		fclose($myfile);
	}

	public function list_create($table_name, $rescheck)
	{
		//print_r($_POST);exit;
		//Creating Cols Array used for Ordering the table: ASC and Descending Order
		//If you change the Columns(of DataTable), please change here too
		$cols = json_decode($rescheck->col_strc);
		$str = "";
		$files_array = array();
		$joins = "";
		if (!empty($cols)) {
			$str .= "\$orderArray = array( ";
			foreach ($cols as $k => $v) {
				$str .= " '$v->col_name',";
				if ($v->f_key) {
					$joins .= "\n\t\t\t\t\$$v->col_name = \$this->db->get_where('$v->ref_table',array('id'=>\$row->$v->col_name))->row();";
					$joins .= "\n\t\t\t\t\$row->$v->col_name = (!empty($$v->col_name) ? $$v->col_name->id:'--');";
				}
			}
			$str .=  " );\n";
		}
		/****** Posted Data From DataTable *************/
		$actu = ucfirst($table_name);
		$str .= "\t\t\$limit = html_escape(\$this->input->post('length'));\n\t";
		$str .= "\t\$start = html_escape(\$this->input->post('start'));\n\t";
		$str .= "\t\$order = '';\n\t";
		$str .= "\t\$dir   = \$this->input->post('order')[0]['dir'];\n\t";
		$str .= "\t\$order   = \$this->input->post('order')[0]['column'];\n\t";
		$str .= "\t\$orderColumn = \$orderArray[\$order];\n\t";
		$str .= "\t\$general_search = \$this->input->post('search')['value'];\n\t";
		$str .= "\t\$filter_data['general_search'] = \$general_search;\n\t";


		$str .= "\t\$totalData = \$this->rudram->count_table_data(\$filter_data,\$this->full_table);\n\t";
		$str .= "\t\$totalFiltered = \$totalData; //Initailly Total and Filtered count will be same\n\t";
		$str .= "\t\t\$rescheck = '';\n\t";
		$str .= "\t\$rows = \$this->rudram->get_table_data(\$limit, \$start, \$orderColumn, \$dir, \$filter_data,\$rescheck,\$this->full_table);\n\t";
		//echo $this->db->last_query();
		//print_r($rows);exit;
		$str .= "\t\$rows_count = \$this->rudram->count_table_data(\$filter_data,\$this->full_table);\n\t";
		$str .= "\t\$totalFiltered = \$rows_count;\n\t";

		$str .= "\tif(!empty(\$rows))\n\t\t{\n\t\t";
		$str .= "\t\$res_json = array();\n\t";

		$str .= "\t\tforeach (\$rows as \$row)\n\t\t\t{\n\t";

		$str .= "\t\t\t\$actions_base_url = '$table_name/post_actions';\n\t";
		$str .= "\t\t\t\$actions_query_string = \"?id= \$row->id.'&target_table='.\$row->id\";\n\t";

		//for Form Modal
		$str .= "\t\t\t\$form_data_url = '$table_name/post_actions';\n\t";
		$str .= "\t\t\t\$action_url = '$table_name/post_actions';\n\t";
		// Different Badges 
		$str .= "\t\t\t\$sucess_badge =  \"class=\'badge badge-success\'\";\n\t";
		$str .= "\t\t\t\$danger_badge =  \"class=\'badge badge-danger\'\";\n\t";
		$str .= "\t\t\t\$info_badge =  \"class=\'badge badge-info\'\";\n\t";
		$str .= "\t\t\t\$warning_badge =  \"class=\'badge badge-warning\'\";\n\t";

		/* actions = array(
                    'delete' => array(
						'action' => "confirm_modal('$actions_base_url/create_files$actions_query_string')",
						'unique_id' => $row->id,
						'unique_name' => 'Create Files',
						'label_class' => 'text-danger',
					),
					
				); 
                $str .= "\$actions = array(\n\t";
               $str .= "'enable' => array(\n";
                   $str .="\n\t'action' => \"form_modal('\$form_data_url/get_data?id=\$row->id','\$action_url/update_data','md','Update Details')\",";
                    $str .= "\n\t'unique_id' => \$row->id,";
                    $str .= "\n\t'unique_name' => 'Edit',";
                    $str .= "\n\t'label_class' =>  'text-success',";
                $str .=  "\n\t),\n);";

				//print_r($actions);exit;

				$str .= "\n\$actions_button = \$this->get_dropdown_button('dropdown_button', \$actions, 'warning');\n";
                */
		$str .= "\n\t\t\t\t\$actions_button =\"<a id='edt\$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\\\"static_form_modal('\$form_data_url/get_data?id=\$row->id','\$action_url/update_data','md','Update Details')\\\" >Edit</a>\";";

		/******* Main Action Button Ends *********/

		// $res_json['table_name'] = $row->tbl_name;



		//$res_json['actions'] = $actions_button;
		$str .= "\n\t\t\t\t\$row->actions = \$actions_button;\n\t";
		$str .= "\n\t\t\t\t//JOINS LOGIC\n\t\t\t\t" . $joins;

		$str .= "\n\t\t\t\t\$data[] = \$row;\n";
		$str .= "\t\t\t}\n\t";
		$str .= "\t}\n\t\telse\n\t\t{\n\t";
		$str .= "\t\t\$data = array();\n\t";
		$str .= "\t}\n\t";

		$str .= "\t\$json_data = array\n\t\t\t(
			'draw'           => intval(\$this->input->post('draw')),
			'recordsTotal'    => intval(\$totalData),
			'recordsFiltered' => intval(\$totalFiltered),
			'data'           => \$data
            );\n\t";

		$str .= "\techo json_encode(\$json_data);\n\t";
		return $str;
	}

	public function create_index_view($table_name, $rescheck)
	{
		//for Form Modal
		//"confirm_modal('$actions_base_url/create_files$actions_query_string')",
		$form_data_url = $table_name . '/post_actions';
		$action_url = $table_name . '/post_actions';
		$onclk = "onclick=\"static_form_modal('$form_data_url/get_data?id=0','$action_url/insert_data','md','Update Details')\"";
		//$csv_import ="onclick=\"confirm_modal('$action_url/export_data?id=0')\"";
		$csv_import = "href=\"$action_url/export_data\"";

		$view_path = "application/views/crm/_" . $table_name . ".php";
		$strf = "<?php \n\$this->load->view('crm/_crud_master_header'); \n?>\n";
		$header = "<link rel=\"stylesheet\" href=\"<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css\" />";
		$header .= " <link rel=\"stylesheet\" href=\"<?= base_url('app_') ?>assets/css/style.css\" />";
		$header .= '
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [Total-user section] start -->
            <!--div class="col-sm-12">
                <div class="card text-left">
                    <div class="card-body">
                        <div class="col-sm-3 float-right">
                            <div class="input-daterange input-group " id="datepicker_range">
                                <input type="text" class="form-control text-left" placeholder="Start date" id="start_date_stat" name="start">
                                <input type="text" class="form-control text-right" placeholder="End date" id="end_date_stat" name="end">
                            </div>
                        </div>
                    </div>
                </div>
            </div-->
            <div class="clear-fix clearfix"></div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                    <div class="row">
                        <div class="col-sm-5">
                        <h5><?= $page_header ?></h5>
                        </div>
                            <div class="col-sm-7 text-right">
                                <button ' . $onclk . ' type="button" class="btn btn-glow-success btn-info" title="" data-toggle="tooltip" data-original-title="Add New Item" aria-describedby="tooltip651610">New</button>&nbsp;
                                <a ' . $csv_import . ' type="button" class="btn btn-glow-danger btn-warning" title="" data-toggle="tooltip" data-original-title="Export CSV" aria-describedby="tooltip651610">CSV</a>
                            
                                </div> 
                            </div> 
                        </div>

                    <div class="card-block">
                        <div class="table-responsive">
                            <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                                <thead>';
		$str = $header;
		$cols = json_decode($rescheck->col_strc);
		if (!empty($cols)) {
			$str .= "<tr>\n";
			foreach ($cols as $k => $v) {
				if ($v->list) {
					$thead = $v->list_caption;
					if ($v->f_key) {
						$theads = explode('_', $v->col_name);
						$thead = ucwords($theads[1]);
					}
					$str .= "<th>" . $thead . "</th>\n";
				}
			}

			$str .= "<th>Actions</th>\n</tr>\n";
		}
		$str .= " </thead>\n";
		$str .= "  </table>\n";
		$str .= "</div>\n";
		$str .= "</div>\n";
		$str .= "</div>\n";
		$str .= "</div>\n";
		$str .= "</div>\n";

		$str .= "\n<!--- FORM Modal Ends ---->\n  <div class=\"md-modal md-effect-11 dynamic-modal\" id=\"form_modal_$table_name\">
       <div class=\"modal-content\">
           <div class=\"modal-header theme-bg2 \">
               <h5 class=\"modal-title text-white\" id=\"heading_$table_name\">Loading...</h5>
               <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
           </div>
           <form id=\"frm_$table_name\" method=\"post\" enctype=\"multipart/form-data\">
               <div class=\"modal-body\" id=\"modal_form_data_$table_name\"> </div>
             ";

		$str .= " \n<div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
        <button type=\"submit\" disabled class=\"btn btn-primary btn-modal-form\">Update</button>
    </div>
</form>
</div>
</div>
<!--- FORM Modal Ends ---->\n";
		$str .= " \n\n<script src=\"<?= base_url('app_') ?>assets/plugins/data-tables/js/datatables.min.js\"></script>\n";

		$str .= "<script type=\"text/javascript\">\n";
		$str .= "var load_type = '<?= \$load_type ?>';\n";
		$str .= "var search_json = new Object();\n";
		$str .= "search_json.load_type = load_type;\n";
		//console.log(search_json);

		$str .= "$('#ddlAgencyFilter').change(function() {\n";

		$str .= "\t$('.user_post').DataTable().ajax.reload();\n";
		//console.log(search_json);
		$str .= "});\n";

		$str .= " $('#start_date_stat').change(function() {\n";
		$str .= " if($(this).val() != '')\n\t{\n";

		$str .= "\t$('.user_post').DataTable().ajax.reload();\n\t}\n";


		//console.log(search_json);
		$str .= " });\n";

		$str .= " $('#end_date_stat').change(function() {\n";
		$str .= " if($(this).val() != '')\n\t{";

		$str .= "\t $('.user_post').DataTable().ajax.reload()\n};\n";

		//console.log(search_json);
		$str .= " });\n";

		$str .= " $.fn.dataTable.ext.errMode = 'throw';\n";
		$str .= "var usrTable;\n";

		$str .= "fill_server_data_table(search_json);\n";
		$cn_name = ucfirst($table_name);
		$lsturl = $table_name;
		$str .= "function fill_server_data_table(search_json) {\n";
		$str .= "\tusrTable = $('.user_post').DataTable({\n";
		$str .= "\"processing\": true,\n";
		$str .= "\"serverSide\": true,\n";
		$str .= " fixedHeader: true,\n";
		$str .= "responsive: true,\n";
		$str .= "\"ajax\": {\n";
		$str .= "\t\"url\": \"<?php echo base_url('$lsturl/list') ?>\",\n";
		$str .= "\t\"dataType\": \"json\",\n";
		$str .= "\t\"type\": \"POST\",\n";
		$str .= "\t\"data\": {\n";
		$str .= "\tsearch_json: search_json,\n";
		$str .= "\t start_date: function() {\n";
		$str .= "\treturn $('#start_date_stat').val()\n";
		$str .= " },\n";
		$str .= "\tend_date: function() {\n";
		$str .= "return $('#end_date_stat').val()\n";
		$str .= " \t},\n";
		$str .= "  }\n";
		$str .= " },";
		$str .= "\t \"columns\": [\n";
		if (!empty($cols)) {

			foreach ($cols as $k => $v) {
				if ($v->list) {
					$str .= "{\n\t\t \"data\": \"$v->col_name\"\n";
					$str .= " },\n";
				}
			}
		}




		$str .= "\t {\n";
		$str .= "\t\t \"data\": \"actions\"\n";
		$str .= "},\n";
		$str .= " ],\n";
		$str .= " \"columnDefs\": [{\n";
		$str .= " targets: \"_all\",\n";
		$str .= " orderable: true\n";
		$str .= " }]\n";
		$str .= "  });\n";

		$str .= " }\n";
		$str .= "</script>\n";
		$str .= $this->create_modal_js_files($table_name);
		$myfile = fopen($view_path, "w") or die("Unable to open file!");
		fwrite($myfile, $str);
		fclose($myfile);
	}

	public function create_modal_js_files($table_name)
	{
		$str = "\n<!-- Form Scripts Starts Here -->\n\n<script type='text/javascript'>";
		$str .= "\n\nfunction static_form_modal(data_url, action_url, mtype, heading) {
            $(\"#form_modal_$table_name\").modal();
            $(\"#form_modal_$table_name\").addClass('md-show');
            $(\"#heading_$table_name\").text(heading);
            $(\"#frm_$table_name\").attr(\"action\", '<?= base_url() ?>' + action_url);
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>' + data_url,
                data: {},
                success: function(data) {
                    response = JSON.parse(data);
                    if (response.status) {
                        jsonData = JSON.parse(JSON.stringify(response.data));
                        //console.log(jsonData);
                        $(\"#modal_form_data_$table_name\").html(jsonData.form_data);
                        $(\".btn-modal-form\").prop(\"disabled\", false);
                    } else {
                        $(\".btnlogin\").prop('disabled', false);
                        $(\".btnlogin\").addClass('btn-success');
                        $(\".btnlogin\").removeClass('btn-warning');
                        $(\".btnlogin\").html('Login');
                    }
                }
            });
        }";

		$str .= "\n\n$(\"#frm_$table_name\").submit(function(e) {
            e.preventDefault();
            $(\".btn-modal-form\").prop('disabled', true);
            $(\".btn-modal-form\").html('Wait...');
            var formData = new FormData($(\"#frm_$table_name\")[0]);
            var action_url = $(\"#frm_$table_name\").attr(\"action\");
            $.ajax({
                type: 'POST',
                url: action_url,
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(data) {
                    response = JSON.parse(data);
                    if (response.text) {
                        loadTextData();
                        $(\".btn-modal-form\").prop('disabled', false);
                        $(\".btn-modal-form\").html('Update');
                        $('.dynamic-modal').modal('hide');
                        $(\"#static_form_modal\").modal('hide');
                        //setTimeout(function(){ window.location = response.data.url; }, 3000);
                    } else if (response.status) {
                        $(\".btn-modal-form\").prop('disabled', false);
                        $(\".btn-modal-form\").html('Update');
                        usrTable.ajax.reload(null, false);
                        $('.dynamic-modal').modal('hide');
                        //setTimeout(function(){ window.location = response.data.url; }, 3000);
                    } else {
                        $(\".btn-modal-form\").prop('disabled', false);
                        $(\".btn-modal-form\").html('Update');
                    }
    
                }
            });
        });";
		$str .= "\n</script>";
		return $str;
	}


	// Create API Controllers

	public function create_api_file($table_name, $rescheck)
	{


		$controller = 'application/controllers/auto_scripts/';
		$cnt_name = $controller . ucfirst($table_name) . '_apis.php';
		//Check for Existing File, Rename OLD and Create New
		$old_name = $cnt_name;
		$new_name = $controller . ucfirst($table_name) . '_apis_' . time() . '.php';
		if (file_exists($old_name)) {
			rename($old_name, $new_name);
		}

		$myfile = fopen($cnt_name, "w") or die("Unable to open file!");
		$model_name = 'global_modeli';
		$rr = str_replace('rudra_', '', $table_name);
		//Preparing Stattic Insert and Update Array
		$cols = json_decode($rescheck->col_strc);
		$strarray = "";
		$str_required = "";
		$str_required_update = "";
		$files_upload_methods = "";
		$small_list_options = array();
		$std = "\$std = date('Y-m-d H:i:s');";
		if (!empty($cols)) {
			$strarray .= "\$updateArray = \n\t\t\t\t\tarray(";
			foreach ($cols as $k => $v) {
				if ($v->col_name != 'id') {
					if ($v->col_name == 'added_at' || $v->col_name == 'updated_at') {
						$std = "\$std = date('Y-m-d H:i:s');";
						$strarray .= "\n\t\t\t\t\t '$v->col_name' => \$this->input->post(\$std,true),";
					} else {
						$strarray .= "\n\t\t\t\t\t '$v->col_name' => \$this->input->post('$v->col_name',true),";
					}
				}

				if ($v->col_name != 'id' && $v->col_name != 'added_at' && $v->col_name != 'updated_at') {
					$str_required .= "\n\t\t\t\$this->form_validation->set_rules('$v->col_name', '$v->col_name', 'required');";
				}
				if ($v->col_name != 'added_at' && $v->col_name != 'updated_at') {
					$str_required_update .= "\n\t\t\t\$this->form_validation->set_rules('$v->col_name', '$v->col_name', 'required');";
				}

				if ($v->small_list) {
					$small_list_options[$v->col_name] = $v->ddl_options;
				}

				if ($v->form_type == 'file') {
					$files_upload_methods .= "\n\t\t\t\t\tif(isset(\$_FILES['$v->col_name']) && \$_FILES['$v->col_name']['name'] != '') 
                    {
                        \$bannerpath = 'uploads/intro_banner';
                        \$thumbpath = 'uploads/intro_banner';
                        \$config['upload_path'] = \$bannerpath;
                        \$config['allowed_types'] = 'gif|jpg|png|jpeg|PNG|JPG|JPEG';
                        \$config['encrypt_name'] = TRUE;
                        \$this->load->library('upload', \$config);
                        \$this->upload->initialize(\$config);
                        if(!\$this->upload->do_upload('$v->col_name'))
                        {
                            \$error = array('error' => \$this->upload->display_errors());
                            print_r(\$error);
                            exit('Errors');
                        }
                        else
                        {
                            \$imagedata = array('image_metadata' => \$this->upload->data());
                            \$uploadedImage = \$this->upload->data();
                        }
                        \$up_array = array(
                                            '$v->col_name' => \$bannerpath . '/' . \$uploadedImage['file_name']
                                        );
                        \$this->db->where('id', \$new_id);
                        \$this->db->update(\$this->full_table, \$up_array);
                    }";
				}
			}
			$strarray .=  "\n\t\t\t\t\t);\n";
			//print_r($small_list_options);exit;
		}
		$str = "
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class " . ucfirst($table_name) . "_apis extends CI_Controller
{                   
    
    private \$api_status = false;
	public function __construct()
    {
        parent::__construct();
		\$this->load->library('form_validation');
		\$this->bdp = \$this->db->dbprefix;
        \$this->table = '$table_name';
		\$this->msg = 'input error';
		\$this->return_data = array();
        \$this->chk = 0;
		//\$this->load->model('global_model', 'gm');
		\$this->set_data();
		
	}
	public function set_data()
    {
        \$method = \$_SERVER['REQUEST_METHOD'];
		if(\$method != 'POST'){
			\$this->json_output(200,array('status' => 200,'api_status' => false,'message' => 'Bad request.'));exit;
		} 
		
        /*
        \$api_key = \$this->db->get_where(\$this->bdp.'app_setting',array('meta_key' =>'rudra_key'))->row();
        \$api_password =  \$this->input->post('api_key',true);
        if (MD5(\$api_key->meta_value) == \$api_password) {

            \$this->api_status = true;
          
        } else {
           
		json_encode(array('status' => 505,'message' => 'Enter YourgMail@gmail.com to get access.', 'data' => array() ));
		  exit;
		  
          
        }
        */
    }

    /***********************Page Route
     
     //$table_name API Routes\n";
		$tt = 'auto_scripts/' . ucfirst($table_name) . '_apis';
		$str .= "\t\$t_name = '$tt/';    
	\$route[\$api_ver.'$rr/(:any)'] = \$t_name.'rudra_$table_name/$1';

    **********************************/
    function json_output(\$statusHeader,\$response)
	{
		\$ci =& get_instance();
		\$ci->output->set_content_type('application/json');
		\$ci->output->set_status_header(\$statusHeader);
		\$ci->output->set_output(json_encode(\$response));
	}

    public function index()
	{
		\$this->json_output(200,array('status' => 200,'api_status' => false,'message' => 'Bad request.'));
	}

    public function rudra_$table_name(\$param1)
    {
        \$call_type = \$param1;
        \$res = array();
        if(\$call_type == 'put')
        {            
            \$res = \$this->rudra_save_data(\$_POST);        
        }
        elseif(\$call_type == 'update')
        {           
            \$res = \$this->rudra_update_data(\$_POST);        
        }
        elseif(\$call_type == 'get')
        {
            \$res = \$this->rudra_get_data(\$_POST);        
        }
        elseif(\$call_type == 'paged_data')
        {
            \$res = \$this->rudra_paged_data(\$_POST);        
        }
        elseif(\$call_type == 'setting_list')
        {
            \$res = \$this->rudra_setting_list_data(\$_POST);        
        }
        elseif(\$call_type == 'delete')
        {
            \$res = \$this->rudra_delete_data(\$_POST);        
        }

        \$this->json_output(200,array('status' => 200,'message' => \$this->msg,'data'=>\$this->return_data,'chk' => \$this->chk));

    }
    
    public function rudra_save_data()
    {     
        if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $str_required   
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
                $std
                //Insert Codes goes here 
                $strarray
                \$this->db->insert(\"\$this->table\",\$updateArray);
                \$new_id = \$this->db->insert_id();
                $files_upload_methods
                \$res = \$this->db->get_where(\"\$this->table\",array('id' => \$new_id ))->row();
                //Format Data if required
                /*********
                 \$res->added_on_custom_date = date('d-M-Y',strtotime(\$res->added_at));
                 \$res->added_on_custom_time = date('H:i:s A',strtotime(\$res->added_at));
                 \$res->updated_on_custom_date = date('d-M-Y',strtotime(\$res->updated_at));
                 \$res->updated_on_custom_time = date('H:i:s A',strtotime(\$res->updated_at));
                 ************/
                \$this->chk = 1;
                \$this->msg = 'Data Stored Successfully';
                \$this->return_data = \$res;
            }
        }
       
    }
    
    public function rudra_update_data()
    {
       if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $str_required_update
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
                \$new_id = \$pk_id = \$this->input->post('id');
                \$chk_data = \$this->db->get_where(\"\$this->table\",array('id' => \$pk_id))->row();
                if(!empty(\$chk_data))
                {
                    $std
                    //Update Codes goes here 
                    $strarray
                    \$this->db->where('id',\$pk_id);
                    \$this->db->update(\"\$this->table\",\$updateArray);
                    $files_upload_methods
                    \$this->chk = 1;
                    \$this->msg = 'Information Updated';
                    \$this->return_data = \$this->db->get_where(\"\$this->table\",array('id' => \$pk_id))->row();
                }
                else
                {
                    \$this->chk = 0;
                    \$this->msg = 'Record Not Found';   
                }
            }
        }
        
    }

    public function rudra_setting_list_data()
    {
       if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
            ";
		// print_r($small_list_options);exit;
		$lst_array = "";
		if (!empty($small_list_options)) {
			foreach ($small_list_options as $slo => $slov) {
				$lst_array .= "\n\$list_array['$slo'] = array(";
				foreach ($slov as $k => $v) {
					$lst_array .= "'$v',";
				}
				$lst_array .= ");";
			}
		}
		$str .= "
                   $lst_array
                    \$this->chk = 1;
                    \$this->msg = 'Small Lists';
                    \$this->return_data = \$list_array;
               
            }
        }
        
    }
    
    public function rudra_delete_data()
    {
       if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $str_required
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
                \$pk_id = \$this->input->post('id');
                \$chk_data = \$this->db->get_where(\"\$this->table\",array('id' => \$pk_id))->row();
                if(!empty(\$chk_data))
                {
                   
                   // \$this->db->where('id',\$pk_id);
                   // \$this->db->delete(\"\$this->table\");
                    \$this->chk = 1;
                    \$this->msg = 'Information deleted';
                    
                }
                else
                {
                    \$this->chk = 0;
                    \$this->msg = 'Record Not Found';   
                }
            }
        }
        
    }

    
    public function rudra_get_data()
    {     
       if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            \$this->form_validation->set_rules('id', 'ID', 'required');
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
                \$pk_id = \$this->input->post('id');
                \$res = \$this->db->get_where(\"\$this->table\",array('id' => \$pk_id))->row();
                if(!empty(\$res))
                {
                    //Format Data if required
                    /*********
                    \$res->added_on_custom_date = date('d-M-Y',strtotime(\$res->added_at));
                    \$res->added_on_custom_time = date('H:i:s A',strtotime(\$res->added_at));
                    \$res->updated_on_custom_date = date('d-M-Y',strtotime(\$res->updated_at));
                    \$res->updated_on_custom_time = date('H:i:s A',strtotime(\$res->updated_at));
                    ************/
                    \$this->chk = 1;
                    \$this->msg = 'Data';
                    \$this->return_data = \$res;
                }
                else
                {
                    \$this->chk = 0;
                    \$this->msg = 'Error: ID not found';
                }
            }
        }
        
    }
    public function rudra_paged_data()
    {     
        if(!empty(\$_POST))
        {
            \$this->form_validation->set_rules('api_key', 'API KEY', 'required');
            //\$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');
            if(\$this->form_validation->run() == FALSE) 
			{ 
                \$this->chk = 0;
                \$this->msg = 'Input Error, Please check Params';
                \$this->return_data = \$this->form_validation->error_array();
            }
            else
            { 
                \$per_page = 50; // No. of records per page
                \$page_number = \$this->input->post('page_number',true);
                \$page_number = (\$page_number == 1 ? 0 : \$page_number);
                \$start_index = \$page_number*\$per_page;
                \$query = \"SELECT * FROM \$this->table ORDER BY id DESC LIMIT \$start_index , \$per_page\";
                \$result = \$this->db->query(\$query)->result();
                if(!empty(\$result))
                {
                    \$list = array();
                    foreach(\$result as \$res)
                    {
                        \$res->added_on_custom_date = date('d-M-Y',strtotime(\$res->added_at));
                        \$res->added_on_custom_time = date('H:i:s A',strtotime(\$res->added_at));
                        \$res->updated_on_custom_date = date('d-M-Y',strtotime(\$res->updated_at));
                        \$res->updated_on_custom_time = date('H:i:s A',strtotime(\$res->updated_at));
                        \$list[] = \$res;
                    }
                    \$this->chk = 1;
                    \$this->msg = 'Paged Data';
                    \$this->return_data = \$list;
                }
                else
                {
                    \$this->chk = 0;
                    \$this->msg = 'No recond exist';
                }
               
            }
        }
       
    }
      
                    
}";
		fwrite($myfile, $str);
		fclose($myfile);
	}


	// Labels
	public function get_dropdown_button($type = "", $source_array = array(), $btn_type = "success", $outline = "outline", $icon = NULL)
	{
		//print_r($source_array);exit;
		$outline_button = "";
		switch ($type) {
			case "button":
				if (!empty($source_array)) {
					foreach ($source_array  as $src) {
						$outline_button .= '<span id="' . $src['unique_id'] . '" class="badge badge-' . $btn_type . '"  onclick="' . $src['action'] . '">' . $src['unique_name'] . '</span>';
					}
				}

				break;
			case "dropdown_button":
				$outline_button = '<div class="btn-group mb-2 mr-2"><button class="btn drp-icon btn-rounded btn-' . $outline . '-' . $btn_type . ' dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button><div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(10px, 44px, 0px); top: 0px; left: 0px; will-change: transform;">';
				foreach ($source_array  as $k => $src) {
					$outline_button .= ' <a class="dropdown-item ' . $src['label_class'] . '" onclick="' . $src['action'] . '" href="javascript:void(0)">' . $src['unique_name'] . '</a>';
				}
				$outline_button .= '</div></div>';

				break;
			case "button-a":
				if ($icon != NULL)
					$button = '<button type="button" class="btn btn-' . $btn_type . ' btn-sm" onclick="' . $source_array['action'] . '"><i class="feather ' . $icon . '"></i>' . $source_array['unique_name'] . '</button>';
				else
					$button = '<button type="button" class="btn btn-' . $btn_type . ' btn-sm" onclick="' . $source_array['action'] . '">' . $source_array['unique_name'] . '</button>';

				return $button;
				break;
			case "button-b":
				$button = '<a class="btn btn-' . $btn_type . ' btn-sm" href="' . $source_array['action'] . '" role="button">' . $source_array['unique_name'] . '</a>';

				return $button;
				break;
		}

		return $outline_button;
	}
}
        
    /* End of file  send_notification.php */
