<?php
#error_reporting(E_ALL);
#ini_set('display_startup_errors', 1);
#ini_set('display_errors', '1');

session_start();
require_once "../orasql.php";
require_once "../utils.php";
require_once "../ut_sum.php";
require_once "tcpdf/tcpdf.php";
ob_start();

// PDF header
  $ffam = "timesnr";

  $pdf = new TCPDF('P','mm','A4'); // and unicode,utf-8
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor(iconv('cp1251','utf-8',"Trianon"));

  if($_REQUEST[typ]==0)
   $typ_akta=' (в физ показателях)';

  if($_REQUEST[typ]==1)
   $typ_akta=' (в рублях)';


  $pdf->SetTitle(iconv('cp1251','utf-8',"Акты " . $typ_akta));
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetFont($ffam,'',10); // size in pt
//AddPage( $orientation = 'L', $format = 'A4', $keepmargins = false, $tocpage = false ) 
//"L" - albomnaya orientaciya
  $pdf->SetMargins(10,10);  // left,top, righ=left
  //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)
  //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
  $lineH = 5;
  $tbw= array(5,110,25,10,15,10,15,5);
  $tbwTorg12 = array(10,120,15,10,10,10,10,10,10,10,10,15,8,12,20);
  $tbwSFA = array(5,90,10,7,12,10,18,20,13,8,18,20,8,18,27);
  $tbwSSV = array(10,80,80,10,10);
//$pageH = $pdf->getPageHeight(); 
// both in user units, as well as GetX(),GetY()
  $pageH = 0;
  $mg = $pdf->getMargins();
  $Torg12 = '';
  
$json_obj=json_decode($_REQUEST[json]);

  $data=array();
  foreach($json_obj as $k => $v)
  {
  //if($v!=0)
  $data[$k]=$v;
  }  
 
$dog_number="1";
$dog_type="1";

$dbeg=$_REQUEST[dbeg];
$dend=$_REQUEST[dend];
$dact=$_REQUEST[dact];

$emp_id=$data['CEMP'];

$i=0;

#echo 'count=' . count($data) .'<br>';

  foreach ($data as $k => $v)
  {
      if($i>8)
      //echo $k . ":" . $v;
      $i++;
  }


$sumstr=sum_by_words($rSumRn);
$emp_full_name_polnostju=iconv('utf-8','cp1251',$data['EMP']);




function print_act($response, $act_typ)
{
  global $db,$scheme;
  $emp_id=$response['CEMP'];
  $db->parse("begin $scheme.DIRS.EMP_GET(:cur,:emp); end;");
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

 
   global $pdf;
   global $table, $table2;
   $dirname=$_REQUEST['dirname'];
   $dirname2=$_REQUEST['dirname2'];
   $dbeg=$_REQUEST[dbeg];
   $dend=$_REQUEST[dend];
   $dact=$_REQUEST[dact];
   $table = array();
   $table2 = array();

   $otdel=$_REQUEST[otdel];


   if($otdel=="torgovie")
   {
         $otdel="торговые";
   }

   if($otdel=="exped")
   {
         $otdel="транспорт";
   }

   if($otdel=="sklad")
   {
         $otdel="склад";
   }

   if($otdel=="opers")
   {
         $otdel="операторы";
   }



  $dog_type="Трудовой договор";
  $dog_type2="трудовому договору";


  if($response['IS_DOG_PODR']==0)
  {
  $dog_type="Трудовой договор";
  $dog_type2="трудовому договору";
  $dog_type3="трудовым договором № ";

  $itogo_text="Итого (c район. коэф.)";
  }
  else
  {
  $dog_type="Договор подряда";
  $dog_type2="договору подряда";
  $dog_type3="договором подряда № ";

  $itogo_text="Итого"; // (без район. коэф.)
  }


      if($_REQUEST[otdel]=="torgovie")
      {
        $table[] = array('выручка, тыс. руб.', round($response['OPLAT']/1000) );
        $table2[] = array('выручка (начислено в зп) руб. ',$response['KVIDACHE']);
      }


      if($_REQUEST[otdel]=="exped")
      {
        $table[] = array('точки',round($response['POINTS'],0));
        $table[] = array('точки совп.',round($response['PTS_B'],0));
        $table[] = array('тонн',round($response['KG']/1000,0));
        $table[] = array('куб.м',round($response['LIT']/1000,0));
        $table[] = array('мест',round($response['PKG'],0));
        $table[] = array('км сверх норм	',round($response['KM_MORE'],0));
        $table[] = array('влж. т	',round($response['KG_NS'],0));
        $table[] = array('влж. куб.м ',round($response['LIT_NS'],0));
        $table[] = array('влж. мест	',round($response['PKG_NS'],0));
        $table[] = array('стр. сет.	',round($response['NET_LNS'],0));
        $table[] = array('стр. несет.	',round($response['NONET_LNS'],0));
        $table[] = array('стр. штуч	',round($response['I_NONET_LNS'],0));
        $table[] = array('дней',round($response['DAYS'],0));
        $table[] = array('вых. вовр	',round($response['OFF_OK'],0));
        $table[] = array('тара',round($response['TARE'],0)*10);


        $table2[] = array('точки',round($response['POINTS'],0)*70); //id201
        $table2[] = array('точки совп.',round($response['PTS_B'],0)*10); //id226
        $table2[] = array('тонн',round($response['KG']/1000,0)*50); //id203
        $table2[] = array('куб.м',round($response['LIT']/1000,0)*30); //id204
        $table2[] = array('мест',round($response['PKG'],0));
        $table2[] = array('км сверх норм	',round($response['KM_MORE'],0)*5);
        $table2[] = array('влж. т	',round($response['KG_NS'],0));
        $table2[] = array('влж. куб.м ',round($response['LIT_NS'],0));
        $table2[] = array('влж. мест	',round($response['PKG_NS'],0));
        $table2[] = array('стр. сет.	',round($response['NET_LNS'],0));
        $table2[] = array('стр. несет.	',round($response['NONET_LNS'],0));
        $table2[] = array('стр. штуч	',round($response['I_NONET_LNS'],0));
        $table2[] = array('дней',round($response['DAYS'],0));
        $table2[] = array('вых. вовр	',round($response['OFF_OK'],0));
        $table2[] = array('тара',round($response['TARE'],0)*10); //206-207
      }



     if($_REQUEST[otdel]=="sklad")
      {
        ////////////////////////////////////////////////// ФИЗ ПОКАЗАТЕЛИ

      $table[] = array('приемка ,строк, шт.',round($response['LINES_GOT'],0));
      $table[] = array('приемка ,коробок, шт.',round($response['KOR_GOT'],0));
      $table[] = array('приемка ,тон, шт.',round($response['TON_GOT'],0));
      $table[] = array('приемка ,метров куб., шт.',round($response['M3_GOT'],0));
      $table[] = array('расстановка ,строк, шт.',round($response['LINES_ARR'],0));
      $table[] = array('расстановка ,коробок, шт.',round($response['KOR_ARR'],0));
      $table[] = array('расстановка ,тон, шт.',round($response['TON_ARR'],0));
      $table[] = array('расстановка ,метров куб., шт.',round($response['M3_ARR'],0));
      $table[] = array('отбор ,листов , шт.',round($response['SHEETS_PI'],0));
      $table[] = array('отбор ,строк , шт.',round($response['LINES_PI'],0));
      $table[] = array('отбор ,тон , шт.',round($response['TON_PI'],0));
      $table[] = array('отбор ,метров куб., шт.',round($response['M3_PI'],0));
      $table[] = array('отбор ,пров., шт.',round($response['LINES_PICHK'],0));
      $table[] = array('отбор ,др секторов., шт.(new!!)',round($response['PI_ALN_S'],0)); ////// 10-02-2020
      $table[] = array('упаковка ,метров куб., шт.',round($response['M3_PA'],0));
      $table[] = array('упаковка ,тон , шт.',round($response['TON_PA'],0));
      $table[] = array('упаковка ,зав. кор , шт.',round($response['ZKOR_PA'],0));
      $table[] = array('упаковка ,сбор. кор , шт.',round($response['SKOR_PA'],0));
      $table[] = array('проверка ,строк , шт.',round($response['LINES_CHK'],0));
      $table[] = array('проверка ,доб. , шт.',round($response['AFF_CHK'],0));
      $table[] = array('проверка ,тон , шт.',round($response['TON_CHK'],0));
      $table[] = array('проверка ,метров куб., шт.',round($response['M3_CHK'],0));
      $table[] = array('комплектовка ,рейсов , шт.',round($response['TRIPS'],0));
      $table[] = array('комплектовка ,адресов , шт.',round($response['NADR'],0));
      $table[] = array('комплектовка ,мест , шт.',round($response['PKGS'],0));
      $table[] = array('вычерки ,сам , шт.',round($response['LINERR_PI0'],0));
      $table[] = array('вычерки ,клиент , шт.',round($response['LINERR_PI'],0));
      $table[] = array('вычерки ,пров. , шт.',round($response['LINERR_CHK'],0));           
      $table[] = array('возвраты (new!!!). , шт.',round($response['ERR_RET_S'],0));            
      $table[] = array('инвентаризация ,строк , шт.',round($response['LINES_INV'],0));
      $table[] = array('возвраты ,строк , шт.',round($response['LN_ARR_R'],0));
      $table[] = array('возвраты ,тон , шт.',round($response['TON_ARR_R'],0));
      $table[] = array('возвраты ,метры куб., шт.',round($response['M3_ARR_R'],0));
      $table[] = array('внесение данных ,штрихкодов , шт.',round($response['N_BARC'],0));
      $table[] = array('внесение данных ,секций , шт.',round($response['N_SECT'],0));
      $table[] = array('внесение данных ,сроков годности , шт.',round($response['N_TILL'],0));
      $table[] = array('внесение данных ,вес , шт.',round($response['N_VES'],0));
      $table[] = array('внесение данных ,объем , шт.',round($response['N_VOL'],0));

      if($response['IS_DOG_PODR']==0) // если договор трудовой вывести часы
      $table[] = array('часов по произв календарю',round($response['TURNS8'],0));

      //$table[] = array('часов рабочих',round($response['HOURS'],0));

/*
$response['NH_LOADER']=$response['NH_LOADER_S']/121;
$response['NH_SECUR']=$response['NH_SECUR_S']/118;
$response['NH_CLEAN']=$response['NH_CLEAN_S']/130;
$response['INDIV']=$response['INDIV_S']/110;
$response['EXW_HRS']=$response['EXW_HRS_S']/110;


if($_REQUEST[dogtype]==0) // трудовой
{
      $table[] = array('грузчик ,часов.',round($response['NH_LOADER']) );
      $table[] = array('охранник ,часов.',round($response['NH_SECUR'],0));
      $table[] = array('уборщик ,часов.',round($response['NH_CLEAN'],0));
      $table[] = array('индив. работы ,часов',round($response['INDIV'],0));
      $table[] = array('доп. работы ,часов (new)',round($response['EXW_HRS'],0)); /// 10-02-2020
 }
else  // подряд
{
      $table[] = array('грузчик ,тн , руб.',round($response['NH_LOADER']) );
      $table[] = array('охранник ,мест , руб.',round($response['NH_SECUR'],0));
      $table[] = array('уборщик ,кв м , руб.',round($response['NH_CLEAN'],0));
      $table[] = array('индив. работы , руб.',round($response['INDIV'],0));
      $table[] = array('доп. работы ,часов (new)',round($response['EXW_HRS'],0)); /// 10-02-2020
}     
*/


                  


 ////////////////////////////////////////////////// РУБЛЕВЫЙ АКТ СКЛАД

        $table2[] = array('приемка ,строк, руб.',round($response['LINES_GOT_S'],0));
        $table2[] = array('приемка ,коробок, руб.',round($response['KOR_GOT_S'],0));
        $table2[] = array('приемка ,тон, руб.',round($response['TON_GOT_S'],0));
        $table2[] = array('приемка ,метров куб., руб.',round($response['M3_GOT_S'],0));
        $table2[] = array('расстановка ,строк, руб.',round($response['LINES_ARR_S'],0));
        $table2[] = array('расстановка ,коробок, руб.',round($response['KOR_ARR_S'],0));
        $table2[] = array('расстановка ,тон, руб.',round($response['TON_ARR_S'],0));
        $table2[] = array('расстановка ,метров куб., руб.',round($response['M3_ARR_S'],0));
        $table2[] = array('отбор ,листов , руб.',round($response['SHEETS_PI_S'],0));
        $table2[] = array('отбор ,строк , руб.',round($response['LINES_PI_S'],0));
        $table2[] = array('отбор ,тон , руб.',round($response['TON_PI_S'],0));
        $table2[] = array('отбор ,метров куб., руб.',round($response['M3_PI_S'],0));
        $table2[] = array('отбор ,пров., руб.',round($response['LINES_PICHK_S'],0));
        $table2[] = array('отбор ,др секторов., руб.(new!!)',round($response['PI_ALN_S'],0)); ////// 10-02-2020
        $table2[] = array('упаковка ,метров куб., руб.',round($response['M3_PA_S'],0));
        $table2[] = array('упаковка ,тон , руб.',round($response['TON_PA_S'],0));
        $table2[] = array('упаковка ,зав. кор , руб.',round($response['ZKOR_PA_S'],0));
        $table2[] = array('упаковка ,сбор. кор , руб.',round($response['SKOR_PA_S'],0));
        $table2[] = array('проверка ,строк , руб.',round($response['LINES_CHK_S'],0));
        $table2[] = array('проверка ,доб. , руб.',round($response['AFF_CHK_S'],0));
        $table2[] = array('проверка ,тон , руб.',round($response['TON_CHK_S'],0));
        $table2[] = array('проверка ,метров куб., руб.',round($response['M3_CHK_S'],0));
        $table2[] = array('комплектовка ,рейсов , руб.',round($response['TRIPS_S'],0));
        $table2[] = array('комплектовка ,адресов , руб.',round($response['NADR_S'],0));
        $table2[] = array('комплектовка ,мест , руб.',round($response['PKGS_S'],0));
        $table2[] = array('вычерки ,сам , руб.',round($response['LINERR_PI0_S'],0));
        $table2[] = array('вычерки ,клиент , руб.',round($response['LINERR_PI_S'],0));
        $table2[] = array('вычерки ,пров. , руб.',round($response['LINERR_CHK_S'],0));            
        $table2[] = array('возвраты (new!!!). , руб.',round($response['ERR_RET_S'],0));            
        $table2[] = array('инвентаризация ,строк , руб.',round($response['LINES_INV_S'],0));
        $table2[] = array('возвраты ,строк , руб.',round($response['LN_ARR_R_S'],0));
        $table2[] = array('возвраты ,тон , руб.',round($response['TON_ARR_R_S'],0));
        $table2[] = array('возвраты ,метры куб., руб.',round($response['M3_ARR_R_S'],0));
        $table2[] = array('внесение данных ,штрихкодов , руб.',round($response['N_BARC_S'],0));
        $table2[] = array('внесение данных ,секций , руб.',round($response['N_SECT_S'],0));
        $table2[] = array('внесение данных,сроков годности,руб.',round($response['N_TILL_S'],0));
        $table2[] = array('внесение данных ,вес , руб.',round($response['N_VES_S'],0));
        $table2[] = array('внесение данных ,объем , руб.',round($response['N_VOL_S'],0));


if($_REQUEST[dogtype]==0) // трудовой
{
      $table2[] = array('грузчик ,часов.',round($response['NH_LOADER_S']) );
      $table2[] = array('охранник ,часов.',round($response['NH_SECUR_S'],0));
      $table2[] = array('уборщик ,часов.',round($response['NH_CLEAN_S'],0));
      $table2[] = array('индив. работы ,часов',round($response['INDIV_S'],0));
      $table2[] = array('доп. работы ,часов (new)',round($response['EXW_HRS_S'],0)); /// 10-02-2020

 }
else  // подряд
{
      $table2[] = array('грузчик ,тн , руб.',round($response['NH_LOADER_S']) );
      $table2[] = array('охранник ,мест , руб.',round($response['NH_SECUR_S'],0));
      $table2[] = array('уборщик ,кв м , руб.',round($response['NH_CLEAN_S'],0));
      $table2[] = array('индив. работы , руб.',round($response['INDIV_S'],0));
      $table2[] = array('доп. работы ,часов (new)',round($response['EXW_HRS_S'],0)); /// 10-02-2020
}     
                   

}





      if($_REQUEST[otdel]=="opers")
      {
    $table[] = array("за строку, ручн.приход ,шт.",round($response['LINES_IN_M'],0));
    $table[] = array("за строку, генер.приход ,шт.",round($response['LINES_IN_A'],0));
    $table[] = array("за док-т, ручн.приход ,шт.",round($NDOCS_I_IG,0));
    $table[] = array("за док-т, ручн.расход проч.,шт.",round($NDOCS_O_OG_OM,0));
    $table[] = array("за док-т, ручн.расход бумПр,шт.",round($response['NDOCS_OM'],0));
    $table[] = array("за док-т, генер.приход,шт.",round($response['NDOCS_IG'],0));
    $table[] = array("за док-т, генер.расход,шт.",round($response['NDOCS_OG'],0));
    $table[] = array("за уч.коробки, ручн.приход,шт.",round($KOR_IN_IG,0));
    $table[] = array("за уч.коробки, ручн.расход,шт.",round($KOR_O_OG,0));
    $table[] = array("за приход бухг.,шт.",round($response['BUH_IN_CNT'],0));
    $table[] = array("за док-т КК,шт.",round($response['KK_CNT'],0));
    $table[] = array("за сверку,шт.",round($response['SVER_CNT'],0));
    $table[] = array("за печать док-та,шт.",round($response['PRN_CNT'],0));
    $table[] = array("за печать прайса,шт.",round($response['PRL'],0));
    $table[] = array("за оригинал прихода,шт.",round($response['ORIG_IN'],0));
    $table[] = array("за оригинал расхода,шт.",round($response['ORIG_OUT'],0));
    $table[] = array("за оригинал, время,шт.",round($response['TIME_CNT'],0));
    $table[] = array("за оригинал приход-бухг,шт.",round($response['ORIG_BUH'],0));
    $table[] = array("за оригинал, измен-е строки,шт.",round($response['OLS_CNT'],0));
    $table[] = array("за товар, сертификат,шт.",round($response['SERT_CNT'],0));
    $table[] = array("за товар, вес/объем,шт.",round($response['VES_CNT'],0));
    $table[] = array("за товар, название,шт.",round($response['NAME_CNT'],0));
    $table[] = array("за товар, ГТД,шт.",round($response['GTD_CNT'],0));
    $table[] = array("за товар, привязка,шт.",round($response['LNK_TOV'],0));
    $table[] = array("за товар, скан сертификата,шт.",round($response['SER_FIL'],0));
    $table[] = array("за договор, новый,шт.",round($response['DOG_N'],0));
    $table[] = array("за договор, изменение,шт.",round($response['DOG_C'],0));
    $table[] = array("облако,шт.",round($response['DOC_FIL'],0));
    $table[] = array("асина,шт.",round($response['NASINA'],0));
    $table[] = array("за печать доверенности,шт.",round($response['DOV_PRN'],0));
    $table[] = array("мон.цен,шт.",round($response['MON_PRC'],0));



    $NDOCS_I_IG_S = ($response['NDOCS_I_S']-$response['NDOCS_IG_S']);
    $NDOCS_O_OG_OM_S = ($response['NDOCS_O_S']-$response['NDOCS_OG_S']-$response['NDOCS_OM_S']);
    $KOR_IN_IG_S = ($response['KOR_IN_S']-$response['KOR_IG_S']);
    $KOR_O_OG_S = ($response['KOR_OUT_S']-$response['KOR_OG_S']);




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


          $response['LINES_IN_M_S'] += $Tarifs[401]['VTAR'] * $response['LINES_IN_M'];
          $response['LINES_IN_A_S'] += $Tarifs[402]['VTAR'] * $response['LINES_IN_A'];
          $NDOCS_I_IG_S += $Tarifs[403]['VTAR'] * $NDOCS_I_IG;
          $NDOCS_O_OG_OM_S += $Tarifs[404]['VTAR'] * $NDOCS_O_OG_OM;
          $response['NDOCS_OM_S'] += $Tarifs[405]['VTAR'] * $response['NDOCS_OM'];
          $response['NDOCS_IG_S'] += $Tarifs[406]['VTAR'] * $response['NDOCS_IG'];
          $response['NDOCS_OG_S'] += $Tarifs[407]['VTAR'] * $response['NDOCS_OG'];
          $KOR_IN_IG_S += $Tarifs[408]['VTAR'] * $KOR_IN_IG;
          $KOR_O_OG_S += $Tarifs[409]['VTAR'] * $KOR_O_OG;
          $response['BUH_IN_CNT_S'] += $Tarifs[410]['VTAR'] * $response['BUH_IN_CNT'];
          $response['KK_CNT_S'] += $Tarifs[411]['VTAR'] * $response['KK_CNT'];
          $response['SVER_CNT_S'] += $Tarifs[412]['VTAR'] * $response['SVER_CNT'];
          $response['PRN_CNT_S'] += $Tarifs[413]['VTAR'] * $response['PRN_CNT'];
          $response['PRL_S'] += $Tarifs[414]['VTAR'] * $response['PRL'];
          $response['ORIG_IN_S'] += $Tarifs[415]['VTAR'] * $response['ORIG_IN'];
          $response['ORIG_OUT_S'] += $Tarifs[416]['VTAR'] * $response['ORIG_OUT'];
          $response['TIME_CNT_S'] += $Tarifs[417]['VTAR'] * $response['TIME_CNT'];
          $response['ORIG_BUH_S'] += $Tarifs[418]['VTAR'] * $response['ORIG_BUH'];
          $response['OLS_CNT_S'] += $Tarifs[419]['VTAR'] * $response['OLS_CNT'];
          $response['SERT_CNT_S'] += $Tarifs[420]['VTAR'] * $response['SERT_CNT'];
          $response['VES_CNT_S'] += $Tarifs[421]['VTAR'] * $response['VES_CNT'];
          $response['NAME_CNT_S'] += $Tarifs[422]['VTAR'] * $response['NAME_CNT'];
          $response['GTD_CNT_S'] += $Tarifs[423]['VTAR'] * $response['GTD_CNT'];
          $response['LNK_TOV_S'] += $Tarifs[424]['VTAR'] * $response['LNK_TOV'];
          $response['SER_FIL_S'] += $Tarifs[425]['VTAR'] * $response['SER_FIL'];
          $response['DOG_N_S'] += $Tarifs[426]['VTAR'] * $response['DOG_N'];
          $response['DOG_C_S'] += $Tarifs[427]['VTAR'] * $response['DOG_C'];
          $response['DOC_FIL_S'] += $Tarifs[428]['VTAR'] * $response['DOC_FIL'];
          $response['NASINA_S'] += $Tarifs[429]['VTAR'] * $response['NASINA'];
          $response['DOV_PRN_S'] += $Tarifs[431]['VTAR'] * $response['DOV_PRN'];
          $response['MON_PRC_S'] += $Tarifs[432]['VTAR'] * $response['MON_PRC'];



    $table2[] = array("за строку, ручн.приход,руб.",round($response['LINES_IN_M_S'],0));
    $table2[] = array("за строку, генер.приход,руб.",round($response['LINES_IN_A_S'],0));
    $table2[] = array("за док-т, ручн.приход,руб.",round($NDOCS_I_IG_S,0));
    $table2[] = array("за док-т, ручн.расход проч.,руб.",round($NDOCS_O_OG_OM_S,0));
    $table2[] = array("за док-т, ручн.расход бумПр,руб.",round($response['NDOCS_OM_S'],0));
    $table2[] = array("за док-т, генер.приход,руб.",round($response['NDOCS_IG_S'],0));
    $table2[] = array("за док-т, генер.расход,руб.",round($response['NDOCS_OG_S'],0));
    $table2[] = array("за уч.коробки, ручн.приход,руб.",round($KOR_IN_IG_S,0));
    $table2[] = array("за уч.коробки, ручн.расход,руб.",round($KOR_O_OG_S,0));
    $table2[] = array("за приход бухг.,руб.",round($response['BUH_IN_CNT_S'],0));
    $table2[] = array("за док-т КК,руб.",round($response['KK_CNT_S'],0));
    $table2[] = array("за сверку,руб.",round($response['SVER_CNT_S'],0));
    $table2[] = array("за печать док-та,руб.",round($response['PRN_CNT_S'],0));
    $table2[] = array("за печать прайса,руб.",round($response['PRL_S'],0));
    $table2[] = array("за оригинал прихода,руб.",round($response['ORIG_IN_S'],0));
    $table2[] = array("за оригинал расхода,руб.",round($response['ORIG_OUT_S'],0));
    $table2[] = array("за оригинал, время",round($response['TIME_CNT_S'],0));
    $table2[] = array("за оригинал приход-бухг,руб.",round($response['ORIG_BUH_S'],0));
    $table2[] = array("за оригинал, измен-е строки,руб.",round($response['OLS_CNT_S'],0));
    $table2[] = array("за товар, сертификат,руб.",round($response['SERT_CNT'],0));
    $table2[] = array("за товар, вес/объем,руб.",round($response['VES_CNT_S'],0));
    $table2[] = array("за товар, название,руб.",round($response['NAME_CNT_S'],0));
    $table2[] = array("за товар, ГТД,руб.",round($response['GTD_CNT_S'],0));
    $table2[] = array("за товар, привязка,руб.",round($response['LNK_TOV_S'],0));
    $table2[] = array("за товар, скан сертификата,руб.",round($response['SER_FIL'],0));
    $table2[] = array("за договор, новый,руб.",round($response['DOG_N_S'],0));
    $table2[] = array("за договор, изменение,руб.",round($response['DOG_C_S'],0));
    $table2[] = array("облако,руб.",round($response['DOC_FIL_S'],0));
    $table2[] = array("асина,руб.",round($response['NASINA_S'],0));
    $table2[] = array("за печать доверенности,руб.",round($response['DOV_PRN_S'],0));
    $table2[] = array("мон.цен,руб.",round($response['MON_PRC_S'],0));

    
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



 // var_dump($response);

  $response['EMP']=iconv('utf-8','cp1251',$response['EMP']);
  //$emp_full_name_polnostju = $response['EMP'];
  $rSumRn= $response['RAIKOEF'];
  $rNdfl=$response['NDFL'];
  $itogo=$response['KVIDACHE'];




 
$rSumRn=round ($rSumRn,2);
$itogo=round ($itogo,2);
$rNdfl=round ($rNdfl,2);





  $lines = array();
  $lines2 = array();

  $table_size = 0;
  $table_size2 = 0;
  $top_offset = 0;
  $top_offset2 = 0;

  $iL = 1;
  $iL2 = 1;

/*
  if($_REQUEST[typ]==0)
  $t=$table;
  else
  $t=$table2;
*/


if($act_typ==0)
{
  //echo '123';
  //var_dump($table);
 
  foreach($table as $one_line)
  {
    if($iL < round((count($table) / 2) + 1,0))
    {
        array_push($lines,
            10,148 + $table_size,18,3.5,"b4 c v","$iL",
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
  //  echo $iL . "<br>";
  }
}
else
{
  foreach($table2 as $one_line)
  {
    if($iL2 < round((count($table2) / 2) + 1,0))
    {
        array_push($lines2,
            10,148 + $table_size2,18,3.5,"b4 c v","$iL2",
            23,148 + $table_size2,70,3.5,"b4","$one_line[0]",
            93,148 + $table_size2,10,3.5,"b4 tc","$one_line[1]"
            );
        $table_size2 += 3.5;
    }
    else
    {
        array_push($lines2,
            103,148 + $top_offset2,18,3.5,"b4 c v","$iL2",
            121,148 + $top_offset2,70,3.5,"b4","$one_line[0]",
            191,148 + $top_offset2,10,3.5,"b4 tc","$one_line[1]"
            );
        $top_offset2 += 3.5;
    }
    $iL2++;
  }
}








$sumstr=sum_by_words($rSumRn);

  $patterns= array(  // each line: left,top,width,height,class,content
                // left,top,width,height = 0 not set
 
  //0 - подряд физ показатели
array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","$otdel",
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
  75,87,20,5,"b4","$dact",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ выполненных по $dog_type2 работ ",
  10,108,80,5,"fn2 ","В соответствии  $dog_type2",
  10,118,20,5,"","Исполнитель",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,100,5,"l","выполнил за отчетный период следующие работы :",
      
  10,133,18,15,"b4 c v bl","номер по порядку",
  23,133,70,15,"b4 c v bl","наименование работ",
  93,133,10,15,"b4 c v bl","сумма, шт",
      
  103,133,18,15,"b4 c v bl","номер по порядку",
  121,133,70,15,"b4 c v bl","наименование работ",
  191,133,10,15,"b4 c v bl","сумма, шт",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

  10,163 + $table_size,50,5,"","Работа(ы) выполнена(ы)",

  65,163 + $table_size,136,5,"bb","в указанном объеме",
  65,168 + $table_size,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",

  8,183 + $table_size,35,5,"bl","Работу сдал",

  37,188 + $table_size,30,5,"bb","",
  13,188 + $table_size,25,5,"","Исполнитель",
  35,193 + $table_size,30,5,"fn4 c","(личная подпись)",
  10,198 + $table_size,35,5,"bl","Работу принял",
  10,203 + $table_size,50,5,"","Представитель Заказчика",
  55,203 + $table_size,30,5,"bb c","Директор",
  90,203 + $table_size,30,5,"bb","",
  130,203 + $table_size,50,5,"bb c","$dirname2",
  55,208 + $table_size,30,5,"fn4 c","(должность)",
  90,208 + $table_size,30,5,"fn4 c","(личная подпись)",
  130,208 + $table_size,50,5,"fn4 c","(расшифровка подписи)"
  ),

 // 1 - подряд рубли
  array(
  90,5,70,5,"fn4 r","унифицированная форма № Т-73",
  90,10,70,5,"fn4 r","утверждена Постановлением Госкомстата",
  90,15,70,5,"fn4 r","России 05.01.2004 № 1",
  174,21,26,5,"b4 c","код",
  144,26,30,5,"r","Форма по ОКУД",
  174,26,26,5,"b4 c","0301053",
  10,29,139,5,"bb tc","ООО ТД Трианон",
  10,34,139,5,"fn4 c","(наименование заказчика)",
  10,39,139,5,"bb tc","$otdel",
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
  75,87,20,5,"b4","$dact",
  100,77,40,5,"b4 c fn4","Отчетный период",
  100,82,20,5,"b4 c","с",
  120,82,20,5,"b4 c","по",
  100,87,20,5,"b4","$dbeg",
  120,87,20,5,"b4","$dend",
  10,92,80,16,"fn1 c","АКТ выполненных по $dog_type2 работ ",
  10,108,80,5,"fn2 c","В соответствии $dog_type2",
  10,118,20,5,"","Исполнитель",
  35,118,165,5,"bb","$emp_full_name_polnostju",
  35,123,165,5,"fn4 c","(имя,фамилия,отчество)",
  10,128,90,5,"l","выполнил за отчетный период следующие работы :",
            
  10,133,18,15,"b4 c v bl","номер по порядку",
  23,133,70,15,"b4 c v bl","наименование работ",
  93,133,10,15,"b4 c v bl","сумма, руб",
      
  103,133,18,15,"b4 c v bl","номер по порядку",
  121,133,70,15,"b4 c v bl","наименование работ",
  191,133,10,15,"b4 c v bl","сумма, руб",
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

  121,150 + $table_size2,60,4,"r","$itogo_text",
  181,150 + $table_size2,20,4,"r","$rSumRn",
  121,154 + $table_size2,60,4,"r","в т.ч. НДФЛ ",
  181,154 + $table_size2,20,4,"r","$rNdfl",
  121,158 + $table_size2,60,4,"r","Всего к выплате",
  181,158 + $table_size2,20,4,"r","$itogo",

  10,163 + $table_size2,50,5,"","Работа(ы) выполнена(ы)",

  65,163 + $table_size2,136,5,"bb","в указанном объеме",
  65,168 + $table_size2,135,5,"fn4 c","(указать качество,объем,уровень выполнения работ)",
  11,173 + $table_size2,25,5,"","на сумму",
  35,173 + $table_size2,166,5,"bb","$sumstr",
  35,178 + $table_size2,165,5,"fn4 c","(прописью)",

  8,183 + $table_size2,35,5,"bl","Работу сдал",

  37,188 + $table_size2,30,5,"bb","",
  13,188 + $table_size2,25,5,"","Исполнитель",
  35,193 + $table_size2,30,5,"fn4 c","(личная подпись)",
  10,198 + $table_size2,35,5,"bl","Работу принял",
  10,203 + $table_size2,50,5,"","Представитель Заказчика",
  55,203 + $table_size2,30,5,"bb c","Директор",
  90,203 + $table_size2,30,5,"bb","",
  130,203 + $table_size2,50,5,"bb c","$dirname2",
  55,208 + $table_size2,30,5,"fn4 c","(должность)",
  90,208 + $table_size2,30,5,"fn4 c","(личная подпись)",
  130,208 + $table_size2,50,5,"fn4 c","(расшифровка подписи)"
  )

);

    $patterns[0] = array_merge($patterns[0],$lines);
    $patterns[1] = array_merge($patterns[1],$lines2);


    $pdf->AddPage('P','A4');
   // $pdf->MultiCell($tbwTorg12[0], $lineHf, iconv('cp1251','utf-8',"№"),1,'C',0,0);

    if($act_typ==0)
        $a= $patterns[0];
    else
        $a= $patterns[1];

    for($i=0; $i < count($a)-5; $i+= 6 )
     {
       $border=1;

       if($a[$i+4]=="" || $a[$i+4]=="bl")
       {
      $border=0;	
      $pdf->SetFont('timesnr', '', 10);
       }

      if($a[$i+4]=="fn1 c")
      {
      $border=0;	
      $pdf->SetFont('timesnr', '', 12);
      }

     if($a[$i+4]=="fn2 c")
      {
      $border=0;	
      $pdf->SetFont('timesnr', '', 10);
      }

      if($a[$i+4]=="fn4 c")
      {
      $border=0;	
      $pdf->SetFont('timesnr', '', 7);
      }

      if($a[$i+4]=="fn4 r")
      {
      $border=0;	
      $pdf->SetFont('timesnr', '', 7);
      }

      if($a[$i+4]=="bb tc")
      {
      $border=0;	
      $pdf->SetFont('timesnr', '', 9);
      }

      if($a[$i+4]=="b4 c")
      $pdf->SetFont('timesnr', '', 8);

      if($a[$i+4]=="bb")
      $pdf->SetFont('timesnr', '', 10);

      if($a[$i+4]=="r")
       {
        $pdf->SetFillColor(255, 255, 127);
        $pdf->SetFont('timesnr', '', 8);
        //$pdf->setCellPaddings(2, 4, 6, 8);
      }

       $align='C';
       if( strpos($a[$i+4], 'c') !== false )
       {
         $align='C';
       }
       if( strpos($a[$i+4], 'l') !== false )
       {
         $align='L';
       }
       if( strpos($a[$i+4], 'r') !== false )
       {
         $align='R';
       }

      if($a[$i+4]=="b4 c v bl")
      $pdf->SetFont('timesnr', '', 8);
 
      $pdf->MultiCell($a[$i+2], $a[$i+3], iconv('cp1251','utf-8',$a[$i+5]),$border,$align,0,0, $a[$i+0], $a[$i+1]); //////////////iconv('cp1251','utf-8',$a[$i+5])
      }
}


/*
$_POST[svdata]=($_POST[svdata]);
//var_dump($_POST[svdata]);
echo $_POST[svdata];
$arr=json_decode($_POST[svdata]);
echo "1=" . $arr[0];
echo "2=" . $arr[0][NDFL];
echo  "<br>" . "<br>" . "<br>" . "<br>" . "<br>" . "<br>" . $_POST[svdata][0];
foreach($_POST[svdata] as $dat)
{
  echo "1";
}

*/


$_POST[svdata]=urldecode($_POST[svdata]);
$_POST[svdata]=str_replace(']','', $_POST[svdata]);
$_POST[svdata]=str_replace('[','', $_POST[svdata]);

$arr=explode ("},", $_POST[svdata]);

//echo count ($arr);
//echo $arr[0] . "}";
/*
switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - Ошибок нет';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Достигнута максимальная глубина стека';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Некорректные разряды или несоответствие режимов';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Некорректный управляющий символ';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Синтаксическая ошибка, некорректный JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Некорректные символы UTF-8, возможно неверно закодирован';
        break;
        default:
            echo ' - Неизвестная ошибка';
        break;
    }
*/
$id = $response['ID'];



   $db= new CMyDb();
   try {
     $db->connect( $_SESSION['OraLogin'],$_SESSION['OraPwd'], "trn");
   }
   catch(Exception $e) {
     throw new Exception($e->getMessage()."<br>Имя / пароль не верны.");
   }
 

$arlg = explode('$', $_SESSION['OraLogin']);
$scheme = $arlg[1];



$ncount=count($arr)-1;

for($i=0;$i<$ncount;$i++) ///
{
   $responseJson=$arr[$i] . "}";
   $response = json_decode($responseJson, true); // преобразование строки в формате json в ассоциативный массив 
 
   //if(strlen($response[CTRIP])==0)
   //{
   print_act($response, 0);
   print_act($response, 1);
   //}
}



  $time = date("_d-m-Y_H-i-s_");
  $doc_name = "Nakladnie_List$time.pdf";
  $pdf->Output($doc_name,"I");
  //ob_end_flush();

 
?>