<script type="text/javascript" src="<?php echo base_url('static/js/shandong.js')?>"></script>
<script type="text/javascript">
function set_code(){
	let val = $('#vc').val();
	let secode = toMD5Str(val);
	$("#secode").val(secode);
}
</script>
<div class="container-fluid main">
  <div class="container">
    <form action="<?php echo site_url('collection/shandong/ps');?>" method="post" class="formex">
      <div class="col-md-12">
        <div class="form-group">
          <label>山东省公司名称：</label>
          <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
          <label>验证码：</label>  <input type="hidden" name="secode" id="secode" />
          <input type="text" name="vc" class="form-control" id="vc" onblur ="javascript:set_code();">
        </div>
        <div class="form-group">
          <img title="点击验证码刷新" src="<?php echo site_url('collection/shandong/vc');?>"  onclick="show(this,'<?php echo site_url('collection/shandong/yzm');?>')" class="vc"/>
        </div>
        <div class="form-group">
          <input type="submit" value="查询" class="btn btn-primary" />
          <a href="/" class="btn btn-default">返回</a>
        </div>
      </div>
    </form>
  </div>
</div>
