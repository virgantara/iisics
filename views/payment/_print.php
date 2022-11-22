<?php 
use app\helpers\MyHelper;

$font_size = '1.6em';


 ?>

<!-- <hr style="height:3px"> -->

<table width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>" width="20%">Received from</td>
        <td style="text-align:left;font-size:<?=$font_size?>" width="2%">: </td>
        <td style="text-align:left;font-size:<?=$font_size?>" width="78%"><?=(!empty($model->p) ? $model->p->name : '-')?></td>
    </tr>
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>">Paper ID</td>
        <td style="text-align:left;font-size:<?=$font_size?>" >: </td>
        <td style="text-align:left;font-size:<?=$font_size?>"><?=$model->abs_id?></td>
    </tr>
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>">Payment of</td>
        <td style="text-align:left;font-size:<?=$font_size?>" >: </td>
        <td style="text-align:left;font-size:1.2em"><?=$keterangan?></td>
    </tr>
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>">Currency</td>
        <td style="text-align:left;font-size:<?=$font_size?>" >: </td>
        <td style="text-align:left;font-size:<?=$font_size?>"><?=$model->pay_currency?></td>
    </tr>
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>">Amount</td>
        <td style="text-align:left;font-size:<?=$font_size?>" >: </td>
        <td style="text-align:left;font-size:<?=$font_size?>"><?=MyHelper::formatRupiah($model->pay_nominal,2)?></td>
    </tr>
    <tr>
        <td style="text-align:left;font-size:<?=$font_size?>">Payment date</td>
        <td style="text-align:left;font-size:<?=$font_size?>" >: </td>
        <td style="text-align:left;font-size:<?=$font_size?>"><?=(!empty($model->pay_date) ? date('Y-m-d H:i:s',strtotime($model->pay_date)) : "")?></td>
    </tr>
</table>
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td style="text-align:center;font-size:<?=$font_size?>" width="70%"></td>
        <td style="text-align:center;font-size:<?=$font_size?>" width="30%">
            Treasurer,<br>
            <img width="80px" src="<?=$imgdata?>"/>
            <br>
            <?=(!empty($chairman_payment) ? $chairman_payment->sys_content : 'PIC Pembayaran Belum Diisi')?></td>
    </tr>
    
</table>