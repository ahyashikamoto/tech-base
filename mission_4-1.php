<?php
//�f�[�^�x�[�X�֐ڑ�����
$db_host = �f�[�^�x�[�X��;
$db_user =�@���[�U�[��;
$db_pass = �p�X���[�h;
$pdo = new PDO($db_host,$db_user,$db_pass);

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");  //���������΍�

//�e�[�u���̍쐬
$sql = "CREATE TABLE finaltest"
."("
."id INT auto_increment primary key,"
."name char(32),"
."message TEXT,"
."date char(32),"
."password TEXT"
.");"; 

$stmt=$pdo->query($sql); //SELECT���𔭍s

$name = $_POST['name'];
$message = $_POST['message'];

if( !empty( $_POST['send'] ) ){   //���M�m�F
	
	$date = date("Y/m/d H:i:s");
	$password = $_POST['password'];
	$del = $_POST['del'];
	$edit = $_POST['edit'];
	
	if( empty($password) ){
		echo  "�p�X���[�h�����ݒ�ł��B".'<br>';
	}else{
	
		
		if( !empty($name) && !empty($message) ){	//���͊m�F
			
			//�f�[�^�̓���
			$sql = $pdo -> prepare( "INSERT INTO finaltest (id,name,message,date,password) VALUES (null,:name,:message,:date,:password)");
			$sql -> bindParam(':name',$name,PDO::PARAM_STR);
			$sql -> bindParam(':message',$message,PDO::PARAM_STR);
			$sql -> bindParam(':date',$date,PDO::PARAM_STR);
			$sql -> bindParam(':password',$password,PDO::PARAM_STR);
			$sql -> execute();
			
		}
	}
}

//�폜����
if( !empty($_POST['delsend']) ){


	$del = $_POST['del'];
	$delpw = $_POST['delpw'];
	
	//�p�X���[�h�擾
	if( !empty($del) ){
		$sql = 'SELECT*FROM finaltest';
		$stmt = $pdo ->query($sql);
		foreach( $stmt as $rowdel ){
			if( $del == $rowdel['id']){
				$delpass = $rowdel['password'];
			}
		}
		
		//�p�X���[�h����
		if( ($delpass == $delpw) or ( $delpass == null) ){
			//�f�[�^�x�[�X����̍폜
			$sql = "delete from finaltest where id=$del";
			$result = $pdo -> query($sql);
		}else{
			echo"�p�X���[�h���Ⴂ�܂��B�ēx���͂��Ă��������B";
		}


	}else{
		echo "�ݒ肵���p�X���[�h����͂��Ă��������B";
	}
}

//�ҏW����
if( !empty($_POST['editsend']) ){

	$edit = $_POST['edit'];
	$editpw = $_POST['editpw'];


	//if( !empty($_POST['edit']) ){
		//�ҏW�O�̒l�ƃp�X���[�h�̎擾
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
	//�p�X���[�h����
	if( $editpass = $editpw ){
		//�ҏW
		if( (!empty($name)) && (!empty($message)) ){
			$sql = "update finaltest set name='$name',message='$message'where id=$edit";
			$result = $pdo ->query($sql);
			$name_old = null;
			$message_old = null;
		}if( !(empty($name_old))){
		echo "�ēx�ҏW�Ώ۔ԍ��ƕҏW���Ƀp�X���[�h����͂̂����h�ҏW����h�������Ă��������B".'<br>';
		}
	}else{
		echo "�p�X���[�h���Ⴂ�܂��B�ēx���͂��Ă��������B";
	}
}
?>
<!DOCTYPE html>
<form method="post" action="mission_4.php">
<input type="text" name="name" placeholder="���O" value="<?=$name_old?>"/><br>
<input type="text" name="message" placeholder="�R�����g" value="<?=$message_old?>"/><br>
<input type="text" name="password" placeholder="�p�X���[�h"/>
<input type="submit" name="send" value="���M"/><br><br>
<input type="text" name="del" placeholder="�폜�Ώ۔ԍ�"/><br>
<input type="text" name="delpw" placeholder="�p�X���[�h"/>
<input type="submit" name="delsend" value="�폜"/><br><br>
<input type="text" name="edit" placeholder="�ҏW�Ώ۔ԍ�"><br>
<input type="text" name="editpw" placeholder="�p�X���[�h"/>
<input type="submit" name="editsend" value="�ҏW����"/><br>
</form>
<?php
//�u���E�U�ɕ\��
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
	
