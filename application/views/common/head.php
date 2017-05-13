<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>企业信息查询</title>
        <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .contanier{margin:50px 100px;}
            table{margin: 20px 0;}
            .item{text-align: right;width: 180px;}
            /* .title{background: #F0F2DF;}*/
            .formex img{cursor : pointer;width:150px;height:30px;}
            .main{margin-top: 100px;}
            caption{border: 1px solid #ddd;border-bottom: none;}
            table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
                border:1px solid #ddd;
            }
            table > thead > tr > th,
            table > tbody > tr > th,
            table > tfoot > tr > th,
            table > thead > tr > td,
            table > tbody > tr > td,
            table > tfoot > tr > td {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd;
                border-right: 1px solid #ddd;
            }
            table > thead > tr > th {
                vertical-align: bottom;
            }
            table > caption + thead > tr:first-child > th,
            table > colgroup + thead > tr:first-child > th,
            table > thead:first-child > tr:first-child > th,
            table > caption + thead > tr:first-child > td,
            table > colgroup + thead > tr:first-child > td,
            table > thead:first-child > tr:first-child > td {
                border-top: 0;
            }
            table > tbody + tbody {
                border-top: 2px solid #ddd;
            }
            table .table {
                background-color: #fff;
            }
            table-condensed > thead > tr > th,
            table-condensed > tbody > tr > th,
            table-condensed > tfoot > tr > th,
            table-condensed > thead > tr > td,
            table-condensed > tbody > tr > td,
            table-condensed > tfoot > tr > td {
                padding: 5px;
            }
            table-bordered {
                border: 1px solid #ddd;
            }
            table-bordered > thead > tr > th,
            table-bordered > tbody > tr > th,
            table-bordered > tfoot > tr > th,
            table-bordered > thead > tr > td,
            table-bordered > tbody > tr > td,
            table-bordered > tfoot > tr > td {
                border: 1px solid #ddd;
            }
            table-bordered > thead > tr > th,
            table-bordered > thead > tr > td {
                border-bottom-width: 2px;
            }
            table-striped > tbody > tr:nth-of-type(odd) {
                background-color: #f9f9f9;
            }
            table-hover > tbody > tr:hover {
                background-color: #f5f5f5;
            }
            h2{
                text-align: center;
            }
        </style>
    </head>
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdn.bootcss.com/handlebars.js/4.0.3/handlebars.min.js"></script>
    <script>
        document.onkeypress = function (event) {
            if (event.keyCode == 13 || event.keyCode == 108) {
                return false;
            }
        }
    </script>
    <body>
