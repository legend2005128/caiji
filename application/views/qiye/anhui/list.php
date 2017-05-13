<table class="table table-hover">
    <?php foreach($list as $k=>$v ):?>
    <tr class="info">
        <td>
            <a href="<?php echo site_url('collection/heilongjiang/contents?id='.$v['id'].'&name='.$v['name'])?>"><?php echo $v['name']?></a>
        </td>
    </tr>
<?php endforeach;?>
</table>