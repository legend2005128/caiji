<div class="container-fluid main">
  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
     <?php foreach($list['title'] as $k=>$v ):?>
    <tr class="info">
        <td>
           <a href="<?php echo site_url('collection/tianjin/contents')."?name=".strip_tags($v)."&url=".str_replace('?','&',$list['url'][$k]);?>"><?php echo $v;?></a>
        </td>
    </tr>
    <?php endforeach;?>
 </table>
<?php echo $uri_back;?>
</div>