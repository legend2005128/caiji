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
                         <th colspan="4" class="text-center"><?php echo $base_data->ENTNAME ?></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="item">统一社会信用代码/注册号：</td>
                            <td  class="item"><?php echo $base_data->REGNO; ?></td>
                            <td  class="item">企业名称：</td>
                            <td  class="item"><?php echo $base_data->ENTNAME ?></td>
                        </tr>
                        <tr>
                             <td  class="item">类型：</td>
                             <td  class="item"><?php echo $base_data->ENTTYPE_CN ?></td>
                             <td  class="item">法人:</td>
                             <td  class="item"><?php echo $base_data->NAME ?></td>
                        </tr>
                        <tr>
                            <td class="item">注册资本：</td>
                             <td><?php echo $base_data->REGCAP ?></td>
                             <td class="item">成立日期：</td>
                             <td><?php echo $base_data->ESTDATE ?></td>
                        </tr>
                        <tr>
                            <td colspan="1" class="item">住所：</td>
                            <td colspan="3"><?php echo $base_data->DOM ?></td>
                        </tr>
                        <tr>
                            <td class="item">营业期限自：</td>
                            <td class="item"><?php echo $base_data->OPFROM ?></td>
                            <td class="item">营业期限至：</td>
                            <td class="item"><?php echo $base_data->OPTO ?></td>
                        </tr>
                        <tr>
                            <td colspan="1"  class="item">经营范围：</td>
                            <td colspan="3"><?php echo $base_data->OPSCOPE ?></td>
                        </tr>
                        <tr>
                            <td  class="item">登记机关：</td>
                             <td><?php echo $base_data->REGORG_CN ?></td>
                             <td  class="item">核准日期：</td>
                             <td><?php echo $base_data->APPRDATE ?></td>
                        </tr>
                        <tr>
                            <td colspan="1" >登记状态：</td>
                            <td colspan="3"><?php echo $base_data->REGSTATE_CN ?></td>
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
                            <th class="item" >股东/发起人类型</th>
                            <th class="item">股东/发起人</th>
                            <th class="item">证照/证件类型</th>
                            <th class="item">证照/证件号码</th>
                        </tr>
                        <?php foreach ($gd as $gd): ?>
                            <tr>
                                <td class="item"><?php echo $gd->INVTYPE_CN ?></td>
                                <td class="item"><?php echo $gd->INV ?></td>
                                <td class="item"><?php echo $gd->CERTYPE_CN ?></td>
                                <td class="item">***</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                <table class="table table-bordered " id="table_chufa">
                    <thead>
                        <tr class="title">
                            <th class="text-center" colspan="6">行政处罚信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th  class="item">行政处罚决定书文号</th>
                            <th  class="item">违法行为类型</th>
                            <th  class="item">行政处罚内容</th>
                            <th  class="item">作出行政处罚<br/>决定机关名称</th>
                            <th  class="item">作出行政处罚决定日期</th>
                            <th  class="item">公示日期</th>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered " id="table_jyyc">
                    <thead>
                        <tr class="title">
                            <th colspan="5" class="text-center">经营异常信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th  class="item">列入经营异常名录原因</th>
                            <th  class="item">列入日期</th>
                            <th  class="item">移出经营异常名录原因</th>
                            <th  class="item">移出日期</th>
                            <th  class="item">作出决定机关</th>
                        </tr>
                         <?php foreach ($jyyc as $yc): ?>
                        <tr >
                            <td class="item"><?php echo $yc->SPECAUSE_CN ?></td>
                            <td class="item"><?php echo $yc->ABNTIME ?></td>
                            <td class="item"><?php echo $yc->DECORG_CN ?></td>
                            <td class="item"></td>
                            <td class="item"></td>
                        </tr>
                         <?php endforeach; ?>
                    </tbody>
                </table>
                <table class="table table-bordered " id="table_yzwf">
                    <thead>
                        <tr class="title">
                            <th colspan="6" class="text-center" >严重违法失信信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr width="100%">
                            <th class="item">列入严重违法失信企业名单原因</th>
                            <th class="item">列入日期</th>
                            <th class="item">作出列入决定机关</th>
                            <th class="item">移出严重违法失信企业名单原因</th>
                            <th class="item">移出日期</th>
                            <th class="item">作出移出决定机关</th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php echo $uri_back; ?>
        </div>
    </div>
</div>

