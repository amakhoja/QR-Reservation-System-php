<?php
include("config.php");
session_start();
$errors = array();

//Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
 
    //Load Composer's autoloader
    require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $Re_password = $_POST['Re-password'];

    // Check if email is already used
    $verifyemail = mysqli_prepare($con, "SELECT email FROM users WHERE email = ?");
    mysqli_stmt_bind_param($verifyemail, "s", $email);
    mysqli_stmt_execute($verifyemail);
    mysqli_stmt_store_result($verifyemail);
    if (mysqli_stmt_num_rows($verifyemail) != 0) {
        array_push($errors, "This email is already used.");
    }
    mysqli_stmt_close($verifyemail);

    // Check if passwords match
    if ($password != $Re_password) {
        array_push($errors, "The passwords do not match.");
    }

    // Proceed if there are no errors
    if (count($errors) == 0) {

        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
 
        try {
            //Enable verbose debug output
            $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;
 
            //Send using SMTP
            $mail->isSMTP();
 
            //Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';
 
            //Enable SMTP authentication
            $mail->SMTPAuth = true;
 
            //SMTP username
            $mail->Username = 'amakhoja@gmail.com';
 
            //SMTP password
            $mail->Password = 'irjvegbruatnbehr';
 
            //Enable TLS encryption;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
 
            //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->Port = 587;
 
            //Recipients
            $mail->setFrom('your_email@gmail.com', 'Reservation booking');
 
            //Add a recipient
            $mail->addAddress($email, $username);
 
            //Set email format to HTML
            $mail->isHTML(true);
 
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
 
            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
 
            $mail->send();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database using prepared statement
        $stmt = $con->prepare("INSERT INTO users (username, email, password,otp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword,$verification_code);
        
        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            // After successful registration, get the user's ID
            $stmt->close();
            $sql = mysqli_prepare($con, "SELECT id FROM users WHERE email = ?");
            mysqli_stmt_bind_param($sql, "s", $email);
            mysqli_stmt_execute($sql);
            $result = mysqli_stmt_get_result($sql);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            mysqli_stmt_close($sql);
            
            header("Location: verificationEmail.php?email=" . $email);
            exit();
        } else {
            // Handle error, e.g. duplicate username, email, etc.
            array_push($errors, "An error occurred while registering. Please try again.");
            $stmt->close();
        }
    }  catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
}}

?>
