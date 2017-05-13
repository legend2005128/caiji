<div class="container-fluid main">
  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
    <?php foreach($list as $k=>$v ):?>
    <tr class="info">
    <td>
            <a href="<?php echo site_url('collection/shandong/contents?enttype='.$v->enttype.'&encrptpripid='.$v->encrptpripid."&entname=".$v->entname);?>"><?php echo $v->entname?></a>
        </td>
    </tr>
    <?php endforeach;?>
 </table>
<?php echo $uri_back;?>
</div>
