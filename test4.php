<?php
$user = 'root';
$password = 'root';
$db = 'personal';
$socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';
$id = $_GET['id'];
$name = $_GET['name'];

$link = mysql_connect(
   $socket, 
   $user, 
   $password
);

if (!$link) {
    die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');



$db_selected = mysql_select_db(
   $db,
   $link
);

if(!$db_selected){
	die('データベース選択失敗です。'.mysql_error());
}

print('<p>personalデータベースを選択しました</p>');

mysql_set_charset('utf8');

$sql = "INSERT INTO friend (id, name) VALUES($id,'$name')";
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}
// MySQLに対する処理

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}



?>