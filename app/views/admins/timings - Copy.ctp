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
        </style>

    <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0">
    <thead>
    <tr>
    <th><?php echo date('m')."/".date('Y'); ?></th>
    <th> Tee Times </th>
    <?php for( $i=$open_time; $i<$close_time; $i+=550) { ?>
        <th><?php echo date("H:i",$i); ?></th>
    <?php } ?>
    </tr>
    </thead>
    
    
    <tbody>
    <tr>
    <td style='font-weight: bold;'> Date </td>
    <td style='font-weight: bold;'> Max Cap </td>
    <?php for( $i=$open_time; $i<$close_time; $i+=550) { ?>
        <td style='font-weight: bold;' class="numeric">4</td>
    <?php } ?>
    </tr>
    
    <?php for( $day=1; $day<=$days_in_sheet_month; $day+=1) { ?>
            <tr>
            <td><?php echo $day; ?></td>
            <td> Booked </td>
            <?php for( $i=$open_time; $i<$close_time; $i+=550) { ?>
                <td class="numeric">2</td>
            <?php } ?>
            </tr>
            
            <tr>
            <td></td>
            <td> Actual </td>
            <?php for( $i=$open_time; $i<$close_time; $i+=550) { ?>
                <td class="numeric">4</td>
            <?php } ?>
            </tr>
    <?php } ?>
    
    </tbody>
    </table>
    
<script>
$(document).ready(function() {
    $('#myTable05').fixedHeaderTable({ altClass: 'odd', footer: true, fixedColumns: 1 });
});
</script>