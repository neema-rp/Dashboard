<?php ?>
<style>
    table tr td{ text-align:left; }
</style>
<script>
$(function() {
   $('#hotel_id').change(function() {
       var val = $(this).val();
       var parent_hotel_id = $('#parent_hotel_id').val();
       window.location = '/admin/GpsPacks/index/' + parent_hotel_id+'/'+val;
    });
});
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('GPS Packs '); ?> <small><i class="icon-double-angle-right"></i>List</small></h1>
        </div>
    
                <input type="hidden" id="parent_hotel_id" value="<?php echo $client_id; ?>"/>
                <?php
                if(!empty($selected_child_hotel)){
                    $client_id = $selected_child_hotel;
                }
                if(!empty($child_data)){
                    echo $this->Form->input('hotel_id',array('type'=>'select','id'=>'hotel_id','empty'=>'Select Hotel','options'=>$child_data,'label'=>'Select Hotel','value'=>$client_id)); ?>
                <?php } ?>
                
                <table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
                    <tbody>
                            <tr>
                                    <td class="sheetIndex buttonscol">
                                            <?php echo $this->Html->link("Add New GPS Pack", array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'new', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                    </td>
                                    <td class="sheetIndex buttonscol">
                                            <?php echo $this->Html->link("GPS Settings", array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'settings', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                            <?php //echo $this->Html->link("Assign Users to Each Steps", array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'steps', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                    </td>
                            </tr>
                    </tbody>
        	</table>
                
                <?php 
                //echo '<pre>'; print_r($userGpsPacks); echo '</pre>';
                
                if(!empty($userGpsPacks)){ ?>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Action</th>
                        </tr>
                        <?php $i='1'; foreach($userGpsPacks as $gps_pack){ ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date('F',strtotime(date('Y').'-'.$gps_pack['GpsPack']['month'].'-'.'01')); ?></td>
                                <td><?php echo $gps_pack['GpsPack']['year']; ?></td>
                                <td class="sheetIndex buttonscol">
                                        <?php echo $this->Html->link(__('<i class="icon-trash bigger-130"></i>', true), array('action' => 'delete', $gps_pack['GpsPack']['id'],$dept_id), array('title' => 'Delete', 'escape' => false,'class'=>'red'), 'Are you sure you want to delete this Pack?'); ?>
                                        
                                        <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'edit_steps',$gps_pack['GpsPack']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                                        <?php echo $this->Html->link('Market Segments', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'segments',$gps_pack['GpsPack']['id']), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                        <?php //echo $this->Html->link('View Report', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'view',$gps_pack['GpsPack']['id']), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                         <?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('prefix' => 'admin', 'admin' => false, 'controller' => 'GpsPacks', 'action' => 'print_pack',$gps_pack['GpsPack']['id']), array('target'=>'_blank', 'escape' => false,'title'=>'View/Print Report','class'=>'blue')); ?>
                                </td>
                            </tr>
                       <?php $i++; } ?>
                    </tbody>
        	</table>
               <?php } ?>
	</fieldset>
</div>