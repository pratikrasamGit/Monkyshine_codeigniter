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