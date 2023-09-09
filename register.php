<?php
 include './db.config.inc.php';

 $name=$email=$password='';
 $users_sql =  "SELECT name,id,email,password FROM users";
 $res = mysqli_query($conn,$users_sql);
 $user = mysqli_fetch_assoc($res);
 //print_r($user);

 if(isset($_POST['submit-register'])){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['pwd'])){
        header('Location:./index.php?error=MissingInputFields');
        exit();
    }
    else{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        if(!preg_match('/^[a-zA-Z]/',$name)){
            header('Location:./index.php?error=InvalidNameFormat&email=' . $email);
            exit();
        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
             header('Location:./index.php?error=InvalidEmailFormat&name=' . $name);
             exit();
         }
        else{
            $name = mysqli_real_escape_string($conn,$name);
            $email = mysqli_real_escape_string($conn,$email);
            $password = mysqli_real_escape_string($conn,$password);

            $hashPwd = password_hash($password,PASSWORD_DEFAULT);
                 
            $sql = "INSERT INTO users(name,email,password) VALUES('$name','$email','$hashPwd')";

             if($email === $user['email']){
                 header('Location:./index.php?error=UserAlreadyExists');
                 exit();
             }
             else{
               if(mysqli_query($conn,$sql)){
                   header('Location:./login.php?userCreate=true');
                   exit();
               }
               else{
                   header('Location:./index.php?error=SqlStatementError');
                   exit();
               }
             }
        }
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="styles.css"/>
</head>
<body>
    <section class="registerform">
    <form action="./register.php" method="POST">
        <h3>Register</h3>
        <input type="text" name="name" placeholder="Full Name">
        <input type="email" name="email" placeholder="Email Address">
        <input type="password" name="pwd" placeholder="Password">
        <button type="submit" name="submit-register">Create Account</button>
        <p>Already have an Account?<a href="./index.php">Login</a></p>
    </form>
    </section>
</body>
</html>