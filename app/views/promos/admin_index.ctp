<?php ?>
<style>
    table tr td{ text-align:left; }
    #flashMessage{ width:100%; }
</style>
<script>
$(function() {
   $('#hotel_id').change(function() {
       var val = $(this).val();
       var parent_hotel_id = $('#parent_hotel_id').val();
       window.location = '/admin/Promos/index/' + parent_hotel_id+'/'+val;
    });
});
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('Promotions Calendar '); ?> <small><i class="icon-double-angle-right"></i>List</small></h1>
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
                                            <?php echo $this->Html->link("Add New Promotions Calendar", array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'new', $client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                    </td>
                            </tr>
                    </tbody>
        	</table>
                
                <?php                 
                if(!empty($userPromos)){ ?>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th style="width:30px;">#</th>
                            <th>Year</th>
                            <th>Action</th>
                        </tr>
                        <?php $i='1'; foreach($userPromos as $promos){ ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $promos['Promo']['year']; ?></td>
                                <td class="sheetIndex buttonscol">
                                        <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit_steps',$promos['Promo']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                                        <?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('action' => 'settings', $promos['Promo']['id']), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
                                        <?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('action' => 'view', $promos['Promo']['id']), array('title' => 'View Calendar', 'escape' => false,'class'=>'orange')); ?>
                                </td>
                            </tr>
                       <?php $i++; } ?>
                    </tbody>
        	</table>
               <?php } ?>
	
</div>