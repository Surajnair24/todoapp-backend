<?php
header("Access-Control-Allow-Origin:*"); 
header("Access-Control-Allow-Method:GET,POST");
header("Access-Control-Allow-Headers:*");

$request = file_get_contents("php://input");
$data=json_decode($request);
$username =$data->username;
$password =$data->password;
$display_name=$data->display_name;

$con = new mysqli("localhost","root","","react")or die("error establishing connection");
$query = "insert into users(username,password,display_name) values('$username',md5('$password'),'$display_name');";
$res=$con -> query($query);
if($res){
    echo"success";
}
else{
    echo"error";
}
?>