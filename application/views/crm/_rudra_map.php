<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" /> <link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
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
                                <!-- <button onclick="static_form_modal('rudra_map/post_actions/get_data?id=0','rudra_map/post_actions/insert_data','md','Update Details')" type="button" class="btn btn-glow-success btn-info" title="" data-toggle="tooltip" data-original-title="Add New Item" aria-describedby="tooltip651610">New</button>&nbsp; -->
                                <!-- <a href="rudra_map/post_actions/export_data" type="button" class="btn btn-glow-danger btn-warning" title="" data-toggle="tooltip" data-original-title="Export CSV" aria-describedby="tooltip651610">CSV</a> -->
                            
                                </div> 
                            </div> 
                        </div>

                    <div class="card-block">
                        <div class="table-responsive">
                            <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                                <thead><tr>
<!-- <th>Id</th> -->
<th>Map Image</th>
<th>Status</th>
<!-- <th>Is Deleted</th> -->
<th>Actions</th>
</tr>
 </thead>
  </table>
</div>
</div>
</div>
</div>
</div>

<!--- FORM Modal Ends ---->
  <div class="md-modal md-effect-11 dynamic-modal" id="form_modal_rudra_map">
       <div class="modal-content">
           <div class="modal-header theme-bg2 ">
               <h5 class="modal-title text-white" id="heading_rudra_map">Loading...</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
           </div>
           <form id="frm_rudra_map" method="post" enctype="multipart/form-data">
               <div class="modal-body" id="modal_form_data_rudra_map"> </div>
              
<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" disabled class="btn btn-primary btn-modal-form">Update</button>
    </div>
</form>
</div>
</div>
<!--- FORM Modal Ends ---->
 

<script src="<?= base_url('app_') ?>assets/plugins/data-tables/js/datatables.min.js"></script>
<script type="text/javascript">
var load_type = '<?= $load_type ?>';
var search_json = new Object();
search_json.load_type = load_type;
$('#ddlAgencyFilter').change(function() {
	$('.user_post').DataTable().ajax.reload();
});
 $('#start_date_stat').change(function() {
 if($(this).val() != '')
	{
	$('.user_post').DataTable().ajax.reload();
	}
 });
 $('#end_date_stat').change(function() {
 if($(this).val() != '')
	{	 $('.user_post').DataTable().ajax.reload()
};
 });
 $.fn.dataTable.ext.errMode = 'throw';
var usrTable;
fill_server_data_table(search_json);
function fill_server_data_table(search_json) {
	usrTable = $('.user_post').DataTable({
"processing": true,
"serverSide": true,
 fixedHeader: true,
responsive: true,
"ajax": {
	"url": "<?php echo base_url('rudra_map/list') ?>",
	"dataType": "json",
	"type": "POST",
	"data": {
	search_json: search_json,
	 start_date: function() {
	return $('#start_date_stat').val()
 },
	end_date: function() {
return $('#end_date_stat').val()
 	},
  }
 },	 "columns": [
// {
// 		 "data": "id"
//  },
{
		 "data": "image"
 },
{
		 "data": "status"
 },
// {
// 		 "data": "is_deleted"
//  },
	 {
		 "data": "actions"
},
 ],
 "columnDefs": [{
 targets: "_all",
 orderable: true
 }]
  });
 }
</script>

<!-- Form Scripts Starts Here -->

<script type='text/javascript'>

function static_form_modal(data_url, action_url, mtype, heading) {
            $("#form_modal_rudra_map").modal();
            $("#form_modal_rudra_map").addClass('md-show');
            $("#heading_rudra_map").text(heading);
            $("#frm_rudra_map").attr("action", '<?= base_url() ?>' + action_url);
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>' + data_url,
                data: {},
                success: function(data) {
                    response = JSON.parse(data);
                    if (response.status) {
                        jsonData = JSON.parse(JSON.stringify(response.data));
                        //console.log(jsonData);
                        $("#modal_form_data_rudra_map").html(jsonData.form_data);
                        $(".btn-modal-form").prop("disabled", false);
                    } else {
                        $(".btnlogin").prop('disabled', false);
                        $(".btnlogin").addClass('btn-success');
                        $(".btnlogin").removeClass('btn-warning');
                        $(".btnlogin").html('Login');
                    }
                }
            });
        }

$("#frm_rudra_map").submit(function(e) {
            e.preventDefault();
            $(".btn-modal-form").prop('disabled', true);
            $(".btn-modal-form").html('Wait...');
            var formData = new FormData($("#frm_rudra_map")[0]);
            var action_url = $("#frm_rudra_map").attr("action");
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
                        $(".btn-modal-form").prop('disabled', false);
                        $(".btn-modal-form").html('Update');
                        $('.dynamic-modal').modal('hide');
                        $("#static_form_modal").modal('hide');
                        //setTimeout(function(){ window.location = response.data.url; }, 3000);
                    } else if (response.status) {
                        $(".btn-modal-form").prop('disabled', false);
                        $(".btn-modal-form").html('Update');
                        usrTable.ajax.reload(null, false);
                        $('.dynamic-modal').modal('hide');
                        //setTimeout(function(){ window.location = response.data.url; }, 3000);
                    } else {
                        $(".btn-modal-form").prop('disabled', false);
                        $(".btn-modal-form").html('Update');
                    }
    
                }
            });
        });
</script>