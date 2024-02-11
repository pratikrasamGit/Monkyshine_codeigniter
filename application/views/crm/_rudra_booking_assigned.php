<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" />
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [Total-user section] start -->
    <div class="col-sm-12">
        <div class="card text-left">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-9">

                        <div class="row">
                            <?php $users_list = userList($type = "1", $id = ""); ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">User</label>
                                    <select class="form-control" name="user" id="">
                                        <option value="">Choose User</option>
                                        <?php foreach ($users_list as $key => $u) { ?>
                                            <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <?php $washer_list = userList($type = "2", $id = ""); ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Washer</label>
                                    <select class="form-control" name="washer" id="">
                                        <option value="">Choose Washer</option>
                                        <?php foreach ($washer_list as $key => $m) { ?>
                                            <option value="<?= $m['id'] ?>"><?= $m['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <?php $wash_status = washStatus($id = ""); ?>
                            <!-- <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" name="status" id="">
                                        <option value="">Select Status</option>
                                        <?php foreach ($wash_status as $key => $s) { ?>
                                            <option value="<?= $key ?>"><?= $s ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> -->


                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Booking ID</label>
                                            <input type="text" class="form-control" name="searchtext" id="searchtext">
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-sm-3 float-right">
                        <label>From -To</label>
                        <div class="input-daterange input-group " id="datepicker_range">
                            <input type="text" class="form-control text-left" placeholder="Start date" id="start_date_stat" name="start">
                            <input type="text" class="form-control text-right" placeholder="End date" id="end_date_stat" name="end">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear-fix clearfix"></div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-5">
                        <h5><?= $page_header ?></h5>
                    </div>
                    <div class="col-sm-7 text-right">
                        <?php /*  ?>
                        <button onclick="static_form_modal('rudra_booking/post_actions/get_data?id=0','rudra_booking/post_actions/insert_data','md','Update Details')" type="button" class="btn btn-glow-success btn-info" title="" data-toggle="tooltip" data-original-title="Add New Item" aria-describedby="tooltip651610">New</button>&nbsp;
                        <?php */ ?>
                        <a href="#" id="csv_export" type="button" class="btn btn-glow-danger btn-warning" title="" data-toggle="tooltip" data-original-title="Export CSV" aria-describedby="tooltip651610">CSV</a>

                    </div>
                </div>
            </div>

            <div class="card-block">
                <div class="table-responsive">
                    <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Wash Date</th>
                                <th>User</th>
                                <th>Booking Id</th>
                                <!-- <th>Zipcode</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Address</th>
                                <th>Car</th>
                                <th>Wash Id</th>
                                <th>Extra Wash Type Ids</th>
                                <th>Notes</th> -->
                                <th>Washer</th>
                                <th>Wash Status</th>
                                <!-- <th>Vat Percentage</th>
                                <th>Wash Type Json</th>
                                <th>Extra Wash Type Json</th>
                                <th>Transaction</th>
                                <th>Pay Method</th> -->
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
<div class="modal dynamic-modal" id="form_modal_rudra_booking">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header theme-bg2 ">
                <h5 class="modal-title text-white" id="heading_rudra_booking">Loading...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form id="frm_rudra_booking" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="modal_form_data_rudra_booking"> </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" disabled class="btn btn-primary btn-modal-form">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--- FORM Modal Ends ---->


<script src="<?= base_url('app_') ?>assets/plugins/data-tables/js/datatables.min.js"></script>
<script type="text/javascript">
    var load_type = '<?= $load_type ?>';
    var search_json = new Object();
    search_json.load_type = load_type;
    $('#ddlAgencyFilter, select[name="status"], select[name="washer"], select[name="user"], #searchtext').change(function() {
        $('.user_post').DataTable().ajax.reload();
    });
    $('#start_date_stat').change(function() {
        if ($(this).val() != '') {
            $('.user_post').DataTable().ajax.reload();
        }
    });
    $('#end_date_stat').change(function() {
        if ($(this).val() != '') {
            $('.user_post').DataTable().ajax.reload()
        };
    });

    var csv = '0';
    $('#csv_export').click(function() {
        csv = '1';
        $('.user_post').DataTable().ajax.reload();
    });

    $.fn.dataTable.ext.errMode = 'throw';
    var usrTable;
    fill_server_data_table(search_json);

    function fill_server_data_table(search_json) {
        console.log(csv);
        usrTable = $('.user_post').DataTable({
            "processing": true,
            "serverSide": true,
            fixedHeader: true,
            responsive: true,
            "ajax": {
                "url": "<?php echo base_url('rudra_booking/list') ?>",
                "dataType": "json",
                "type": "POST",
                "data": {
                    csv: function() {
                        return csv;
                    },
                    search_json: search_json,
                    start_date: function() {
                        return $('#start_date_stat').val()
                    },
                    end_date: function() {
                        return $('#end_date_stat').val()
                    },
                    status: function() {
                        return '2'
                    },
                    searchtext: function() {
                        return $('#searchtext').val()
                    },
                    user: function() {
                        return $('select[name="user"]').val()
                    },
                    washer: function() {
                        return $('select[name="washer"]').val()
                    },

                }
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "wash_date"
                },
                {
                    "data": "u_name"
                },
                {
                    "data": "booking_id"
                },
                /* {
                     "data": "zipcode"
                 },
                  {
                     "data": "latitude"
                 },
                 {
                     "data": "longitude"
                 },
                 {
                     "data": "address"
                 },
                {
                    "data": "fk_car_reg_id"
                },
                {
                    "data": "wash_id"
                },
                {
                    "data": "extra_wash_type_ids"
                },
                {
                    "data": "notes"
                }, */
                {
                    "data": "washer_name"
                },
                {
                    "data": "wash_status"
                },
                /* {
                    "data": "vat_percentage"
                },
                 {
                    "data": "wash_type_json"
                },
                {
                    "data": "extra_wash_type_json"
                },
                {
                    "data": "transaction"
                },
                {
                    "data": "pay_method"
                }, */
                {
                    "data": "status"
                },
                // {
                //     "data": "is_deleted"
                // },
                {
                    "data": "actions"
                },
            ],
            "columnDefs": [{
                targets: "_all",
                orderable: true
            }],
            aaSorting : [[0, 'desc']],
        });
    }
</script>

<!-- Form Scripts Starts Here -->

<script type='text/javascript'>
    function static_form_modal(data_url, action_url, mtype, heading) {
        $("#form_modal_rudra_booking").modal();
        $("#form_modal_rudra_booking").addClass('md-show');
        $("#heading_rudra_booking").text(heading);
        $("#frm_rudra_booking").attr("action", '<?= base_url() ?>' + action_url);
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>' + data_url,
            data: {},
            success: function(data) {
                response = JSON.parse(data);
                if (response.status) {
                    jsonData = JSON.parse(JSON.stringify(response.data));
                    //console.log(jsonData);
                    $("#modal_form_data_rudra_booking").html(jsonData.form_data);
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

    $("#frm_rudra_booking").submit(function(e) {
        e.preventDefault();
        $(".btn-modal-form").prop('disabled', true);
        $(".btn-modal-form").html('Wait...');
        var formData = new FormData($("#frm_rudra_booking")[0]);
        var action_url = $("#frm_rudra_booking").attr("action");
        $.ajax({
            type: 'POST',
            url: action_url,
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                response = JSON.parse(data);
                console.log(response);
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

    $(document).ready(function(){
    $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>remove_new',
            data: {},
            success: function(data) {
                
            }
        });
    });
</script>