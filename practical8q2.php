<!doctype html>
<body>
    <script>
    
    
      function ajaxFunction(varBrand){
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
                       if(xmlhttp.readyState == 4 && xmtp.status == 200){
                           document.getElementByName("ddlmodel").innerHTML = xmlhttp.responseText;
                           //if all ok display output by the server
                           
                       }
                   }
               xmlhttp.open("GET","p82.php?brand="+varBrand,true);
               
               xmlhttp.send();
               }
               
    </script>
    <form name="frmCar">
        CAR BRAND : 
        <select name="ddlBrand" onchange="ajaxFunction(this.value);">
            <option>Select</option>
            <option value="TOYOTA">TOYOTA</option>
            <option>PROTON</option>
            <option>HONDA</option>
            <option>PERODUA</option>
          
          
        </select>
        <br>
        <br>
        <br>
        model:<select name="ddlModel">
            <option>Select Model</option>
        </select>
    </form>
</body>