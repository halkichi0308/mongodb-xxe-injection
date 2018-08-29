<?php
  require "function.php";
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <?php echo_header();?>
  <style>
  .container{
    margin-top:10%;
  }
  </style>
</head>
<body>
    <div class="container">
      <h1>NoSQL Injection</h1>
      <div class="panel panel-default">
      <div class="panel-body">
        <a href="signup.php"><button class="btn btn-info">①SignUp</button></a>
        <a href="search.php"><button class="btn btn-info">②Search</button></a>
        <a href="update.php"><button class="btn btn-info">③update</button></a>
      </div>
    </div>
</div>
</body>
</html>
