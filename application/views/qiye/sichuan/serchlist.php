<div class="container-fluid main">
<table class="table table-hover">
    <?php
    if (!empty($list)) {
        ?>
            <a href="<?php echo site_url('collection/sichuan'); ?>">返回搜索页</a>
            <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
        <?php
        foreach ($list as $key => $val) {
            ?>
            <tr class="info">
                <td>
                    <a href="<?php echo site_url('collection/sichuan/detail') . '?info_url='.$val['info_url'].'&title='.$val['title'];?>" ><?php echo $val['title'];?></a>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr class="info">
            <td>验证码错误！</td>
        </tr>
        <tr class="info">
            <td>
                <a href="<?php echo site_url('collection/sichuan'); ?>">返回搜索页</a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
</div>