<div class="container-fluid main">
    <div class="container">
        <form action="<?php echo site_url('collection/hubei/serchlist');?>" method="post" class="formex">
            <div class="col-md-12">
                <div class="form-group">
                    <label>湖北省公司名：</label>
                    <input type="text" name="name"  class="form-control">
                </div>
                <div class="form-group">
                    <label>验证码：</label>
                    <input type="text" name="code" class="form-control">
                </div>
                <div class="form-group">
                    <img id="img" src="<?php echo site_url('collection/hubei/verify');?>" border="0" alt="看不清楚请点击刷新验证码"
                         onclick="show(this,'<?php echo site_url('collection/hubei/verify');?>')"/>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <input type="submit" value="查询" class="btn btn-primary"/>
                        <a href="/" class="btn btn-default">返回</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
