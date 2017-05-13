<form action="<?php echo site_url('collection/zhixingren/ps');?>" method="post" class="formex">
    被执行人姓名/名称：<input type="text" name="pname"> <br>
    身份证号码/组织机构代码：<input type="text" name="cardNum"><br>
    执行法院范围：<br>
    验证码：<img src="<?php echo site_url('collection/anhui/vc');?>" alt="看不清楚请点击刷新验证码"  onclick="show(this,'<?php echo site_url('collection/anhui/vc');?>')" />
    
    <input type="submit" value="查询" />
</form>