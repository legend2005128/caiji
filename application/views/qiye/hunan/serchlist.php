<div class="container-fluid main">
    <table class="table table-hover">
    <?php
    if (!empty($list)) {
        ?>
            <a href="<?php echo site_url('collection/hunan'); ?>">返回搜索页</a>
            <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
        <?php
        foreach ($list as $key => $val) {
            ?>
            <tr class="info">
                <td>
                    <a href="<?php echo site_url('collection/hunan/detail') . '?info_url='.$val['info_url'];?>" ><?php echo $val['title'];?></a>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr class="info">
            <td>暂时数据，请重新搜索</td>
        </tr>
        <tr class="info">
            <td>
                <a href="<?php echo site_url('collection/hunan'); ?>">返回搜索页</a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
</div>