<div class="contanier">
    <div class="bs-example bs-example-tabs">
        <ul role="tablist" class="nav nav-tabs" id="myTab">
            <li class="active" role="presentation"><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">基本信息</a></li>
            <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile" aria-expanded="true">风险信息</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div aria-labelledby="home-tab" id="home" class="tab-pane fade  active in" role="tabpanel">
                <?php echo $base?>
                <table cellpadding="0" cellspacing="0" class="detailsList">
                    <tr width="100%">
                        <th colspan="4" style="text-align:center;">变更信息</th>
                    </tr>
                    <tr width="100%">
                        <th width="15%" style="text-align:center;"> 变更事项</th>
                        <th width="25%" style="text-align:center;"> 变更前内容</th>
                        <th width="25%" style="text-align:center;"> 变更后内容</th>
                        <th width="10%" style="text-align:center;"> 变更日期</th>
                    </tr>
                </table>
                <?php echo $base_bg?>
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


