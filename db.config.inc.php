<?php
 $conn = mysqli_connect('localhost','jimmy','test123','movies_app');
 if(!$conn){
    echo 'error Connecting to Db' . mysqli_connect_error();
 }
?>