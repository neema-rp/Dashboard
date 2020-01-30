<?php ?>
<style type="text/css">
    .internal_div{
        width:24%;float:left;clear:all;text-align:center;padding:20px;border: 1px solid #ccc;margin:14px;border-radius:4px;
    }
</style>
<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php echo $hotelname; ?> <small><i class="icon-double-angle-right"></i> More Options</small></h1>
        </div>
    
               <div width="90%" style="text-align:center;">
                        <a href="http://www.mypricingwizard.net/login.php?hotel_id=<?php echo $client_id; ?>" target="_blank">
                            <div class="internal_div">
                                Pricing Wizard
                            </div>
                        </a>
                        
                        <a href="/admin/clients/flash/<?php echo $client_id; ?>">
                            <div class="internal_div">
                                Daily Flash
                            </div>
                        </a>
                   
                        <a href="/admin/SurveyUsers/index/<?php echo $client_id; ?>">
                            <div class="internal_div">
                                Survey
                            </div>
                        </a>
                 </div>
            
                 <br/><br/>
                 
                 <div width="90%" style="margin-top:20px;text-align:center;">
                        <a href="/admin/departments/index/<?php echo $client_id; ?>" >
                            <div class="internal_div">
                                Departments
                            </div>
                        </a>
                        
                        <a href="/admin/clients/assign/<?php echo $client_id; ?>" >
                            <div class="internal_div">
                                Assign Users
                            </div>
                        </a>
                     
                        <a href="/admin/GpsPacks/index/<?php echo $client_id; ?>" >
                            <div class="internal_div">
                                GPS Packs
                            </div>
                        </a>
                 </div>
                 <div width="90%" style="margin-top:20px;text-align:center;">
                        <a href="/admin/Promos/index/<?php echo $client_id; ?>">
                            <div class="internal_div">
                                Promotions Calendar
                            </div>
                        </a>
                         <a href="/admin/columns/range/<?php echo $client_id; ?>">
                            <div class="internal_div">
                              Calendar Heat Map Range
                            </div>
                        </a>
                        <a href="/admin/MarketSegments/list/<?php echo $client_id; ?>">
                            <div class="internal_div">
                              Market Segments
                            </div>
                        </a>
                 </div>
</div>