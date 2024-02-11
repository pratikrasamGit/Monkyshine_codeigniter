<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" />
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />

<!-- [ Main Content ] start -->
<div class="row">
    <!-- [Total-user section] start -->
    <div class="col-sm-12">
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
    </div>
    <div class="clear-fix clearfix"></div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= $page_header ?></h5>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                        <thead>
                            <tr>
                                <th>Table</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- [ Main Content ] end -->

<!-- datatable Js -->
<script src="<?= base_url('app_') ?>assets/plugins/data-tables/js/datatables.min.js"></script>
<!--script src="<?= base_url('app_') ?>assets/js/pages/tbl-datatable-custom.js"></script-->

<script type="text/javascript">
    var load_type = '<?= $load_type ?>';
    var search_json = new Object();
    search_json.load_type = load_type;
    //console.log(search_json);

    $("#ddlAgencyFilter").change(function() {
        
        $(".user_post").DataTable().ajax.reload();
        //console.log(search_json);
    });

    $("#start_date_stat").change(function() {
        if($(this).val() != '')
        {
            $(".user_post").DataTable().ajax.reload();
        }
        
        //console.log(search_json);
    });

    $("#end_date_stat").change(function() {
        if($(this).val() != '')
        {
        $(".user_post").DataTable().ajax.reload();
        }
        //console.log(search_json);
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
                "url": "<?php echo base_url('crudmaster/list') ?>",
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
            },
            "columns": [{
                    "data": "table_name"
                },
              
              {
                  "data": "status"
              },
              
                {
                    "data": "actions"
                },
            ],
            "columnDefs": [{
                targets: "_all",
                orderable: false
            }]
        });

    }
</script>