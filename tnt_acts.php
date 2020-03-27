<?php
// secondary page after cash_ops.php, started 2.04.18
// login is OraLogin/OraPwd

session_start();
require_once "../orasql.php";
require_once "../utils.php";
require_once "../ut_sum.php";
$year0= "2017";
$mtime= filemtime(basename($_SERVER['SCRIPT_NAME']));
$wrange= (date('Y',$mtime)==$year0 ? $year0 : "$year0 - ".date('Y',$mtime));
?>
<html>
<head>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>��� � ������ ����� (<?php echo $_REQUEST['otdel']; ?>)</title>
<!-- link rel=stylesheet type="text/css"  href="../default.css" -->
<style type="text/css">
  div { position: absolute;}
  div.fn1 {font: 12pt Verdana; }
  div.fn2 {font: 10pt Verdana; }
  div.fn3 {font: 9pt Verdana; }
  div.fn4 {font: 7pt Verdana; }
  div.tb  { vertical-align: bottom; }
  div.tc  {text-align: center; vertical-align: middle; }
  div.b4 { border: 1px solid black; }
  div.bb { border-bottom: 2px solid black; }
  div.c {text-align: center;}
  div.r {text-align: right;}
  div.l {text-align: left;}
  div.v {vertical-align: middle;}
  div.bl {font-weight: bold;}
</style>
</head>
<body>
<form method=post target=_self  name=frmMain >
<?php
try {
  if( !isset($_SESSION['OraLogin']) || !isset($_SESSION['OraPwd']) ) {
    throw new Exception("���/������ �� ����������!");
  }
   $db= new CMyDb();
   try {
     $db->connect( $_SESSION['OraLogin'],$_SESSION['OraPwd'], "trn");
   }
   catch(Exception $e) {
     throw new Exception($e->getMessage()."<br>��� / ������ �� �����.");
   }
   $arlg= explode("$", $_SESSION['OraLogin']);
  $typ= isset($_REQUEST['typ']) ? $_REQUEST['typ'] : 0;
  $emp= isset($_REQUEST['emp']) ? esc_decode($_REQUEST['emp']) : "";
  //$sumstr= sum_by_words($sum);
  $cur_date = date('d.m.Y');
  $emp_id = $emp;
  
  //------------------------------------------------------------------------------
  $emp_pos = 0;
  $emp_full_name = '';
  $emp_full_name_polnostju = '';
  $emp_pos_name = '';
  $emp_passport = '';
  $emp_addr = '';
  $dog_number = '';
  $dog_date = '';
  


  function get_summ_oplat2($idAg, $dbeg, $dend)
  {
        global $db, $scheme ;
        $iTyp=1;  
  
        $db->parse("begin ROSTOA\$NSK08.TNT_PACK.GET_SUM_OPLAT2(:cur,to_date(:d0,'dd.mm.yyyy'), to_date(:d1,'dd.mm.yyyy'),:ag); end;");
  
        $db->bind(":cur", $cur, OCI_B_CURSOR);
        $db->bind(":d0",  $dbeg, SQLT_CHR, 20);
        $db->bind(":d1",  $dend, SQLT_CHR, 20);
        $db->bind(":ag", $idAg, SQLT_INT);
  
        $db->execute();
        $db->execute_cursor($cur);
  
        while( $row = $db->fetch_cursor($cur) )
        {
          $summ+=$row[SM];
        }
        
        return $summ;
  }


  $db->parse("begin $arlg[1].DIRS.EMP_GET(:cur,:emp); end;");
  $db->bind(":cur", $cur, OCI_B_CURSOR);
  $db->bind(":emp",  $emp_id, SQLT_INT);
  $db->execute();
  $db->execute_cursor($cur);
  if($row= $db->fetch_cursor($cur))
  {
      $emp_pos = $row['CSHTAT'];
      $name_parts = explode(' ',$row['NAME']);
      if(count($name_parts) > 1)
      {
        $familiya = $name_parts[0];
      }
      else
      {
        $familiya = $row['NAME'];
      }
      $emp_full_name = $familiya.' '.substr($row['NAME_NAME'],0,1).'. '.substr($row['NAME_FATHER'],0,1).'.';
      $emp_full_name_polnostju = $familiya.' '.$row['NAME_NAME'].' '.$row['NAME_FATHER'];
      $emp_passport = $row['PASPORT'];
      $emp_addr = $row['ADDR_REG'];
      
      $dog_number = $row['JOBBOOK'];

      if(strlen($dog_number)==0)
        $dog_number="�/�";

      $dog_date = $row['DJOBB']; //DEMPLOY
  }
  $emp_passport = trim($emp_passport);
  $addr_arr = explode(',',$emp_addr);
  $dog_day = date('d', strtotime("$dog_date"));
  $dog_mth = date('m', strtotime("$dog_date"));
  $dog_year = date('Y', strtotime("$dog_date"));
  $dog_year = substr($dog_year,2,2);
  //----------------------------------------------------------------------------
      
    $mth= isset($_REQUEST['mth']) ? $_REQUEST['mth'] : "";
    $yr= isset($_REQUEST['yr']) ? $_REQUEST['yr'] : "";
    $sec= 0; // all sectors
    if( !$mth ) // for the 1st time here
    {
      $mth= date("m"); $yr= date("Y");
    }
    $tm= mktime(0,0,0, $mth,1,$yr);
    $dbeg= "1.$mth.$yr";
    $dend= date("j.m.Y", strtotime("+1 month",$tm));
    $dend= date("j.m.Y", strtotime("$dend -1 day"));
    $min0= 8 * 60;  $min1=  20 * 60;  // daylight turn: (min0,min1)
  
      $flg= 3; // komplekt+book
      $arlg= explode("$", $_SESSION['OraLogin']);

      if($_REQUEST[otdel]=="torgovie") ////////////////////////////////////////////////////////////////////////
      {
        $viruchka=get_summ_oplat2($emp_id,$dbeg,$dend);
        //echo "get_summ_oplat2 " . $viruchka;


        if($viruchka > 0) /// 519 520 521 522
        $STAVKA=1.25;
    
        if($viruchka > 1000000)
        $STAVKA=1;
    
        if($viruchka > 2000000)
        $STAVKA=0.75;
    
        if($viruchka > 3000000)
        $STAVKA=0.5;

        $rSumN=round($viruchka * $STAVKA/100);

        if($_REQUEST[dogtype]==0)
        $rSumRn=$rSumN*1; //*1.25;
        else
        $rSumRn=$rSumN*1;
       
        $rNdfl=$rSumRn*0.13;
        $rSum=$rSumRn-$rNdfl;
      }

      if($_REQUEST[otdel]=="sklad")  ////////////////////////////////////////////////////////////////////////
      {
      $db->parse("begin $arlg[1].DELIV_WG.S_WAGES(:cur,to_date(:d0,'dd.mm.yyyy'),".
          "to_date(:d1,'dd.mm.yyyy'),:sec,:m0,:m1,:flg); end;");
      $db->bind(":cur", $cur, OCI_B_CURSOR);
      $db->bind(":d0",  $dbeg, SQLT_CHR);
      $db->bind(":d1",  $dend, SQLT_CHR);
      $db->bind(":sec", $sec, SQLT_INT);
      $db->bind(":m0", $min0, SQLT_INT);
      $db->bind(":m1", $min1, SQLT_INT);
      $db->bind(":flg", $flg, SQLT_INT);

      set_time_limit(60);
      $db->execute();
      $db->execute_cursor($cur);
      
      $data = array();
      while($row = $db->fetch_cursor($cur))
      {
          if($row['CEMP'] == $emp_id)
          {
              $data = $row;
              break;
          }
      }
      
      $rSumN= round($data['VYCH'] < $data['SAL_1C'] ?
          ($data['SAL_1C'] - $data['VYCH']*$data['NDFL'])/($data['R_COF']*(1-$data['NDFL'])) :
          $data['SAL_1C']/$data['R_COF']);
      $rSumRn= round($rSumN*$data['R_COF']);
      $rNdfl= ($data['VYCH'] < $data['SAL_1C'] ? round(($rSumRn - $data['VYCH'])*$data['NDFL']) : 0);
      $rSum= round($data['SAL_1C']);
      $sumstr = sum_by_words($rSumRn);
      }

      


      if($_REQUEST[otdel]=="opers")  ////////////////////////////////////////////////////////////////////////
      {
        echo "otdel=opers";
        $db->parse("begin $arlg[1].STATIS.OPERATORS_2(:cur,to_date(:d0,'dd.mm.yyyy'),".
                "to_date(:d1,'dd.mm.yyyy'),:flg); end;");
        $db->bind(":cur", $cur, OCI_B_CURSOR);
        $db->bind(":d0",  $dbeg, SQLT_CHR, 20);
        $db->bind(":d1",  $dend, SQLT_CHR, 20);
        $db->bind(":flg", $iFlg, SQLT_INT);

        set_time_limit(60);
        $db->execute();
        $db->execute_cursor($cur);
      
      $data = array();
      while($row = $db->fetch_cursor($cur))
      {
          if($row['CEMP'] == $emp_id)
          {
              echo "emp_id=" . $emp_id; 
var_dump($row);

              $data = $row;
              break;
          }
      }
      
      $rSumN= round($data['VYCH'] < $data['SAL_1C'] ?
          ($data['SAL_1C'] - $data['VYCH']*$data['NDFL'])/($data['R_COF']*(1-$data['NDFL'])) :
          $data['SAL_1C']/$data['R_COF']);
      $rSumRn= $rSumN*$data['R_COF'];
      $rNdfl= ($data['VYCH'] < $data['SAL_1C'] ? round(($rSumRn - $data['VYCH'])*$data['NDFL']) : 0);
      $rSum= round($data['SAL_1C']);
      $sumstr = sum_by_words($rSum);
      
      $NDOCS_I_IG = ($data['NDOCS_I']-$data['NDOCS_IG']);
      $NDOCS_O_OG_OM = ($data['NDOCS_O']-$data['NDOCS_OG']-$data['NDOCS_OM']);
      $KOR_IN_IG = ($data['KOR_IN']-$data['KOR_IG']);
      $KOR_O_OG = ($data['KOR_OUT']-$data['KOR_OG']);
       }


      
      $table = array();
      $table2 = array();

      if($_REQUEST[otdel]=="torgovie")
      {
        $table[] = array('�������, ���.',round($viruchka));
        $table2[] = array('�������, ��.',round($viruchka));
      }

      if($_REQUEST[otdel]=="exped")
      {
        $table[] = array('�������, ���.',round($data['LINES_GOT_S'],0));
        $table2[] = array('�������, ��.',round($data['LINES_GOT'],0));
      }

      if($_REQUEST[otdel]=="sklad")
      {
      $table[] = array('������� ,�����, ���.',round($data['LINES_GOT_S'],0));
      $table[] = array('������� ,�������, ���.',round($data['KOR_GOT_S'],0));
      $table[] = array('������� ,���, ���.',round($data['TON_GOT_S'],0));
      $table[] = array('������� ,������ ���., ���.',round($data['M3_GOT_S'],0));
      $table[] = array('����������� ,�����, ���.',round($data['LINES_ARR_S'],0));
      $table[] = array('����������� ,�������, ���.',round($data['KOR_ARR_S'],0));
      $table[] = array('����������� ,���, ���.',round($data['TON_ARR_S'],0));
      $table[] = array('����������� ,������ ���., ���.',round($data['M3_ARR_S'],0));
      $table[] = array('����� ,������ , ���.',round($data['SHEETS_PI_S'],0));
      $table[] = array('����� ,����� , ���.',round($data['LINES_PI_S'],0));
      $table[] = array('����� ,��� , ���.',round($data['TON_PI_S'],0));
      $table[] = array('����� ,������ ���., ���.',round($data['M3_PI_S'],0));
      $table[] = array('����� ,����., ���.',round($data['LINES_PICHK_S'],0));
      $table[] = array('�������� ,������ ���., ���.',round($data['M3_PA_S'],0));
      $table[] = array('�������� ,��� , ���.',round($data['TON_PA_S'],0));
      $table[] = array('�������� ,���. ��� , ���.',round($data['ZKOR_PA_S'],0));
      $table[] = array('�������� ,����. ��� , ���.',round($data['SKOR_PA_S'],0));
      $table[] = array('�������� ,����� , ���.',round($data['LINES_CHK_S'],0));
      $table[] = array('�������� ,���. , ���.',round($data['AFF_CHK_S'],0));
      $table[] = array('�������� ,��� , ���.',round($data['TON_CHK_S'],0));
      $table[] = array('�������� ,������ ���., ���.',round($data['M3_CHK_S'],0));
      $table[] = array('������������ ,������ , ���.',round($data['TRIPS_S'],0));
      $table[] = array('������������ ,������� , ���.',round($data['NADR_S'],0));
      $table[] = array('������������ ,���� , ���.',round($data['PKGS_S'],0));
      $table[] = array('������� ,��� , ���.',round($data['LINERR_PI0_S'],0));
      $table[] = array('������� ,������ , ���.',round($data['LINERR_PI_S'],0));
      $table[] = array('������� ,����. , ���.',round($data['LINERR_CHK_S'],0));            
      $table[] = array('�������������� ,����� , ���.',round($data['LINES_INV_S'],0));
      
      $table[] = array('�������� ,����� , ���.',round($data['LN_ARR_R_S'],0));
      $table[] = array('�������� ,��� , ���.',round($data['TON_ARR_R_S'],0));
      $table[] = array('�������� ,����� ���., ���.',round($data['M3_ARR_R_S'],0));
      
      $table[] = array('�������� ������ ,���������� , ���.',round($data['N_BARC_S'],0));
      $table[] = array('�������� ������ ,������ , ���.',round($data['N_SECT_S'],0));
      $table[] = array('�������� ������,������ ��������,���.',round($data['N_TILL_S'],0));
      $table[] = array('�������� ������ ,��� , ���.',round($data['N_VES_S'],0));
      $table[] = array('�������� ������ ,����� , ���.',round($data['N_VOL_S'],0));


if($_REQUEST[dogtype]==0) // ��������
{
      $table[] = array('������� ,�����.',round($data['NH_LOADER_S']) );
      $table[] = array('�������� ,�����.',round($data['NH_SECUR_S'],0));
      $table[] = array('������� ,�����.',round($data['NH_CLEAN_S'],0));
      $table[] = array('�����. ������ ,�����',round($data['INDIV_S'],0));
 }
else
{
      $table[] = array('������� ,�� , ���.',round($data['NH_LOADER_S']) );
      $table[] = array('�������� ,���� , ���.',round($data['NH_SECUR_S'],0));
      $table[] = array('������� ,�� � , ���.',round($data['NH_CLEAN_S'],0));
      $table[] = array('�����. ������ ,����� , ���.',round($data['INDIV_S'],0));
}     

//////////////////////////////////////////////////

      $table2[] = array('������� ,�����, ��.',round($data['LINES_GOT'],0));
      $table2[] = array('������� ,�������, ��.',round($data['KOR_GOT'],0));
      $table2[] = array('������� ,���, ��.',round($data['TON_GOT'],0));
      $table2[] = array('������� ,������ ���., ��.',round($data['M3_GOT'],0));
      $table2[] = array('����������� ,�����, ��.',round($data['LINES_ARR'],0));
      $table2[] = array('����������� ,�������, ��.',round($data['KOR_ARR'],0));
      $table2[] = array('����������� ,���, ��.',round($data['TON_ARR'],0));
      $table2[] = array('����������� ,������ ���., ��.',round($data['M3_ARR'],0));
      $table2[] = array('����� ,������ , ��.',round($data['SHEETS_PI'],0));
      $table2[] = array('����� ,����� , ��.',round($data['LINES_PI'],0));
      $table2[] = array('����� ,��� , ��.',round($data['TON_PI'],0));
      $table2[] = array('����� ,������ ���., ��.',round($data['M3_PI'],0));
      $table2[] = array('����� ,����., ��.',round($data['LINES_PICHK'],0));
      $table2[] = array('�������� ,������ ���., ��.',round($data['M3_PA'],0));
      $table2[] = array('�������� ,��� , ��.',round($data['TON_PA'],0));
      $table2[] = array('�������� ,���. ��� , ��.',round($data['ZKOR_PA'],0));
      $table2[] = array('�������� ,����. ��� , ��.',round($data['SKOR_PA'],0));
      $table2[] = array('�������� ,����� , ��.',round($data['LINES_CHK'],0));
      $table2[] = array('�������� ,���. , ��.',round($data['AFF_CHK'],0));
      $table2[] = array('�������� ,��� , ��.',round($data['TON_CHK'],0));
      $table2[] = array('�������� ,������ ���., ��.',round($data['M3_CHK'],0));
      $table2[] = array('������������ ,������ , ��.',round($data['TRIPS'],0));
      $table2[] = array('������������ ,������� , ��.',round($data['NADR'],0));
      $table2[] = array('������������ ,���� , ��.',round($data['PKGS'],0));
      $table2[] = array('������� ,��� , ��.',round($data['LINERR_PI0'],0));
      $table2[] = array('������� ,������ , ��.',round($data['LINERR_PI'],0));
      $table2[] = array('������� ,����. , ��.',round($data['LINERR_CHK'],0));            
      $table2[] = array('�������������� ,����� , ��.',round($data['LINES_INV'],0));
      
      $table2[] = array('�������� ,����� , ��.',round($data['LN_ARR_R'],0));
      $table2[] = array('�������� ,��� , ��.',round($data['TON_ARR_R'],0));
      $table2[] = array('�������� ,����� ���., ��.',round($data['M3_ARR_R'],0));
                  
      $table2[] = array('�������� ������ ,���������� , ��.',round($data['N_BARC'],0));
      $table2[] = array('�������� ������ ,������ , ��.',round($data['N_SECT'],0));
      $table2[] = array('�������� ������ ,������ �������� , ��.',round($data['N_TILL'],0));
      $table2[] = array('�������� ������ ,��� , ��.',round($data['N_VES'],0));
      $table2[] = array('�������� ������ ,����� , ��.',round($data['N_VOL'],0));
                  

if($_REQUEST[dogtype]==0) // ��������
{
      $table2[] = array('������� ,�����.',round($data['NH_LOADER']) );
      $table2[] = array('�������� ,�����.',round($data['NH_SECUR'],0));
      $table2[] = array('������� ,�����.',round($data['NH_CLEAN'],0));
      $table2[] = array('�����. ������ ,�����',round($data['INDIV'],0));
 }
else
{
      $table2[] = array('������� ,�� , ���.',round($data['NH_LOADER']) );
      $table2[] = array('�������� ,���� , ���.',round($data['NH_SECUR'],0));
      $table2[] = array('������� ,�� � , ���.',round($data['NH_CLEAN'],0));
      $table2[] = array('�����. ������ ,����� , ���.',round($data['INDIV'],0));
}     

      }


      if($_REQUEST[otdel]=="opers")
      {

/*
    $table[] = array("�� ������, ����.������ ,��.",round($data['LINES_IN_M'],0));
    $table[] = array("�� ������, �����.������ ,��.",round($data['LINES_IN_A'],0));
    $table[] = array("�� ���-�, ����.������ ,��.",round($NDOCS_I_IG,0));
    $table[] = array("�� ���-�, ����.������ ����.,��.",round($NDOCS_O_OG_OM,0));
    $table[] = array("�� ���-�, ����.������ �����,��.",round($data['NDOCS_OM'],0));
    $table[] = array("�� ���-�, �����.������,��.",round($data['NDOCS_IG'],0));
    $table[] = array("�� ���-�, �����.������,��.",round($data['NDOCS_OG'],0));
    $table[] = array("�� ��.�������, ����.������,��.",round($KOR_IN_IG,0));
    $table[] = array("�� ��.�������, ����.������,��.",round($KOR_O_OG,0));
    $table[] = array("�� ������ ����.,��.",round($data['BUH_IN_CNT'],0));
    $table[] = array("�� ���-� ��,��.",round($data['KK_CNT'],0));
    $table[] = array("�� ������,��.",round($data['SVER_CNT'],0));
    $table[] = array("�� ������ ���-��,��.",round($data['PRN_CNT'],0));
    $table[] = array("�� ������ ������,��.",round($data['PRL'],0));
    $table[] = array("�� �������� �������,��.",round($data['ORIG_IN'],0));
    $table[] = array("�� �������� �������,��.",round($data['ORIG_OUT'],0));
    $table[] = array("�� ��������, �����,��.",round($data['TIME_CNT'],0));
    $table[] = array("�� �������� ������-����,��.",round($data['ORIG_BUH'],0));
    $table[] = array("�� ��������, �����-� ������,��.",round($data['OLS_CNT'],0));
    $table[] = array("�� �����, ����������,��.",round($data['SERT_CNT'],0));
    $table[] = array("�� �����, ���/�����,��.",round($data['VES_CNT'],0));
    $table[] = array("�� �����, ��������,��.",round($data['NAME_CNT'],0));
    $table[] = array("�� �����, ���,��.",round($data['GTD_CNT'],0));
    $table[] = array("�� �����, ��������,��.",round($data['LNK_TOV'],0));
    $table[] = array("�� �����, ���� �����������,��.",round($data['SER_FIL'],0));
    $table[] = array("�� �������, �����,��.",round($data['DOG_N'],0));
    $table[] = array("�� �������, ���������,��.",round($data['DOG_C'],0));
    $table[] = array("������,��.",round($data['DOC_FIL'],0));
    $table[] = array("�����,��.",round($data['NASINA'],0));
    $table[] = array("�� ������ ������������,��.",round($data['DOV_PRN'],0));
    $table[] = array("���.���,��.",round($data['MON_PRC'],0));
    */

    $NDOCS_I_IG_S = ($data['NDOCS_I_S']-$data['NDOCS_IG_S']);
    $NDOCS_O_OG_OM_S = ($data['NDOCS_O_S']-$data['NDOCS_OG_S']-$data['NDOCS_OM_S']);
    $KOR_IN_IG_S = ($data['KOR_IN_S']-$data['KOR_IG_S']);
    $KOR_O_OG_S = ($data['KOR_OUT_S']-$data['KOR_OG_S']);
    
/*
    $table2[] = array("�� ������, ����.������,���.",round($data['LINES_IN_M_S'],0));
    $table2[] = array("�� ������, �����.������,���.",round($data['LINES_IN_A_S'],0));
    $table2[] = array("�� ���-�, ����.������,���.",round($NDOCS_I_IG_S,0));
    $table2[] = array("�� ���-�, ����.������ ����.,���.",round($NDOCS_O_OG_OM_S,0));
    $table2[] = array("�� ���-�, ����.������ �����,���.",round($data['NDOCS_OM_S'],0));
    $table2[] = array("�� ���-�, �����.������,���.",round($data['NDOCS_IG_S'],0));
    $table2[] = array("�� ���-�, �����.������,���.",round($data['NDOCS_OG_S'],0));
    $table2[] = array("�� ��.�������, ����.������,���.",round($KOR_IN_IG_S,0));
    $table2[] = array("�� ��.�������, ����.������,���.",round($KOR_O_OG_S,0));
    $table2[] = array("�� ������ ����.,���.",round($data['BUH_IN_CNT_S'],0));
    $table2[] = array("�� ���-� ��,���.",round($data['KK_CNT_S'],0));
    $table2[] = array("�� ������,���.",round($data['SVER_CNT_S'],0));
    $table2[] = array("�� ������ ���-��,���.",round($data['PRN_CNT_S'],0));
    $table2[] = array("�� ������ ������,���.",round($data['PRL_S'],0));
    $table2[] = array("�� �������� �������,���.",round($data['ORIG_IN_S'],0));
    $table2[] = array("�� �������� �������,���.",round($data['ORIG_OUT_S'],0));
    $table2[] = array("�� ��������, �����",round($data['TIME_CNT_S'],0));
    $table2[] = array("�� �������� ������-����,���.",round($data['ORIG_BUH_S'],0));
    $table2[] = array("�� ��������, �����-� ������,���.",round($data['OLS_CNT_S'],0));
    $table2[] = array("�� �����, ����������,���.",round($data['SERT_CNT_S'],0));
    $table2[] = array("�� �����, ���/�����,���.",round($data['VES_CNT_S'],0));
    $table2[] = array("�� �����, ��������,���.",round($data['NAME_CNT_S'],0));
    $table2[] = array("�� �����, ���,���.",round($data['GTD_CNT_S'],0));
    $table2[] = array("�� �����, ��������,���.",round($data['LNK_TOV_S'],0));
    $table2[] = array("�� �����, ���� �����������,���.",round($data['SER_FIL_S'],0));
    $table2[] = array("�� �������, �����,���.",round($data['DOG_N_S'],0));
    $table2[] = array("�� �������, ���������,���.",round($data['DOG_C_S'],0));
    $table2[] = array("������,���.",round($data['DOC_FIL_S'],0));
    $table2[] = array("�����,���.",round($data['NASINA_S'],0));
    $table2[] = array("�� ������ ������������,���.",round($data['DOV_PRN_S'],0));
    $table2[] = array("���.���,���.",round($data['MON_PRC_S'],0));
*/
      }







  //----------------------------------------------------------------------------
  $clsdef= array(
    "fn3 ta","fn3 ta","fn3 ta","fn3 ta","fn3 ta","fn3 ta"
  );
  
  $lines = array();
  $table_size = 0;
  $top_offset = 0;
  $iL = 1;
  foreach($table as $one_line)
  {
    if($iL < round((count($table) / 2) + 1,0))
    {
        array_push($lines,
            5,148 + $table_size,18,3.5,"b4 c v","$iL",
            23,148 + $table_size,70,3.5,"b4","$one_line[0]",
            93,148 + $table_size,10,3.5,"b4 tc","$one_line[1]"
            );
        $table_size += 3.5;
    }
    else
    {
        array_push($lines,
            103,148 + $top_offset,18,3.5,"b4 c v","$iL",
            121,148 + $top_offset,70,3.5,"b4","$one_line[0]",
            191,148 + $top_offset,10,3.5,"b4 tc","$one_line[1]"
            );
        $top_offset += 3.5;
    }
    
    $iL++;
  }
  
  $lines2 = array();
  $table_size2 = 0;
  $top_offset = 0;
  $iL2 = 1;
  foreach($table2 as $one_line)
  {
    if($iL2 < round((count($table2) / 2) + 1,0))
    {
        array_push($lines2,
            5,148 + $table_size2,18,3.5,"b4 c v","$iL2",
            23,148 + $table_size2,70,3.5,"b4","$one_line[0]",
            93,148 + $table_size2,10,3.5,"b4 tc","$one_line[1]"
            );
        $table_size2 += 3.5;
    }
    else
    {
        array_push($lines2,
            103,148 + $top_offset,18,3.5,"b4 c v","$iL2",
            121,148 + $top_offset,70,3.5,"b4","$one_line[0]",
            191,148 + $top_offset,10,3.5,"b4 tc","$one_line[1]"
            );
        $top_offset += 3.5;
    }
    $iL2++;
  }

  




  $dog_type="�������� �������";
  $dog_type2="��������� ��������";


  if($_REQUEST['dogtype']==0)
  {
  $dog_type="�������� �������";
  $dog_type2="��������� ��������";
  $itogo="����� (c �����. ����.)";
  }
  else
  {
  $dog_type="������� �������";
  $dog_type2="�������� �������";
  $itogo="�����"; // (��� �����. ����.)
  }


  $dirname="������ �.�.";
  $dirname2=$_SESSION['CUR_USER_FIO'] . " �� ������������";

  $patterns= array(  // each line: left,top,width,height,class,content
                // left,top,width,height = 0 not set
  // 0 - ������ �����
  array(
  90,5,70,5,"fn4 r","��������������� ����� � �-73",
  90,10,70,5,"fn4 r","���������� �������������� �����������",
  90,15,70,5,"fn4 r","������ 05.01.2004 � 1",
  174,21,26,5,"b4 c","���",
  144,26,30,5,"r","����� �� ����",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","��� �� �������",
  10,34,139,5,"fn4 c","(������������ ���������)",
  10,39,139,5,"bb tc","�����",
  10,44,139,5,"fn4 c","(����������� �������������)",
  144,31,30,5,"r","�� ����",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","�� �����",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","������� ����������-��������� ���������",
  154,49,20,5,"b4 r","�����",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","����",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","���������",
  145,77,25,5,"","������������",
  170,77,30,5,"bb c","��������",
  170,82,30,5,"fn4 c","(���������)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(������ �������)",
  175,87,25,5,"bb c","�.�. ������",
  145,97,25,5,"c","�.�.",
  55,77,20,10,"b4","����� ���������",
  75,77,20,10,"b4","���� �����������",
  55,87,20,5,"b4","�/�",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","�������� ������",
  100,82,20,5,"b4 c","�",
  120,82,20,5,"b4 c","��",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","���<br> ����������� �� $dog_type2<br> ����� ",
  10,108,80,5,"fn2 ","� ������������ � ��������� �������",
  10,118,20,5,"","�����������",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(���,�������,��������)",
  10,128,100,5,"l","�������� �� �������� ������ ��������� ������ :",
            
  5,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  23,133,70,15,"b4 c v bl","������������<br> �����",
  93,133,10,15,"b4 c v bl","�����,<br> ���",
      
  103,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  121,133,70,15,"b4 c v bl","������������<br> �����",
  191,133,10,15,"b4 c v bl","�����,<br> ���",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  113,148 + $table_size,60,5,"r","$itogo",
  173,148 + $table_size,30,5,"b4 tc","$rSumRn",
  113,153 + $table_size,60,5,"r","� �.�. ����",
  173,153 + $table_size,30,5,"b4 tc","$rNdfl",
  83,158 + $table_size,90,5,"r","����� � �������",
  173,158 + $table_size,30,5,"b4 tc","$rSum",
  10,163 + $table_size,50,5,"","������(�) ���������(�)",
  65,163 + $table_size,135,5,"bb","� ��������� ������",
  65,168 + $table_size,135,5,"fn4 c","(������� ��������,�����,������� ���������� �����)",
  10,173 + $table_size,25,5,"","�� �����",
  35,173 + $table_size,165,5,"bb","$sumstr",
  35,178 + $table_size,165,5,"fn4 c","(��������)",
  10,183 + $table_size,25,5,"bl","������ ����",
  35,188 + $table_size,30,5,"bb","",
  10,188 + $table_size,25,5,"","�����������",
  35,193 + $table_size,30,5,"fn4 c","(������ �������)",
  10,198 + $table_size,35,5,"bl","������ ������",
  10,203 + $table_size,50,5,"","������������� ���������",
  55,203 + $table_size,30,5,"bb c","��������",
  90,203 + $table_size,30,5,"bb","",
  130,203 + $table_size,50,5,"bb c","������ �.�.",
  55,208 + $table_size,30,5,"fn4 c","(���������)",
  90,208 + $table_size,30,5,"fn4 c","(������ �������)",
  130,208 + $table_size,50,5,"fn4 c","(����������� �������)"
  ),
  //1 - ������ ��� ����������
array(
  90,5,70,5,"fn4 r","��������������� ����� � �-73",
  90,10,70,5,"fn4 r","���������� �������������� �����������",
  90,15,70,5,"fn4 r","������ 05.01.2004 � 1",
  174,21,26,5,"b4 c","���",
  144,26,30,5,"r","����� �� ����",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","��� �� �������",
  10,34,139,5,"fn4 c","(������������ ���������)",
  10,39,139,5,"bb tc","�����",
  10,44,139,5,"fn4 c","(����������� �������������)",
  144,31,30,5,"r","�� ����",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","�� �����",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","������� ����������-��������� ���������",
  154,49,20,5,"b4 r","�����",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","����",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","���������",
  145,77,25,5,"","������������",
  170,77,30,5,"bb c","��������",
  170,82,30,5,"fn4 c","(���������)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(������ �������)",
  175,87,25,5,"bb c","�.�. ������",
  145,97,25,5,"c","�.�.",
  55,77,20,10,"b4","����� ���������",
  75,77,20,10,"b4","���� �����������",
  55,87,20,5,"b4","�/�",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","�������� ������",
  100,82,20,5,"b4 c","�",
  120,82,20,5,"b4 c","��",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","���<br> ����������� �� $dog_type2<br> ����� ",
  10,108,80,5,"fn2 ","� ������������ � ��������� �������",
  10,118,20,5,"","�����������",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(���,�������,��������)",
  10,128,100,5,"l","�������� �� �������� ������ ��������� ������ :",
            
  5,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  23,133,70,15,"b4 c v bl","������������<br> �����",
  93,133,10,15,"b4 c v bl","�����.<br> �����.",
      
  103,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  121,133,70,15,"b4 c v bl","������������<br> �����",
  191,133,10,15,"b4 c v bl","�����.<br> �����.",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  10,153 + $table_size2,50,5,"","������(�) ���������(�)",
  65,153 + $table_size2,135,5,"bb","� ��������� ������",
  65,158 + $table_size2,135,5,"fn4 c","(������� ��������,�����,������� ���������� �����)",
  10,173 + $table_size2,25,5,"bl","������ ����",
  35,178 + $table_size2,30,5,"bb","",
  10,178 + $table_size2,25,5,"","�����������",
  35,183 + $table_size2,30,5,"fn4 c","(������ �������)",
  10,188 + $table_size2,35,5,"bl","������ ������",
  10,193 + $table_size2,50,5,"","������������� ���������",
  55,193 + $table_size2,30,5,"bb c","��������",
  90,193 + $table_size2,30,5,"bb","",
  130,193 + $table_size2,50,5,"bb c","$dirname",
  55,198 + $table_size2,30,5,"fn4 c","(���������)",
  90,198 + $table_size2,30,5,"fn4 c","(������ �������)",
  130,198 + $table_size2,50,5,"fn4 c","$dirname2"
  ),
  //2 - �������� ������� �����    
  array(
  90,5,70,5,"fn4 r","��������������� ����� � �-73",
  90,10,70,5,"fn4 r","���������� �������������� �����������",
  90,15,70,5,"fn4 r","������ 05.01.2004 � 1",
  174,21,26,5,"b4 c","���",
  144,26,30,5,"r","����� �� ����",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","��� �� �������",
  10,34,139,5,"fn4 c","(������������ ���������)",
  10,39,139,5,"bb tc","�����",
  10,44,139,5,"fn4 c","(����������� �������������)",
  144,31,30,5,"r","�� ����",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","�� �����",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","$dog_type",
  154,49,20,5,"b4 r","�����",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","����",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","���������",
  145,77,25,5,"","������������",
  170,77,30,5,"bb c","��������",
  170,82,30,5,"fn4 c","(���������)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(������ �������)",
  175,87,25,5,"bb c","�.�. ������",
  145,97,25,5,"c","�.�.",
  55,77,20,10,"b4","����� ���������",
  75,77,20,10,"b4","���� �����������",
  55,87,20,5,"b4","�/�",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","�������� ������",
  100,82,20,5,"b4 c","�",
  120,82,20,5,"b4 c","��",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","���<br> � ������ ����� ��<br> $dog_type2",
  10,108,80,5,"fn2 ","� ������������ � �������� ��������� �",
  90,108,20,5,"bb","$dog_number",
  110,108,10,5,"fn2 ","�� �",
  120,108,20,5,"bb ","$dog_day",
  140,108,10,5,"fn2 ","�",
  150,108,20,5,"bb","$dog_mth",
  170,108,7,5,"fn2 ","20",
  177,108,10,5,"bb","$dog_year",
  187,108,10,5,"fn2 ","�.",
  10,118,20,5,"","��������",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(���,�������,��������)",
  10,128,100,5,"l","�������� �� �������� ������ ��������� ������ :",
            
  5,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  23,133,70,15,"b4 c v bl","������������<br> �����",
  93,133,10,15,"b4 c v bl","�����,<br> ���",
      
  103,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  121,133,70,15,"b4 c v bl","������������<br> �����",
  191,133,10,15,"b4 c v bl","�����,<br> ���",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  113,148 + $table_size,60,5,"r","$itogo",
  173,148 + $table_size,30,5,"b4 tc","$rSumRn",
  113,153 + $table_size,60,5,"r","� �.�. ����",
  173,153 + $table_size,30,5,"b4 tc","$rNdfl",
  83,158 + $table_size,90,5,"r","����� � �������",
  173,158 + $table_size,30,5,"b4 tc","$rSum",
  10,163 + $table_size,50,5,"","������(�) ���������(�)",
  65,163 + $table_size,135,5,"bb","� ��������� ������",
  65,168 + $table_size,135,5,"fn4 c","(������� ��������,�����,������� ���������� �����)",
  10,173 + $table_size,25,5,"","�� �����",
  35,173 + $table_size,165,5,"bb","$sumstr",
  35,178 + $table_size,165,5,"fn4 c","(��������)",
  10,183 + $table_size,25,5,"bl","������ ����",
  35,188 + $table_size,30,5,"bb","",
  10,188 + $table_size,25,5,"","��������",
  35,193 + $table_size,30,5,"fn4 c","(������ �������)",
  10,198 + $table_size,35,5,"bl","������ ������",
  10,203 + $table_size,50,5,"","������������� ���������",
  55,203 + $table_size,30,5,"bb c","��������",
  90,203 + $table_size,30,5,"bb","",
  130,203 + $table_size,50,5,"bb c","$dirname",
  55,208 + $table_size,30,5,"fn4 c","(���������)",
  90,208 + $table_size,30,5,"fn4 c","(������ �������)",
  130,208 + $table_size,50,5,"fn4 c","$dirname2"
  ),
 // 3 - �������� ������� ��� ����������
 array(
  90,5,70,5,"fn4 r","��������������� ����� � �-73",
  90,10,70,5,"fn4 r","���������� �������������� �����������",
  90,15,70,5,"fn4 r","������ 05.01.2004 � 1",
  174,21,26,5,"b4 c","���",
  144,26,30,5,"r","����� �� ����",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","��� �� �������",
  10,34,139,5,"fn4 c","(������������ ���������)",
  10,39,139,5,"bb tc","�����",
  10,44,139,5,"fn4 c","(����������� �������������)",
  144,31,30,5,"r","�� ����",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","�� �����",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","$dog_type",
  154,49,20,5,"b4 r","�����",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","����",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","���������",
  145,77,25,5,"","������������",
  170,77,30,5,"bb c","��������",
  170,82,30,5,"fn4 c","(���������)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(������ �������)",
  175,87,25,5,"bb c","�.�. ������",
  145,97,25,5,"c","�.�.",
  55,77,20,10,"b4","����� ���������",
  75,77,20,10,"b4","���� �����������",
  55,87,20,5,"b4","�/�",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","�������� ������",
  100,82,20,5,"b4 c","�",
  120,82,20,5,"b4 c","��",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","���<br> � ������ ����� ��<br> $dog_type2",
  10,108,80,5,"fn2 ","� ������������ � �������� ��������� �",
  90,108,20,5,"bb","$dog_number",
  110,108,10,5,"fn2 ","�� �",
  120,108,20,5,"bb","$dog_day",
  140,108,10,5,"fn2 ","�",
  150,108,20,5,"bb","$dog_mth",
  170,108,7,5,"fn2 ","20",
  177,108,10,5,"bb","$dog_year",
  187,108,10,5,"fn2 ","�.",
  10,118,20,5,"","��������",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(���,�������,��������)",
  10,128,100,5,"l","�������� �� �������� ������ ��������� ������ :",
            
  5,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  23,133,70,15,"b4 c v bl","������������<br> �����",
  93,133,10,15,"b4 c v bl","�����.<br> �����.",
      
  103,133,18,15,"b4 c v bl","�����<br> ��<br> �������",
  121,133,70,15,"b4 c v bl","������������<br> �����",
  191,133,10,15,"b4 c v bl","�����.<br> �����.",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  10,153 + $table_size2,50,5,"","������(�) ���������(�)",
  65,153 + $table_size2,135,5,"bb","� ��������� ������",
  65,158 + $table_size2,135,5,"fn4 c","(������� ��������,�����,������� ���������� �����)",
  10,173 + $table_size2,25,5,"bl","������ ����",
  35,178 + $table_size2,30,5,"bb","",
  10,178 + $table_size2,25,5,"","��������",
  35,183 + $table_size2,30,5,"fn4 c","(������ �������)",
  10,188 + $table_size2,35,5,"bl","������ ������",
  10,193 + $table_size2,50,5,"","������������� ���������",
  55,193 + $table_size2,30,5,"bb c","��������",
  90,193 + $table_size2,30,5,"bb","",
  130,193 + $table_size2,50,5,"bb c","$dirname",
  55,198 + $table_size2,30,5,"fn4 c","(���������)",
  90,198 + $table_size2,30,5,"fn4 c","(������ �������)",
  130,198 + $table_size2,50,5,"fn4 c","$dirname2"
  ),
  );
  
  $patterns[0] = array_merge($patterns[0],$lines);
  $patterns[1] = array_merge($patterns[1],$lines2);
  $patterns[2] = array_merge($patterns[2],$lines);
  $patterns[3] = array_merge($patterns[3],$lines2);
  

  if( 0 <= $typ && $typ < 5 ) {  // evolve data into html
    echo "<div id=dmain class='{$clsdef[$typ]}' style='position:relative; left: 0; top: 0; width: 210mm; height: 297mm;'>";
    $a= $patterns[$typ];
    for($i=0; $i < count($a)-5; $i+= 6 )
      echo "<div style='left: {$a[$i+0]}mm; top: {$a[$i+1]}mm;width:{$a[$i+2]}mm;height:{$a[$i+3]}mm;' class='{$a[$i+4]}'>{$a[$i+5]}</div>";
    echo "</div>";
  }
}
catch( Exception $e) {
  echo "<p class=err>". $e->getMessage() ."</p>\n";
}
?>
</form>
</body>
</html>
