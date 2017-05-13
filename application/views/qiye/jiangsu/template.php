<div class="contanier">
    <div class="bs-example bs-example-tabs">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active" role="presentation"><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">基本信息</a></li>
            <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile" aria-expanded="true">风险信息</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div aria-labelledby="home-tab" id="home" class="tab-pane fade  active in" role="tabpanel">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr class="title">
                            <th colspan="4" class="text-center"><?php echo $base_data->C2 ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="item">统一社会信用代码</td>
                            <td><?php echo $base_data->C1 ?></td>
                            <td class="item">名称</td>
                            <td><?php echo $base_data->C2 ?></td>
                        </tr>
                        <tr>
                            <td class="item">类型</td>
                            <td><?php echo $base_data->C3 ?></td>
                            <td class="item">法定代表人</td>
                            <td><?php echo $base_data->C5 ?></td>
                        </tr>
                        <tr>
                            <td class="item">注册资本</td>
                            <td><?php echo $base_data->C6 ?></td>
                            <td class="item">成立日期</td>
                            <td><?php echo $base_data->C9 ?></td>
                        </tr>
                        <tr>
                            <td class="item">住所</td>
                            <td colspan="3"><?php echo $base_data->C7 ?></td>
                        </tr>
                        <tr>
                            <td class="item">营业期限自</td>
                            <td><?php echo $base_data->C4 ?></td>
                            <td class="item">营业期限至</td>
                            <td><?php echo $base_data->C12 ?></td>
                        </tr>
                        <tr>
                            <td class="item">经营范围</td>
                            <td colspan="3"><?php echo $base_data->C8 ?></td>
                        </tr>
                        <tr>
                            <td class="item">登记机关</td>
                            <td><?php echo $base_data->C11 ?></td>
                            <td class="item">核准日期</td>
                            <td><?php echo $base_data->C9 ?></td>
                        </tr>
                        <tr>
                            <td class="item">登记状态</td>
                            <td colspan="3"><?php echo $base_data->C13 ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered " id="table_gd">
                    <thead>
                        <tr class="title">
                            <th colspan="5" class="text-center" >股东（发起人) 信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="item" >股东/发起人类型</td>
                            <td class="item">股东/发起人</td>
                            <td class="item">证照/证件类型</td>
                            <td class="item">证照/证件号码</td>
                        </tr>
                        <?php foreach ($gd_data as $gd): ?>
                            <tr>
                                <td class="item"><?php echo $gd->C1 ?></td>
                                <td class="item"><?php echo $gd->C2 ?></td>
                                <td class="item"><?php echo $gd->C3 ?></td>
                                <td class="item">***</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" class="detailsList" name="biangengxinxiTAB">
                    <tr width="95%">
                        <th colspan="5" style="text-align: center;">变更信息</th>
                    </tr>
                    <tr width="95%">
                        <th width="188px" style="text-align: center;">变更事项</th>
                        <th width="30%" style="text-align: center;">变更前内容</th>
                        <th width="30%" style="text-align: center;">变更后内容</th>
                        <th width="20%" style="text-align: center;">变更日期</th>
                    </tr>
                    <?php foreach ($biangeng_data as $bg): ?>
                        <tr >
                            <td><?php echo $bg->C1 ?></td>
                            <td ><?php echo $bg->C2 ?></td>
                            <td><?php echo $bg->C3 ?></td>
                            <td><?php echo $bg->C4 ?></td>
                        </tr> 
                    <?php endforeach; ?>
                </table>
            </div>
            <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                <table class="table table-bordered " id="table_chufa">
                    <thead>
                        <tr class="title">
                            <th class="text-center" colspan="5">行政处罚信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="item">行政处罚 决定书文号</td>
                            <td class="item">违法行为类型</td>
                            <td class="item" >行政处罚内容</td>
                            <td class="item">作出行政处罚 决定机关名称</td>
                            <td class="item">作出行政处罚 决定日期
                                </th>
                        </tr>
                        <?php foreach ($chufa_data as $cf): ?>
                            <tr >
                                <td><?php echo $cf->C1 ?></td>
                                <td ><?php echo $cf->C2 ?></td>
                                <td><?php echo $cf->C3 ?></td>
                                <td><?php echo $cf->C4 ?></td>
                                <td ><?php echo $cf->C5 ?></td>
                            </tr> 
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <table class="table table-bordered " id="table_jyyc">
                    <tr width="95%">
                        <th colspan="6" style="text-align: center;">经营异常信息</th>
                    </tr>
                    <tr width="95%">
                        <th width="25%" style="text-align: center;">列入经营异常名录原因</th>
                        <th width="10%" style="text-align: center;">列入日期</th>
                        <th width="27%" style="text-align: center;">移出经营异常名录原因</th>
                        <th width="10%" style="text-align: center;">移出日期</th>
                        <th width="10%" style="text-align: center;">作出决定机关</th>
                    </tr>
                    <?php foreach ($jyyc_data as $yc): ?>
                        <tr >
                            <td><?php echo $yc->C1 ?></td>
                            <td ><?php echo $yc->C2 ?></td>
                            <td><?php echo $yc->C3 ?></td>
                            <td ><?php echo $yc->C4 ?></td>
                            <td ><?php echo $yc->C5 ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <table class="table table-bordered " id="table_yzwf">

                    <thead>
                        <tr class="title">
                            <th colspan="6" class="text-center" >严重违法失信信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr width="100%">
                            <td class="item">列入严重违法失信企业名单原因</td>
                            <td class="item">列入日期</td>
                            <td class="item">作出列入决定机关</td>
                            <td class="item">移出严重违法失信企业名单原因</td>
                            <td class="item">移出日期</td>
                            <td class="item">作出移出决定机关</td>
                        </tr>
                        <?php foreach ($yzwf_data as $wf): ?>
                            <tr >
                                <td><?php echo $wf->C1 ?></td>
                                <td ><?php echo $wf->C2 ?></td>
                                <td><?php echo $wf->C3 ?></td>
                                <td ><?php echo $wf->C4 ?></td>
                                <td ><?php echo $wf->C5 ?></td>
                                <td ><?php echo $wf->C6 ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $uri_back; ?>
        </div>
    </div>
</div>
<script id="table-template_base_info" type="text/x-handlebars-template">

</script>
<script id="table-template_gd_info" type="text/x-handlebars-template">

</script>
<script  id="table-template_xzcf_info" type="text/x-handlebars-template">

</script>
<script  id="table-template_jyyc_info" type="text/x-handlebars-template">
    <thead>
    <tr class="title">
    <th colspan="5" class="text-center">经营异常信息</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td  class="item">列入经营异常名录原因</td>
    <td  class="item">列入日期</td>
    <td  class="item">移出经营异常名录原因</td>
    <td  class="item">移出日期</td>
    <td  class="item">作出决定机关</td>
    </tr>
    {{#each info}}
    <tr >
    <td >{{C1}}</td>
    <td >{{C2}}</td>
    <td >{{C3}}</td>
    <td >{{C4}}</td>
    <td >{{C5}}</td>
    </tr>
    {{/each}}
    </tbody>
</script>
<script  id="table-template_yzwf_info" type="text/x-handlebars-template">
    <thead>
    <tr class="title">
    <th colspan="6" class="text-center" >严重违法失信信息</th>
    </tr>
    </thead>
    <tbody>
    <tr width="100%">
    <td class="item">列入严重违法失信企业名单原因</td>
    <td class="item">列入日期</td>
    <td class="item">作出列入决定机关</td>
    <td class="item">移出严重违法失信企业名单原因</td>
    <td class="item">移出日期</td>
    <td class="item">作出移出决定机关</td>
    </tr>
    {{#each info}}
    <tr >
    <td >{{C1}}</td>
    <td >{{C2}}</td>
    <td>{{C3}}</td>
    <td >{{C4}}</td>
    <td >{{C5}}</td>
    <td >{{C6}}</td>
    </tr>
    {{/each}}
    </tbody>
</script>

