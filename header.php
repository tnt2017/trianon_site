
<?php
header ( "Expires: Mon, 26 Jul 1990 05:00:00 GMT" );
header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
header ( "Cache-Control: no-cache, must-revalidate" );
header ( "Pragma: no-cache" );

$curURLname = '����� ����';
if (isset ( $_SESSION ['OraLogin'] )) 
{
	if(!isset($_REQUEST ['url']))
	{
		$_REQUEST ['url']='index.php';
		$curURLname="�������";
	}
	

	$curURL = $_REQUEST ['url'];
	#$curURLname = $_POST ['url_name'];

	$cur_cat = $_POST ['cat'];
	$cur_subcat = $_POST ['subcat'];
	
	if($curURL=="oper_tasks.php")
	$curURLname = "������ ����������";	

	if($curURL=="month_report.php")
	$curURLname = "����� � ��������";	

	if($curURL=="zarplata.php")
	$curURLname = "��������";	

	if($curURL=="trucks.php")
	$curURLname = "������";	

	if($curURL=="trips.php")
	$curURLname = "�����";	

	if($curURL=="jrn_zak.php")
	$curURLname = "������ �������";	

	if($curURL=="worknotes_tabs.php")
	$curURLname = "�������";	

	if($curURL=="strikeouts.php")
	$curURLname = "�������";	

	if($curURL=="non_liq.php")
	$curURLname = "���������";	


	if($curURL=="returns.php")
	$curURLname = "��������";	

	if($curURL=="doc_lines.php")
	$curURLname = "���";	




}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="ru" />
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1251" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=0.8, minimum-scale=0.4, maximum-scale=5" />
<title><?php echo '����� ���� :: ' . $curURLname;?></title>

<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="icon" href="images/favicon.ico" />

<link href="css/login.css" rel="stylesheet" />
<!-- <link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />-->
<link href="css/simple-sidebar.css" rel="stylesheet" type="text/css" />
<link href="css/all_our.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/datatables/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<!-- add class .modal_full_width -->
<link href="css/bootstrap/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/multisuggest-plugin.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap/bootstrap-iso12.css" rel="stylesheet" type="text/css"/>
<link href="css/bootstrap/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
<!-- <link href="css/bootstrap/build.css" rel="stylesheet" type="text/css"/> -->
<!-- site guide css -->
<link data-mailscript="site_guide/faqtoidmail.php" id="faqtoidScript" href="site_guide/faqtoid.css" rel="stylesheet" type="text/css" />
<!-- <link href="css/bootstrap/bootstro.css" rel="stylesheet" type="text/css" /> -->

<!-- 10.05.2016 added (ylankovskiy) -->
<link rel=stylesheet type="text/css" href="css/trio.css" />
<!-- 10.05.2016 -->

<!-- 04.10.2016 added (ylankovskiy) -->
<!-- <link id="size-stylesheet" rel="stylesheet" type="text/css" href="css/wide.css" /> -->
<link id="size-stylesheet" rel="stylesheet" type="text/css" href="css/narrow.css" />


<!-- 20.02.2020 -->
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/searchpanes/1.0.1/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>



<script type="text/javascript" language="javascript" src="DataTables/datatables.js"></script>


 
<script type="text/javascript" language="javascript" src="js/jqlib.js"></script>
<!-- <script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap.js"></script> -->
<script type="text/javascript" language="javascript" src="js/moment.min.js"></script>



<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap-datetimepicker.ru.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap.typeahead_v.2.3.2.js"></script>
<!-- add 'value' param in updater function  -->

<!-- site guide js -->
<!-- <script type="text/javascript" language="javascript" src="js/bootstrap/bootstro.js"></script> -->
<script type="text/javascript" language="javascript" src="site_guide/faqtoid.js"></script>

<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap-tooltip.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap-select.min.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap/bootstrap-switch.min.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap/multisuggest-plugin.js"></script>
<!-- add value callback on select method -->

<!-- 10.05.2016 added (ylankovskiy) -->
<script src="js/jquery.simple-dtpicker.js"></script>
<script src="js/LocalStorageHandler.js"></script>
<script src="js/js.cookie.js"></script>
<script src="js/js.cookie.js"></script>
<script src="js/script.js"></script>
<script src="js/bootbox.min.js"></script>
<!-- add param 'full-width' in 'size' option 
use: size: 'full-width'
on initialize bootbox dialog -->
<script src="js/notify.js"></script>
<script src="js/modernizr.js"></script>
<!-- <script src="js/dates.js"></script> -->
<!-- <script src="js/dyna_cal.js"></script> -->
<!-- 10.05.2016 -->

<!-- 04.10.2016 added (ylankovskiy) -->
<script>
window.mobileAndTabletcheck = function() {
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};
// function adjustStyle(width) {
//   width = parseInt(width);
//   if (width < 1400) {
//     $("#size-stylesheet").attr("href", "css/narrow.css");
//   } else {
//      $("#size-stylesheet").attr("href", "css/wide.css"); 
//   }
// }

// $(function() {
//   adjustStyle($(this).width());
//   $(window).resize(function() {
//     adjustStyle($(this).width());
//   });
// });
</script>
<style>
@media all and (max-width: 1200px) { /* screen size until 1200px */
    body {
        font-size: 1.5em; /* 1.5x default size */
    }
}
@media all and (max-width: 1000px) { /* screen size until 1000px */
    body {
        font-size: 1.2em; /* 1.2x default size */
        }
    }
@media all and (max-width: 500px) { /* screen size until 500px */
    body {
        font-size: 0.8em; /* 0.8x default size */
        }
    }
@media all and (max-width: 768px) {
/*   .btn { */
/*     font-size:0.8em; */
/*     padding:2px 3px; */
/*   } */
  .bootstrap-switch {
  	font-size:0.8em!important;
/*     padding:2px 3px; */
    margin: 4px!important;
  }
  .float-button {
  	height: 51px!important;
  	width: 51px!important;
  	font-size: 38px!important;
  }
}
@media all and (min-width: 768px) {
/*   .btn { */
/*     font-size:0.9em; */
/*     padding:4px 6px; */
/*   } */
  .bootstrap-switch {
  	font-size:0.9em!important;
/*     padding:2px 3px; */
    margin: 4px!important;
  }
  .float-button {
  	height: 56px!important;
  	width: 56px!important;
  	font-size: 42px!important;
  }
}
@media all and (min-width: 1000px) {
/*   .btn { */
/*     font-size:1em; */
/*     padding:5px 7px; */
/*   } */
  .bootstrap-switch {
  	font-size:1em!important;
/*     padding:2px 3px; */
    margin: 4px!important;
  }
  .float-button {
  	height: 64px!important;
  	width: 64px!important;
  	font-size: 48px!important;
  }
}
/* Navbar */
.navbar-default {
  background-color: #3c94e7;
  border-color: #d2d2d2;
}
.navbar-default .navbar-brand {
  color: #ecf0f1;
}
.navbar-default .navbar-brand:hover,
.navbar-default .navbar-brand:focus {
  color: #194065;
}
.navbar-default .navbar-text {
  color: #ecf0f1;
}
.navbar-default .navbar-nav > li > a {
  color: #ecf0f1;
}
.navbar-default .navbar-nav > li > a:hover,
.navbar-default .navbar-nav > li > a:focus {
  color: #194065;
}
.navbar-default .navbar-nav > .active > a,
.navbar-default .navbar-nav > .active > a:hover,
.navbar-default .navbar-nav > .active > a:focus {
  color: #194065;
  background-color: #d2d2d2;
}
.navbar-default .navbar-nav > .open > a,
.navbar-default .navbar-nav > .open > a:hover,
.navbar-default .navbar-nav > .open > a:focus {
  color: #194065;
  background-color: #d2d2d2;
}
.navbar-default .navbar-toggle {
  border-color: #d2d2d2;
}
.navbar-default .navbar-toggle:hover,
.navbar-default .navbar-toggle:focus {
  background-color: #d2d2d2;
}
.navbar-default .navbar-toggle .icon-bar {
  background-color: #ecf0f1;
}
.navbar-default .navbar-collapse,
.navbar-default .navbar-form {
  border-color: #ecf0f1;
}
.navbar-default .navbar-link {
  color: #ecf0f1;
}
.navbar-default .navbar-link:hover {
  color: #194065;
}

@media (max-width: 767px) {
  .navbar-default .navbar-nav .open .dropdown-menu > li > a {
    color: #ecf0f1;
  }
  .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
  .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
    color: #194065;
  }
  .navbar-default .navbar-nav .open .dropdown-menu > .active > a,
  .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover,
  .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus {
    color: #194065;
    background-color: #d2d2d2;
  }
}
</style>
<!-- 04.10.2016 -->
</head>
<body>
<div class="form-wrap">
    <div class="title">
        �����
        <section class="close close_WRAP">�������</section>
    </div>
    <div id=mapp style="display:none; width: 700px; height: 400px; border: 1px gray solid">
    </div>
</div>
