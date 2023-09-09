<?php
 include './db.config.inc.php';
 session_start();

 if(isset($_POST['submit-file'])){
      $name = $_POST['file-name'];
      $title = $_POST['Image-Title'];
      $title = strtolower(str_replace(' ','-',$title));
      $description = $_POST['Image-Description'];

      $file = $_FILES["media-file"];
      print_r($file);
     $fileName = $file['name'];
     $fileError = $file['error'];
     $fileType = $file['type'];
     $fileTmpName = $file['tmp_name'];
     $fileSize = $file['size'];

     $fileExt = explode('.',$fileName);
     $fileActualExt = strtolower(end($fileExt));

     $allowedExts = array('jpeg','jpg','png');

     if(in_array($fileActualExt,$allowedExts)){
       if($fileError === 0){
         if($fileSize < 200000){
            $ActualFileName = $name . "."  . $fileActualExt;
            $fileDestination = "./img/gallery/" . $ActualFileName;

            if(empty($_POST['file-name']) || empty($_POST['Image-Title']) || empty($_POST['Image-Description'])){
               header('Location:./index.php?error=MissingInputFields&upload=empty');
            }
            else{
               $sql = "INSERT INTO movies(name,title,description,image_path) VALUES('$name','$title','$description','$fileDestination')";
               if(mysqli_query($conn,$sql)){
                  header('Location:./index.php?upload=success');
                  move_uploaded_file($fileTmpName,$fileDestination);
                  exit();
               }
               else{
                  header('Location:./index.php?error=ErrorFileUpload');
                  exit();
               }
            }
         }
         else{
            header('Location:./index.php?error=FileSizeExceededLimit');
            exit();
         }
       }else{
         header('Location:./index.php?error = FileCorrupted');
         exit();
       }
     }else{
       header('Location:./index.php?error=fileExtensionNotAllowed');
       exit();
     }
}
$get_movie_details = 'SELECT id,name,title,description,image_path FROM movies';

$res = mysqli_query($conn,$get_movie_details);

$movies = mysqli_fetch_all($res,MYSQLI_ASSOC);
//print_r($movies);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Hub</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
    <div class="header">
       <div class="left">
       <h2>JIMTUTS</h2>
       <span></span>
        <li>PORTFOLIO</li>
        <li>ABOUT ME</li>
        <li>CONTACT</li>
       </div>
       <div class="right">
          <?php
            if(isset($_SESSION['session_id'])){
               echo ' <form action="./logout.php" method="POST">
               <input type="submit" name="submit-logout" value="Logout" class="button">
               </form>';
            }
          ?>
        <button class="button">CASES</button>
       </div>
    </div> 
    <section class="container">
       <h3>GALLERY</h3>
       <div class="movie_section">
        <?php foreach($movies as $movie){?>
         <div class="movie-container">
         <img src="<?php echo $movie['image_path']?>"/>
            <h2><?php echo htmlspecialchars($movie['title']) ?></h2>
            <p><?php echo htmlspecialchars($movie['description'])?></p>
         </div>
         <?php }?>
       </div>
       <?php if(isset($_SESSION['session_id'])):?>
         <?php echo '<div class="form-section">
          <h2>UPLOAD</h2>
          <form action="./index.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="file-name" placeholder="File Name"/>
            <input type="text" name="Image-Title" placeholder="Image Title"/>
            <input type="text" name="Image-Description" placeholder="Image Description">
            <input type="file" name="media-file">
            <button type="submit" name="submit-file">UPLOAD</button>
          </form>
          </div>';?>
           <?php else :?>
            <?php echo "You arent Logged in"; ?>
            <?php include './login.php';?>
         <?php endif ?>
    </section>
    <div class="footer">
       <div class="left_footer">
          <li>Home</li>
          <li>Cases</li>
          <li>Portfolio</li>
       </div>
       <div class="right_footer">
          <li>LATEST CASES</li>
          <li>SHAOLIN DEVELOPMENT</li>
          <li>SESTO ELEMENTO,SEO</li>
       </div>
    </div>
</body>
</html>