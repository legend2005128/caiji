<div class="container-fluid main">
  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
    <?php foreach($list as $k=>$v ):?>
    <tr class="info">
      <td>
            <a href="<?php echo site_url('collection/zhejiang/contents'.str_replace('/appbasicinfo/doViewAppBasicInfoByLog.do','',$v['url']).'&name='.$v['name'])?>"><?php echo $v['name']?></a>
        </td>
    </tr>
    <?php endforeach;?>
 </table>
<?php echo $uri_back;?>
</div>