<div class="container-fluid main">
  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
    <?php foreach($list_js as $k=>$v ):?>
    <tr class="info">
        <td>
            <a href="<?php echo site_url('collection/jiangsu/do_info_list')."?corp_id=".$v[2]."&company_name=".$v[7]."&corp_org=".$v[1]."&corp_seq_id=".$v[3]."&".$v[8] ;?>"><?php echo $v[7];?></a>
        </td>
    </tr>
    <?php endforeach;?>
 </table>
<?php echo $uri_back;?>
</div>
