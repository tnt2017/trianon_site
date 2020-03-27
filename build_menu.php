<?php
class IDX_CAT extends Enum {
	const TOP = 0;
	const PRICE_LIST = 1;
	const SALE_REPORTS = 2;
	const DOCS = 3;
	const CLIENTS = 4;
	const TOVAR = 5;
	const DOSTAVKA = 6;
	const FILE_STORAGE = 7;
	const BUH = 8;
	const MAP = 9;
	const TRACK = 10;
}
;
class IDX_SUBCAT extends Enum {
	const NONE = 0;
}
;
class IDX_SUBCAT_DOSTAVKA extends Enum {
	const TRANSPORT = 0;
	const SKLAD = 1;
}
;
class IDX_SUBCAT_FILE_STORAGE extends Enum {
	const MAIN = 0;
	const BUH = 1;
}
;
function init_menu($db, &$cat, &$subcat, &$menu, &$rights) {
	$cat [IDX_CAT::TOP] = 'Быстрый доступ';
	$cat [IDX_CAT::PRICE_LIST] = 'Главная';
	$cat [IDX_CAT::PRICE_LIST] = 'Прайс-лист1';
	$cat [IDX_CAT::SALE_REPORTS] = 'Продажи';
	$cat [IDX_CAT::DOCS] = 'Документы';
	$cat [IDX_CAT::CLIENTS] = 'Клиенты';
	$cat [IDX_CAT::TOVAR] = 'Товары';
	$cat [IDX_CAT::DOSTAVKA] = 'Доставка';
	$cat [IDX_CAT::FILE_STORAGE] = 'Файловое-хранилище';
	$cat [IDX_CAT::BUH] = 'Бухгалтерия';
	$cat [IDX_CAT::MAP] = 'Карта';
	$cat [IDX_CAT::TRACK] = 'Трэкер-маячок';
	
	$subcat [IDX_CAT::TOP] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] = 'Транспорт';
	$subcat [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] = 'Склад';
	$subcat [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] = 'Основные';
	$subcat [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] = 'Бухгалтерские';
	$subcat [IDX_CAT::BUH] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::MAP] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] = '';
	
	 $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [0] = 'Новости компании';
	 $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [1] = 'firm_news.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [0] = 'Новости Вид 2';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [1] = 'firm_news2.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [0] = 'Приказы, распоряжения по компании';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [1] = 'firm_dirc.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [0] = 'Работа с контрагентами';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [1] = 'worknotes_org.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [0] = 'Список сотрудников';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [1] = 'email_list.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [0] = 'Болталка компании';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [1] = 'worknotes_tabs.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [0] = 'Обработка заявок от Контура';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [1] = 'kontur.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [0] = 'Обработка счетов, АСИНА';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [1] = 'asina.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [0] = 'Рабочий стол агента';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [1] = 'agent_report.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [0] = 'Выставление балов';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [1] = 'set_mark.php';


	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [0] [0] = 'Экспресс прайс-лист';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [0] [1] = 'prle.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [1] [0] = 'Прайс-лист для конкретного клиента в формате Excel';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [1] [1] = 'prlk.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [2] [0] = 'Максимальные доп.скидки клиента по группам';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [2] [1] = 'org_maxdis.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [3] [0] = 'Поиск клиента по адресу';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [3] [1] = 'addr_act.php';
	
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [0] [0] = 'Недопоставки клиенту';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [0] [1] = 'org_short.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [1] [0] = 'Рекламации по клиенту';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [1] [1] = 'reclame.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [2] [0] = 'Динамика продаж по направлениям/торгпредам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [2] [1] = 'sale_dir0.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [3] [0] = 'Динамика продаж по выбранной группе';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [3] [1] = 'sale_grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [4] [0] = 'Месячная Динамика агентов';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [4] [1] = 'dyn_ag.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [5] [0] = 'Месячные планы торгпредов - старый';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [5] [1] = 'ag_plan.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [6] [0] = 'Месячные планы торгпредов (по клиентам)';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [6] [1] = 'ag_plans.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [7] [0] = 'Месячные планы торгпредов (по клиентам) 2';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [7] [1] = 'ag_plans2.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [8] [0] = 'Месячные планы торгпредов (новые клиенты) 2';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [8] [1] = 'ag_plans2_n.php';
	$menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [9] [0] = 'Календарь-планировщик';
	$menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [9] [1] = 'ag_plans_calendar.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [10] [0] = 'Визит - планер';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [10] [1] = 'ag_visitp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [11] [0] = 'Продажи по спецотделу Юнилевер/Калина';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [11] [1] = 'lev_specdiv.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [12] [0] = 'Сравнить Продажи по 6 группам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [12] [1] = 'sale_cmp6grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [13] [0] = 'Фиксированные цены клиента - список';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [13] [1] = 'org_fixp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [14] [0] = 'Фиксированные цены клиента - продажи';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [14] [1] = 'org_fixp_sold.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [15] [0] = 'Продажи клиенту потоварно';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [15] [1] = 'sale_org.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [16] [0] = 'Сверка книг продаж';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [16] [1] = 'sales_book_check.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [17] [0] = 'Все Месячные планы торгпредов (по клиентам)';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [17] [1] = 'ag_plans_all.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [18] [0] = 'Работа торгового представителя';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [18] [1] = 'slr_tp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [19] [0] = 'Работа торгового представителя 2014г';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [19] [1] = 'slr_tp2.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [20] [0] = 'Коэффициенты по группам для торгпредов';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [20] [1] = 'ag_coef.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [21] [0] = 'Продажи в группе : брэнды';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [21] [1] = 'lev_brands.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [22] [0] = 'Продажи в группе : потоварно';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [22] [1] = 'lev_tovs.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [23] [0] = 'Продажи сетевым клиентам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [23] [1] = 'sale_net.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [24] [0] = 'Продажи по брэнду,id';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [24] [1] = 'sale_pat.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [25] [0] = 'Сравнить Продажи по 2 группам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [25] [1] = 'sale_cmp2grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [26] [0] = 'Торгпреды - динамика по группам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [26] [1] = 'sale_agd.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [27] [0] = 'Продажи ниже СМЦ';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [27] [1] = 'sale_smp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [28] [0] = 'Наценки по клиенту в 2х периодах';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [28] [1] = 'org_nac_2p.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [29] [0] = 'Динамика по всем группам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [29] [1] = 'sale_gd.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [30] [0] = 'Продажи по отчетам клиентов';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [30] [1] = 'tov_rep.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [31] [0] = 'Бонусы - баланс по группам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [31] [1] = 'bongrp_bal.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [32] [0] = 'Бонусы - баланс по клиентам';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [32] [1] = 'bonorg_bal.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [33] [0] = 'Продажи по торгпреду в смежных периодах';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [33] [1] = 'org_coef.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [34] [0] = 'Продажи по адресу';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [34] [1] = 'sale_adr.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [35] [0] = 'Продажи тов.позиции по адресу';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [35] [1] = 'sale_it.php';
	
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [0] [0] = 'Проверка создания бонуса по акциям';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [0] [1] = 'bonus_emu.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [1] [0] = 'Различия суммы: оригинал - склад';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [1] [1] = 'ori_diff.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [2] [0] = 'Различия по ответственному: оригинал - склад';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [2] [1] = 'ori_difemps.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [3] [0] = 'Подготовка документов по заявкам для импорта';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [3] [1] = 'gen_order.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [4] [0] = 'Акты после оригинала';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [4] [1] = 'act_ori.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [5] [0] = 'Список документов';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [5] [1] = 'list_docs.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [6] [0] = 'План оплат';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [6] [1] = 'plan_plat.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [7] [0] = 'Документы на сотрудника';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [7] [1] = 'emp_doc_up.php';
	
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [0] = 'Кредитная история клиента';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [1] = 'cred_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [0] = 'Кредитные истории по торгпреду';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [1] = 'ag_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [0] = 'Товарные группы клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [1] = 'clnt_tovgrps.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [0] = 'Скидки клиентов по группам';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [1] = 'edit_discl.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [0] = 'Простая кредит-история клиента';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [1] = 'cr_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [0] = 'Пропущенные атрибуты клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [1] = 'org_misattr.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [6] [0] = 'Плохие долги клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [6] [1] = 'debts_big.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [7] [0] = 'Просроченные договора клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [7] [1] = 'org_agr_ovd.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [8] [0] = 'Сверки с клиентами';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [8] [1] = 'sverka.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [9] [0] = 'Клиенты без сверки';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [9] [1] = 'sverka_no.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [10] [0] = 'Долги клиентов торгпреда на дату';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [10] [1] = 'ag_debts.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [11] [0] = 'Расчет Кредитных лимитов клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [11] [1] = 'org_lim.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [12] [0] = 'Статистика получения заявок от клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [12] [1] = 'org_ordtyps.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [13] [0] = 'Изменения свойств/атрибутов контрагента';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [13] [1] = 'au_org.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [14] [0] = 'Внесение координат торг.точек';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [14] [1] = 'org_adcrd.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [15] [0] = 'Список проектов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [15] [1] = 'list_projects.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [16] [0] = 'Оплата клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [16] [1] = 'pay_cache.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [17] [0] = 'Экспедиторы';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [17] [1] = 'pay_cache_exped.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [18] [0] = 'Журнал возвратов клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [18] [1] = 'jrn_ret_cli.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [19] [0] = 'Список номеров сотовых телефонов клиентов';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [19] [1] = 'tel_list.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [20] [0] = 'Контрагенты : закрепленные сотрудники';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [20] [1] = 'org_hand_lst.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [21] [0] = 'Редактирование запретов на группы';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [21] [1] = 'org_ban.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [22] [0] = 'Корректировка СМЦ';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [22] [1] = 'org_smp_coef.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [23] [0] = 'Дополнительные клиенты для торгпреда';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [23] [1] = 'ag_ex_orgs.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [24] [0] = 'Клиенты выделенного отдела';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [24] [1] = 'div_ex_orgs.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [25] [0] = 'Контрагенты и их ответственные';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [25] [1] = 'org_hand.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [26] [0] = 'Список адресов по поставщику';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [26] [1] = 'ven_adrs_ls.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [27] [0] = 'Список адресов по поставщику (пагинация)';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [27] [1] = 'ven_adrs_ls2.php';

	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [0] = 'Работа по клиентам';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [1] = 'emp_work.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [0] = 'Назначение обзвона для оператора';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [1] = 'oper_tasks_tt.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [0] = 'Задачи операторов';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [1] = 'oper_tasks.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [0] = 'Просроченная задолженность по клиентам';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [1] = 'month_report.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [0] = 'Просроченная задолженность по поставщикам';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [1] = 'month_report.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [0] = 'Карточка клиента';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [1] = 'org_work.php';
	

	$menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [0] = 'Загрузка прайсов контрагентов';
	$menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [1] = 'topol.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [0] = 'Поиск товара по штрихкоду';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [1] = 'tov_by_bc.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [1] [0] = 'Товары с совпадающим штрихкодом';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [1] [1] = 'tov_mbars.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [2] [0] = 'Товары с отсутствующими данными';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [2] [1] = 'tov_bugs.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [3] [0] = 'Товар в Фиктивных секциях';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [3] [1] = 'tov_fic_sec.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [4] [0] = 'Описание и др.свойства товара';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [4] [1] = 'tov_des.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [5] [0] = 'Название товара для интернет-ресурсов';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [5] [1] = 'tov_names.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [6] [0] = 'Акции для отдельных клиентов';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [6] [1] = 'tpromo.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [7] [0] = 'Неликвиды на сегодня';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [7] [1] = 'tov_nliq.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [8] [0] = 'Иерархии товаров';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [8] [1] = 'tov_hier.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [9] [0] = 'Скрытые товары с остатком 0';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [9] [1] = 'tov_hid.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [10] [0] = 'Ограничения кол-ва товара в заявках';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [10] [1] = 'tov_limiter.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [11] [0] = 'Привязка сертификатов к товару';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [11] [1] = 'tov_certs.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [12] [0] = 'Работа с базовым прайс-листом (цены пост,СМЦ,база)';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [12] [1] = 'edit_bp.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [13] [0] = 'Редактирование веса, размера товаров';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [13] [1] = 'tov_ves.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [14] [0] = 'Атрибуты товаров для магазинов';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [14] [1] = 'mtov_attr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [15] [0] = 'Снятие задолженности экспедиторов';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [15] [1] = 'exp_revert.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [16] [0] = 'Качество работы по группам (заказ-приход-продажи)';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [16] [1] = 'grp_qual.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [17] [0] = 'Атрибуты товарных групп';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [17] [1] = 'grp_attr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [18] [0] = 'Оборот в магазине';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [18] [1] = 'mag_tov.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [19] [0] = 'Остатки по группе на дату';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [19] [1] = 'tov_ost_gr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [20] [0] = 'Управление акциями';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [20] [1] = 'bonus.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [21] [0] = 'Управление скрытыми позициями';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [21] [1] = 'tov_hid_mng.php';
	
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [0] [0] = 'Реквизиты доставки по адресу id';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [0] [1] = 'addr_id.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [1] [0] = 'Рейсы доставки';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [1] [1] = 'trips.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [2] [0] = 'Доставка по торгпреду';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [2] [1] = 'ag_deliv.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [3] [0] = 'Рейсы за период';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [3] [1] = 'trip_list.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [4] [0] = 'Объемы контрагентов + транспортные';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [4] [1] = 'trans_ex.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [5] [0] = 'Динамика продаж по направлениям';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [5] [1] = 'sale_dir.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [0] [0] = 'Сроки годности по группе';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [0] [1] = 'tov_vtill.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [1] [0] = 'Материалы на хоз. нужды';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [1] [1] = 'hoz_needs.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [2] [0] = 'Время отборок';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [2] [1] = 'sk_pick.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [3] [0] = 'Качество работы по отборкам - 2010';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [3] [1] = 'sk_pickq10.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [4] [0] = 'Качество работы по отборкам';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [4] [1] = 'sk_pickq.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [5] [0] = 'Проверка отборок';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [5] [1] = 'sk_chkq.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [6] [0] = 'Контроль вычерков отборщиков';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [6] [1] = 'sk_pk_out.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [7] [0] = 'Сроки годности товара в диапазоне дат';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [7] [1] = 'tv_term.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [8] [0] = 'Результаты инвентаризации';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [8] [1] = 'inv_res.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [9] [0] = 'Унифицированная И н в е н т а р и з а ц и я';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [9] [1] = 'inv.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [10] [0] = 'Проверка инвентаризации';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [10] [1] = 'inv_vrfy.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [11] [0] = 'Сотрудники в инвентаризации 2';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [11] [1] = 'inv2_res.php';
	
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [0] [0] = 'Повышения, акции';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [0] [1] = 'prd_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [1] [0] = 'Презентации';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [1] [1] = 'present_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [2] [0] = 'Мониторинг цен';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [2] [1] = 'competitor_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [3] [0] = 'Поставщики';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [3] [1] = 'provider.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [4] [0] = 'Документы контрагентов';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [4] [1] = 'org_doc_ls.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [5] [0] = 'Инструкции Маячок/Картун/Сайт и тд';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [5] [1] = 'cloud_instruct.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [6] [0] = 'Официальные письма исходящие';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [6] [1] = 'cloud_official_let.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [7] [0] = 'Официальные письма входящие';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [7] [1] = 'cloud_official_let_inb.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [8] [0] = 'Претензии исходящие';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [8] [1] = 'cloud_claim.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [9] [0] = 'Претензии входящие';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [9] [1] = 'cloud_claim_inb.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [10] [0] = 'Исковые заявления';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [10] [1] = 'cloud_law.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [11] [0] = 'Приказы организации';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [11] [1] = 'cloud_compa_injun.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [12] [0] = 'Доверенности';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [12] [1] = 'cloud_authority.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [0] [0] = 'Шаблоны договоров';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [0] [1] = 'buch_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [1] [0] = 'Авансовые Отчеты';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [1] [1] = 'cloud_buch_prepay.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [2] [0] = 'Z-Отчет';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [2] [1] = 'cloud_buch_z.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [3] [0] = 'Книга продаж';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [3] [1] = 'cloud_book_sale.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [4] [0] = 'Книга покупок';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [4] [1] = 'cloud_book_purch.php';
	
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [0] = 'Подотчет';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [1] = 'exch.php';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [0] = 'График отпусков';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [1] = 'vac.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [0] = 'Сканирование документов';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [1] = 'document_barcode_upload.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [0] = 'Учет рабочего времени';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [1] = 'wrk_tab.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [2] [0] = 'План оплат';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [2] [1] = 'plan_plat.php';
	
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [0] [0] = 'Исправление координат клиента';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [0] [1] = 'addr_chk_coord.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [1] [0] = 'Карта клиентов';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [1] [1] = 'list_addrs.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [2] [0] = 'Адреса без координаты';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [2] [1] = 'addr_to_coord.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [3] [0] = 'Карта, презентация';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [3] [1] = 'present_addrs.php';
	
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [0] [0] = 'Список мобильных устройств';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [0] [1] = 'mobile_dev_chk.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [1] [0] = 'Трианон-Трэк Отчет';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [1] [1] = 'gps_report.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [2] [0] = 'Трианон-Трэк Маячок';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [2] [1] = 'gps_draw.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [3] [0] = 'Без карты';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [3] [1] = 'gps_draw_lm.php';
	
	/*
	 * $menu[IDX_CAT::DOCS][IDX_SUBCAT::NONE][][0] = '';
	 * $menu[IDX_CAT::DOCS][IDX_SUBCAT::NONE][][1] = '.php';
	 */
	
	try {
		$db->parse ( "begin SSEC.AUTH_PATH.GET_CUR_USER_RIGHTS(:cur,:cnt); end;" );
		$db->bind ( ":cur", $cur, OCI_B_CURSOR );
		$db->bind ( ":cnt", $cnt, SQLT_INT );
		$db->execute ();
		$db->execute_cursor ( $cur );
		
		while ( $row = $db->fetch_cursor ( $cur ) ) {
			$rights [$row ['CUSER_RIGHT']] = $row ['SECURE'];
		}
	} catch ( Exception $e ) {
		echo "ERROR:" . $e->getMessage ();
		exit ();
	}
}
function build_menu($cat, $subcat, $menu, $rights, $cur_cat, $cur_subcat, $curURL, $db) {
	$i = IDX_CAT::TOP;
	$j = IDX_SUBCAT::NONE;
	
	foreach ( $cat as $key => $value ) {
		// echo $cat[$i].'<br>';
		if ($i > 0) {
			echo "
			<li class='sidebar-main'>
			<a data-toggle='collapse' data-parent='#collapse-group' href='#" . $cat [$i] . "'>" . $cat [$i] . "</a>
			</li>
			";
			$j = 0;
			foreach ( $subcat [$i] as $key_s => $value_s ) {
				if ($subcat [$i] [$j] != '') {
					if ($j == 0) {
						if ($cat [$i] == $cur_cat) {
							$isin = 'in';
						} else {
							$isin = '';
						}
						echo "<div id='" . $cat [$i] . "' class='panel-collapse collapse $isin'>";
					}
					// echo '...'.$subcat[$i][$j].'<br>';
					echo "
					<li class='sidebar-sub'>
					<a data-toggle='collapse' data-parent='#collapse-group' href='#" . $subcat [$i] [$j] . "'>" . $subcat [$i] [$j] . "</a>
					</li>
					";
				}
				$k = 0;
				if (isset ( $menu [$i] [$j] )) {
					foreach ( $menu [$i] [$j] as $key_m => $value_m ) {
						if ($k == 0) {
							if ($subcat [$i] [$j] != '') {
								if ($subcat [$i] [$j] == $cur_subcat) {
									$isin = 'in';
								} else {
									$isin = '';
								}
								echo "<div id='" . $subcat [$i] [$j] . "' class='panel-collapse collapse $isin'>";
							} else {
								if ($cat [$i] == $cur_cat) {
									$isin = 'in';
								} else {
									$isin = '';
								}
								echo "<div id='" . $cat [$i] . "' class='panel-collapse collapse $isin'>";
							}
						}
						// echo '......'.$menu[$i][$j][$k][0].'<br>';
						
						if (check_page_rights ( $menu [$i] [$j] [$key_m] [1], $rights, $db )) {
							$cur_menu_cat = $cat [$i];
							$cur_menu_subcat = $subcat [$i] [$j];
							$cur_menu_url = $menu [$i] [$j] [$key_m] [1];
							$cur_menu_name = $menu [$i] [$j] [$key_m] [0];
							if ($cur_menu_subcat == '') {
								$cur_menu_subcat = $cur_menu_cat;
							}
							if ($cur_menu_url == $curURL) {
								$lighter = 'menu-lighter';
							} else {
								$lighter = '';
							}
							echo "
						<li class='sidebar-item $lighter'>
						<a onclick=\"callPage('" . $cur_menu_url . "','" . $cur_menu_name . "','" . $cur_menu_cat . "','" . $cur_menu_subcat . "')\">" . $menu [$i] [$j] [$key_m] [0] . "</a>
						</li>
						";
						}
						$k ++;
					}
				}
				if ($k > 0) {
					echo "</div>";
				}
				$j ++;
			}
			if ($j > 1) {
				echo "</div>";
			}
		}
		$i ++;
	}
}
function build_fast_menu($cat, $subcat, $menu, $rights, $db) {
	$i = IDX_CAT::TOP;
	$j = IDX_SUBCAT::NONE;
	
	foreach ( $menu [$i] [$j] as $key => $value ) {
		if (check_page_rights ( $menu [$i] [$j] [$key] [1], $rights, $db )) {
			$cur_cat = $cat [$i];
			$cur_subcat = $subcat [$i] [$j];
			$cur_menu_url = $menu [$i] [$j] [$key] [1];
			$cur_menu_name = $menu [$i] [$j] [$key] [0];
			if ($cur_menu_subcat == '') {
				$cur_menu_subcat = $cur_menu_cat;
			}
			echo "<li>";
			echo "<a class = 'dropdown-item' onclick=\"callPage('" . $cur_menu_url . "','" . $cur_menu_name . "','" . $cur_menu_cat . "','" . $cur_menu_subcat . "')\">" . $menu [$i] [$j] [$key] [0] . "</a>";
			echo "</li>";
		}
	}
}

?>
