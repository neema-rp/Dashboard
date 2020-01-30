<?php ?>
<style>
    table tr td{ text-align:left; }
</style>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Promotions Calendar <small><i class="icon-double-angle-right"></i> Edit</small></h1>
        </div>
    	        <?php echo $this->Html->link("List Promos", array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'index', $this->data['Promo']['client_id']), array('escape' => false,'class'=>'btn btn-info')); ?>
                
                <?php
                $months = array(
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July ',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                );
                if(!empty($PromoId)){ ?>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered table-hover">
                    <tbody>
                        <?php
                        foreach($months as $month_id=>$month){ 
                            $m_id = $month_id + '1';
                            ?>
                            <tr>
                                <td><?php echo $month; ?> <?php echo $this->data['Promo']['year']; ?></td>
                                <td class="sheetIndex buttonscol">
                                        <?php echo $this->Html->link('Edit', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'edit',$m_id,$PromoId), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                </td>
                            </tr>
                       <?php } ?>
                    </tbody>
        	</table>
               <?php } ?>
	
</div>