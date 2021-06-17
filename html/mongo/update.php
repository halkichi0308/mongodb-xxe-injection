<?php
  require "function.php";
  session_start();
  //$username = $_GET['field'];
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["params"])){

    if(hasRequireParams($_POST["params"], 'name') === FALSE || hasRequireParams($_POST["params"], 'passwd') === FALSE){

      $_SESSION['msg'] = 'ユーザ名もしくはパスワードが入力されていません。';
      header('Location: ./update.php');
      exit;
    }

    $postArrayKeys  = array_keys($_POST["params"]);

    $updateQueryCmd = 'role:"user"';

    foreach($postArrayKeys as $key){
      $updateQueryCmd = strlen($updateQueryCmd) > 0 ? $updateQueryCmd.',' : $updateQueryCmd;
      $input[$key] = $_POST["params"][$key];//echo back用
      $updateQueryCmd .= sprintf('%s:"%s"', $key, $_POST["params"][$key]);
    }

    $cursor = fetchUser($manager, $input['name']);
    if($cursor === FALSE){
      $_SESSION['msg'] = 'ユーザー登録がありません';
      header('Location: ./update.php');
      exit;
    }
    $query = "db.user.update(
                { name:'{$input['name']}' },
                { {$updateQueryCmd} },
                true );";
    try{

      $cmd = new \MongoDB\Driver\Command(['eval' => $query]);
      $manager->executeCommand($db['dbName'], $cmd );
      //$manager->executeCommand($db['collection'], $cmd );
      $_SESSION['msg'] = "ユーザ:{$input['name']}のパスワードを更新しました。";
      $db_connect = TRUE;

    }catch(Explesion $e){
      print($e);
    }
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
    <h2>ユーザ情報更新</h2>
  <?php
    $msg = (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) ? $_SESSION['msg'] : '';
    echo '<p style="color:#ff0000">'.$msg.'</p>';
    $_SESSION['msg'] = '';
  ?>
   <form action="#" method="post">
     <div class="col-md-6">
       <div class="form-group">
         <label for="_name">name:</label>
         <input type="text" class="form-control" id="_name" name='params[name]' value="<?php echo $input['name'];?>">
       </div>
       <div class="form-group">
       </div>
       <div class="form-group">
         <label for="passwd">passwd:</label>
         <input type="text" rows="5" class="form-control" id="passwd" name='params[passwd]' value="<?php echo $input['passwd'];?>"><br>
       <button class="btn btn-primary">Submit</button>
     </div>
   </form>
    <?php
      if($db_connect === TRUE){
        $msg = '発行されたクエリ:';
        echo sprintf("<hr><p style=opacity:0.6>%s</br>%s</p>", $msg, $query);
      }
    ?>
  </div>
</body>
</html>
