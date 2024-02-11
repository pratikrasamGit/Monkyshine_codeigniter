<style>
    .pcoded-navbar.menupos-static {
        min-height: 188%;
    }
</style>
<!-- Required Js -->

<!--script src="<?= base_url() ?>app_assets/js/menu-setting.min.js"></script-->
<script src="<?= base_url() ?>app_assets/js/pcoded.min.js"></script>
<!-- amchart js -->
<!--script src="<?= base_url() ?>app_assets/plugins/amchart/js/amcharts.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/gauge.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/serial.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/light.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/pie.min.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/ammap.min.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/usaLow.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/radar.js"></script>
    <script src="<?= base_url() ?>app_assets/plugins/amchart/js/worldLow.js"></script-->
<!-- notification Js -->
<script src="<?= base_url() ?>app_assets/plugins/notification/js/bootstrap-growl.min.js"></script>

<!-- dashboard-custom js -->
<!--script src="<?= base_url() ?>app_assets/js/pages/dashboard-custom.js"></script-->

<!-- datepicker js -->
<script src="<?= base_url() ?>app_assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>app_assets/js/pages/ac-datepicker.js"></script>
<!-- modal-window-effects Js -->
<script src="<?= base_url() ?>app_assets/plugins/modal-window-effects/js/classie.js"></script>
<script src="<?= base_url() ?>app_assets/plugins/modal-window-effects/js/modalEffects.js"></script>
<!-- sweet alert Js -->
<script src="<?= base_url() ?>app_assets/plugins/sweetalert/js/sweetalert.min.js"></script>
<script src="<?= base_url() ?>app_assets/js/pages/ac-alert.js"></script>
<?php $this->load->view('crm/common/modal_js'); ?>

</body>

</html>

<script>

setInterval(function() {
    $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>get_new_bookings',
        data: {},
        success: function(data) {
            console.log(data);
            if(data>0){
                $('#newbooks').html(data);
                $('#newbooks').addClass('unmessages');
            }else{
                $('#newbooks').html('');
                $('#newbooks').removeClass('unmessages');

            }
        }
    });

    $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>get_new_chats',
        data: {},
        success: function(data) {
            console.log(data);
            if(data>0){
                $('#newchats').html(data);
                $('#newchats').addClass('unmessages');
            }else{
                $('#newchats').html('');
                $('#newchats').removeClass('unmessages');

            }
        }
    });

}, 1000);
</script>