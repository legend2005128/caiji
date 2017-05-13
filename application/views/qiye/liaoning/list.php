<div class="container-fluid">
  <table class="table table-hover">
    <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
      <?php foreach($list as $k=>$v ):?>
      <tr class="info">
          <td>
              <a href="<?php echo site_url('collection/liaoning/contents?revdate='.$v->revdate.'&enttype='.$v->enttype.'&id='.$v->id.'&optstate='.$v->optstate.'&pripid='.$v->pripid.'&regno='.$v->regno.'&entname='.$v->entname)?>"><?php echo $v->entname?></a>
          </td>
      </tr>
  <?php endforeach;?>
  </table>
    <?php echo $uri_back;?>
</div>