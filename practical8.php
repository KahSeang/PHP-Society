<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
               <script>
               function ajaxFunction(){
                  //check the browser can support ajax or not
                  if(window.XMLHttpRequest){
                      xmlhttp = new XMLHttpRequest();// create object
                      
                  }
                  // settle old browser
                  else if(ActiveXObject("Microsoft.XMLHTTP")){
                      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                  }
                  else{
                      alert("PROBLEM");
                      return false;
                  }
                  
                     xmlhttp.onreadystatechange=function(){
                       if(xmlhttp.readyState == 4){
                           document.q1.txtTime.value = xmlhttp.responseText;
                           
                       }
                   }
               xmlhttp.open("GET","time.php",true);
               xmlhttp.send();
               }
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
          
                
               </script>

               <form name="q1">
                   Name:<input type="text" name="txtName" onchange="ajaxFunction()"><br>
                   Time:<input type="text" name="txtTime" >
               </form>
        
    </body>
</html>
