<?php
class HomeController{
    public function __construct(){
        
    }
    public function index(){
        Response::json(["name" => "Todo API", "Version" => "2.1.1", "status" => "running", "data" => date("Y-m-d H:i:s")]);
    }
}
?>