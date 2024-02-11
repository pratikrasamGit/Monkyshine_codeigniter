
<!--UNComment if SCROLL BAR required --><div class='col-sm-12' style='max-height:500px;overflow: auto;'>
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
<div class="form-group col-sm-12">
	<label>Day</label>
 	<input type="date"  name="day" class="form-control" value="<?= (!empty($form_data) ? $form_data->day : ''); ?>"   placeholder="Day"  min=<?=date('Y-m-d')?> />
</div>

<?php $i=1; foreach($slots_data as $row){ ?>
<div class="form-group col-sm-3 row<?=$i?>">
	<label>Time</label>
 	<input type="time"  name="time<?=$i?>" class="form-control" value="<?= (!empty($row) ? $row['time'] : $times[$i-1]); ?>"   placeholder="Time"   />
</div>

<div class="form-group col-sm-3 row<?=$i?>">
	<label>Slots Taken</label>
 	<input type="number"  name="slots_taken<?=$i?>" class="form-control" value="<?= (!empty($row) ? $row['slots_taken'] : '0'); ?>"   placeholder="Slots Taken"   />
</div>
<div class="form-group col-sm-3 row<?=$i?>">
	<label>Total Slots</label>
 	<input type="number"  name="total_slots<?=$i?>" class="form-control" value="<?= (!empty($row) ? $row['total_slots'] : '1'); ?>"   placeholder="Total Slots"   />
</div>
<div class="form-group col-sm-3 row<?=$i?>" style="margin-top: 5%;">
<button type="button" class="btn-danger btn_remove" id="<?=$i?>" >Remove</button>
</div>
<?php $i++; } ?>
 
<div class="form-group col-sm-4">
 
<button type="button" class="btn btn-primary" id="add">Add More</button>
</div>
</div>
<div class='row' id="dynamic_field"  >

	


</div>




<input type="hidden" name="count" id="count" value="<?=$i?>">

<!-- 
<div class='row'>
<?php $options = $status; ?>
<div class="form-group col-sm-6">

	<label>Status</label>

	<select  id='status'  name="status" class="form-control" >
		<option <?php if($form_data->status == '1'){ echo "selected"; } ?> value="1">Active</option>
		<option <?php if($form_data->status == '0'){ echo "selected"; } ?> value="0">Inactive</option>
	</select>
</div>

<?php $options = $is_deleted; ?>
<div class="form-group col-sm-6">

	<label>Is Deleted</label>

	<select  id='is_deleted'  name="is_deleted" class="form-control" >
	<option <?php if($form_data->status == '0'){ echo "selected"; } ?> value="0">Active</option>
		<option <?php if($form_data->status == '1'){ echo "selected"; } ?> value="1" >Deleted</option>
	</select>
</div>
</div> -->
<!--Uncomment if Scroll Required div -->
</div>


<script>

$(document).ready(function() {

	var i = $('#count').val();

	$('#add').click(function(){  
            i++;  
            console.log(i);
        
            $('#dynamic_field').append('<div class="form-group col-sm-3 row'+i+'">	<label>Time</label> 	<input type="time"  name="time'+i+'" class="form-control"   placeholder="Time"   /></div><div class="form-group col-sm-3 row'+i+'">	<label>Slots Taken</label> 	<input type="number"  name="slots_taken'+i+'" class="form-control" value="0"   placeholder="Slots Taken"   /></div><div class="form-group col-sm-3  row'+i+'">	<label>Total Slots</label> 	<input type="number"  name="total_slots'+i+'" class="form-control" value="1"   placeholder="Total Slots"   /></div>  <div class="form-group col-sm-3 row'+i+'" style="margin-top: 5%;"><button type="button" class="btn-danger btn_remove" id="'+i+'" >Remove</button></div>');


            $(function () {
                $('#count').val(i);
            });
    });  


	$(document).on('click', '.btn_remove', function(){  
            var button_id = $(this).attr("id");   
            $('.row'+button_id+'').remove();  
        }); 

});  
</script>