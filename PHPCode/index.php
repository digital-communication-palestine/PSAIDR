<?php
	session_start();
	include("config.php");
	$op=$_GET['op'];

function index(){
		global $error,$db;
		
		/********************
		First page let patients to enter  symptoms or what they feels .
		then Send
		*********************/
		
?>
	<!DOCTYPE html>
	<html lang="ar" dir=rtl>
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>تشخيص الامراض</title>
		<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

		<link href="style.css" rel="stylesheet">
		
		</head>
		<body >

		<div class='page_title'>تشخيص الأمراض </div>
		<div class='forms_div'>

					<form   method="POST" action="index.php?op=dosend"  >
						<div class='describe'> قم بوصف الاعراض التي تشعر بها </div>
						<textarea name='text_decribe' class='textarea' ></textarea>
						<button class='button' > أرسل </button>
			</form>
		</div>

		</body>
	</html>
		
<?php
				
}

	/********************
		Function txt_to_Sym() take what patients writen then enter it to Artificial Intelligence then return symptoms related 
	*********************/
		
		
function txt_to_Sym($text){
	$locale = 'en_US.utf-8';
	setlocale(LC_ALL, $locale);
	putenv('LC_ALL='.$locale);
	//$text=base64_encode($text);
	//echo "python3 /home/ai/Downloads/PSAIDR/PSAIDR.py -v \"$text\"";
	$ret = shell_exec("python3 /home/ai/Downloads/PSAIDR/PSAIDR.py -v \"$text\"");
	//print_r ($ret);
	$returnarr = array();
	$returnarr = explode('|',$ret);
	//print_r ($returnarr);
	return $returnarr;
	
}

function dosend(){
		global $error,$db;
		
		/*********
		The Artificial Intelligence return here 5 questions with symptoms related 
		
		*******/
	
		$text_decribe=trim($_POST['text_decribe']);
		$symps=txt_to_Sym($text_decribe);
		
		foreach($symps as $key => $val ){
			$sympss.="'".$val."',";
		}
		$sympss=rtrim($sympss,",");
		
		/******
		Code to return the id for the entered  symptoms that founded in database 
		*****/
		$query = "SELECT * FROM symptoms WHERE sname  IN ( $sympss )   order by RAND() LIMIT 5   ";
		$result = mysqli_query($db,$query );
		$count = @mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
					@extract($row);
					$questions.= "<div class=''>
									<div class='question_text'>$question</div>
									<div class='radio'><input type=radio name='symptoms' value='$sid'> </div>
									<div class='clear'></div>
								</div> " ;
				}
	  
			
?>
	<!DOCTYPE html>
	<html lang="en" dir=rtl>
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>تشخيص الامراض</title>
		<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

		<link href="style.css" rel="stylesheet">
		
		</head>
		<body >

		<div class='page_title'>تشخيص الأمراض </div>
		<div class='forms_div'>

					<form   method="POST" action="index.php?op=dosend2"  >
						<?=$questions?>
						<button class='button' > أرسل </button>

						<center> <?=$error['msg']?></center>
					</form>
		</div>

		</body>
	</html>
			
	<?php
			
			
		
	
		
}



function dosend2(){
		global $error,$db;
	
		/******
		Code to return diseases contains these   symptoms
		*****/
		
		$symptoms=intval($_POST['symptoms']);
		
		$result = mysqli_query($db,"SELECT * FROM  dis_symp  WHERE sid=$symptoms  order by RAND() LIMIT 3   ");
		 $count = @mysqli_num_rows($result);
		while ($row = @mysqli_fetch_array($result,MYSQLI_ASSOC)){
					extract($row);
					$dis_list.="'".$did."',";
				}
	
		$dis_list=rtrim($dis_list,",");
		
		/******
		Code to return 3 questions of 3 diseases contains these   symptoms
		*****/
		
		$query1 = mysqli_query($db,"SELECT * FROM  dis_symp dsm  "
		." left join  symptoms syms on dsm.sid=syms.sid  WHERE dsm.did in ($dis_list ) and dsm.sid !=$symptoms  GROUP BY dsm.did  order by RAND() LIMIT 3  ");
		while( $row2 = @mysqli_fetch_array($query1,MYSQLI_ASSOC)){
		extract($row2);
		
		$questions.= "<div class=''>
						<div class='question_text'>$question   </div>
						<div class='radio'><input type=radio name='symptoms[]' value='$sid'>  </div>
						<div class='clear'><input type=hidden name='diseases[]' value='$did'> </div>
					</div> " ;
		}						
										
			
?>
	<!DOCTYPE html>
	<html lang="en" dir=rtl>
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>تشخيص الامراض</title>
		<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

		<link href="style.css" rel="stylesheet">
		
		</head>
		<body >

		<div class='page_title'>تشخيص الأمراض </div>
		<div class='forms_div'>

					<form   method="POST" action="index.php?op=doanswer"  >
						<?=$questions?>
						<button class='button' > أرسل </button>

						<center> <?=$error['msg']?></center>
					</form>
		</div>

		</body>
	</html>
			
	<?php
			
			
		
	
		
}
function doanswer(){
		global $error,$db;
		
		
		/******
		Code to return the maximaum number of symptoms witch related to one disease
		*****/
		$diseases=$_POST['diseases'];
		$symptoms=$_POST['symptoms'];
		
		$count_numbers_diseases=array_count_values($diseases);
		
		$maxs = array_keys($count_numbers_diseases, max($count_numbers_diseases));

		$ids= $maxs[0];
		$result = mysqli_query($db,"SELECT * FROM diseases where did=$ids   ");
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		extract($row);
		
		/******
		Code to return doctors specialized in these diseases
		*****/
		
		$query = mysqli_query($db,"SELECT * FROM doctors_diseases doc_dis "
		."left join doctors docs on doc_dis.doc_id=docs.id  where doc_dis.dis_id=$ids   ");
		$count = @mysqli_num_rows($query);
		$i=1;
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
		@extract($row);
		
		$docs.="<tr>
			<td>$i</td>
			<td>$name</td>
			<td>$specialty</td>
			<td>$address</td>
			<td>$tel</td>
			<td>$web</td>
		</tr>";
		$i++;
		}
		if($count >0){
			$doctors ="
			<div class=''>
			<center class='docs_names' >قائمة الدكاترة المختصين </center>
			<table class='doc_tables' cellspacing=0> 
				<tr>
					<th>#</th>
					<th>اسم الدكتور </th>
					<th>التخصص </th>
					<th>العنوان  </th>
					<th>التلفون  </th>
					<th>صفحة ويب  </th>
				</tr>
				$docs
			
			</table>
			</div>
			
			";
			
		}
		
		
					
		?>
	<!DOCTYPE html>
	<html lang="en" dir=rtl>
		<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>تشخيص الامراض</title>
		<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

		<link href="style.css" rel="stylesheet">
		
		</head>
		<body >

		<div class='page_title'>تشخيص الأمراض </div>
		<div class='forms_div'>
					
					<div class='disease_found'>
						<p>المرض الذي تم تشخيصه هو  </p>
						<p><font color=red > <?=$dname?> </font></p>
					 
					<?=$doctors?>	
					</div>
				
		</div>
		

		</body>
	</html>
			
	<?php

}



switch($op) {


	case "index":
        index();
        break;
	
      	
	case "doanswer":
        doanswer();
        break;
      	
	case "dosend":
        dosend();
        break;	
		
		case "dosend2":
        dosend2();
        break;
      
	  
	default:
        index();
        break;
}


?>