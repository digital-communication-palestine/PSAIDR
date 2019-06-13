<?php
   define('DB_SERVER', 'xx.xx.xx.xx');
   define('DB_USERNAME', 'xxxxxxx');
   define('DB_PASSWORD', 'xxxxxxx');
   define('DB_DATABASE', '2019_doctors');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   mysqli_set_charset($db,"utf8");
?>