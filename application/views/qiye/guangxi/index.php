<div class="container-fluid main">
    <div class="container">
        <form action="<?php echo site_url('collection/guangxi/serchlist');?>" method="post" class="formex">
            <div class="col-md-12">
                <div class="form-group">
                    <label>广西省公司名称：</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="form-group">
                    <label>验证码：</label>
                    <input type="text" name="code" class="form-control">
                </div>
                <div class="form-group">
                    <img title="点击验证码刷新" src="<?php echo site_url('collection/guangxi/verify');?>"  onclick="show(this,'<?php echo site_url('collection/guangxi/verify');?>')" class="vc"/>
                </div>
                <div class="form-group">
                    <input type="submit" value="查询" class="btn btn-primary" />
                    <a href="/" class="btn btn-default">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>


