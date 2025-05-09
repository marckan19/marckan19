<?php
ob_start();
session_start();
require_once('header.php');

if((isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'a') || (isset($_COOKIE['ciastko_zalogowany']) && $_COOKIE['ciastko_zalogowany'] == 'a')){
	require_once('menu.php');
	
	if(isset($_GET['id_wpisu'])){
		$id_wpisu = $_GET['id_wpisu'];
	}else{
		$id_wpisu = 0;
	}	

	echo '
	<table>
		<tr>';	
			if($row_header['header'] == 'n'){
				echo '<td><h2>Mängel - den Eintrag bearbeiten</h2></td><td width="20px" />';			
			}else{
				echo '<td><h2>Usterki - edytowanie wpisu</h2></td><td width="20px" />';			
			}
			//require_once('legenda_usterki.php');
		echo '</tr>
	</table>';
	
	if(isset($_POST['back'])){
		header('Location: tabela_usterki_edytuj.php?id='.$id_wpisu);
	}elseif(isset($_POST['upload'])){		
		$creation_date = date('Y-m-d G:i:s');
		if(isset($_SESSION['user_id'])){
			$creator_id = $_SESSION['user_id'];
		}else{
			$creator_id = $_COOKIE['ciastko_user_id'];
		}
		
		if (!file_exists("images/usterki/$id_wpisu")) {
			mkdir("images/usterki/$id_wpisu", 0777, true);			
		}						

		for($i=0;$i<count($_FILES['file']['size']);$i++){ 				
			if(strstr($_FILES['file']['type'][$i], 'image')!==false){ 		
				$file = 'images/usterki/'.$id_wpisu.'/'.$_FILES['file']['name'][$i]; 
				if(file_exists($file)){	
				}else{					
					move_uploaded_file($_FILES['file']['tmp_name'][$i],$file); 									
					
					$file = substr($file,3);
					$query_img = "INSERT INTO panel_tabela_usterki_images (id_usterki, path, creator_id, creation_date) VALUES (".$id_wpisu.", 'images/usterki/".$id_wpisu."/".$_FILES['file']['name'][$i]."', ".$creator_id.", '".$creation_date."');";
					mysql_query($query_img);
				}				
			}
		}	
		mysql_query($query_update);
		
		header('Location: tabela_usterki_edytuj.php?id='.$id_wpisu);
		
	}else{
		echo '
		<form action="tabela_usterki_img.php?id_wpisu='.$id_wpisu.'" method="post" enctype="multipart/form-data">	
			<input type="file" multiple="multiple" name="file[]" /> 
			<input type="submit"  value="Prześlij" name="upload" />
			<br /><br /><br /><br /><input type="submit"  value="Powrót" name="back" />
		</form>';
	}
}else{
	require_once('logout.php');
}

require_once('footer.php');