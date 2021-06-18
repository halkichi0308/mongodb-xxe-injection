<?php
  require "function.php";
  session_start();

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["params"])){
    $postArrayKeys  = array_keys($_POST["params"]);

    if(hasRequirementParams($_POST["params"], 'name') === FALSE || hasRequirementParams($_POST["params"], 'passwd') === FALSE){
      $_SESSION['msg'] = 'ユーザ名もしくはパスワードが入力されていません。';
      header('Location: ./signup.php');
      exit;
    }
    $bulk = new MongoDB\Driver\BulkWrite;
    $query = [];
    foreach($postArrayKeys as $key){
      $query[$key] = $_POST["params"][$key];
      $input[$key] = htmlspecialchars($_POST["params"][$key]);
    }
    //存在ユーザのチェック。もっと簡単なやりかたがありそう
    $cursor = fetchUser($manager, $query['name']);
    if($cursor !== FALSE){
      $_SESSION['msg'] = 'すでに登録されているユーザです。';
      header('Location: ./signup.php');
      exit;
    }
    //$bulk->update($existUser,  ['$set' => $query]);
    $bulk->insert($query);
    $manager->executeBulkWrite($db['collection'], $bulk);
    $cnt_db = true;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <?php echo_header();?>
  </head>
  <body>
      <a href="index.php" style="margin:5%"><button class="btn btn-default">←Back</button></a>
    <div class="container">
      <h2>ユーザ登録</h2>
    <?php
      $msg = (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) ? $_SESSION['msg'] : '';
      echo '<p style="color:#ff0000">'.$msg.'</p>';
      $_SESSION['msg'] = '';
    ?>
    <form action="#" method="post">
      <div class="col-md-6">
        <div class="form-group">
          <input type="hidden" id="role" name='params[role]' value="user">
          <label for="_name">name:</label>
          <input type="text" class="form-control" id="_name" name='params[name]' value="<?php echo h($input['name']);?>">
        </div>
        <div class="form-group">
        </div>
        <div class="form-group">
          <label for="passwd">passwd:</label>
          <input type="password" rows="5" class="form-control" id="passwd" name='params[passwd]' value="<?php echo h($input['passwd']);?>"><br>
        <button class="btn btn-primary">Submit</button>
      </div>
    </form>
      <?php
        if(isset($cnt_db)){
          echo <<<EOF
          <div class="panel panel-default">
            <div id="div_1" class="panel-body">
              <p>Connecting databases was completed.
              <p name="result">at {$input['name']}
              <p name="disp">
            </div>
          </div>
EOF;
        }
      ?>
    </div>
  </body>
</html>
