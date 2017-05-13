<div class="contanier">
    <div class="bs-example bs-example-tabs">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active" role="presentation"><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">基本信息</a></li>
            <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile" aria-expanded="true">风险信息</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div aria-labelledby="home-tab" id="home" class="tab-pane fade  active in" role="tabpanel">
                <?php echo $base?>
            </div>
            <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                <?php echo $chufa?>
                <?php echo $jyyc?>
                <?php echo $yzwf?>
            </div>
<?php echo $uri_back;?>
        </div>
    </div>

</div>
<script type="text/javascript">
    var trs ='';
    <?php foreach($data_gd as $k=>$v ):?>
                     trs +='<tr>'+'<td><?php echo $v->invtypeName?></td>'
                                                        +  '<td><?php echo $v->inv?></td>'
                                                        + ' <td><?php echo $v->blictypeName?></td>'
                                                        + '<td></td>'
                                                        + '<td></td>'
                                                        + '</tr>';
    <?php endforeach;?>
       var jyyctrs ='';
    <?php foreach($data_jyyc as $k=>$v ):?>
                     jyyctrs += '<tr>'+'<td><?php echo ($k+1)?></td>'
                                                        +  '<td><?php echo $v->specauseName?></td>'
                                                        + ' <td><?php echo $v->abnDate?></td>'
                                                        + '<td></td>'
                                                        + '<td></td>'
                                                        + '<td><?php echo $v->lrregorgName?></td>'
                                                        + '</tr>';
    <?php endforeach;?>
           var yzwftrs ='';
//    <?php foreach($data_yzwf as $k=>$v ):?>
//                     yzwftrs +='<tr>'+'<td><?php echo ($k+1)?></td>'
//                                                        +  '<td><?php echo $v->specauseName?></td>'
//                                                        + ' <td><?php echo $v->abnDate?></td>'
//                                                        + '<td></td>'
//                                                        + '<td></td>'
//                                                  + '<td><?php echo $v->lrregorgName?></td>'
//                                                        + '</tr>';
//    <?php endforeach;?>
   
    $(document).ready(function () {
         $('#tzr_itemContainer').html(trs);
          $('#jyyc_itemContainer').html(jyyctrs);
        
    })
</script>

