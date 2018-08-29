<?php
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['name' => '山田', 'address' => '東京']);
$manager->executeBulkWrite('test_db.test', $bulk);
?>


