<?php
//connect sql
$dockerMongoIp = gethostbyname("mongo-server");
$user = 'admin';
$passwd = 'example';
$manager = new MongoDB\Driver\Manager("mongodb://{$dockerMongoIp}:27017");

$input = [];//ユーザの入力値を格納

$db['dbName'] = 'main_db';
$db['tblName'] = 'user_tbl';
$db['collection'] = $db['dbName'].'.'.$db['tblName'];
//$manager = new MongoDB\Driver\Manager("mongodb://{$user}:{$passwd}@{$dockerMongoIp}:27017");
//$manager = new MongoDB\Driver\Manager("mongodb://{$user}:{$passwd}@{$dockerMongoIp}:27017/admin");
$options = [
  'projection' => ['_id' => 0],
  'sort' => ['_id' => -1],
];

//echo header
function echo_header(){
  $s = <<<EOF
  <meta charset="utf-8">
  <title></title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <script src="../js/jquery-3.3.1.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <style>
  .container{
    margin-top:5%;
  }
  </style>
EOF;
  echo $s;
}
function echo_footer(){

}

function hasRequireParams($input, $requires){
    if($input[$requires] === ''){
      return FALSE;
    }else{
      return TRUE;
    }
}

function existUserId($manager, $username){

  global $options, $db;

  $existUser = array(
    'name' => $username
  );
  $mongoQueryDriver = new MongoDB\Driver\Query($existUser, $options);
  $cursor = $manager->executeQuery($db['collection'], $mongoQueryDriver);

  foreach ($cursor as $document) {
    $rows[] = (array)$document;
  }

  if($rows !== NULL){
    return $cursor;
  }else{
    return FALSE;
  }
}

function h($str = ''){

  if(empty($str)){
    return '';
    
  }elseif(gettype($str) === 'string'){

    $_tmp = htmlspecialchars($str, ENT_QUOTES);
    return $_tmp;
  }
  return '';
}

?>
