<table class="table table-condensed">
    <tr class="active">
        <th>“<?php echo $kw;?>”的检索记录 :</th>
    </tr>
    <?php foreach($lists as $key=>$val ):?>
    <tr class="info">
        <td><a href="<?php echo site_url('index/show_detail?id='.$val['id']);?>"><?php  echo $val['company_name'];?></a>&nbsp;&nbsp; [<?php echo $val['province'];?>]</td>
    </tr>
    <?php endforeach;?>
     <tr class="info">
        <td><?php echo $page;?></td>
    </tr>
      <tr class="info">
        <td>查询的信息最多显示50条记录，请点击“<a href="<?php echo $backurl;?>">重新查询</a>” 输入更精准的查询条件</td>
    </tr>
   
</table>