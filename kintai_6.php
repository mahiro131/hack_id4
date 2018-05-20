<!-- PHPの設定 -->
<?php
	#定数名の定義
	define("YES", "出席");
	define("NO", "欠席");
	define("AT_OK","出勤");
	define("AT_BAD","欠勤");
	
	#週末かどうか(デバック用)
	define("WEEKEND",true);
	
	#データベース,テーブル名の定義
	define("DATA_BASE","ichikawa-lab");
	define("TABLE_KI","kintai");
	define("TABLE_SY","syukkin");
	
	define("LIMIT",480);#現状の集計数の母数
	define("UNDER",24);#勤怠の状態の下限
	
	define("AT_WEEK_LIMIT",5);#計測日数
	define("AT_WEEK_UNDER",3);#週当たりの出席日数の下限
	
	define("PRESSED",900);#座っていると判断する圧力センサーの値
	
	#現状の取得関数の定義
	function is_sit($t_num, $t_link){
		//testテーブルからすべてのデータを取り出すSQL文を作る
		$sql = "SELECT * FROM ".TABLE_KI." WHERE id = ".$t_num." order by number desc limit 1";
		//SQLクエリ(問い合わせ)をデータベースに発行する
		//問い合わせ結果が$rstに入ってくる
		$rst = mysql_query($sql, $t_link);
		$col = mysql_fetch_array($rst);
		if($col['press']<PRESSED) :
			print YES;
		else :
			print NO;
		endif;
	}
	
	#現状の取得関数の定義(bootstrap用)
	function is_sit_bootstrap($t_num, $t_link){
		//testテーブルからすべてのデータを取り出すSQL文を作る
		$sql = "SELECT * FROM ".TABLE_KI." WHERE id = ".$t_num." order by number desc limit 1";
		//SQLクエリ(問い合わせ)をデータベースに発行する
		//問い合わせ結果が$rstに入ってくる
		$rst = mysql_query($sql, $t_link);
		$col = mysql_fetch_array($rst);
		if($col['press']<PRESSED) :
			print('<button type="button" class="btn btn-block btn-success btn-lg">&nbsp;&nbsp;&nbsp;');
			echo sprintf('%02d', $t_num);
			print('&nbsp;&nbsp;&nbsp;</button>');
		else :
			print('<button type="button" class="btn btn-block btn-danger btn-lg">&nbsp;&nbsp;&nbsp;');
			echo sprintf('%02d', $t_num);
			print('&nbsp;&nbsp;&nbsp;</button>');
		endif;
	}
		
	#勤怠の取得関数の定義
	function is_attend($t_num, $t_link){
		$sql = "SELECT * FROM ".TABLE_KI." WHERE id = ".$t_num ." limit ".LIMIT;
		$rst = mysql_query($sql, $t_link);
		$count = 0;
		#読み込んだデータリストから圧力が900以下の回数を計測
		while ($row = mysql_fetch_assoc($rst)) {
			if($row['press'] < PRESSED) :
				$count++;
			endif;
		}
		if($count > UNDER) :
			print AT_OK;
		else :
			print AT_BAD;
		endif;
	}
	
	#名前のリスト生成用関数の定義
	function make_list($t_id,$t_name,$link){
		print('<th scope="row">');
		echo sprintf('%02d', $t_id);
		print('</th><td>');
		print $t_name;
		print('</td><td>');
		is_sit($t_id, $link);
		print('</td><td>');
		is_attend($t_id, $link);
		print('</td><td>');
		count_attendance($t_id,$link);
		print('</td>');
	}
	
	#出勤日数の計測
	function count_attendance($t_num,$t_link){
		$sql = "SELECT * FROM ".TABLE_SY." WHERE id = ".$t_num ." limit 5".AT_WEEK_LIMIT;
		$rst = mysql_query($sql, $t_link);
		$count = 0;
		while ($row = mysql_fetch_assoc($rst)) {
			if($row['attendance'] == 1) :
				$count++;
			endif;
		}
		if($count > AT_WEEK_UNDER) :
			print $count."/".AT_WEEK_LIMIT;
		else :
			print('<font color ="red">');
			print $count."/".AT_WEEK_LIMIT;
			if(WEEKEND){
				print('&nbsp;&nbsp;&nbsp;&nbsp;');
				display_penalty();
			}
			print('</font>');
		endif;
	}
	
	#罰ゲームの表示
	function display_penalty(){
		$rnd = rand(0,5);
		switch($rnd){
			case 0:
				print("金タライ");
				break;
			case 1:
				print("備品整理");
				break;
			case 2:
				print("一発芸");
				break;
			case 3:
				print("輪講準備");
				break;
			case 4:
				print("料理製作");
				break;
			case 5:
				print("掃除");
				break;
		}
	}
	
	$user = 'root';
	$password = 'root';
	$db = DATA_BASE;
	$socket = 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock';
	print('<center>');
	$link = mysql_connect($socket,$user,$password);
	if (!$link) {
    	die('接続失敗です。'.mysql_error());
	}

	#print('<p>接続に成功しました。</p>');

	$db_selected = mysql_select_db($db, $link);
	if (!$db_selected){
    	die('データベース選択失敗です。'.mysql_error());
	}

	#print('<p>syukketuデータベースを選択しました。</p>');

	print('</center>');

?>

<!DOCTYPE hmtl>
<html　lang="ja">
	<head>
		<meta charset=UTF-8>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- BootstrapのCSS読み込み -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- jQuery読み込み -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- BootstrapのJS読み込み -->
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
	
		<p class="text-primary"><font size="5">&nbsp;&nbsp;市川" 川喜田研究室 勤怠管理表</p>	

		<br>
		<div class="panel panel-primary">
		<div class="panel-heading">勤怠状況</div>
			
			<br>
				<center>
					<div class="table-responsive">
  						<table class="table-bordered">
							<thead>
							<tr>
								<th><?php is_sit_bootstrap(1, $link) ?></th>
								<th><?php is_sit_bootstrap(2, $link) ?></th>
								<th><?php is_sit_bootstrap(3, $link) ?></th>
								<th><?php is_sit_bootstrap(4, $link) ?></th>
							</tr>
							</thead>
						</table>
						<br>
						<table class="table-bordered">
							<thread>
							<tr>
								<th><?php is_sit_bootstrap(5, $link) ?></th>
								<th><?php is_sit_bootstrap(6, $link) ?></th>
								<th><?php is_sit_bootstrap(7, $link) ?></th>
								<th><?php is_sit_bootstrap(8, $link) ?></th>
							</tr>
							<tr>
								<th><?php is_sit_bootstrap(9, $link) ?></th>
								<th><?php is_sit_bootstrap(10, $link) ?></th>
								<th><?php is_sit_bootstrap(11, $link) ?></th>
								<th><?php is_sit_bootstrap(12, $link) ?></th>
							</tr>
							</thead>
						</table>
					</div>
				</center>
				<br>
				<br>

<?php
print('
		<div class="container">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>#</th>
					<th>名前</th>
					<th>出席状況</th>
					<th>勤怠</th>
					<th>出勤日数</th>
				</tr>
				</thead>
				<tbody>
				<tr>');
					make_list(1,"朝倉　健太",$link);
				print('</tr>
				<tr>');
					make_list(2,"大谷　将洋",$link);
				print('</tr>
				<tr>');
					make_list(3,"中野　遥平",$link);
				print('</tr>
				<tr>');
					make_list(4,"上野　里奈",$link);
				print('</tr>
				</tbody>
			</table>
		</div>
');
?>
	</body>
</html>