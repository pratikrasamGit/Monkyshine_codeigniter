<!--- FORM Modal Starts ---->
<div class="md-modal md-effect-11 dynamic-modal" id="form_modal">
	<div class="modal-content">
		<div class="modal-header theme-bg2 ">
			<h5 class="modal-title text-white" id="form_modal_heading">Loading...</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		</div>
		<form id="frm_ajax_form_modal" method="post" enctype="multipart/form-data">
			<div class="modal-body" id="modal_form_data"> </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" disabled class="btn btn-primary btn-modal-form">Update</button>
			</div>
		</form>
	</div>
</div>
<!--- FORM Modal Ends ---->

<!-- Info Alert Modal -->
<div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-body p-4">
				<div class="text-center">
					<i class="dripicons-information h1 text-info"></i>
					<h4 class="mt-2">HeadsUp!</h4>
					<p class="mt-3">Are you Sure?</p>
					<button type="button" class="btn btn-info my-2" data-dismiss="modal">Cancel</button>
					<button id="confirm_button" class="btn btn-danger my-2">Confirm</a>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	function confirm_modal(action_url, method = "POST") {
		$("#confirm_button").attr('data-url', '');
		var url = action_url;
		$("#alert-modal").modal();
		$("#confirm_button").attr('data-url', url);
		$("#confirm_button").attr('data-method', method);
	}

	$("#confirm_button").click(function() {
		var url = $(this).attr('data-url');
		var method = $(this).attr('data-method');
		$("#confirm_button").prop("disabled", true);
		$("#confirm_button").html("Wait...");
		$("#confirm_button").attr('data-url', '');
		$.ajax({
			type: method,
			url: '<?= base_url() ?>' + url,
			data: {},
			success: function(data) {
				response = JSON.parse(data);
				if (response.status) {
					$("#confirm_button").prop("disabled", false);
					$("#confirm_button").html("Confirm");
					$("#alert-modal").modal('hide');
					usrTable.ajax.reload(null, false);
				} else {
					$("#confirm_button").prop("disabled", false);
					$("#confirm_button").html("Confirm");
					$("#alert-modal").modal('hide');
				}
			}
		});
	});

	function form_modal(data_url, action_url, mtype, heading) {
		$("#form_modal").modal();
		$("#form_modal").addClass('md-show');
		$("#form_modal_heading").text(heading);
		$("#frm_ajax_form_modal").attr("action", '<?= base_url() ?>' + action_url);
		$.ajax({
			type: 'POST',
			url: '<?= base_url() ?>' + data_url,
			data: {},
			success: function(data) {
				response = JSON.parse(data);
				if (response.status) {
					jsonData = JSON.parse(JSON.stringify(response.data));
					//console.log(jsonData);
					$("#modal_form_data").html(jsonData.form_data);
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

	$("#frm_ajax_form_modal").submit(function(e) {
		e.preventDefault();
		$(".btn-modal-form").prop('disabled', true);
		$(".btn-modal-form").html('Wait...');
		var formData = new FormData($("#frm_ajax_form_modal")[0]);
		var action_url = $("#frm_ajax_form_modal").attr("action");
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
					$("#form_modal").modal('hide');
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

	$(document).ready(function() {
		$('.md-close').click(function() {
			$('.dynamic-modal').modal('hide');
		});
	});
</script>

<!-- data view modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" id="content-view-model">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="view-model-heading">View Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="view_model">
				<div class="modal-body">
					<div id="view-model-body"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- data view modal -->

<script>
	function view_model(data_url, action_url, mtype, heading) {
		$('#content-view-model').modal('show');
		$("#view-model-heading").text(heading);
		$("#content-view-model").addClass('bd-example-modal-' + mtype);
		$("#view_model").attr('action', '<?= base_url() ?>' + action_url);
		$("#view-model-body").load(data_url, "", function(response, status, request) {});
	}
</script>