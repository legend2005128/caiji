<script>
    function show(obj,url ){
        obj.src = url +'?random='+Math.random() ;
    }
    $(function () {
        /*标签切换*/
        $('#myTab a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    })
    //查询按钮失效操作
    $('.container').on('click',':submit',function(){
         //$(this).attr('disabled','disabled');
         $(this).val('搜索中...');
    })
</script>

</body>
</html>