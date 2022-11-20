<?php 
use app\helpers\MyHelper;

$font_size = '1.6em';


 ?>
<table border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td width="25%">
            <br><br>
            <span style="font-size:14px">RECEIPT</span>
        </td>
        <td width="50%" style="text-align: center;">
            <span style="font-size:16px;font-weight: bold;">SEMINAR NASIONAL</span><br>
            <span style="font-size:16px;font-weight: bold;">SAINS DAN TEKNOLOGI <?=$year?></span><br>
            <!-- <span style="font-size:14px;font-weight: bold;"></span><br> -->
            <span style="font-size:14px;font-weight: bold;"><?=(!empty($seminar_institution) ? $seminar_institution->sys_content : '')?></span>
        </td>
        <td width="25%" style="text-align:center;">
            
        </td>
    </tr>
    
</table>
<table width="100%" cellpadding="" cellspacing="0">
    <tr>
        <td width="100%" style="border-top:1px solid  black;"></td>
    </tr>
</table>
