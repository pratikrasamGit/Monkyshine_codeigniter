<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" />
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->
<div class="row">
    <div class="clear-fix clearfix"></div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-5">
                        <h5><?= $page_header ?></h5>
                    </div>
                   
                </div>
            </div>

            <div class="card-block">
                <div class="table-responsive">
                    <table id="responsive-table-modeldddd" class="display table dt-responsive nowrap table-striped table-hover user_post" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date & time</th>
                                <th>From</th>
                                <th>User type</th>
                                <!-- <th>Total Messages</th> -->
                                <th>Unread Messages</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($list as $row){ 
                                $class='messages';
                                if($row->unread_messages > 0){
                                    $class="unmessages";
                                }
                                ?>
                                <tr>
                                    <td><?=date('d-m-Y h:i A', strtotime($row->created_at));?></td>
                                    <td><?=$row->sender?></td>
                                    <td><?=$row->sender_type?></td>
                                    <!-- <td><?=$row->total_messages?></td> -->
                                    <td ><span class="<?=$class?>" ><?=$row->unread_messages?></span></td>
                                    <td><a target="_blank" href="<?=base_url('rudra_chat/user?id='.$row->fk_from_id)?>" id="" class="label label-primary f-12"  role="button" onclick="window.setTimeout(function(){location.reload()},2000)">Chat</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('app_') ?>assets/plugins/data-tables/js/datatables.min.js"></script>
<script type="text/javascript">
$('.user_post').DataTable({
    responsive: true,
    "columnDefs": [{
                targets: "_all",
                orderable: false
            }],
            "aaSorting": []

});

</script>