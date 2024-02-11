<script>
$(document).ready(function(){
	load_dashboard_data(0);
	$(".load_ajax_data").click(function(){
		var dType = $(this).data('value');
		var bid = $(this).attr("id");
		//alert(bid);
		$(".load_ajax_data").removeClass('btn-info');
		$(".load_ajax_data").addClass('btn-warning');
		$(this).removeClass('btn-warning');
		$(this).addClass('btn-info');
		load_dashboard_data(dType);
	});
	
	$("#start_date_stat").change(function(){
		var start_date = $(this).val();
		var end_date = $("#end_date_stat").val();
		if(start_date != '' && end_date != '')
		{
			dType = start_date+'-'+end_date;
			load_dashboard_data(dType);
		}
	});
	
	
	$("#end_date_stat").change(function(){
		var end_date = $(this).val();
		var  start_date = $("#start_date_stat").val();
		if(start_date != '' && end_date != '')
		{
			dType = start_date+'-'+end_date;
			load_dashboard_data(dType);
		}
	});
	
	function load_dashboard_data(dType)
	{
		$.ajax({
			type: 'POST',
			url: '<?=base_url()?>load-ajax-data',
			data:  {time:dType},
			success: function (data) {
				
				response = JSON.parse(JSON.stringify(data));
				//console.log(response.data);
				if(response.status)
				{ 
					jsonData = JSON.parse(JSON.stringify(data.data));
					$("#divUsersStats").html(jsonData.users_stat);

				}
				else{
					$(".btnlogin").prop('disabled',false);
					$(".btnlogin").addClass('btn-success');
					$(".btnlogin").removeClass('btn-warning');
					$(".btnlogin").html('Login');
					
				}

			}
		});
		
	}
	
});


</script>