<?php

session_start ();

#error_reporting(E_ALL);
#ini_set('display_startup_errors', 1);
#ini_set('display_errors', '1');

require_once "extras/orasql.php";
require_once "extras/utils.php";

require_once "../auth_chk.php";

// show list of users
if (isset ( $_SESSION ['OraLogin'] ) && isset ( $_SESSION ['OraPwd'] )) 
{
	header ( 'Location: index.php' );
}


  $db= new CMyDb();
  $bRegist=2; $dbq= "trn";
  $arRt= array();  // arRt[id]= val  - user rights
	// bRegist: 0 not registed,
  //          1 logged as NNN,
  //          2 OraLogin/OraPwd stored before

 //$bRegist= 0;

 if( !isset($_SESSION['OraLogin']) || !isset($_SESSION['OraPwd']) )
  		$bRegist= 0;

  if( !$bRegist && $_REQUEST['lgin']>0 ) // translate id -> login
  {
   echo "translate id -> login";
    try {
      $id= $_REQUEST['lgin'];
      $lgn= "";
      $db->connect("NNN","registr",$dbq);
      $db->parse("begin SSEC.AUTH_PATH.GET_LOGIN(:lgn,:id); end;");
      $db->bind(":lgn", $lgn, SQLT_CHR,32);
      $db->bind(":id", $id, SQLT_INT);
      $db->execute();
      if( !isset($lgn) || empty($lgn) )
        echo "<span class=err>имя по id не найдено. </span><br>\n";
      else {
        $_SESSION['OraLogin']= "".$lgn;
        $_SESSION['OraPwd']= "".$_REQUEST['pswd'];

        $bRegist= 2;
      }
    }
    catch(Exception $e) {
      echo "<span class=err>имя по id не найдено. </span><br>\n";
    }
  }
	try 
	{
		if( $bRegist == 2 )
		{
			echo "успешно залогинились";
      			$db->connect($_SESSION['OraLogin'],$_SESSION['OraPwd'],$dbq);	
			header ( 'Location: index.php' );
		}
	}
	catch(Exception $e)
	{
		$bRegist= 0;
		if( isset($_SESSION['OraLogin']) && !empty($_SESSION['OraLogin']) )
    		{
     			 echo "<span class=err>Имя / пароль не верны. </span><br>\n";
     			 unset($_SESSION['OraLogin']);
  		  }
	}

	if( !$bRegist )
	{
		try 
		{
			$db->connect("NNN","registr",$dbq);
			$bRegist= 1;
		}
		catch(Exception $e)
		{
			echo "<span class=err>".$e->getMessage()." </span><br>\n";
		}
	}
	
?>


<body class="container-signin">
	<div id='loginModal' class="container">
	<div class="form-signin">
	<h2 class="form-signin-heading">Трианон Bootstrap</h2>


<?php # echo "vd=" . var_dump($_REQUEST) ;

require_once 'header.php';
 ?>




<form action="login.php" method="post" id="form1">
<select name="lgin" size="1">

<?php 

if( $bRegist == 1 )
	{
		    try 
		    {
		      	$db->parse("begin SSEC.AUTH_PATH.LIST_USERBASE(:cur); end;");
	      		$db->bind(":cur", $cur, OCI_B_CURSOR);
			      $db->execute();
			      oci_execute($cur);
			      while( $row= oci_fetch_array($cur,OCI_ASSOC) ) 
			      {	
		        		echo "<option class=\"form-control\" value=$row[ID] > $row[USERDESCR] $row[BASEDESCR]\n";
			      }
		    }
		    catch( Exception $e) 
		     {
			      echo "<p class=err>sql error: ". $e->getMessage()."</p>";
		    }
	}

?>

  </select>




	<input  type="password" name="pswd" id="empPass" class="form-control" placeholder="Введите пароль..." onkeydown='check_key(event)' required>
	<div class="checkbox"> <label> <input type="checkbox" id="remember_me" value="remember-me"> Запомнить меня</label>	</div>
	<button id='btn_enter' class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
	</div>
	</div>
</form>
	

<?php
include_once 'footer.php';
?>
