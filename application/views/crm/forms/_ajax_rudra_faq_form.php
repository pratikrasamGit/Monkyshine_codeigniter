<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<div class="form-group col-sm-6">
		<label>Question (English)</label>
		<input type="text" name="question" class="form-control" value="<?= (!empty($form_data) ? $form_data->question : ''); ?>" placeholder="Question En" />
	</div>
	<div class="form-group col-sm-6">
		<label>Question (Danish)</label>
		<input type="text" name="question_dk" class="form-control" value="<?= (!empty($form_data) ? $form_data->question_dk : ''); ?>" placeholder="Question Dk" />
	</div>
	<div class="form-group col-sm-12">
		<label>Answer (English)</label>
		<textarea name="answer" class="form-control" ><?= (!empty($form_data) ? $form_data->answer : ''); ?></textarea>
		<!-- <input type="text" name="answer" class="form-control" value="<?= (!empty($form_data) ? $form_data->answer : ''); ?>" placeholder="Answer" /> -->
	</div>

	<div class="form-group col-sm-12">
		<label>Answer (Danish)</label>
		<textarea name="answer_dk" class="form-control" ><?= (!empty($form_data) ? $form_data->answer_dk : ''); ?></textarea>
		<!-- <input type="text" name="answer" class="form-control" value="<?= (!empty($form_data) ? $form_data->answer : ''); ?>" placeholder="Answer" /> -->
	</div>

	<?php $options = statusDropdown($id = ""); ?>
	<div class="form-group col-sm-6">
		<label>Status</label>
		<select id='status' name="status" class="form-control">
			<?php foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->status == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = isDeletedDropdown($id = ""); ?>
	<!-- <div class="form-group col-sm-6">
		<label>Is Deleted</label>
		<select id='is_deleted' name="is_deleted" class="form-control">
			<?php foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->is_deleted == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div> -->
</div>
<!--Uncomment if Scroll Required div -->