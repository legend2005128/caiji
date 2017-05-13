<div class="contanier">
    <h2>
        <?php echo $info_data['base']['entname']; ?>    注册号/统一社会信用代码：<?php echo $info_data['base']['regno']; ?>
    </h2>
    <div class="bs-example bs-example-tabs">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active" role="presentation"><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">基本信息</a></li>
            <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile" aria-expanded="true">风险信息</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div aria-labelledby="home-tab" id="home" class="tab-pane fade  active in" role="tabpanel">
                <table class="table table-bordered " id="table">

                    <tr><th colspan="4" style="text-align:center;">基本信息</th></tr>
                    <tr>
                        <th width="20%">统一社会信用代码/注册号</th>
                        <td width="30%"><?php echo $info_data['base']['regno']; ?></td>
                        <th width="20%">名称</th>
                        <td width="30%"><?php echo $info_data['base']['entname']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%">类型</th>
                        <td><?php echo $info_data['base']['enttype']; ?></td>
                        <th >经营者</th>
                        <td><?php echo $info_data['base']['name']; ?></td>
                    </tr>
                    <tr>
                        <th>经营场所</th>
                        <td colspan="3"><?php echo $info_data['base']['oploc']; ?></td>
                    </tr>
                    <tr>
                        <th>组成形式</th>
                        <td width="20%"><?php echo $info_data['base']['compform']; ?></td>
                        <th>注册日期</th>
                        <td><?php echo $info_data['base']['regdate']; ?></td>
                    </tr>
                    <tr>
                        <th>经营范围</th>
                        <td colspan="3"><?php echo $info_data['base']['opscoandform']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%">登记机关</th>
                        <td><?php echo $info_data['base']['regorg']; ?></td>
                        <th>核准日期</th>
                        <td><?php echo $info_data['base']['issblicdate']; ?></td>
                    </tr>
                    <tr>
                        <th width="20%">登记状态</th>
                        <td><?php echo $info_data['base']['opstate']; ?></td>
                    </tr>

                    <tr width="95%"><th colspan="4" style="text-align:center;">变更信息</th></tr>
                    <tr width="95%">
                        <th width="15%" style="text-align:center;"> 变更事项</th>
                        <th width="35%" style="text-align:center;"> 变更前内容</th>
                        <th width="35%" style="text-align:center;"> 变更后内容</th>
                        <th width="15%" style="text-align:center;"> 变更日期</th>
                    </tr>
                    <tr width="95%"><td colspan="9" style="text-align:center;">暂无数据</td></tr>
                </table>
                <table class="table table-bordered " id="table_gd">

                </table>
            </div>
            <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                <table class="table table-bordered " id="table_chufa">

                    <tr width="95%"><th colspan="7" style="text-align:center;">行政处罚信息</th></tr>
                    <tr width="95%">
                        <th width="5%" style="text-align:center;"> 序号</th>
                        <th width="10%" style="text-align:center;">行政处罚<br>决定书文号 </th>
                        <th width="25%" style="text-align:center;"> 违法行为类型</th>
                        <th width="25%" style="text-align:center;"> 行政处罚内容</th>
                        <th width="15%" style="text-align:center;"> 作出行政处罚<br>决定机关名称</th>
                        <th width="10%" style="text-align:center;"> 作出行政处罚决定日期</th>
                        <th width="10%" style="text-align:center;">详情</th>
                    </tr>
                    <tr width="95%"><td colspan="7" style="text-align:center;">暂无数据</td></tr>
                </table>
                <table class="table table-bordered " id="table_jyyc">

                    <tr width="95%"><th colspan="6" style="text-align:center;">经营异常信息</th></tr>
                    <tr width="95%">
                        <th width="5%" style="text-align:center;">序号</th>
                        <th width="20%" style="text-align:center;">列入经营异常名录原因</th>
                        <th width="15%" style="text-align:center;"> 列入日期</th>
                        <th width="20%" style="text-align:center;">移出经营异常名录原因</th>
                        <th width="15%" style="text-align:center;">移出日期</th>
                        <th width="15%" style="text-align:center;"> 作出决定机关</th>
                    </tr>
                    <tr width="95%"><td colspan="6" style="text-align:center;">暂无数据</td></tr>
                </table>
                <table class="table table-bordered " id="table_yzwf">

                    <tr width="95%"><th colspan="6" style="text-align:center;"> 严重违法失信信息</th></tr>
                    <tr style="text-align: center">
                        <th width="10%" style="text-align: center">序号</th>
                        <th width="25%" style="text-align: center">列入严重违法失信企业名单原因</th>
                        <th width="15%" style="text-align: center">列入日期</th>
                        <th width="25%" style="text-align: center">移出严重违法失信企业名单原因</th>
                        <th width="10%" style="text-align: center">移出日期</th>
                        <th width="10%" style="text-align: center">作出决定机关</th>
                    </tr>
                    <tr width="95%"><td colspan="6" style="text-align:center;">暂无数据</td></tr>
                </table>
            </div>
            <?php echo $uri_back;?>
        </div>
    </div>
</div>