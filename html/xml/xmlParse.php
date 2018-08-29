<?php

  function h($str = ''){

    if(empty($str)){
      return '';

    }elseif(gettype($str) === 'string'){

      $_tmp = htmlspecialchars($str, ENT_QUOTES);
      return $_tmp;
    }
    return '';
  }
  $input = [];
  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_body'])){

    if(isset($_POST['id']) && $_POST['id'] == 'xml'){

      $input['xml'] = $_POST['xml_body'];

    }else{

      $input['xml'] = $_POST['xml_body'] ? base64_decode($_POST['xml_body']) : '';

      if(preg_match("/[\"\'\<]/",$_POST['xml_body'])){
        $err = 'Oops! You have to Base64-encode before Submit xml.';
        $input['xml'] = '';
      }

      if(preg_match("/^PD/",$_POST['xml_body']) == 0){
        $err = 'Oops! You maybe mistake what form paramator submit.';
        $input['xml'] = '';
      }

    }
    if(isset($input['xml']) && $input['xml'] !== ''){

        $xmlData = new DOMDocument();
        $xmlData->substituteEntities = true;
        $xmlData->loadXML($input['xml'], LIBXML_DTDLOAD);

        $inputLinkArys = [];

        $getItems = $xmlData->getElementsByTagName('item');

        for($i=0; $i < count($getItems); $i++){

          $inputLinkArys[$i] = $xmlData->getElementsByTagName('link')->item($i)->textContent;
        }

    }else{
      $title = 0;
      $link = 0;
    }
  }else{
    $title = 0;
    $link = 0;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <style>
      body{
        margin: 10%;
      }
    </style>
  </head>
  <body>
   <div class="container col-md-6 col-md-offset-3">
    <table class="table table-bordered table-hover">
      <?php echo isset($err)&& $err !== '' ? '<p>'.$err : ''; ?>
      <?php
        foreach($inputLinkArys as $key){
          $key = h($key);
          echo "<tbody><td>Your input is {$key}.</td></tbody>";
        }
      ?>

    </table>
    <button id="btn" class="btn btn-primary">←Back</button>
   </div>
   <script>
    let btn = document.getElementById('btn');
      btn.addEventListener('click',function(){location.href='./xmlSubmit.html'});
    <?php echo 'let dump='.sprintf("`%s`",$input['xml']).';';
    echo 'console.log(dump);';
    ?>
   </script>
  </body>
</html>
