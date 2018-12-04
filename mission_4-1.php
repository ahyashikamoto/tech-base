<?php
//データベースへ接続する
$db_host = データベース名;
$db_user =　ユーザー名;
$db_pass = パスワード;
$pdo = new PDO($db_host,$db_user,$db_pass);

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");  //文字化け対策

//テーブルの作成
$sql = "CREATE TABLE finaltest"
."("
."id INT auto_increment primary key,"
."name char(32),"
."message TEXT,"
."date char(32),"
."password TEXT"
.");"; 

$stmt=$pdo->query($sql); //SELECT文を発行

$name = $_POST['name'];
$message = $_POST['message'];

if( !empty( $_POST['send'] ) ){   //送信確認
	
	$date = date("Y/m/d H:i:s");
	$password = $_POST['password'];
	$del = $_POST['del'];
	$edit = $_POST['edit'];
	
	if( empty($password) ){
		echo  "パスワードが未設定です。".'<br>';
	}else{
	
		
		if( !empty($name) && !empty($message) ){	//入力確認
			
			//データの入力
			$sql = $pdo -> prepare( "INSERT INTO finaltest (id,name,message,date,password) VALUES (null,:name,:message,:date,:password)");
			$sql -> bindParam(':name',$name,PDO::PARAM_STR);
			$sql -> bindParam(':message',$message,PDO::PARAM_STR);
			$sql -> bindParam(':date',$date,PDO::PARAM_STR);
			$sql -> bindParam(':password',$password,PDO::PARAM_STR);
			$sql -> execute();
			
		}
	}
}

//削除処理
if( !empty($_POST['delsend']) ){


	$del = $_POST['del'];
	$delpw = $_POST['delpw'];
	
	//パスワード取得
	if( !empty($del) ){
		$sql = 'SELECT*FROM finaltest';
		$stmt = $pdo ->query($sql);
		foreach( $stmt as $rowdel ){
			if( $del == $rowdel['id']){
				$delpass = $rowdel['password'];
			}
		}
		
		//パスワード判定
		if( ($delpass == $delpw) or ( $delpass == null) ){
			//データベースからの削除
			$sql = "delete from finaltest where id=$del";
			$result = $pdo -> query($sql);
		}else{
			echo"パスワードが違います。再度入力してください。";
		}


	}else{
		echo "設定したパスワードを入力してください。";
	}
}

//編集処理
if( !empty($_POST['editsend']) ){

	$edit = $_POST['edit'];
	$editpw = $_POST['editpw'];


	//if( !empty($_POST['edit']) ){
		//編集前の値とパスワードの取得
		$sql = 'SELECT*FROM finaltest';
		$stmt = $pdo ->query($sql);
		
		foreach( $stmt as $rowedit ){
			if( $edit == $rowedit['id'] ){
				$name_old = $rowedit['name'];
				$message_old = $rowedit['message'];
				$editpass = $rowedit['password'];

			}
		}
	//}
	//パスワード判定
	if( $editpass = $editpw ){
		//編集
		if( (!empty($name)) && (!empty($message)) ){
			$sql = "update finaltest set name='$name',message='$message'where id=$edit";
			$result = $pdo ->query($sql);
			$name_old = null;
			$message_old = null;
		}if( !(empty($name_old))){
		echo "再度編集対象番号と編集欄にパスワードを入力のうえ”編集する”を押してください。".'<br>';
		}
	}else{
		echo "パスワードが違います。再度入力してください。";
	}
}
?>
<!DOCTYPE html>
<form method="post" action="mission_4.php">
<input type="text" name="name" placeholder="名前" value="<?=$name_old?>"/><br>
<input type="text" name="message" placeholder="コメント" value="<?=$message_old?>"/><br>
<input type="text" name="password" placeholder="パスワード"/>
<input type="submit" name="send" value="送信"/><br><br>
<input type="text" name="del" placeholder="削除対象番号"/><br>
<input type="text" name="delpw" placeholder="パスワード"/>
<input type="submit" name="delsend" value="削除"/><br><br>
<input type="text" name="edit" placeholder="編集対象番号"><br>
<input type="text" name="editpw" placeholder="パスワード"/>
<input type="submit" name="editsend" value="編集する"/><br>
</form>
<?php
//ブラウザに表示
$sql='SELECT *FROM finaltest ORDER BY id ASC';
$result=$pdo->query($sql);
foreach($result as $row){
echo $row['id'].' ';
echo $row['name'].' ';
echo $row['message'].' ';
echo $row['date'].'<br>';
}
?>
</html>
	
