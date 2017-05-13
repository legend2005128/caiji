<form action="<?php echo site_url('collection/jilin/ps');?>" method="post" class="formex">
    吉林公司名：<input type="text" name="name"> 验证码：<input type="text" name="vc"> <img src="<?php echo site_url('collection/jilin/vc');?>" alt="看不清楚请点击刷新验证码"  onclick="show(this,'<?php echo site_url('collection/jilin/yzm');?>')" />
    <input type="submit" value="查询" class="btn btn-primary" />  <a href="/" class="btn btn-default">返回</a>
</form>