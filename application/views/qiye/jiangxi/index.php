<script type="text/javascript" src="<?php echo base_url('static/js/jiangxi.js')?>"></script>
<script type="text/javascript">
function set_code(){
					var b = new Base64();
					var ename = b.encode($.trim($("#name").val() )); 
					$("#ename").val(ename);
}
</script>
<div class="container-fluid main">
  <div class="container">
    <form action="<?php echo site_url('collection/jiangxi/ps');?>" method="post" class="formex">
      <div class="col-md-12">
        <div class="form-group">
          <label>  江西省公司名称：</label>
          <input type="text" name="name"  id ="name" onblur ="javascript:set_code();" class="form-control">
        </div>
        <div class="form-group">
          <label>验证码：</label>
          <input type="text" name="vc" id="vc" class="form-control">
        </div>
        <div class="form-group">
       <input type="hidden" name="ename" id="ename">   <img title="点击验证码刷新" src="<?php echo site_url('collection/jiangxi/vc');?>"  onclick="show(this,'<?php echo site_url('collection/jiangxi/yzm');?>')" class="vc"/>
        </div>
        <div class="form-group">
          <input type="submit" value="查询" class="btn btn-primary" />
          <a href="/" class="btn btn-default">返回</a>
        </div>
      </div>
    </form>
  </div>
</div>
