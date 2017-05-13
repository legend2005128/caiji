<div class="container-fluid main">
    <table class="table table-hover">
    <?php
    if ($list['successed'] == true) {
        ?>
            <a href="<?php echo site_url('collection/guizhou'); ?>">返回搜索页</a>
            <caption align="top" class="active text-center" style="background: #f5f5f5;">查询到的结果</caption>
        <?php
        foreach ($list['data'] as $key => $val) {
            ?>
            <tr class="info">
                <td>
                    <a href="<?php echo site_url('collection/guizhou/detail') . '?info_url='.$val['nbxh'].'&ztlx='.$val['ztlx'];?>" ><?php echo $val['qymc'];?></a>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr class="info">
            <td><?php echo $list['message']; ?></td>
        </tr>
        <tr class="info">
            <td>
                <a href="<?php echo site_url('collection/guizhou'); ?>">返回搜索页</a>
            </td>
        </tr>
    <?php
    }
    ?>
    </table>
</div>