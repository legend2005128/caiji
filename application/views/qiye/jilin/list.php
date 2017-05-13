<table class="table table-hover">
    <?php foreach($list as $k=>$v ):?>
    <tr class="info">
        <td>
            <a href="<?php echo site_url('collection/jilin/contents?revdate='.$v->revdate.'&enttype='.$v->enttype.'&id='.$v->id.'&optstate='.$v->optstate.'&pripid='.$v->pripid.'&regno='.$v->regno.'&entname='.$v->entname)?>"><?php echo $v->entname?></a>
        </td>
    </tr>
<?php endforeach;?>
</table>