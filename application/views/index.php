<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>工商局信息模拟查询系统</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <style type="text/css">
        .main{margin: 50px auto;}
        caption{border: 1px solid #ddd;border-bottom: none;}
    </style>
</head>
<body>
    <div class="container-fluid main">
        <table class="table table-bordered text-center">
            <caption align="top" class="active text-center" style="background: #f5f5f5;">请选择要查询的省份</caption>
              <?php foreach ($list as $k_r=>$v_r):?>
                 <tr>
                 <?php foreach ($v_r as $k=>$v):?>
                 <td><a href="<?php echo $k;?>"><?php echo $v;?></a></td>
                 <?php endforeach;?>
                 </tr>
               <?php endforeach;?>
        </table>
    </div>
</body>
</html>
