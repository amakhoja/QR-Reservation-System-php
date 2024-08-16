
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
    <link rel="stylesheet" type="text/css" href="../Reservation System website/style.css">
    <title>Sign Up</title>
</head>
<body style="background-color: white;" >
<?php include("rigster.php"); 
      
?>
    <header>
      
    </header>
     <div class="Sign-container">
          <div class="logo">
            <a href="Home_page.php"><img src=""></a>
        </div>
        <div class="form" >
            <h2>Create account</h2>
            <?php
   if(count($errors)> 0) { ?>
   <div class="error">
    <?php  foreach($errors as $error):?>
        <p><?php echo $error; ?> </p>
        <?php endforeach ?>
   </div>
   <?php } ?>

            <form class="Sign-form" action="sign-up.php" method="POST">
        
                <label for="username" >Username</label>
                <input type="text" autocomplete="off" name="username" maxlength="128" id="username" required>
                <label for="email" >Email</label>
                <input type="email" autocomplete="off" name="email" maxlength="128" id="email" required>
                <label for="phone" >phone Number</label>
                <input type="tel"  maxlength="10" placeholder="05********" autocomplete="off" name="mobile"  id="mobile" required>
                <label for="password">password</label>
                <input type="password" autocomplete="off" name="password" id="password" required>
                <label for="password">Re-enter password</label>
                <input type="password" autocomplete="off" name="Re-password" id="Re_password" required>

                <input class="submit" type="submit" name="submit" value="Sign Up">
               
                <p> Already have an account?<a href="sign-in.php">Sign In</a></p>

            </form>   
          </div> 
        </div> 
        <style>
                body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .Sign-container {
            width: 100%;
            max-width: 400px;
            margin: 5% auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .Sign-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .Sign-container form {
            display: flex;
            flex-direction: column;
        }

        .Sign-container label {
            margin-top: 10px;
        }

        .Sign-container input {
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .Sign-container .submit {
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 4px;
            color: white;
            background-color: #007bff; /* Blue color for the button */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .Sign-container .submit:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .Sign-container p {
            text-align: center;
            margin-top: 20px;
        }

        .Sign-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .Sign-container p a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        header {
    background-color: #007bff; /* A modern blue shade */
    color: white;
    padding: 10px 0;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

header nav {
    display: flex;
    justify-content: center;
    align-items: center;
}

header nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    margin: 0;
}

header nav ul li {
    padding: 0 15px;
}

header nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

header nav ul li a:hover {
    color: #d0e2ff; /* Light blue on hover for links */
}

@media (max-width: 768px) {
    header nav ul li {
        padding: 0 10px;
    }
}

        
    </style> 
    <script>
        // Add your JavaScript code here for form validation or other interactivity
        document.querySelector('.Sign-form').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('Re_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>  
</body>
</html>

