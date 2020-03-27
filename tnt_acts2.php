<?php
 
//error_reporting(E_ALL);
//ini_set('display_startup_errors', 1);
//ini_set('display_errors', '1');

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>Акт о приеме работ (<?php echo $_REQUEST['otdel']; ?>) EMP=<?php echo $_REQUEST['emp']; ?> </title>
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
<?php

function array_to_cp1251 (&$entry) 
{
    if( is_array($entry) ) {
        array_walk($entry, 'array_to_cp1251');
    } else {
        $entry = iconv('utf-8', 'cp1251', strval($entry));
    }
}

$json_obj=json_decode($_REQUEST[json]);
#echo  iconv('utf-8', 'cp1251', strval($json_obj->{'EMP'}));
#var_dump($json_obj);

?>

<form method=post target=_self  name=frmMain >
<?php
try {
  if( !isset($_SESSION['OraLogin']) || !isset($_SESSION['OraPwd']) ) {
    throw new Exception("имя/пароль не существует!");
  }
   $db= new CMyDb();
   try {
     $db->connect( $_SESSION['OraLogin'],$_SESSION['OraPwd'], "trn");
   }
   catch(Exception $e) {
     throw new Exception($e->getMessage()."<br>Имя / пароль не верны.");
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


  $data=array();
  foreach($json_obj as $k => $v)
  {
  //if($v!=0)
  $data[$k]=$v;
  }  

  //var_dump($data);
  $emp_id=$data['CEMP'];


 $i=0;

#echo 'count=' . count($data) .'<br>';

  foreach ($data as $k => $v)
  {
      if($i>8)
      //echo $k . ":" . $v;
      $i++;
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
        $dog_number="б/н";

      $dog_date = $row['DJOBB']; //DEMPLOY
  }

  $emp_passport = trim($emp_passport);
  $addr_arr = explode(',',$emp_addr);
  $dog_day = date('d', strtotime("$dog_date"));
  $dog_mth = date('m', strtotime("$dog_date"));
  $dog_year = date('Y', strtotime("$dog_date"));
  $dog_year = substr($dog_year,2,2);
  //----------------------------------------------------------------------------
  $dbeg=$_REQUEST[dbeg];
  $dend=$_REQUEST[dend];
  $min0= 8 * 60;  $min1=  20 * 60;  // daylight turn: (min0,min1)
  
      $flg= 3; // komplekt+book
      $arlg= explode("$", $_SESSION['OraLogin']);


     // $emp_full_name=data['EMP'];
     // $emp_full_name=iconv('utf-8', 'cp1251', strval($emp_full_name));
      //echo $emp_full_name;


      $dirname=$data['DIRNAME'];    //"Петров П.Г.";
      $dirname=iconv('utf-8', 'cp1251', strval($dirname));
    
      $dirname2=$data['DIRNAME2'];    //"Петров П.Г.";

      $dirname2=iconv('utf-8', 'cp1251', strval($dirname2));
    
      $dirname=$_REQUEST[dirname];
      $dirname2=$_REQUEST[dirname2];
    
      $dirname3=$dirname2  . " по доверенности";
      $dirname2='';


      if($_REQUEST[otdel]=="torgovie") ////////////////////////////////////////////////////////////////////////
      {
        $viruchka=get_summ_oplat2($emp_id,$dbeg,$dend);

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
        $rSumRn=round($rSumN*1.25); //*1.25;
        else
        $rSumRn=$rSumN*1;
       
        $rNdfl=round($data['NDFL']);
        $rSum=round($rSumRn-$rNdfl);
      }
      
      if($_REQUEST[otdel]=="exped")  ////////////////////////////////////////////////////////////////////////
      { 
          $rSumRn= round($data['RAIKOEF']); //$rSumN*
          $rNdfl = round($data['NDFL']);
          $rSum = $rSumRn-$rNdfl;
          $sumstr = sum_by_words($rSumRn);
      }

      if($_REQUEST[otdel]=="sklad")  ////////////////////////////////////////////////////////////////////////
      {    
          $rSumRn= round($data['RAIKOEF']); //$rSumN*
          $rNdfl=round($data['NDFL']);
          $rSum=round($data['KVIDACHE']); //$rSumRn-$rNdfl;  //
          $sumstr = sum_by_words($rSumRn);
      }
    
      if($_REQUEST[otdel]=="opers")  ////////////////////////////////////////////////////////////////////////
      {    
        $NDOCS_I_IG = ($data['NDOCS_I']-$data['NDOCS_IG']);
        $NDOCS_O_OG_OM = ($data['NDOCS_O']-$data['NDOCS_OG']-$data['NDOCS_OM']);
        $KOR_IN_IG = ($data['KOR_IN']-$data['KOR_IG']);
        $KOR_O_OG = ($data['KOR_OUT']-$data['KOR_OG']);

        $rSumRn= round($data['RAIKOEF']); 
        $rNdfl=round($data['NDFL']);
        $rSum=round($rSumRn-$rNdfl);  

        $sumstr = sum_by_words($rSumRn);
      }
      

      $table = array();
      $table2 = array();

      if($_REQUEST[otdel]=="torgovie")
      {
        $table[] = array('выручка, тыс. руб.', $rSumN);
        $table2[] = array('выручка, тыс. руб.',round($viruchka/1000));
      }

      if($_REQUEST[otdel]=="exped")
      {
        $table = array();
        $table[] = array('точки',round($data['POINTS'],0)*70); //id201
        $table[] = array('точки совп.',round($data['PTS_B'],0)*10); //id226
        $table[] = array('тонн',round($data['KG']/1000,0)*50); //id203
        $table[] = array('куб.м',round($data['LIT']/1000,0)*30); //id204
          
        $table[] = array('мест',round($data['PKG'],0));
        $table[] = array('км сверх норм	',round($data['KM_MORE'],0)*5);
        $table[] = array('влж. т	',round($data['KG_NS'],0));
        $table[] = array('влж. куб.м ',round($data['LIT_NS'],0));
        $table[] = array('влж. мест	',round($data['PKG_NS'],0));
  
        $table[] = array('стр. сет.	',round($data['NET_LNS'],0));
        $table[] = array('стр. несет.	',round($data['NONET_LNS'],0));
        $table[] = array('стр. штуч	',round($data['I_NONET_LNS'],0));
        $table[] = array('дней',round($data['DAYS'],0));
        $table[] = array('вых. вовр	',round($data['OFF_OK'],0));
        
        $table[] = array('тара',round($data['TARE'],0)*10); //206-207
  
        $table2 = array();
 
        $table2[] = array('точки',round($data['POINTS'],0));
        $table2[] = array('точки совп.',round($data['PTS_B'],0));
        $table2[] = array('тонн',round($data['KG']/1000,0));
        $table2[] = array('куб.м',round($data['LIT']/1000,0));
        $table2[] = array('мест',round($data['PKG'],0));
        $table2[] = array('км сверх норм	',round($data['KM_MORE'],0));
        $table2[] = array('влж. т	',round($data['KG_NS'],0));
        $table2[] = array('влж. куб.м ',round($data['LIT_NS'],0));
        $table2[] = array('влж. мест	',round($data['PKG_NS'],0));
        $table2[] = array('стр. сет.	',round($data['NET_LNS'],0));
        $table2[] = array('стр. несет.	',round($data['NONET_LNS'],0));
        $table2[] = array('стр. штуч	',round($data['I_NONET_LNS'],0));
        $table2[] = array('дней',round($data['DAYS'],0));
        $table2[] = array('вых. вовр	',round($data['OFF_OK'],0));
        $table2[] = array('тара',round($data['TARE'],0)*10);
      }

      if($_REQUEST[otdel]=="sklad")
      {
        $table[] = array('приемка ,строк, руб.',round($data['LINES_GOT_S'],0));
        $table[] = array('приемка ,коробок, руб.',round($data['KOR_GOT_S'],0));
        $table[] = array('приемка ,тон, руб.',round($data['TON_GOT_S'],0));
      $table[] = array('приемка ,метров куб., руб.',round($data['M3_GOT_S'],0));
      $table[] = array('расстановка ,строк, руб.',round($data['LINES_ARR_S'],0));
      $table[] = array('расстановка ,коробок, руб.',round($data['KOR_ARR_S'],0));
      $table[] = array('расстановка ,тон, руб.',round($data['TON_ARR_S'],0));
      $table[] = array('расстановка ,метров куб., руб.',round($data['M3_ARR_S'],0));
      $table[] = array('отбор ,листов , руб.',round($data['SHEETS_PI_S'],0));
      $table[] = array('отбор ,строк , руб.',round($data['LINES_PI_S'],0));
      $table[] = array('отбор ,тон , руб.',round($data['TON_PI_S'],0));
      $table[] = array('отбор ,метров куб., руб.',round($data['M3_PI_S'],0));
      $table[] = array('отбор ,пров., руб.',round($data['LINES_PICHK_S'],0));

      $table[] = array('отбор ,др секторов., руб.(new!!)',round($data['PI_ALN_S'],0)); ////// 10-02-2020


      $table[] = array('упаковка ,метров куб., руб.',round($data['M3_PA_S'],0));
      $table[] = array('упаковка ,тон , руб.',round($data['TON_PA_S'],0));
      $table[] = array('упаковка ,зав. кор , руб.',round($data['ZKOR_PA_S'],0));
      $table[] = array('упаковка ,сбор. кор , руб.',round($data['SKOR_PA_S'],0));
      $table[] = array('проверка ,строк , руб.',round($data['LINES_CHK_S'],0));
      $table[] = array('проверка ,доб. , руб.',round($data['AFF_CHK_S'],0));
      $table[] = array('проверка ,тон , руб.',round($data['TON_CHK_S'],0));
      $table[] = array('проверка ,метров куб., руб.',round($data['M3_CHK_S'],0));
      $table[] = array('комплектовка ,рейсов , руб.',round($data['TRIPS_S'],0));
      $table[] = array('комплектовка ,адресов , руб.',round($data['NADR_S'],0));
      $table[] = array('комплектовка ,мест , руб.',round($data['PKGS_S'],0));
      $table[] = array('вычерки ,сам , руб.',round($data['LINERR_PI0_S'],0));
      $table[] = array('вычерки ,клиент , руб.',round($data['LINERR_PI_S'],0));
      $table[] = array('вычерки ,пров. , руб.',round($data['LINERR_CHK_S'],0));            

      $table[] = array('возвраты (new!!!). , руб.',round($data['ERR_RET_S'],0));            

      $table[] = array('инвентаризация ,строк , руб.',round($data['LINES_INV_S'],0));
      
      $table[] = array('возвраты ,строк , руб.',round($data['LN_ARR_R_S'],0));
      $table[] = array('возвраты ,тон , руб.',round($data['TON_ARR_R_S'],0));
      $table[] = array('возвраты ,метры куб., руб.',round($data['M3_ARR_R_S'],0));
      
      $table[] = array('внесение данных ,штрихкодов , руб.',round($data['N_BARC_S'],0));
      $table[] = array('внесение данных ,секций , руб.',round($data['N_SECT_S'],0));
      $table[] = array('внесение данных,сроков годности,руб.',round($data['N_TILL_S'],0));
      $table[] = array('внесение данных ,вес , руб.',round($data['N_VES_S'],0));
      $table[] = array('внесение данных ,объем , руб.',round($data['N_VOL_S'],0));


if($_REQUEST[dogtype]==0) // трудовой
{
      $table[] = array('грузчик ,часов.',round($data['NH_LOADER_S']) );
      $table[] = array('охранник ,часов.',round($data['NH_SECUR_S'],0));
      $table[] = array('уборщик ,часов.',round($data['NH_CLEAN_S'],0));
      $table[] = array('индив. работы ,часов',round($data['INDIV_S'],0));
      $table[] = array('доп. работы ,часов (new)',round($data['EXW_HRS_S'],0)); /// 10-02-2020

 }
else  // подряд
{
      $table[] = array('грузчик ,тн , руб.',round($data['NH_LOADER_S']) );
      $table[] = array('охранник ,мест , руб.',round($data['NH_SECUR_S'],0));
      $table[] = array('уборщик ,кв м , руб.',round($data['NH_CLEAN_S'],0));
      $table[] = array('индив. работы , руб.',round($data['INDIV_S'],0));
      $table[] = array('доп. работы ,часов (new)',round($data['EXW_HRS_S'],0)); /// 10-02-2020
}     

////////////////////////////////////////////////// ФИЗ ПОКАЗАТЕЛИ

      $table2[] = array('приемка ,строк, шт.',round($data['LINES_GOT'],0));
      $table2[] = array('приемка ,коробок, шт.',round($data['KOR_GOT'],0));
      $table2[] = array('приемка ,тон, шт.',round($data['TON_GOT'],0));
      $table2[] = array('приемка ,метров куб., шт.',round($data['M3_GOT'],0));
      $table2[] = array('расстановка ,строк, шт.',round($data['LINES_ARR'],0));
      $table2[] = array('расстановка ,коробок, шт.',round($data['KOR_ARR'],0));
      $table2[] = array('расстановка ,тон, шт.',round($data['TON_ARR'],0));
      $table2[] = array('расстановка ,метров куб., шт.',round($data['M3_ARR'],0));
      $table2[] = array('отбор ,листов , шт.',round($data['SHEETS_PI'],0));
      $table2[] = array('отбор ,строк , шт.',round($data['LINES_PI'],0));
      $table2[] = array('отбор ,тон , шт.',round($data['TON_PI'],0));
      $table2[] = array('отбор ,метров куб., шт.',round($data['M3_PI'],0));
      $table2[] = array('отбор ,пров., шт.',round($data['LINES_PICHK'],0));

      $table2[] = array('отбор ,др секторов., шт.(new!!)',round($data['PI_ALN_S'],0)); ////// 10-02-2020


      $table2[] = array('упаковка ,метров куб., шт.',round($data['M3_PA'],0));
      $table2[] = array('упаковка ,тон , шт.',round($data['TON_PA'],0));
      $table2[] = array('упаковка ,зав. кор , шт.',round($data['ZKOR_PA'],0));
      $table2[] = array('упаковка ,сбор. кор , шт.',round($data['SKOR_PA'],0));
      $table2[] = array('проверка ,строк , шт.',round($data['LINES_CHK'],0));
      $table2[] = array('проверка ,доб. , шт.',round($data['AFF_CHK'],0));
      $table2[] = array('проверка ,тон , шт.',round($data['TON_CHK'],0));
      $table2[] = array('проверка ,метров куб., шт.',round($data['M3_CHK'],0));
      $table2[] = array('комплектовка ,рейсов , шт.',round($data['TRIPS'],0));
      $table2[] = array('комплектовка ,адресов , шт.',round($data['NADR'],0));
      $table2[] = array('комплектовка ,мест , шт.',round($data['PKGS'],0));
      $table2[] = array('вычерки ,сам , шт.',round($data['LINERR_PI0'],0));
      $table2[] = array('вычерки ,клиент , шт.',round($data['LINERR_PI'],0));
      $table2[] = array('вычерки ,пров. , шт.',round($data['LINERR_CHK'],0));           
      $table2[] = array('возвраты (new!!!). , шт.',round($data['ERR_RET_S'],0));            
      $table2[] = array('инвентаризация ,строк , шт.',round($data['LINES_INV'],0));
      $table2[] = array('возвраты ,строк , шт.',round($data['LN_ARR_R'],0));
      $table2[] = array('возвраты ,тон , шт.',round($data['TON_ARR_R'],0));
      $table2[] = array('возвраты ,метры куб., шт.',round($data['M3_ARR_R'],0));
                  
      $table2[] = array('внесение данных ,штрихкодов , шт.',round($data['N_BARC'],0));
      $table2[] = array('внесение данных ,секций , шт.',round($data['N_SECT'],0));
      $table2[] = array('внесение данных ,сроков годности , шт.',round($data['N_TILL'],0));
      $table2[] = array('внесение данных ,вес , шт.',round($data['N_VES'],0));
      $table2[] = array('внесение данных ,объем , шт.',round($data['N_VOL'],0));
                  

if($_REQUEST[dogtype]==0) // трудовой
{
      $table2[] = array('грузчик ,часов.',round($data['NH_LOADER']) );
      $table2[] = array('охранник ,часов.',round($data['NH_SECUR'],0));
      $table2[] = array('уборщик ,часов.',round($data['NH_CLEAN'],0));
      $table2[] = array('индив. работы ,часов',round($data['INDIV'],0)); /// EXW_HRS ???????
      $table2[] = array('доп. работы ,часов(new)',round($data['EXW_HRS'],0)); /// EXW_HRS ???????

 }
else // подряд
{
      $table2[] = array('грузчик ,тн',round($data['NH_LOADER']) );
      $table2[] = array('охранник ,мест',round($data['NH_SECUR'],0));
      $table2[] = array('уборщик ,кв м',round($data['NH_CLEAN'],0));
      $table2[] = array('индив. работы',round($data['INDIV'],0)); //////////////// EXW_HRS ????
      $table2[] = array('доп. работы ,часов(new)',round($data['EXW_HRS'],0)); /// EXW_HRS ???????

}     

      }


      if($_REQUEST[otdel]=="opers")
      {
          $summa += $Tarifs[401]['VTAR'] * $row['LINES_IN_M'];
          $summa += $Tarifs[402]['VTAR'] * $row['LINES_IN_A'];
          $summa += $Tarifs[403]['VTAR'] * $NDOCS_I_IG;
          $summa += $Tarifs[404]['VTAR'] * $NDOCS_O_OG_OM;
          $summa += $Tarifs[405]['VTAR'] * $row['NDOCS_OM'];
          $summa += $Tarifs[406]['VTAR'] * $row['NDOCS_IG'];
          $summa += $Tarifs[407]['VTAR'] * $row['NDOCS_OG'];
          $summa += $Tarifs[408]['VTAR'] * $KOR_IN_IG;
          $summa += $Tarifs[409]['VTAR'] * $KOR_O_OG;
          $summa += $Tarifs[410]['VTAR'] * $row['BUH_IN_CNT'];
          $summa += $Tarifs[411]['VTAR'] * $row['KK_CNT'];
          $summa += $Tarifs[412]['VTAR'] * $row['SVER_CNT'];
          $summa += $Tarifs[413]['VTAR'] * $row['PRN_CNT'];
          $summa += $Tarifs[414]['VTAR'] * $row['PRL'];
          $summa += $Tarifs[415]['VTAR'] * $row['ORIG_IN'];
          $summa += $Tarifs[416]['VTAR'] * $row['ORIG_OUT'];
          $summa += $Tarifs[417]['VTAR'] * $row['TIME_CNT'];
          $summa += $Tarifs[418]['VTAR'] * $row['ORIG_BUH'];
          $summa += $Tarifs[419]['VTAR'] * $row['OLS_CNT'];
          $summa += $Tarifs[420]['VTAR'] * $row['SERT_CNT'];
          $summa += $Tarifs[421]['VTAR'] * $row['VES_CNT'];
          $summa += $Tarifs[422]['VTAR'] * $row['NAME_CNT'];
          $summa += $Tarifs[423]['VTAR'] * $row['GTD_CNT'];
          $summa += $Tarifs[424]['VTAR'] * $row['LNK_TOV'];
          $summa += $Tarifs[425]['VTAR'] * $row['SER_FIL'];
          $summa += $Tarifs[426]['VTAR'] * $row['DOG_N'];
          $summa += $Tarifs[427]['VTAR'] * $row['DOG_C'];
          $summa += $Tarifs[428]['VTAR'] * $row['DOC_FIL'];
          $summa += $Tarifs[429]['VTAR'] * $row['NASINA'];
          $summa += $Tarifs[431]['VTAR'] * $row['DOV_PRN'];
          $summa += $Tarifs[432]['VTAR'] * $row['MON_PRC'];



        $arlg = explode("$", $_SESSION['OraLogin']);
        $datOn='01.01.2020';
        $iDiv=4;
        $db->parse("begin $arlg[1].STAFF.TARIF_LIST(:cur, to_date(:dtOn,'dd.mm.yyyy'), :iDiv); end;");
        $db->bind(":cur", $cur, OCI_B_CURSOR);
        $db->bind(":dtOn", $datOn, SQLT_CHR);
        $db->bind(":iDiv", $iDiv, SQLT_INT);
        $db->execute();
        $db->execute_cursor($cur);
        while ($row = $db->fetch_cursor($cur)) 
        {
            $Tarifs[$row['IDTAR']] = $row;
        }


//var_dump($Tarifs);

if($_REQUEST[typ]==2)
{
$data['LINES_IN_M']=$Tarifs[400]['VTAR'] * $data['LINES_IN_M'];
$data['LINES_IN_A']=$Tarifs[401]['VTAR'] * $data['LINES_IN_A'];
$NDOCS_I_IG = $Tarifs[403]['VTAR'] * $NDOCS_I_IG;
$NDOCS_O_OG_OM = $Tarifs[404]['VTAR'] * $NDOCS_O_OG_OM;
$data['NDOCS_OM']= $Tarifs[405]['VTAR'] * $data['NDOCS_OM'];
$data['NDOCS_IG']= $Tarifs[406]['VTAR'] * $data['NDOCS_IG'];
$data['NDOCS_OG']= $Tarifs[407]['VTAR'] * $data['NDOCS_OG'];
$KOR_IN_IG = $Tarifs[408]['VTAR'] * $KOR_IN_IG;
$KOR_O_OG= $Tarifs[409]['VTAR'] * $KOR_O_OG;
$data['BUH_IN_CN'] += $Tarifs[410]['VTAR'] * $data['BUH_IN_CNT'];
$data['KK_CNT']= $Tarifs[411]['VTAR'] * $data['KK_CNT'];
$data['SVER_CNT']= $Tarifs[412]['VTAR'] * $data['SVER_CNT'];
$data['PRN_CNT']= $Tarifs[413]['VTAR'] * $data['PRN_CNT'];
$data['PRL']= $Tarifs[414]['VTAR'] * $data['PRL'];
$data['ORIG_IN']= $Tarifs[415]['VTAR'] * $data['ORIG_IN'];
$data['ORIG_OUT']= $Tarifs[416]['VTAR'] * $data['ORIG_OUT'];
$data['TIME_CNT']= $Tarifs[417]['VTAR'] * $data['TIME_CNT'];
$data['ORIG_BUH']= $Tarifs[418]['VTAR'] * $data['ORIG_BUH'];
$data['OLS_CNT']= $Tarifs[419]['VTAR'] * $data['OLS_CNT'];
$data['SERT_CNT']= $Tarifs[420]['VTAR'] * $data['SERT_CNT'];
$data['VES_CNT']= $Tarifs[421]['VTAR'] * $data['VES_CNT'];
$data['NAME_CNT']= $Tarifs[422]['VTAR'] * $data['NAME_CNT'];
$data['GTD_CNT']= $Tarifs[423]['VTAR'] * $data['GTD_CNT'];
$data['LNK_TOV']= $Tarifs[424]['VTAR'] * $data['LNK_TOV'];
$data['SER_FIL']= $Tarifs[425]['VTAR'] * $data['SER_FIL'];
$data['DOG_N'] = $Tarifs[426]['VTAR'] * $data['DOG_N'];
$data['DOG_C']= $Tarifs[427]['VTAR'] * $data['DOG_C'];
$data['DOC_FIL']= $Tarifs[428]['VTAR'] * $data['DOC_FIL'];
$data['NASINA']= $Tarifs[429]['VTAR'] * $data['NASINA'];
$data['DOV_PRN']= $Tarifs[431]['VTAR'] * $data['DOV_PRN'];
$data['MON_PRC']= $Tarifs[432]['VTAR'] * $data['MON_PRC'];

//$data['SER_FIL']=$data['SER_FIL']*$Tarifs[425]['VTAR'];
//$data['SERT_CNT']=$data['SERT_CNT']*$Tarifs[420]['VTAR'];
}

    $table[] = array("за строку, ручн.приход ,шт.",round($data['LINES_IN_M'],0));
    $table[] = array("за строку, генер.приход ,шт.",round($data['LINES_IN_A'],0));
    $table[] = array("за док-т, ручн.приход ,шт.",round($NDOCS_I_IG,0));
    $table[] = array("за док-т, ручн.расход проч.,шт.",round($NDOCS_O_OG_OM,0));
    $table[] = array("за док-т, ручн.расход бумПр,шт.",round($data['NDOCS_OM'],0));
    $table[] = array("за док-т, генер.приход,шт.",round($data['NDOCS_IG'],0));
    $table[] = array("за док-т, генер.расход,шт.",round($data['NDOCS_OG'],0));
    $table[] = array("за уч.коробки, ручн.приход,шт.",round($KOR_IN_IG,0));
    $table[] = array("за уч.коробки, ручн.расход,шт.",round($KOR_O_OG,0));
    $table[] = array("за приход бухг.,шт.",round($data['BUH_IN_CNT'],0));
    $table[] = array("за док-т КК,шт.",round($data['KK_CNT'],0));
    $table[] = array("за сверку,шт.",round($data['SVER_CNT'],0));
    $table[] = array("за печать док-та,шт.",round($data['PRN_CNT'],0));
    $table[] = array("за печать прайса,шт.",round($data['PRL'],0));
    $table[] = array("за оригинал прихода,шт.",round($data['ORIG_IN'],0));
    $table[] = array("за оригинал расхода,шт.",round($data['ORIG_OUT'],0));
    $table[] = array("за оригинал, время,шт.",round($data['TIME_CNT'],0));
    $table[] = array("за оригинал приход-бухг,шт.",round($data['ORIG_BUH'],0));
    $table[] = array("за оригинал, измен-е строки,шт.",round($data['OLS_CNT'],0));
    $table[] = array("за товар, сертификат,шт.",round($data['SERT_CNT'],0));
    $table[] = array("за товар, вес/объем,шт.",round($data['VES_CNT'],0));
    $table[] = array("за товар, название,шт.",round($data['NAME_CNT'],0));
    $table[] = array("за товар, ГТД,шт.",round($data['GTD_CNT'],0));
    $table[] = array("за товар, привязка,шт.",round($data['LNK_TOV'],0));
    $table[] = array("за товар, скан сертификата,шт.",round($data['SER_FIL'],0));
    $table[] = array("за договор, новый,шт.",round($data['DOG_N'],0));
    $table[] = array("за договор, изменение,шт.",round($data['DOG_C'],0));
    $table[] = array("облако,шт.",round($data['DOC_FIL'],0));
    $table[] = array("асина,шт.",round($data['NASINA'],0));
    $table[] = array("за печать доверенности,шт.",round($data['DOV_PRN'],0));
    $table[] = array("мон.цен,шт.",round($data['MON_PRC'],0));
    
    $NDOCS_I_IG_S = ($data['NDOCS_I_S']-$data['NDOCS_IG_S']);
    $NDOCS_O_OG_OM_S = ($data['NDOCS_O_S']-$data['NDOCS_OG_S']-$data['NDOCS_OM_S']);
    $KOR_IN_IG_S = ($data['KOR_IN_S']-$data['KOR_IG_S']);
    $KOR_O_OG_S = ($data['KOR_OUT_S']-$data['KOR_OG_S']);

    $table2[] = array("за строку, ручн.приход,руб.",round($data['LINES_IN_M_S'],0));
    $table2[] = array("за строку, генер.приход,руб.",round($data['LINES_IN_A_S'],0));
    $table2[] = array("за док-т, ручн.приход,руб.",round($NDOCS_I_IG_S,0));
    $table2[] = array("за док-т, ручн.расход проч.,руб.",round($NDOCS_O_OG_OM_S,0));
    $table2[] = array("за док-т, ручн.расход бумПр,руб.",round($data['NDOCS_OM_S'],0));
    $table2[] = array("за док-т, генер.приход,руб.",round($data['NDOCS_IG_S'],0));
    $table2[] = array("за док-т, генер.расход,руб.",round($data['NDOCS_OG_S'],0));
    $table2[] = array("за уч.коробки, ручн.приход,руб.",round($KOR_IN_IG_S,0));
    $table2[] = array("за уч.коробки, ручн.расход,руб.",round($KOR_O_OG_S,0));
    $table2[] = array("за приход бухг.,руб.",round($data['BUH_IN_CNT_S'],0));
    $table2[] = array("за док-т КК,руб.",round($data['KK_CNT_S'],0));
    $table2[] = array("за сверку,руб.",round($data['SVER_CNT_S'],0));
    $table2[] = array("за печать док-та,руб.",round($data['PRN_CNT_S'],0));
    $table2[] = array("за печать прайса,руб.",round($data['PRL_S'],0));
    $table2[] = array("за оригинал прихода,руб.",round($data['ORIG_IN_S'],0));
    $table2[] = array("за оригинал расхода,руб.",round($data['ORIG_OUT_S'],0));
    $table2[] = array("за оригинал, время",round($data['TIME_CNT_S'],0));
    $table2[] = array("за оригинал приход-бухг,руб.",round($data['ORIG_BUH_S'],0));
    $table2[] = array("за оригинал, измен-е строки,руб.",round($data['OLS_CNT_S'],0));
    $table2[] = array("за товар, сертификат,руб.",round($data['SERT_CNT'],0));
    $table2[] = array("за товар, вес/объем,руб.",round($data['VES_CNT_S'],0));
    $table2[] = array("за товар, название,руб.",round($data['NAME_CNT_S'],0));
    $table2[] = array("за товар, ГТД,руб.",round($data['GTD_CNT_S'],0));
    $table2[] = array("за товар, привязка,руб.",round($data['LNK_TOV_S'],0));
    $table2[] = array("за товар, скан сертификата,руб.",round($data['SER_FIL'],0));
    $table2[] = array("за договор, новый,руб.",round($data['DOG_N_S'],0));
    $table2[] = array("за договор, изменение,руб.",round($data['DOG_C_S'],0));
    $table2[] = array("облако,руб.",round($data['DOC_FIL_S'],0));
    $table2[] = array("асина,руб.",round($data['NASINA_S'],0));
    $table2[] = array("за печать доверенности,руб.",round($data['DOV_PRN_S'],0));
    $table2[] = array("мон.цен,руб.",round($data['MON_PRC_S'],0));
  }




foreach ($table as $k => $v)
{
  if($v[1]==0)
  {
   #echo $k;
   unset($table[$k]);
  }
}

foreach ($table2 as $k => $v)
{
  if($v[1]==0)
  {
   #echo $k;
   unset($table2[$k]);
  }
}

//var_dump($table2);






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
  
  $dog_type="Трудовой договор";
  $dog_type2="трудовому договору";


  if($_REQUEST['dogtype']==0)
  {
  $dog_type="Трудовой договор";
  $dog_type2="трудовому договору";
  $dog_type3="трудовым договором № ";

  $itogo="Итого (c район. коэф.)";
  }
  else
  {
  $dog_type="Договор подряда";
  $dog_type2="договору подряда";
  $dog_type3="договором подряда № ";

  $itogo="Итого"; // (без район. коэф.)
  }



 //$_SESSION['CUR_USER_FIO'] . " по доверенности";

  $patterns= array(  // each line: left,top,width,height,class,content
                // left,top,width,height = 0 not set
  // 0 - подряд рубли
  array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","склад",
  10,44,139,5,"fn4 c","(структурное подразделение)",
  144,31,30,5,"r","по ОКПО",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","по ОКВЭД",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","Договор гражданско-правового характера",
  154,49,20,5,"b4 r","номер",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","дата",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","УТВЕРЖДАЮ",
  145,77,25,5,"","Руководитель",
  170,77,30,5,"bb c","директор",
  170,82,30,5,"fn4 c","(должность)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(личная подпись)",
  175,87,25,5,"bb c","$dirname",
  145,97,25,5,"c","М.П.",
  55,77,20,10,"b4","Номер документа",
  75,77,20,10,"b4","Дата составления",
  55,87,20,5,"b4","б/н",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ<br> выполненных по $dog_type2<br> работ ",
  10,108,80,5,"fn2 ","В соответствии с договором подряда",
  10,118,20,5,"","Исполнитель",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,100,5,"l","выполнил за отчетный период следующие работы :",
            
  5,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  23,133,70,15,"b4 c v bl","наименование<br> работ",
  93,133,10,15,"b4 c v bl","сумма,<br> руб",
      
  103,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  121,133,70,15,"b4 c v bl","наименование<br> работ",
  191,133,10,15,"b4 c v bl","сумма,<br> руб",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  113,158 + $table_size,60,5,"r","$itogo",
  173,148 + $table_size,30,5,"b4 tc","$rSumRn",
  113,153 + $table_size,60,5,"r","в т.ч. НДФЛ",
  173,153 + $table_size,30,5,"b4 tc","$rNdfl",
  83,158 + $table_size,90,5,"r","Всего к выплате",
  173,158 + $table_size,30,5,"b4 tc","$rSum",
  10,163 + $table_size,50,5,"","Работа(ы) выполнена(ы)",
  65,163 + $table_size,135,5,"bb","в указанном объеме",
  65,168 + $table_size,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",
  10,173 + $table_size,25,5,"","на сумму",
  35,173 + $table_size,165,5,"bb","$sumstr",
  35,178 + $table_size,165,5,"fn4 c","(прописью)",
  10,183 + $table_size,25,5,"bl","Работу сдал",
  35,188 + $table_size,30,5,"bb","",
  10,188 + $table_size,25,5,"","Исполнитель",
  35,193 + $table_size,30,5,"fn4 c","(личная подпись)",
  10,198 + $table_size,35,5,"bl","Работу принял",
  10,203 + $table_size,50,5,"","Представитель Заказчика",
  55,203 + $table_size,30,5,"bb c","Директор",
  90,203 + $table_size,30,5,"bb","",
  130,203 + $table_size,50,5,"bb c","$dirname",
  55,208 + $table_size,30,5,"fn4 c","(должность)",
  90,208 + $table_size,30,5,"fn4 c","(личная подпись)",
  130,208 + $table_size,50,5,"fn4 c","(расшифровка подписи)"
  ),
  //1 - подряд физ показатели
array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","склад",
  10,44,139,5,"fn4 c","(структурное подразделение)",
  144,31,30,5,"r","по ОКПО",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","по ОКВЭД",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","Договор гражданско-правового характера",
  154,49,20,5,"b4 r","номер",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","дата",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","УТВЕРЖДАЮ",
  145,77,25,5,"","Руководитель",
  170,77,30,5,"bb c","директор",
  170,82,30,5,"fn4 c","(должность)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(личная подпись)",
  175,87,25,5,"bb c","$dirname",
  145,97,25,5,"c","М.П.",
  55,77,20,10,"b4","Номер документа",
  75,77,20,10,"b4","Дата составления",
  55,87,20,5,"b4","б/н",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ<br> выполненных по $dog_type2<br> работ ",
  10,108,80,5,"fn2 ","В соответствии с договором подряда",
  10,118,20,5,"","Исполнитель",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,100,5,"l","выполнил за отчетный период следующие работы :",
            
  5,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  23,133,70,15,"b4 c v bl","наименование<br> работ",
  93,133,10,15,"b4 c v bl","физич.<br> показ.",
      
  103,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  121,133,70,15,"b4 c v bl","наименование<br> работ",
  191,133,10,15,"b4 c v bl","физич.<br> показ.",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  10,153 + $table_size2,50,5,"","Работа(ы) выполнена(ы)",
  65,153 + $table_size2,135,5,"bb","в указанном объеме",
  65,158 + $table_size2,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",
  10,173 + $table_size2,25,5,"bl","Работу сдал",
  35,178 + $table_size2,30,5,"bb","",
  10,178 + $table_size2,25,5,"","Исполнитель",
  35,183 + $table_size2,30,5,"fn4 c","(личная подпись)",
  10,188 + $table_size2,35,5,"bl","Работу принял",
  10,193 + $table_size2,50,5,"","Представитель Заказчика",
  55,193 + $table_size2,30,5,"bb c","Директор",
  90,193 + $table_size2,30,5,"bb","",
  130,193 + $table_size2,50,5,"bb c","$dirname2",
  55,198 + $table_size2,30,5,"fn4 c","(должность)",
  90,198 + $table_size2,30,5,"fn4 c","(личная подпись)",
  130,198 + $table_size2,50,5,"fn4 c","$dirname3"
  ),
  //2 - трудовой договор рубли    
  array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","склад",
  10,44,139,5,"fn4 c","(структурное подразделение)",
  144,31,30,5,"r","по ОКПО",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","по ОКВЭД",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","$dog_type",
  154,49,20,5,"b4 r","номер",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","дата",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","УТВЕРЖДАЮ",
  145,77,25,5,"","Руководитель",
  170,77,30,5,"bb c","директор",
  170,82,30,5,"fn4 c","(должность)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(личная подпись)",
  175,87,25,5,"bb c","$dirname",
  145,97,25,5,"c","М.П.",
  55,77,20,10,"b4","Номер документа",
  75,77,20,10,"b4","Дата составления",
  55,87,20,5,"b4","б/н",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ<br> о приеме работ по<br> $dog_type2",
  10,108,80,5,"fn2 ","В соответствии с $dog_type3",
  90,108,20,5,"bb","$dog_number",
  110,108,10,5,"fn2 ","от “",
  120,108,20,5,"bb ","$dog_day",
  140,108,10,5,"fn2 ","”",
  150,108,20,5,"bb","$dog_mth",
  170,108,7,5,"fn2 ","20",
  177,108,10,5,"bb","$dog_year",
  187,108,10,5,"fn2 ","г.",
  10,118,20,5,"","Работник",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,100,5,"l","выполнил за отчетный период следующие работы :",
            
  5,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  23,133,70,15,"b4 c v bl","наименование<br> работ",
  93,133,10,15,"b4 c v bl","сумма,<br> руб",
      
  103,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  121,133,70,15,"b4 c v bl","наименование<br> работ",
  191,133,10,15,"b4 c v bl","сумма,<br> руб",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  113,148 + $table_size,60,5,"r","$itogo",
  173,148 + $table_size,30,5,"b4 tc","$rSumRn",
  113,153 + $table_size,60,5,"r","в т.ч. НДФЛ",
  173,153 + $table_size,30,5,"b4 tc","$rNdfl",
  83,158 + $table_size,90,5,"r","Всего к выплате",
  173,158 + $table_size,30,5,"b4 tc","$rSum",
  10,163 + $table_size,50,5,"","Работа(ы) выполнена(ы)",
  65,163 + $table_size,135,5,"bb","в указанном объеме",
  65,168 + $table_size,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",
  10,173 + $table_size,25,5,"","на сумму",
  35,173 + $table_size,165,5,"bb","$sumstr",
  35,178 + $table_size,165,5,"fn4 c","(прописью)",
  10,183 + $table_size,25,5,"bl","Работу сдал",
  35,188 + $table_size,30,5,"bb","",
  10,188 + $table_size,25,5,"","Работник",
  35,193 + $table_size,30,5,"fn4 c","(личная подпись)",
  10,198 + $table_size,35,5,"bl","Работу принял",
  10,203 + $table_size,50,5,"","Представитель Заказчика",
  55,203 + $table_size,30,5,"bb c","Директор",
  90,203 + $table_size,30,5,"bb","",
  130,203 + $table_size,50,5,"bb c","$dirname2",
  55,208 + $table_size,30,5,"fn4 c","(должность)",
  90,208 + $table_size,30,5,"fn4 c","(личная подпись)",
  130,208 + $table_size,50,5,"fn4 c","$dirname3"
  ),
 // 3 - трудовой договор физ показатели
 array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","склад",
  10,44,139,5,"fn4 c","(структурное подразделение)",
  144,31,30,5,"r","по ОКПО",
  174,31,26,5,"b4 c","",
  174,36,26,8,"b4 c","",
  144,44,30,5,"r","по ОКВЭД",
  174,44,26,5,"b4 c","",
  84,49,70,5,"r","$dog_type",
  154,49,20,5,"b4 r","номер",
  174,49,26,5,"b4","$dog_number",    
  154,54,20,5,"b4 r","дата",
  174,54,26,5,"b4","$dog_date",
  170,72,25,5,"","УТВЕРЖДАЮ",
  145,77,25,5,"","Руководитель",
  170,77,30,5,"bb c","директор",
  170,82,30,5,"fn4 c","(должность)",
  145,87,25,5,"bb","",
  145,92,25,5,"fn4 c","(личная подпись)",
  175,87,25,5,"bb c","$dirname",
  145,97,25,5,"c","М.П.",
  55,77,20,10,"b4","Номер документа",
  75,77,20,10,"b4","Дата составления",
  55,87,20,5,"b4","б/н",
  75,87,20,5,"b4","$cur_date",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ<br> о приеме работ по<br> $dog_type2",
  10,108,80,5,"fn2 c","В соответствии с $dog_type3",
  90,108,20,5,"bb","$dog_number",
  110,108,10,5,"fn2 ","от “",
  120,108,20,5,"bb","$dog_day",
  140,108,10,5,"fn2 ","”",
  150,108,20,5,"bb","$dog_mth",
  170,108,7,5,"fn2 ","20",
  177,108,10,5,"bb","$dog_year",
  187,108,10,5,"fn2 ","г.",
  10,118,20,5,"","Работник",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,100,5,"l","выполнил за отчетный период следующие работы :",
            
  5,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  23,133,70,15,"b4 c v bl","наименование<br> работ",
  93,133,10,15,"b4 c v bl","физич.<br> показ.",
      
  103,133,18,15,"b4 c v bl","номер<br> по<br> порядку",
  121,133,70,15,"b4 c v bl","наименование<br> работ",
  191,133,10,15,"b4 c v bl","физич.<br> показ.",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  10,153 + $table_size2,50,5,"","Работа(ы) выполнена(ы)",
  65,153 + $table_size2,135,5,"bb","в указанном объеме",
  65,158 + $table_size2,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",
  10,173 + $table_size2,25,5,"bl","Работу сдал",
  35,178 + $table_size2,30,5,"bb","",
  10,178 + $table_size2,25,5,"","Работник",
  35,183 + $table_size2,30,5,"fn4 c","(личная подпись)",
  10,188 + $table_size2,35,5,"bl","Работу принял",
  10,193 + $table_size2,50,5,"","Представитель Заказчика",
  55,193 + $table_size2,30,5,"bb c","Директор",
  90,193 + $table_size2,30,5,"bb","",
  130,193 + $table_size2,50,5,"bb c","$dirname2",
  55,198 + $table_size2,30,5,"fn4 c","(должность)",
  90,198 + $table_size2,30,5,"fn4 c","(личная подпись)",
  130,198 + $table_size2,50,5,"fn4 c","$dirname3"
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