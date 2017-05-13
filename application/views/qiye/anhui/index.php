<form action="<?php echo site_url('collection/anhui/ps');?>" method="post" class="formex">
    安徽公司名：<input type="text" name="name"> 验证码：<input type="text" name="vc"> <img src="<?php echo site_url('collection/anhui/vc');?>" alt="看不清楚请点击刷新验证码"  onclick="show(this,'<?php echo site_url('collection/anhui/vc');?>')" />
    <input type="submit" value="查询" />
</form>