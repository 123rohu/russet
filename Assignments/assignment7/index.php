<?php

require_once 'FileUploadProc.php';
$output = "";

if (isset($_POST['submitButton'])) {
$upload = new Upload();
$output = $upload->checkFile();
} 



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>PDO Crud Example</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <header>
        <h1>File Upload</h1>
        <?php
		$file = "display_files.php";
		echo "<a href= $file>Show file list</a><br><br>";
		echo $output; 
		?>
<br>
<br>
<form action="index.php" method="post" enctype="multipart/form-data">

<div class="form-group">
<label for="fileName">File Name</label>
<input type="text" class="form-control" name="enteredFileName" id="enteredFileName">
</div>

<div class="form-group">
<label for="selectedFile">A file select field</label>
<input type="file" name="selectedFile" id="selectedFile">
</div>


<input class="btn btn-primary" type="submit" name="submitButton" id="submitButton" value="Upload File">
</form>

    </div>
  </body>
</html>