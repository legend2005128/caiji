<div class="container-fluid main">

  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
    <?php if(!$list):?>
       <tr class="info">
        <td>
          暂无数据！
        </td>
    </tr>
    <?php else: ?>
    <?php foreach($list as $k=>$v ):?>
    <tr class="info">
        <td>
          <a href="<?php echo site_url('collection/fujian/contents?url='.$v['url'].'&name='.$v['name']);?>"><?php echo $v['name']?></a> 
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif;?>
 </table>
<?php echo $uri_back;?>
</div>