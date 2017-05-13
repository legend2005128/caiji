<div class="contanier">
    <div class="bs-example bs-example-tabs">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active" role="presentation"><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">基本信息</a></li>
            <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile" aria-expanded="true">风险信息</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div aria-labelledby="home-tab" id="home" class="tab-pane fade  active in" role="tabpanel">
                <?php echo @$base?>
            </div>
            <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                <?php echo @$chufa?>
                <?php echo @$jyyc?>
                <?php echo @$yzwf?>
            </div>
            <?php echo $uri_back;?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var trs ='';
    <?php foreach($base_gd as $k=>$v ):?>
                     trs +='<tr>'+'<td><?php echo $v->invtype?></td>'
                                                        +  '<td><?php echo $v->inv?></td>'
                                                        + ' <td><?php echo $v->blictype?></td>'
                                                        + '<td><?php echo $v->blicno?></td>'
                                                        + '</tr>'
    <?php endforeach;?>
    var trs_bg ='';
    <?php foreach($base_bg as $k=>$v ):?>
                     trs_bg +='<tr>'+'<td><?php echo $v->altitem?></td>'
                                                        +  '<td><?php echo $v->altbe ?></td>'
                                                        + ' <td><?php echo $v->altbe?></td>'
                                                        + '<td><?php echo date('Y-m-d',($v->altdate->time)/1000);?></td>'
                                                        + '</tr>';
    <?php endforeach;?>
     var trs_jyyc= '';   
     <?php foreach($jyyc_con as $k=>$v ):?>
                     trs_jyyc +='<tr>'+'<td><?php echo ($k+1)?></td>'
                                                        +  '<td><?php echo $v->specause ?></td>'
                                                        + '<td><?php echo date('Y-m-d',($v->abntime->time)/1000);?></td>'
                                                        + '<td><?php echo $v->decorg;?></td>'
                                                        + '</tr>';
    <?php endforeach;?>
    $(document).ready(function () {
         $('#czxxtable').html(trs);
         $('#bgsxtable').html(trs_bg);
         $('#gsgsjyyclist').html(trs_jyyc);
    })
</script>


