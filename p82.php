<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$brand = trim($_GET['brand']);
//2D ARRAy
$model = array(
    "TOYOTA"=>array(
        "TOYATA AE86",
        "TOYATA AAA"
    ),
    "HONDA" =>array(
        "HONDA MEOW",
        "HONDA CITY"
    )

);

$output = $model[$brand];


foreach($output as $key => $value){
    echo '<option value = "'.$value.'">'.$value.'</option>';
}
