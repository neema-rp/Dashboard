<?php
    $open_time = strtotime("8:00");
    $close_time = strtotime("14:06");
    $now = time(); 
    $days_in_sheet_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
?>

        <link href="/fixedTable/defaultTheme.css" rel="stylesheet" media="screen" />
        <link href="/fixedTable/myTheme.css" rel="stylesheet" media="screen" />
        <script src="/js/jquery-2.0.3.min.js"></script>
        <script src="/fixedTable/jquery.fixedheadertable.js"></script>
        <style>
            .fancyTable tbody tr td{ background-color:#fff; }
            .bold{ font-weight:bold !important; }
        </style>

    <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0">
    <thead>
    <tr>
    <th><?php echo date('m')."/".date('Y'); ?></th>
    <th> Date </th>
    <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
        <th colspan="2"><?php echo $day; ?></th>
    <?php } ?>
    </tr>
    </thead>
    
    <tbody>
    <tr>
    <td class='bold'> Tee Times </td>
    <td class='bold'> Max Cap </td>
    <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
        <td class="numeric bold">Booked</td>
        <td class="numeric bold">Actual</td>
    <?php } ?>
    </tr>
    
    
    <?php $count = '1'; $max_cap_count = '0';
    for( $i=$open_time; $i<$close_time; $i+=550) { ?>
            <tr>
            <td class='bold'><?php echo date("H:i",$i); ?></td>
            <td class='bold'><?php echo $max_cap = '4';
                    $max_cap_count += $max_cap;
                    ?></td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric">2</td>
                <td class="numeric">3</td>
            <?php } ?>
            </tr>
    <?php $count++; } ?>
            
            <tr>
            <td></td>
            <td></td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric"></td>
                <td class="numeric"></td>
            <?php } ?>
            </tr>
            
            <tr>
            <td class='bold'>Total Players</td>
            <td>  </td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric">2</td>
                <td class="numeric">3</td>
            <?php } ?>
            </tr>
            <tr>
            <td class='bold'>REVPARO</td>
            <td> </td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric"></td>
                <td class="numeric"></td>
            <?php } ?>
            </tr>
            <tr>
            <td class='bold'>Total SHIRE</td>
            <td> </td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric"></td>
                <td class="numeric"></td>
            <?php } ?>
            </tr>
            <tr>
            <td class='bold'>Tee Time Interval</td>
            <td> 00:09 </td>
            <?php for( $day=1; $day<=$days_in_sheet_month;$day+=1){ ?>
                <td class="numeric"></td>
                <td class="numeric"></td>
            <?php } ?>
            </tr>
            
    </tbody>
    </table>
    
<script>
$(document).ready(function() {
    $('#myTable05').fixedHeaderTable({ altClass: 'odd', footer: true, fixedColumns: 2 });
});
</script>