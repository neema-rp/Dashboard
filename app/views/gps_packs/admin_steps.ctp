<?php ?>
<script>
$(function() {
   $('#hotel_id').change(function() {
       var val = $(this).val();
       var parent_hotel_id = $('#parent_hotel_id').val();
       window.location = '/admin/GpsPacks/steps/' + parent_hotel_id+'/'+val;
    });
});
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('GPS Pack '); ?> <small><i class="icon-double-angle-right"></i>Assign Users to Each Steps</small></h1>
        </div>  
                <table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
                    <tbody>
                            <tr>
                                    <td class="sheetIndex buttonscol">
                                            <?php echo $this->Html->link("GPS Pack", array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'index', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                    </td>
                            </tr>
                    </tbody>
        	</table>
                
                <input type="hidden" id="parent_hotel_id" value="<?php echo $client_id; ?>"/>
                <?php
                if(!empty($selected_child_hotel)){
                    $client_id = $selected_child_hotel;
                }
                if(!empty($child_data)){
                    echo $this->Form->input('hotel_id',array('type'=>'select','id'=>'hotel_id','empty'=>'Select Hotel','options'=>$child_data,'label'=>'Select Hotel','value'=>$client_id)); ?>
                <?php } ?>
                
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th>Steps</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        if(!empty($users)){
                        foreach($users as $user){
                        ?>
                            <tr>
                                <td><?php echo $user['User']['firstname'].'&nbsp;'.$user['User']['lastname']; ?></td>
                                <td class="sheetIndex buttonscol">
                                    <?php echo $this->Html->link('Assign User', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'assign',$user['User']['id'],$client_id), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                </td>
                            </tr>
                       <?php } 
                       } ?>
                    </tbody>
        	</table>
</div>