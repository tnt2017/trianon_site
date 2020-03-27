<?php
chdir ( str_replace ( 'pages', '', getcwd () ) );
$bExt = auth_right ( $db, 206, 0, 0 );

$db->parse ( "begin :n:=SSEC.AUTH_PATH.GET_CUR_USER_KATSOTR_ID; end;" );
$db->bind ( ":n", $cur_user_id, SQLT_INT );
$db->execute ();

function get_org_name($db, $base, $idOrg) {
	// select INN, k.NAME,k.VID,k.CORP,k.STATUS, nvl(k.TERM,0) TERM,k.KREDIT_LIMIT,
	// k.ROOT_ID,k.CREGION, OUR_FIRM, k.CAGENT,k.COEF,k.POINTS, k.NKD,
	// nvl(to_char(k.DATEKD,'DD-MM-YYYY'),'01-01-0001') DKD,
	// nvl(to_char(k.DATEOKD,'DD-MM-YYYY'),'01-01-0001') DOKD,
	// KPP, k.ADR, k.TEL,k.DIR, OKONH,OKPO,k.SVIDET,
	// k.TXT, k.E_MAIL, k.CONTACT,
	// JURTYP, nvl(DOGTYP,0) DOGTYP, WORK_HR_0, WORK_HR_1, PRLIST, PRL_TXT,
	// k.CBOOKER,k.COPER, WORK_HR_2, WORK_HR_3, CRIVAL,RIVAL_VOL, k.TEL2,
	// k.TXT2, e.E_MAIL AG_MAIL, e.NAME||' '||substr(NAME_NAME,1,1)||'.'||
	// substr(NAME_FATHER,1,1)||'.' AG_NAME
	$db->parse ( "begin $base.DIRS.ORG_GET(:cur, :idOrg); end;" );
	$db->bind ( ":cur", $cur, OCI_B_CURSOR );
	$db->bind ( ":idOrg", $idOrg, SQLT_INT );
	$db->execute ();
	$db->execute_cursor ( $cur );
	
	while ( $row = $db->fetch_cursor ( $cur ) ) {
		return $row['NAME'];
	}
}
?>

<script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/plug-ins/1.10.11/sorting/date-de.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="js/client.js"></script>
<script type="text/javascript" src="js/PapaParse-4.1.2/papaparse.js"></script>
<link rel="stylesheet" href="css/bootstrap/bootstrap-multiselect.css" type="text/css"/>
<!--<link rel="stylesheet" href="/path/to/dist/css/bootstrapValidator.min.css"/></p>-->
<style>
    .top-buffer {
        margin-top: 20px;
    }
    .tags {
        margin: 5px 5px;
    }
    .color-ico {
    color: #3c763d;
         display: inline;

    }
</style>
<div id="spinner_system" class="spinner_ajax">
    <img id="img-spinner" src="images/loader.gif" alt="Загрузка" />
</div>

<?php

$tabsel_arr = array (
		'tab_org',
		'tab_emp' 
);
$tabsel = isset ( $_REQUEST ['tabsel'] ) ? $tabsel_arr [$_REQUEST ['tabsel']] : (isset ( $_COOKIE ['worknotes_tabs_tabsel'] ) ? $_COOKIE ['worknotes_tabs_tabsel'] : $tabsel_arr [0]);
function build_layout($db, $base) {
	$org_id = isset ( $_REQUEST ['org_id'] ) ? $_REQUEST ['org_id'] : (isset ( $_COOKIE ['worknotes_tabs_org_id'] ) ? $_COOKIE ['worknotes_tabs_org_id'] : 0);
	
	if (isset($_REQUEST['org_id'])) {
		$org_name = get_org_name($db, $base, $org_id);
	} else {
		$org_name = isset ( $_COOKIE ['worknotes_tabs_org_name'] ) ? esc_decode ( $_COOKIE ['worknotes_tabs_org_name'] ) : null;
	}
	
	$dt1 = isset ( $_REQUEST ['dt_beg_org'] ) ? $_REQUEST ['dt_beg_org'] : (isset ( $_COOKIE ['worknotes_tabs_dt_beg_org'] ) ? $_COOKIE ['worknotes_tabs_dt_beg_org'] : date ( 'd.m.Y' ));
	$dt0 = isset ( $_REQUEST ['dt_end_org'] ) ? $_REQUEST ['dt_end_org'] : (isset ( $_COOKIE ['worknotes_tabs_dt_end_org'] ) ? $_COOKIE ['worknotes_tabs_dt_end_org'] : date ( 'd.m.Y', strtotime ( '-1 month' ) ));
	$dt1_emp = isset ( $_REQUEST ['dt_beg_emp'] ) ? $_REQUEST ['dt_beg_emp'] : (isset ( $_COOKIE ['worknotes_tabs_dt_beg_emp'] ) ? $_COOKIE ['worknotes_tabs_dt_beg_emp'] : date ( 'd.m.Y' ));
	$dt0_emp = isset ( $_REQUEST ['dt_end_emp'] ) ? $_REQUEST ['dt_end_emp'] : (isset ( $_COOKIE ['worknotes_tabs_dt_end_emp'] ) ? $_COOKIE ['worknotes_tabs_dt_end_emp'] : date ( 'd.m.Y', strtotime ( '-1 month' ) ));
	$dt1_sms = isset ( $_REQUEST ['dt_beg_sms'] ) ? $_REQUEST ['dt_beg_sms'] : (isset ( $_COOKIE ['worknotes_tabs_dt_beg_sms'] ) ? $_COOKIE ['worknotes_tabs_dt_beg_sms'] : date ( 'd.m.Y' ));
	$dt0_sms = isset ( $_REQUEST ['dt_end_sms'] ) ? $_REQUEST ['dt_end_sms'] : (isset ( $_COOKIE ['worknotes_tabs_dt_end_sms'] ) ? $_COOKIE ['worknotes_tabs_dt_end_sms'] : date ( 'd.m.Y', strtotime ( '-1 month' ) ));
	echo "<div>
  <!-- Навигация -->
  <ul class=\"nav nav-tabs\" id='tabs_work' role=\"tablist\">
    <li><a id='tab_org' href=\"#worknotes_org\" aria-controls=\"worknotes_org\" role=\"tab\" data-toggle='tab'>Работа с контрагентами</a></li>
    <li><a id='tab_emp' href=\"#worknotes_emp\" aria-controls=\"worknotes_emp\" role=\"tab\" data-toggle='tab'>Записи для подразделений</a></li>
    <li><a href=\"#sms_messages\" aria-controls=\"sms_messages\" role=\"tab\" data-toggle=\"tab\">SMS рассылка</a></li>
    <li><a href=\"#worknotes_all\" aria-controls=\"worknotes_all\" role=\"tab\" data-toggle=\"tab\">Сводная</a></li>
  </ul>
  <!-- Содержимое вкладок -->
  <div class=\"tab-content\">
<!--Вкладка Работа с контрагентами-->
    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"worknotes_org\">
    <div class='container-fluid'>
    <div class='row top-buffer'>
        <div class='col-xs-18 col-md-12'>
            <label for='datetimepicker1'>Период:</label>
            <table>
                <tbody>
                <tr>
                    <td>
                        <div id='datetimepicker1' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt1' class='form-control' value='$dt1'/>
                        </div>
                    </td>
                    <td width='20px' align='center'>- -</td>
                    <td>
                        <div id='datetimepicker2' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt0' class='form-control' value='$dt0'/>
                            <span id='faqtoid9' class='input-group-addon' title='Загрузка данных' rel='tooltip'
                                  data-placement='bottom'>
                                    <span onClick='init_load()' class='glyphicon glyphicon-refresh'></span>
                                </span>
                        </div>
                    </td>
                    <td width='100px'></td>
                    <td>
                        <button onclick='build_modal_new_message()' type='button' class='btn btn-success'>Новое
                            сообщение <span class='glyphicon glyphicon-envelope'></span></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class='row top-buffer'>
        <div class='col-xs-5 col-md-5'>
            <label for='input_coag'>Контрагент:</label>
        </div>
    </div>
    <div class='row top-buffer'>
        <div class='col-xs-5 col-md-5'>
            <input id='input_coag' type='text' name='coag' class='form-control' value='$org_name' data-value='$org_id'/>
        </div>
        <div>
            <button onclick='cancel()' type='button' class='btn btn-danger'>Сброс <span
                    class='glyphicon glyphicon-trash'></span></button>
        </div>
    </div>
    <div class='row top-buffer'>

        <div class='col-xs-5 col-md-5'>
            <label for='input_coagad'>Адрес точки:</label>
            <select id='input_coagad' type='text' name='coagad' class='form-control' value=''></select>
        </div>
    </div>
</div>
<br/>
<hr/>
    <div class='row top-buffer'>
		<div class='col-xs-18 col-md-12'>
			<table id='table_work_notes' style='display: none; width: 100%;' class='table table-condensed table-striped table-bordered table-responsive dataTable'>
			    <thead>
			    <tr>
			        <th>Когда</th>
			        <th>Автор</th>
			        <th>Тип</th>
			        <th>Тема</th>
			        <th>Текст</th>
			        <th>Торгпред</th>
			        <th>Клиент</th>
			        <th>Адрес</th>
			    </tr>
			    </thead>
			    <tbody id=dat>
			    </tbody>
			</table>
		</div>
	</div>
    </div>
<!--Вкладка Работа с сотрудниками-->
    <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"worknotes_emp\">
    <div class='container-fluid'>
    <div class='row top-buffer'>
        <div class='col-xs-18 col-md-12'>
            <label for='datetimepicker0'>Период:</label>
            <table>
                <tbody>
                <tr>
                    <td>
                        <div id='datetimepicker0' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt1_emp' class='form-control' value='$dt1_emp'/>
                        </div>
                    </td>
                    <td width='20px' align='center'>- -</td>
                    <td>
                        <div id='datetimepicker' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt0_emp' class='form-control' value='$dt0_emp'/>
                            <span id='faqtoid9' class='input-group-addon' title='Загрузка данных' rel='tooltip'
                                  data-placement='bottom'>
                                    <span onClick='initload_emp()' class='glyphicon glyphicon-refresh'></span>
                                </span>
                        </div>
                    </td>
                    <td width='100px'></td>
                    <td>
                        <button onclick='build_modal_new_message_emp()' type='button' class='btn btn-success'>Новое
                            сообщение <span class='glyphicon glyphicon-envelope'></span></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br/>
    <hr/>
    <div class='row top-buffer'>
		<div class='col-xs-18 col-md-12'>
			<table id='table_emp_notes' style='display: none; width: 100%;' class='table table-condensed table-striped table-bordered table-responsive dataTable'>
				<thead>
					<tr>
						<th>Дата</th>
						<th>Кому</th>
						<th>Тип</th>
						<th>Тема</th>
						<th>Текст</th>
						<th>Автор</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
    
    </div>
    </div>
    <!--Вкладка Sms-->
    <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"sms_messages\">
    <div class='container-fluid'>
    <div class='row top-buffer'>
        <div class='col-xs-18 col-md-12'>
            <label for='datetimepicker3'>Период:</label>
            <table>
                <tbody>
                <tr>
                    <td>
                        <div id='datetimepicker3' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt1_sms' class='form-control' value='$dt1_sms'/>
                        </div>
                    </td>
                    <td width='20px' align='center'>- -</td>
                    <td>
                        <div id='datetimepicker4' class='input-group date'>
                                <span class='input-group-addon datepickerbutton' title='Отобразить данные на дату'
                                      rel='tooltip' data-placement='right'>
                                    <span class='glyphicon glyphicon-calendar'></span>
                                </span>
                            <input type='text' name='dt0_sms' class='form-control' value='$dt0_sms'/>
                            <span id='faqtoid9' class='input-group-addon' title='Загрузка данных' rel='tooltip'
                                  data-placement='bottom'>
                                    <span onClick='initload_sms()' class='glyphicon glyphicon-refresh'></span>
                                </span>
                        </div>
                    </td>
                    <td width='100px'></td>
                    <td>
                        <button onclick='build_modal_new_sms()' type='button' class='btn btn-success'>Новое sms
                            сообщение <span class='glyphicon glyphicon-envelope'></span></button>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            </div>
            </div>
            <br/>
            <hr/>
		    <div class='row top-buffer'>
				<div class='col-xs-18 col-md-12'>
					<table id='table_sms_org' style='display: none; width: 100%;' class='table table-condensed table-striped table-bordered table-responsive dataTable'>
						<thead>
							<tr>
								<th>Дата</th>
								<th>Клиент</th>
								<th>Задолжность</th>
								<th>Отправитель</th>
								<th>Телефон</th>
								<th>Сообщение</th>
								<th>Агент</th>
								<th>Статус</th>
								<th>Ошибка</th>
								<th>Стоимость</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<br/>
            <hr/>
		    <div class='row top-buffer'>
				<div class='col-xs-18 col-md-12'>
					<table id='table_sms_emp' style='display: none; width: 100%;' class='table table-condensed table-striped table-bordered table-responsive dataTable'>
						<thead>
							<tr>
								<th>Дата</th>
								<th>Отправитель</th>
								<th>Телефон</th>
								<th>Сообщение</th>
								<th>Статус</th>
								<th>Ошибка</th>
								<th>Стоимость</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
    </div>
    <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"worknotes_all\">4</div>
  </div>
</div>";
}
build_layout ( $db, $arlg [1] );
?>
<script type="text/javascript">
    var rules = '<?php echo $bExt; ?>';
    var param_id = '<?php echo $cur_user_id; ?>';
//    var groups = [
//        { id: 'sales', name: 'торг. отдел' },
//        {id: 'purch', name: 'отдел закупа' },
//        {id: 'account', name: 'бухгалтерия' },
//        {id: 'deliv', name: 'ТСС' },
//        {id: 'admin', name: 'админ.' },
//        {id: 'devel', name: 'програм./IT' }
//        ];
var email_dept_conf = [
    {id: 'deliv', name: 'ТСС' },
    {id: 'sales', name: 'торг. отдел' },
    {id: 'purch', name: 'отдел закупа' }
];

    var groups = [
        {id: 'account', name: 'бухгалтерия' },
        {id: 'admin', name: 'админ.' },
        {id: 'devel', name: 'програм./IT' }
    ];
    if (rules > 0) {
        $.each(email_dept_conf, function (data, id) {
        groups.push(id);
    })
    };


    var inn = {id: 'group', name: 'тест'};
    var tgroups = [];

    tgroups.push(groups);


var groups_sorted = {
    sales: {},
    purch: {},
    account: {},
    deliv: {},
    admin: {},
    devel: {}
};

var default_filter_emp = '1,2,3,4,5,6,9';
var selectedFilerByTypeEmp = null;
var table_emp = '';
var FILTER_IDX_EMP = Object.freeze({
    ARCHIVE: "1",
    CERT: "2",
    FORWARDER: "3",
    TORGPRED: "4",
    STOCK: "5",
    MEMO: "6",
    OTHER: "9"
});

var orgId = 0;
var idAddress = 0;
var nameOrg = '';
var default_filter = '1,2,3,4,5,6,7,8,9,10,11';
var selectedFilerByType = null;
var table = '';
var FILTER_IDX = Object.freeze({
    PRICE_TORGPRED: "1",
    PRICE_OFFICE: "2",
    RANGE: "3",
    REVISE: "4",
    CALL_NON_BID: "5",
    DEBT: "6",
    BID: "7",
    CREDIT_NOTE: "8",
    CONTRACT: "10",
    MEETING: "11",
    OTHER: "9"
});
var tabsel = '<?php echo $tabsel; ?>';
function get_cookies_support() {
    var persist = true;
    do {
        var c = 'gCStest=' + Math.floor(Math.random() * 100000000);
        document.cookie = persist ? c + ';expires=Tue, 01-Jan-2030 00:00:00 GMT' : c;
        if (document.cookie.indexOf(c) !== -1) {
            document.cookie = c + ';expires=Sat, 01-Jan-2000 00:00:00 GMT';
            return persist;
        }
    } while (!(persist = !persist));
    return null;
}

var cookieSupport = get_cookies_support();
if (cookieSupport == null) {
    $.notify.defaults({
        clickToHide: true,
        autoHide: true,
        autoHideDelay: 1500
    });

    $.notify('Ваш браузер не настроен на работу с Cookies, функционал страницы будет утерен', 'error');
}
else {
    if ((Cookies.get('worknotes_tabs_org_id')) !== undefined) {
        orgId = Cookies.get("worknotes_tabs_org_id");
        idAddress = Cookies.get("adr_id");
        $("#input_coagad").selectpicker('refresh');
    }

    if (Cookies.get('filter') !== undefined) {
        selectedFilerByType = Cookies.get('filter', null);
        if (typeof selectedFilerByType !== 'undefined' && selectedFilerByType.length > 0) {
            selectedFilerByType = selectedFilerByType.split(',');
        }
    } else {
        selectedFilerByType = default_filter.split(',');
    }
    if (Cookies.get('filter_emp') !== undefined) {
        selectedFilerByTypeEmp = Cookies.get('filter_emp', null);
        if (typeof selectedFilerByTypeEmp !== 'undefined' && selectedFilerByTypeEmp.length > 0) {
            selectedFilerByTypeEmp = selectedFilerByTypeEmp.split(',');
        }
    } else {
        selectedFilerByTypeEmp = default_filter_emp.split(',');
    }

}

var emp_data_list = null;
function emp_data() {
    $.ajax({

        type: 'POST',
        dataType: 'json',
        url: 'extras/emp_for_email_list.php',
        success: function (emp_data) {
            emp_data_list = emp_data;
            $.ajax({
                url: "../tmp/config/email_departments.log",
                type: 'POST',
                dataType: 'text',
                success: function(response) {
                    var temp = Papa.parse(response,{delimiter: ";"});
                    $.each(temp.data, function (data, id) {
                            if (emp_data_list[id[0]] != undefined) {
                                for (i = 0; i <= 5; i++) {
                                    var group = id[1] >> i & 0x1;
                                    if (i == 0) {
                                        if (group == 1) {
                                            groups_sorted.sales[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }
                                    if (i == 1) {
                                        if (group == 1) {
                                            groups_sorted.purch[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }

                                    if (i == 2) {
                                        if (group == 1) {
                                            groups_sorted.account[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }

                                    if (i == 3) {
                                        if (group == 1) {
                                            groups_sorted.deliv[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }

                                    if (i == 4) {
                                        if (group == 1) {
                                            groups_sorted.admin[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }

                                    if (i == 5) {
                                        if (group == 1) {
                                            groups_sorted.devel[id[0]] = emp_data_list[id[0]].emp;
                                        }
                                    }
                                }
                            }

                        }
                    )},

                error: function () {
                console.log('error');
            },
            complete: function () {

                if (param_id in groups_sorted.purch) {
                    groups.push(email_dept_conf[1]);
                }


            }
            });
                },
                error: function () {
                    console.log('error');
                },
                complete: function () {
                    console.log('complete');
                }
            });

}
emp_data();









    $(document.body).ready(function () {
        spinner_activation("#spinner_system");
        $('#datetimepicker').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });

        $('#datetimepicker0').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });

        $('#datetimepicker1').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });

        $('#datetimepicker2').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });
        $('#datetimepicker3').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });
        $('#datetimepicker4').datetimepicker({
            locale: 'ru',
            stepping: '1',
            format: 'DD.MM.YYYY',
            useCurrent: true,
            tooltips: {
                today: 'Текущий день',
                clear: 'Очистить',
                close: 'Закрыть',
                selectMonth: 'Выбрать месяц',
                prevMonth: 'Предыдущий месяц',
                nextMonth: 'Следующий месяц',
                selectYear: 'Выбрать год',
                prevYear: 'Предыдущий год',
                nextYear: 'Следующий год'
            }
        });

    });



function init_datatable_emp(table_emp, jdata_emp, jdata_emp_recv, t_name) {
    if ($.fn.DataTable.isDataTable('#' + t_name)) {
        table_emp.clear();
    }
    var cur_table = '#' + t_name;
    table_emp = $(cur_table).DataTable({
        deferRender: true,
        searching: true,
        info: true,
        stateSave: true,
        autoWidth: false,
        scrollCollapse: true,
        pagingType: 'full_numbers',
        pageLength: 75,
        paging: true,
        "lengthMenu": [[25, 75, 200, -1], [25, 75, 200, "Все"]],
        destroy: true,
        columnDefs: [
            {
                type: "de_datetime",
                targets: 0
            }
        ],
        dom: "<'row'<'col-md-2'l><'col-md-7 text-center selectedClassEmp 'B><'col-md-3'f>>" +
        "<'row'<'col-md-12'tr>>" +
        "<'row'<'col-md-3'i><'col-md-9'p>>",
        buttons: [
            {
                extend: 'copy',
                title: 'Копировать',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel экспорт',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'PDF экспорт',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                title: 'Печать',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        fixedHeader: {
            header: true,
            footer: true,
            headerOffset: navigation_offset
        },
        aaSorting: [],
        "bSort": true,
        language: {
            "url": "js/Russian.js",
            buttons: {
                copy: 'Копировать',
                print: 'Печать'
            },
            search: 'Поиск'
        },
        data: jdata_emp,
        columns: [
            {
                data: "dt",
                class: ""
            },
            {
                data: "whom",
                class: "",
                "className": "details-control"
            },
            {
                data: "typ",
                class: ""
            },
            {
                data: "subj",
                class: ""
            },
            {
                data: "txt",
                class: ""
            },
            {
                data: "emp",
                class: ""
            },
            {
                data: "ctype",
                class: "hide"
            }

        ],
        "order": [[1, 'asc']],
        "rowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull)
        {

            function format ( d ) {
                // `d` is the original data object for the row
                var ret = '<div class="container"><div class="row"><div class="col-xs-18 col-md-12">';
                $.each(jdata_emp_recv[aData['unique_key']], function (i) {
					ret += '<span class="label label-primary" style="">'+jdata_emp_recv[aData['unique_key']][i]['whom']+'</span>&nbsp;';
                });
                
                return ret += '</div></div></div>';
            }
            
            $(nRow).off('click', 'td.details-control');
            $(nRow).on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table_emp.row(tr);
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child(format(aData)).show();
                    tr.addClass('shown');
                }
            });

        },
       "initComplete": function (settings, json) {

            var content_select_emp = '';
            content_select_emp += "<label for='type_filter_emp'>&nbsp;Фильтр по типу работ:&nbsp;</label>";
            content_select_emp += "</select>";
            content_select_emp += "<select id='filter_by_type_emp' class='selectpicker' name='type_filter_emp' data-style='btn-success' multiple='multiple'>";
            content_select_emp += "<option selected value='1'>С архивом</option>";
            content_select_emp += "<option selected value='2'>С сертификатом</option>";
            content_select_emp += "<option selected value='3'>С экспедитором</option>";
            content_select_emp += "<option selected value='4'>С торговым представителем</option>";
            content_select_emp += "<option selected value='5'>Со складом</option>";
            content_select_emp += "<option selected value='6'>Слежебная записка</option>";
            content_select_emp += "<option selected value='9'>Прочее</option>";
            content_select_emp += "</select>";

            $('.selectedClassEmp').append(content_select_emp);
            var optSelect_emp = $('#filter_by_type_emp option:selected');
            $(optSelect_emp).each(function () {
                if ($.inArray($(this).val(), selectedFilerByTypeEmp) >= 0) {
                    $(this).prop("selected", true);
                } else {
                    $(this).prop("selected", false);
                }

            });

            $('#filter_by_type_emp').multiselect({
                buttonClass: 'btn btn-success',
                selectedClass: 'bg-danger',
                onDropdownHide: function (event) {
                    table_emp.draw();
                },
                onChange: function (option, checked) {
                    var opts = $('#filter_by_type_emp option:selected');

                    selectedFilerByTypeEmp = [];
                    $(opts).each(function (index, opt) {
                        selectedFilerByTypeEmp.push($(this).val());
                    });
                    Cookies.set("filter_emp", selectedFilerByTypeEmp.join(','));
                }
            })
        }

    });
    return table_emp;

}

    function initload_emp() {
        var date0_emp = $('input[name=dt0_emp]').val();
        var date1_emp = $('input[name=dt1_emp]').val();
        Cookies.set('worknotes_tabs_dt_beg_emp', date1_emp);
        Cookies.set('worknotes_tabs_dt_end_emp', date0_emp);
        var jdata_emp = null;
        var jdata_emp_recv = null;



        $.ajax({
            type: 'POST',
            url: "pages/worknotes_emp_load.php",
            dataType: 'json',
            cache: false,
            data: {
                dt0_emp: date1_emp,
                dt1_emp: date0_emp
            },
            success: function (data_emp) {
                if (data_emp !== null && typeof data_emp === 'object') {
                    jdata_emp = data_emp['LIST_EMP_NOTES']['MESSAGES'];
                    jdata_emp_recv = data_emp['LIST_EMP_NOTES']['RECEIVERS'];
                    table_emp = init_datatable_emp(table_emp, jdata_emp, jdata_emp_recv, 'table_emp_notes');

                }
            },
            error: function (xhr, textStatus, hz) {
                // console.log(textStatus);
            },
            complete: function () {
                $('#table_emp_notes').show();


            }

        });
    };


    function build_modal_new_message_emp() {
        //var whoms = new Object();



        var whoms_n = 0;
        var bootbox_title = 'Новое сообщение</p>';
        var content_bootbox = "<div id='frm'>";
        content_bootbox += "<div class='bootstrap-iso'>";
        content_bootbox += "<div class='container-fluid'>";
        content_bootbox += "<div class='row'>";
        content_bootbox += "<div class='col-md-7 col-md-push-5'>";

        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='subj-msg-emp'>";
        content_bootbox += "Тема сообщения";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<input class='form-control'  id='' name='subj-msg-emp'/>";
        content_bootbox += "</div>";

        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='kind-of-work-emp'>";
        content_bootbox += "Тип работ";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<select class='form-control' id='' name='kind-of-work-emp'>" +
        "<option value='9' selected='selected'>Прочее</option>" +
        "<option value='1'>С архивом</option>" +
        "<option value='2'>С сертификатами</option>" +
        "<option value='3'>С экспедиторами</option>" +
        "<option value='4'>С торговыми представителями</option>" +
            "<option value='5'>По складу</option>" +
            "<option value='6'>Служебные записки</option>" +
        "</select>";
        content_bootbox += "</div>";

        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='message-emp'>";
        content_bootbox += "Примечание:";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<textarea class='form-control' name='message-emp' rows='10' id='message-emp'></textarea>";
        content_bootbox += "</div>";

        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='org-for-emp'>";
        content_bootbox += "Контрагент";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<input class='form-control' value='' data-value='' id='org-for-emp' name='org-for-emp'/>";
        content_bootbox += "</div>";

        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='address-for-org-emp'>";
        content_bootbox += "Адрес контрагента";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<select id='address-for-org-emp' type='text' name='address-for-org-emp' class='form-control' value=''></select>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";

        content_bootbox += "<div class='col-md-5 col-md-pull-7'>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='whom-dep'>";
        content_bootbox += "Выбор получателя";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<input class='form-control' id='whom-dep' name='whom-dep' width='100%'>";
        content_bootbox += "</div>";

        content_bootbox +="<button class='btn btn-primary' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>";
        content_bootbox += "Список получателей" + " <span id ='whoms' class='badge'>" + whoms_n + "</span>";
        content_bootbox +="</button>";

        content_bootbox +="<div class='collapse' id='collapseExample'>";
        content_bootbox +="<div class='well'>";
        content_bootbox +="....";
        content_bootbox +="</div>";
        content_bootbox +="</div>";

        content_bootbox += "</div>";

        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";



        bootbox.dialog({
            size: 'large',
            title: bootbox_title,
            message: content_bootbox,
            buttons: {
                success: {
                    label: "Сохранить",
                    className: "btn-success",
                    callback: function () {

                        var message = {};
                        message.subj = $('input[name = subj-msg-emp]').val();
                        message.kind = $('select[name = kind-of-work-emp').val();
                        message.text = $('textarea[name = message-emp').val();
                        if ($('input[name = org-for-emp]').val() != '' && $('input[name = org-for-emp]').val() != undefined) {
                            message.org = $('input[name = org-for-emp]').val();
                            message.kind = 9;
                            if ($('#address-for-org-emp').val() == null) {
                                message.addr = 0;
                            } else {
                                message.addr =  $('#address-for-org-emp').val();
                            }
                        } else {
                            message.org = 0;
                        }
                        if (Object.keys(whoms).length == 0) {
                            message.whom = 0;
                        } else {
                            message.whom = whoms;
                        }




                        if (message.text.length <= 0) {
                                alert('Ошибка отправки: пустое сообщение');

                        } else {
                            var emps = {};
                            i=0;
                            $.each(message.whom, function (id) {

                                var emp = {id: id};
                                emps[i] = emp;
                                i++;
                            });

                            var jemps = JSON.stringify(emps);


                           save_note(message.org, message.addr, message.text, message.subj, message.kind,'','','',true, jemps);
//
                        }

                        }
                    }
                },

            onEscape: false,
            backdrop: true
        }).on("shown.bs.modal", function () {

           // $(".selectpicker", this).selectpicker('render');

            var all_whoms = ['Отправить всем'];
            whoms = new Object();
            $("#org-for-emp").blur();
            $("#org-for-emp").multisuggest({
                sources: [
                    {
                        data: "extras/org_sel.php",
                        queryParam: "query",
                        type: "url",
                        listFormatter: function (item, term) {
                            var compiled = _.template("<% print(hl(name)) %>");
                            return compiled({
                                hl: $.proxy($.fn.multisuggest.highlighter, this, term),
                                name: item.name

                            });
                        },
                        inputFormatter: function (item) {
                            var compiled = _.template("<%= name %>");

                            return item.name;
                        },
                        valueAttribute: function (item) {
                            return item.id;
                        },
                        resultsProcessor: function (data) {
                            return data;
                        },
                        header: "Контрагенты",
                        maxEntries: 7
                    }
                ],
                loadingIconClass: "msuggest-loading",
                noResultsText: "Контрагент не найден.",
                enableDropdownMode: true,
                delay: 300,
                minLength: 2,
                maxLength: 10,
                updater: function (display, value) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: 'extras/addr_sel.php',
                        data: {query: value},
                        success: function (e) {
                            var htm = '<option value="0">' + "выберите адрес" + '</option>'; // = "<option value='0'>--</option>";
//                            var htm; // = "<option value='0'>--</option>";
                            $.each(e, function (index, addr) {
                                htm += '<option value="' + addr.id + '">' + addr.adr + '</option>';
                            });
                            $("#address-for-org-emp").empty();
                            $("#address-for-org-emp").html(htm);
                            $("#address-for-org-emp").selectpicker('refresh');
                        }
                    });
                    return display;
                }

            });



            $('#whom-dep').blur();
            $('#whom-dep').multisuggest({
                sources: [
                    {
                        data: "extras/emp_sel.php",
                        queryParam: "query",
                        type: "url",
                        listFormatter: function(item, term) {
                            var compiled = _.template("<% print(hl(name)) %>");
                            return compiled({
                                hl:             $.proxy($.fn.multisuggest.highlighter, this, term),
                                name:         item.name
                            });
                        },
                        inputFormatter: function(item) {
                            var compiled = _.template("<%= name %>");
                            return item.name;
                        },
                        valueAttribute: function(item) {
                            return item.id;
                        },
                        resultsProcessor: function(data) {
                            return data;
                        },
                        header: "Пользователи",
                        maxEntries: 7
                    },
                    <?php if ($bExt > 0) { ?>
                    {
                        data: all_whoms,
                        type: "array",
                        listFormatter: function(item, term) {
                            return item;
                        },

                        valueAttribute: function(item) {
                            return "all";
                        },
                        resultsProcessor: function(data) {
                            return data;
                        },
                        maxEntries: 7
                    },
                    <?php } ?>
                    {
                        data: groups,
                        type: "array",
                        filter: function(items, term) {
                        var matcher = new RegExp($.fn.multisuggest.escapeRegex(term), "i");
                        return _.filter(items, function(item) {
                            return (matcher.test(item.name) || matcher.test(item.description));
                        });
                    },
                        listFormatter: function(item, term) {
                            var compiled = _.template("<% print(hl(name)) %>");
                            return compiled({
                                hl:           $.proxy($.fn.multisuggest.highlighter, this, term),
                                name:         item.name
                            });
                        },
                        inputFormatter: function(item) {
                            var compiled = _.template("<%= name %>");
                            return item.name;
                        },
                        valueAttribute: function(item) {
                            return item.id;
                        },
                        resultsProcessor: function(data) {
                            return data;
                        },
                        header: "Подразделения",
                        maxEntris: 5
                    }
                ],
                loadingIconClass: "msuggest-loading",
                noResultsText: "Пользователь не найден.",
                enableDropdownMode: true,
                delay: 300,
                minLength: 2,
                maxLength: 10,
                updater: function(display, value) {
                    if (value == "all") console.log('all');
                    switch (value) {
                        case "sales":
                            $.each(groups_sorted.sales, function (index, element) {
                                    whoms[index] = element;
                            });
                            break;
                        case "purch":
                            $.each(groups_sorted.purch, function (index, element) {
                                    whoms[index] = element;
                            });
                            break;
                        case "account":
                            $.each(groups_sorted.account, function (index, element) {
                                whoms[index] = element;
                            });
                            break;
                        case "deliv":
                            $.each(groups_sorted.deliv, function (index, element) {

                                whoms[index] = element;
                            });
                            break;
                        case "admin":
                            $.each(groups_sorted.admin, function (index, element) {
                                whoms[index] = element;
                            });
                            break;
                        case "devel":
                            $.each(groups_sorted.devel, function (index, element) {
                                whoms[index] = element;
                            });
                            break;
                        case "all":
                            $.each(emp_data_list, function (value, element) {
                                whoms[value] = element.emp;
                            });

                            break;
                        default:
                            whoms[value] = display;
                    }

                    buildWhoms(whoms);
                    get_email(whoms, emp_data_list);
                    whoms_n = Object.keys(whoms).length;
                    $("#whoms").text(whoms_n);
                    $("#whom-dep:content").empty();
                   // $("#collapseExample").selectpicker('refresh');
                    return display;
                }
            });

            $(".selectpicker", this).selectpicker('render');
        });
    }

function removeWhom(id) {
    delete whoms[id];
    buildWhoms(whoms);
    return 0;
}

function buildWhoms(whoms) {
    $(".well").empty();
    $.each(whoms, function (index, element) {

        $('<div class="btn-group tags">' +
            '<button type="button" disabled="disabled" color="#fff"  class="btn btn-success btn-xs">' + element + '</button>' +
            '<button onClick="removeWhom(' + index + ')" value=' + index + ' type="button"  class="btn btn-danger btn-xs">' +
            '&nbsp;<span class="glyphicon glyphicon-remove-circle"></span></button>' +
            '</div>').appendTo('.well');
    });
    whoms_n = Object.keys(whoms).length;
    $("#whoms").text(whoms_n);
    return 0;
}

var emails = {};
function get_email(whoms, emp_data_list) {
    $.each(whoms, function (id, name) {
        var tmp = { name: name, mail: emp_data_list[id].email};
        emails[id] = tmp;
        return 0;
    })
}


</script>
<script>



    function cancel() {
        orgId = 0;
        idAddress = 0;
        Cookies.remove('worknotes_tabs_org_id');
        Cookies.remove('adr_id');
        Cookies.remove('worknotes_tabs_org_name');
        Cookies.remove('adr_name');
        $("#input_coag").empty();
        $("#input_coagad").empty();
        $("#input_coagad").selectpicker('refresh');
        $("#input_coag").val('');
        $.notify.defaults({
            clickToHide: true,
            autoHide: true,
            autoHideDelay: 1500
        });

        $.notify('Все поля сброшены', 'info');
        init_load();
    }

    function get_cookies_support() {
        var persist = true;
        do {
            var c = 'gCStest=' + Math.floor(Math.random() * 100000000);
            document.cookie = persist ? c + ';expires=Tue, 01-Jan-2030 00:00:00 GMT' : c;
            if (document.cookie.indexOf(c) !== -1) {
                document.cookie = c + ';expires=Sat, 01-Jan-2000 00:00:00 GMT';
                return persist;
            }
        } while (!(persist = !persist));
        return null;
    }

    function init_datatable(table, jdata, t_name) {
        if ($.fn.DataTable.isDataTable('#' + t_name)) {
            table.clear();
        }
        var cur_table = '#' + t_name;
        table = $(cur_table).DataTable({
            deferRender: true,
            searching: true,
            info: true,
            stateSave: true,
            autoWidth: false,
            scrollCollapse: true,
            pagingType: 'full_numbers',
            pageLength: 75,
            paging: true,
            "lengthMenu": [[25, 75, 200, -1], [25, 75, 200, "Все"]],
            destroy: true,
            columnDefs: [
                {
                    type: "de_datetime",
                    targets: 0
                }
            ],
            dom: "<'row'<'col-md-2'l><'col-md-8 text-center selectedClass 'B><'col-md-2'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-3'i><'col-md-9'p>>",
            buttons: [
                {
                    extend: 'copy',
                    title: 'Копировать',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Excel экспорт',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'PDF экспорт',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: 'Печать',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            fixedHeader: {
                header: true,
                footer: true,
                headerOffset: navigation_offset
            },
            aaSorting: [],
            "bSort": true,
            language: {
                "url": "js/Russian.js",
                buttons: {
                    copy: 'Копировать',
                    print: 'Печать'
                },
                search: 'Поиск'
            },
            data: jdata,
            columns: [
                {
                    data: "dt",
                    className: ""
                },
                {
                    data: "emp",
                    class: ""
                },
                {
                    data: "typ",
                    class: ""
                },
                {
                    data: "subj",
                    class: ""
                },
                {
                    data: "txt",
                    class: ""
                },
                {
                    data: "agt",
                    class: ""
                },
                {
                    data: "org",
                    class: ""
                },
                {
                    data: "adr",
                    class: ""
                },
                {
                    data: "ntyp",
                    class: "hide"
                }
            ],

            "initComplete": function (settings, json) {

                var content_select = '';
                content_select += "<label for='type_filter'>&nbsp;Фильтр по типу:&nbsp;</label>";
                content_select += "</select>";
                content_select += "<select id='filter_by_type' class='selectpicker' name='type_filter' data-style='btn-success' multiple='multiple'>";
                content_select += "<option selected value='1'>Прайс лист от торгпреда</option>";
                content_select += "<option selected value='2'>Прайс лист из офиса</option>";
                content_select += "<option selected value='3'>По ассортименту</option>";
                content_select += "<option selected value='4'>Сверка</option>";
                content_select += "<option selected value='5'>Звонок без заявки</option>";
                content_select += "<option selected value='6'>По долгам клиента</option>";
                content_select += "<option selected value='7'>Заявка</option>";
                content_select += "<option selected value='8'>Кредитная записка</option>";
                content_select += "<option selected value='10'>Договор</option>";
                content_select += "<option selected value='11'>Собрание</option>";
                content_select += "<option selected value='9'>Прочее</option>";
                content_select += "</select>";

                $('.selectedClass').append(content_select);
                var optSelect = $('#filter_by_type option:selected');
                $(optSelect).each(function () {
                    if ($.inArray($(this).val(), selectedFilerByType) >= 0) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).prop("selected", false);
                    }

                });

                $('#filter_by_type').multiselect({
                    buttonClass: 'btn btn-success',
                    selectedClass: 'bg-danger',
                    onDropdownHide: function (event) {
                        table.draw();
                    },
                    onChange: function (option, checked) {
                        var opts = $('#filter_by_type option:selected');

                        selectedFilerByType = [];
                        $(opts).each(function (index, opt) {
                            selectedFilerByType.push($(this).val());
                        });
                        Cookies.set("filter", selectedFilerByType.join(','));
                    }
                })
            }

        })
        return table;

    }


    $(document.body).ready(function () {
       // init_load(); // load ajax data

    });
    $('#'+ tabsel).tab('show');
    if (tabsel == 'tab_org') init_load();
    if (tabsel == 'tab_emp') initload_emp();

    $('a[data-toggle="tab"]').on ('show.bs.tab', function (e) {
        if (e.target.id == 'tab_org')
        {
            tabsel = 'tab_org';
            Cookies.set("worknotes_tabs_tabsel","tab_org");
            if ( ! $.fn.DataTable.isDataTable( '#table_work_notes' ) ) {
                init_load();
            }
        }
        if (e.target.id == 'tab_emp')
        {
            tabsel = 'tab_emp';
            Cookies.set("worknotes_tabs_tabsel","tab_emp");
            if ( ! $.fn.DataTable.isDataTable( '#table_emp_notes' ) ) {
                initload_emp();
            }
        }
    });
    
    $.fn.dataTable.ext.search.push(
                function (settings, data) {
                    if ( tabsel == 'tab_emp') {
                        var IDX_TAB_TYPE_EMP = 6;
                        if ($.inArray(FILTER_IDX_EMP.ARCHIVE, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.ARCHIVE))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.CERT, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.CERT))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.FORWARDER, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.FORWARDER))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.TORGPRED, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.TORGPRED))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.STOCK, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.STOCK))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.MEMO, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.MEMO))
                            return true;
                        if ($.inArray(FILTER_IDX_EMP.OTHER, selectedFilerByTypeEmp) >= 0 && (data[IDX_TAB_TYPE_EMP] == FILTER_IDX_EMP.OTHER))
                            return true;
                        return false;
                    }
                    if ( tabsel == 'tab_org') {
                        var IDX_TAB_TYPE = 8;
                        if ($.inArray(FILTER_IDX.PRICE_TORGPRED, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.PRICE_TORGPRED))
                            return true;
                        if ($.inArray(FILTER_IDX.PRICE_OFFICE, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.PRICE_OFFICE))
                            return true;
                        if ($.inArray(FILTER_IDX.RANGE, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.RANGE))
                            return true;
                        if ($.inArray(FILTER_IDX.REVISE, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.REVISE))
                            return true;
                        if ($.inArray(FILTER_IDX.CALL_NON_BID, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.CALL_NON_BID))
                            return true;
                        if ($.inArray(FILTER_IDX.DEBT, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.DEBT))
                            return true;
                        if ($.inArray(FILTER_IDX.BID, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.BID))
                            return true;
                        if ($.inArray(FILTER_IDX.CREDIT_NOTE, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.CREDIT_NOTE))
                            return true;
                        if ($.inArray(FILTER_IDX.CONTRACT, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.CONTRACT))
                            return true;
                        if ($.inArray(FILTER_IDX.MEETING, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.MEETING))
                            return true;
                        if ($.inArray(FILTER_IDX.OTHER, selectedFilerByType) >= 0 && (data[IDX_TAB_TYPE] == FILTER_IDX.OTHER))
                            return true;
                        return false;
                    }
                }
    );



    function init_load() {



        var date0 = $('input[name=dt0]').val();
        var date1 = $('input[name=dt1]').val();
        Cookies.set('worknotes_tabs_dt_beg_org', date1);
        Cookies.set('worknotes_tabs_dt_end_org', date0);

        var jdata = null;
        $.ajax({
            type: 'POST',
            url: "pages/worknotes_org_load.php",
            dataType: 'json',
            cache: false,
            data: {
                dt0: date1,
                dt1: date0,
                corg: orgId,
                cadr: idAddress
            },
            success: function (data) {
                if (data !== null && typeof data === 'object') {
                    jdata = data['LIST_ORG_NOTES'];
                    table = init_datatable(table, jdata, 'table_work_notes');
                }
            },
            error: function (xhr, textStatus, hz) {

            },
            complete: function () {
                $('#table_work_notes').show();


            }

        });


        $('#input_coag').blur();
        $('#input_coag').multisuggest({
            sources: [
                {
                    data: "extras/org_sel.php",
                    queryParam: "query",
                    type: "url",
                    listFormatter: function (item, term) {
                        var compiled = _.template("<% print(hl(name)) %>");
                        return compiled({
                            hl: $.proxy($.fn.multisuggest.highlighter, this, term),
                            name: item.name
                        });
                    },
                    inputFormatter: function (item) {
                        var compiled = _.template("<%= name %>");
                        return item.name;
                    },
                    valueAttribute: function (item) {
                        return item.id;
                    },
                    resultsProcessor: function (data) {
                        return data;
                    },
                    header: "Контрагенты",
                    maxEntries: 7
                }
            ],
            loadingIconClass: "msuggest-loading",
            noResultsText: "Контрагент не найден.",
            enableDropdownMode: true,
            delay: 300,
            minLength: 2,
            maxLength: 10,

            updater: function (display, value) {
                orgId = value;
                nameOrg = display;
                Cookies.set("worknotes_tabs_org_id", orgId);
                Cookies.set("worknotes_tabs_org_name", escape(nameOrg));

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'extras/addr_sel.php',
                    data: {query: value},
                    success: function (e) {

                        var htm = '<option value="">' + "выберите адрес" + '</option>'; // = "<option value='0'>--</option>";
                        $.each(e, function (index, addr) {

                            htm += '<option value="' + addr.id + '">' + addr.adr + '</option>';


                        });

                        $('#input_coagad').change(function () {
                            Cookies.set("adr_id", $("#input_coagad").selectpicker('val'));
                        });
                        $("#input_coagad").html(htm);
                        $("#input_coagad").selectpicker('refresh');
                        $("#input_coagad").selectpicker('val', idAddress);
                        $("#input_coagad").selectpicker('refresh');
                    }
                });
                return display;
            }
        });


    }


    $(document).on("hidden.bs.modal", ".bootbox.modal", function (e) {

        callback();
    });


    function callback() {
        if (status === 'true') {
            onOK();
            status = false;
        } else {
            onClose();
        }
    }

    function onClose() {
        console.log('dismissed');
    }

    function onOK() {
        console.log('ok');
    }


    function build_modal_new_message() {
        var sel_org_id = orgId;
        var sel_adr_id = $("#input_coagad").selectpicker('val');
        var bootbox_title = 'Новое сообщение</p>';
        var content_bootbox = "<div id='frm'>";
        content_bootbox += "<div class='bootstrap-iso'>";
        content_bootbox += "<div class='container-fluid'>";
        content_bootbox += "<div class='row'>";
        content_bootbox += "<div class='col-md-12 col-sm-12 col-xs-12'>";
        content_bootbox += "<form method='post'>";

        content_bootbox += "<div class='form-group '>";
        content_bootbox += "<label class='control-label requiredField' for='messageType'>";
        content_bootbox += "Тип задачи";
        content_bootbox += "<span class='asteriskField'>*</span>";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<select class='selectpicker' id='messageType' name='messageType'>" +
            "<option disabled selected='selected'>Выберите тему сообщения</option>" +
            "<option value='1'>Прайс лист от торгпреда</option>" +
            "<option value='2'>Прайс лист из офиса</option>" +
            "<option value='3'>По ассортименту</option>" +
            "<option value='4'>Сверка</option>" +
            "<option value='5'>Звонок без заявки</option>" +
            "<option value='6'>По долгам клиента</option>" +
            "<option value='7'>Заявка</option>" +
            "<option value='8'>Кредитная записка</option>" +
            "<option value='10'>Договор</option>" +
            "<option value='11'>Собрание</option>" +
            "<option value='9'>Прочее</option>" +
            "</select>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='coag_modal'>";
        content_bootbox += "Контрагент";
        content_bootbox += "<span class='asteriskField'>*</span>";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<input class='form-control' value='" + nameOrg + "' data-value='" + sel_org_id + "' id='coag_modal' name='coag_modal'/>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='coagad_modal'>";
        content_bootbox += "Адрес контрагента";
        content_bootbox += "<span class='asteriskField'>*</span>";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<select id='coagad_modal' type='text' name='coagad_modal' class='form-control' value=''></select>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label' for='subject'>";
        content_bootbox += "Тема сообщения:";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<input class='form-control' id='subject' name='subject' autocomplete='off' down='1' class='' autofocus />";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<label class='control-label requiredField' for='message'>";
        content_bootbox += "Текст сообщения:";
        content_bootbox += "<span class='asteriskField'>*</span>";
        content_bootbox += "</label>";
        content_bootbox += "</div>";
        content_bootbox += "<div class='form-group'>";
        content_bootbox += "<textarea class='form-control' name='message' rows='10' id='message'></textarea>";
        content_bootbox += "</div>";
        content_bootbox += "</form>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";
        content_bootbox += "</div>";


        bootbox.dialog({
            title: bootbox_title,
            message: content_bootbox,
            buttons: {
                success: {
                    label: "Сохранить",
                    className: "btn-success",
                    callback: function () {
                        sel_adr_id = $('select[name=coagad_modal]').selectpicker('val');
                        var messageType = $('select[name=messageType]').val();
                        var subj = $('input[name=subject').val();
                        var message = $('textarea[id=message]').val();
                        if (messageType.length > 0) {
                            save_org_note(sel_org_id, sel_adr_id,message, subj, messageType,'','','',true);
                            init_load();
                        }
                    }
                }
            },
            onEscape: false,
            backdrop: true
        }).on("shown.bs.modal", function () {
//            function getNameOrg(orgId) {
//                $.ajax({
//                    type: 'POST',
//                    dataType: 'json',
//                    url: 'extras/org_sel.php',
//                    data: {query: orgId},
//                    success: function (e) {
//                    },
//                    error: function () {
//                        console.log("error");
//                    }
//                });
//            }

//            function getAddress(orgId) {
//                $.ajax({
//                    type: 'POST',
//                    dataType: 'json',
//                    url: 'extras/addr_sel.php',
//                    data: {query: orgId},
//                    success: function (e) {
//                        // console.log(e);
//                        var htm; // = "<option value='0'>--</option>";
//                        $.each(e, function (index, addr) {
//                            htm += '<option value="' + addr.id + '">' + addr.adr + '</option>';
//                        });
//                        $("#coagad_modal").empty();
//                        $("#coagad_modal").html(htm);
//                        $("#coagad_modal").selectpicker('refresh');
//                    }
//                });
//            }

//            getNameOrg(sel_org_id);
//            getAddress(sel_org_id);

            $("#coag_modal").blur();
            $("#coag_modal").multisuggest({
                sources: [
                    {
                        data: "extras/org_sel.php",
                        queryParam: "query",
                        type: "url",
                        listFormatter: function (item, term) {
                            var compiled = _.template("<% print(hl(name)) %>");
                            return compiled({
                                hl: $.proxy($.fn.multisuggest.highlighter, this, term),
                                name: item.name,

                            });
                        },
                        inputFormatter: function (item) {
                            var compiled = _.template("<%= name %>");

                            return item.name;
                        },
                        valueAttribute: function (item) {
                            return item.id;
                        },
                        resultsProcessor: function (data) {
                            return data;
                        },
                        header: "Контрагенты",
                        maxEntries: 7
                    }
                ],
                loadingIconClass: "msuggest-loading",
                noResultsText: "Контрагент не найден.",
                enableDropdownMode: true,
                delay: 300,
                minLength: 2,
                maxLength: 10,
                updater: function (display, value) {
                    sel_org_id = value;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: 'extras/addr_sel.php',
                        data: {query: value},
                        success: function (e) {


                            var htm = '<option value="">' + "выберите адрес" + '</option>'; // = "<option value='0'>--</option>";

                            $.each(e, function (index, addr) {

                                htm += '<option value="' + addr.id + '" ' + (sel_adr_id > 0 && sel_adr_id == addr.id ? 'selected' : '') + '>' + addr.adr + '</option>';

                            });

                            $("#coagad_modal").empty();
                            $("#coagad_modal").html(htm);
                            $("#coagad_modal").selectpicker('refresh');
                        }
                    });
                    return display;
                }
            });
            $(".selectpicker", this).selectpicker('render');
        });
    }

    function build_modal_new_sms() {
        var bootbox_title = 'Новое SMS сообщение</p>';
        var content_bootbox_sms = "<div id='sms'>";
        content_bootbox_sms += "<div class='bootstrap-iso'>";
        content_bootbox_sms += "<div class='container-fluid'>";
        content_bootbox_sms += "<div class='row'>";
        content_bootbox_sms += "<div class='col-md-12 col-sm-12 col-xs-12'>";
        content_bootbox_sms += "<form method='post'>";



        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<label class='control-label' for='sms-modal-org'>";
        content_bootbox_sms += "Организация:";
        content_bootbox_sms += "</label>";
        content_bootbox_sms += "</div>";

        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<input class='form-control' value='' data-value='' id='sms-modal-org' name='sms-modal-org' />";
        content_bootbox_sms += "</div>";

        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<label class='control-label ' for='sms-modal-emp'>";
        content_bootbox_sms += "Сотрудник:";
        content_bootbox_sms += "</label>";
        content_bootbox_sms += "</div>";

        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<input class='form-control' value='' data-value='' id='sms-modal-emp' name='sms-modal-emp' />";
        content_bootbox_sms += "</div>";


        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<label class='control-label' for='sms-modal-msg'>";
        content_bootbox_sms += "Текст сообщения:";

        content_bootbox_sms += "</label>";
        content_bootbox_sms += "</div>";

        content_bootbox_sms += "<div class='form-group'>";
        content_bootbox_sms += "<textarea class='form-control' name='sms-modal-msg' rows='10' id='sms-modal-msg'></textarea>";
        content_bootbox_sms += "</div>";
        content_bootbox_sms += "</form>";
        content_bootbox_sms += "</div>";
        content_bootbox_sms += "</div>";
        content_bootbox_sms += "</div>";
        content_bootbox_sms += "</div>";
        content_bootbox_sms += "</div>";


        bootbox.dialog({
            title: bootbox_title,
            message: content_bootbox_sms,
            buttons: {
                success: {
                    label: "Отправить",
                    className: "btn-success"
                    }
                },

            onEscape: false,
            backdrop: true
        }).on("shown.bs.modal", function () {

            $("#sms-modal-org").blur();
            $("#sms-modal-org").multisuggest({
                sources: [
                    {
                        data: "extras/org_sel.php",
                        queryParam: "query",
                        type: "url",
                        listFormatter: function (item, term) {
                            var compiled = _.template("<% print(hl(name)) %>");
                            return compiled({
                                hl: $.proxy($.fn.multisuggest.highlighter, this, term),
                                name: item.name,

                            });
                        },
                        inputFormatter: function (item) {
                            var compiled = _.template("<%= name %>");

                            return item.name;
                        },
                        valueAttribute: function (item) {
                            return item.id;
                        },
                        resultsProcessor: function (data) {
                            return data;
                        },
                        header: "Контрагенты",
                        maxEntries: 7
                    }
                ],
                loadingIconClass: "msuggest-loading",
                noResultsText: "Контрагент не найден.",
                enableDropdownMode: true,
                delay: 300,
                minLength: 2,
                maxLength: 10,
                updater: function (display, value) {

                    return display;
                }
            });



            })
    }

    function initload_sms()
    {
        var date0_sms = $('input[name=dt0_sms]').val();
        var date1_sms = $('input[name=dt1_sms]').val();
        Cookies.set('worknotes_tabs_dt_beg_sms', date1_sms);
        Cookies.set('worknotes_tabs_dt_end_sms', date0_sms);
        var jdata_sms = null;



        $.ajax({
            type: 'POST',
            url: "pages/worknotes_sms_load.php",
            dataType: 'json',
            cache: false,
            data: {
                dt0_sms: date1_sms,
                dt1_sms: date0_sms
            },
            success: function (data_emp) {
                if (data_emp !== null && typeof data_emp === 'object') {
                    jdata_emp = data_emp['LIST_EMP_NOTES']['MESSAGES'];
                    table_emp = init_datatable_emp(table_emp, jdata_emp, jdata_emp_recv, 'table_emp_notes');

                }
            },
            error: function (xhr, textStatus, hz) {
                // console.log(textStatus);
            },
            complete: function () {
                $('#').show();


            }

        });
    }







</script>