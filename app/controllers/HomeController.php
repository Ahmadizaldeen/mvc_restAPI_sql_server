<?php
class HomeController{
    public function __construct(){
        echo"Sex and love";
    }
    public function index(){
        Response::json(["name" => "Todo API", "Version" => "2.1.1", "status" => "running"]);
    }
}
?>