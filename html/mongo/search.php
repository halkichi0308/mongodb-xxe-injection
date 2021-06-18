<?php
  require "function.php";

  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $input['role'] = empty($_GET['role']) ? '' : (string)$_GET['role'];
    $input['name'] = empty($_GET['name']) ? '' : $_GET['name'];
    $cnt_db = true;
    if($input['role']){
      //$regex = new MongoDB\BSON\Regex ($input['name']);//正規表現検索
      $filter = [
        'role' => $input['role'],
        'name' => $input['name']
      ];

      //$filter = ['name' => (string)$input['name'] ];Injection対策
      //$filter = ['name' => ['$ne' => $input['name']]];
      $options = [
        'projection' => ['_id' => 0],
        'sort' => ['_id' => -1],
      ];

      $query = new MongoDB\Driver\Query($filter, $options);
      $cursor = $manager->executeQuery($db['collection'], $query);

      // Select 結果表示
      foreach ($cursor as $document) {
        $rows[] = (array)$document;
      }
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
      <div class="col-md-8">
        <h2>ユーザー照会</h2>
        <form action="#" method="GET" id="form1">
          <div class="radio">
            <label>
              <input type="radio" id="type1" name="role" value="admin" <?php echo $input['role'] == 'admin'? 'checked':'';?>>admin
            </label>
            <label>
              <input type="radio" id="type2" name="role" value="user"<?php echo $input['role'] == 'user'? 'checked':'';?>>user
            </label>
            <div class="form-group">
              <label for="name">name:</label>
              <input type="text" class="form-control" id="search" name="name" value="<?php echo h($input['name']);?>">
            </div>
            <input type="submit" class="btn btn-primary" value="Search">
          </div>
        </form>
        <div id="msg" class="well well-sm"></div>
        <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>passwd</th>
          </tr>
        </thead>
        <tbody>
        <?php
          if(isset($cnt_db)){
            $cnt = 1;
            if(isset($rows)){
              foreach($rows as $row){
                $h_name = h($row['name']);
                $h_passwd = h($row['passwd']);
                echo <<<EOF
                <tr>
                <th id="{$row['id']}">{$cnt}</th>
                <td id="{$h_name}">{$h_name}</td>
                <td>{$h_passwd}</td>
                <script>
                  $(function(){
                    $('#type{$row['id']}').on('click',function(){
                      $('#{$type_name}').css('color','#DCDCDC');
                    });
                  });
                </script>
EOF;
                $cnt++;
              }
            }
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
      let elem = document.getElementById('search');
      if(elem.value){
        (document.getElementById('msg')).innerHTML = '<h2>Search results for ' + elem.value + '</h2>';
      }
    </script>
    <script>
      const phpVarDumped = `<?php var_dump($query);?>`;
      console.log(phpVarDumped);
    </script>
  </body>
</html>
