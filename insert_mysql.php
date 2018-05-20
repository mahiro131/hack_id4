<html>
<head>
<title>INSERT_MYSQL</title>
</head>

<body>

<?php

$link = mysql_connect('localhost', 'testuser', 'testpassword');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');

$db_selected = mysql_select_db('shusseki', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

print('<p>shussekiデータベースを選択しました。</p>');

mysql_set_charset('utf8');

$result = mysql_query('SELECT id,press FROM meibo');
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

<!-- デバック用(更新前の表示) -->
while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    print('id='.$row['id']);
    print(',press='.$row['press']);
    print('</p>');
}

print('<p>データを追加します。</p>');

$sql = "INSERT INTO meibo (id, press) VALUES ($_GET['id'], $_GET['press'])";
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}

print('<p>追加後のデータを取得します。</p>');

$result = mysql_query('SELECT id,press FROM meibo');
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

<!-- デバック用(結果の表示)-->
while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    print('id='.$row['id']);
    print(',press='.$row['press']);
    print('</p>');
}

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}

?>
</body>
</html>