<script type="text/javascript" src="<?php echo base_url('static/js/jiangxi.js')?>"></script>
<script type="text/javascript">
    var url_base = '<?php echo site_url('collection/jiangxi/contents?pripid='); ?>';
    var trs ='';
    <?php foreach($list as $k=>$v ):?>
     var pid = set_codes('<?php echo $v->PRIPID;?>');
     var REGNO = set_codes('<?php echo $v->REGNO ;?>');
     trs +=
         '<tr class="info">'+
        '<td>'+
        '<a href="'+url_base+pid+'&name=<?php echo $v->ENTNAME;?>&zchregno='+REGNO+'&regno='+REGNO+'"><?php echo $v->ENTNAME?></a>' +
        '</td>'+
        '</tr>';
    <?php endforeach;?>
    $(document).ready(function () {
         $('.list').html(trs);
    })

    function set_codes( vals ){
        var b = new Base64();
        var ename = b.encode(vals);
        return ename;
    }
</script>


<div class="container-fluid main">
<table class="table table-hover list">
</table>
<?php echo $uri_back;?>
</div>
