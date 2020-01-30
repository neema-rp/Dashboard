	<div id="div_departments" style="margin-left:-15px;">
	<?php	
		echo $this->Form->input('department_name', array('options' => $departments,'multiple'=>'multiple','class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	?>
	</div>
