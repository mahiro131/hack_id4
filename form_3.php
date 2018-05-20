<?php
$user = 'root';
$password = 'root';
$db = 'test';
$socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';
$id = $_GET['id'];
$press = $_GET['press'];

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

print('<p>testデータベースを選択しました</p>');

mysql_set_charset('utf8');

$sql = "INSERT INTO test_tb (id, press) VALUES($id,$press)";
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