<?php

$con = new mysqli('localhost','root','1projectK!','oodoo_current');
if(!$con){
	die('mysql could not connected: '.mysql_error());
}
