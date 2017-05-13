<?php
/**
 * 
 * 计划任务
 * 
 */
class Cronjob extends MY_Controller
{
    protected $error_msg ;
            
    function __construct()
    {
        parent::__construct();
    }
 
    public function index()
    {
        exit('11');
        $this->load->view('cronjob/index');
    }
    
}
?>