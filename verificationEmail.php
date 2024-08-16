<?php
include("config.php");
session_start();
$errors=array();
$success=array();
if(isset($_GET['email'])){
    $email=$_GET['email'];
    array_push($success,"verification code was send to $_GET[email]");
}

if(isset($_POST['submit'])){
    
    $optCheck=$_POST['opt'];
    $sql= "SELECT * FROM users WHERE email= '$email' ";
    $result= mysqli_query($con,$sql);
    
    if(mysqli_num_rows($result)==1){
          $row=mysqli_fetch_assoc($result);
          
         
         if($row['otp']==$optCheck){
           
            $update = "UPDATE users SET active = 'activated' WHERE users.email='$_GET[email]' ";
            $query= mysqli_query($con,$update);
            header("location:Home_page.php");
            exit();
         }else{
            $error="incorrect verification code";
            
        }
    }

}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
    <link rel="stylesheet" href="./style.css">
    <title>Sign in</title>
</head>
<body style="background-color: white;" >


   
  
   <?php 
    if(count($success)> 0) { ?>
        <div class="success">
         <?php  foreach($success as $succes):?>
             <p><?php echo $succes; ?> </p>
             <?php endforeach ?>
        </div>
        <?php }
   ?>
        
    
   
        <div class="Sign-container">
         
        </div>
    
        <div class="form">
            <h2>Email verification</h2>

             <?php
              if(isset($error)){ echo"<div class='error'> $error</div>";}
            ?>
           
            <form class="Sign-form" action="" method="post">
            
                <label for="opt" >Enter verification code here</label>
                <input type="text" autocomplete="off"  name="opt" id="opt" maxlength="6" required>
                
                
                <input class="submit" name="submit" type="submit" value="verification">
                

            </form>
            
          </div>
          
          
        </div>
        
    

    
</body>
</html>
