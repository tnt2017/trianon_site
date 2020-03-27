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
	$cat [IDX_CAT::TOP] = '������� ������';
	$cat [IDX_CAT::PRICE_LIST] = '�������';
	$cat [IDX_CAT::PRICE_LIST] = '�����-����1';
	$cat [IDX_CAT::SALE_REPORTS] = '�������';
	$cat [IDX_CAT::DOCS] = '���������';
	$cat [IDX_CAT::CLIENTS] = '�������';
	$cat [IDX_CAT::TOVAR] = '������';
	$cat [IDX_CAT::DOSTAVKA] = '��������';
	$cat [IDX_CAT::FILE_STORAGE] = '��������-���������';
	$cat [IDX_CAT::BUH] = '�����������';
	$cat [IDX_CAT::MAP] = '�����';
	$cat [IDX_CAT::TRACK] = '������-������';
	
	$subcat [IDX_CAT::TOP] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] = '���������';
	$subcat [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] = '�����';
	$subcat [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] = '��������';
	$subcat [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] = '�������������';
	$subcat [IDX_CAT::BUH] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::MAP] [IDX_SUBCAT::NONE] = '';
	$subcat [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] = '';
	
	 $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [0] = '������� ��������';
	 $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [1] = 'firm_news.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [0] = '������� ��� 2';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [1] = 'firm_news2.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [0] = '�������, ������������ �� ��������';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [1] = 'firm_dirc.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [0] = '������ � �������������';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [1] = 'worknotes_org.php';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [0] = '������ �����������';
	// $menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [1] = 'email_list.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [0] = '�������� ��������';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [0] [1] = 'worknotes_tabs.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [0] = '��������� ������ �� �������';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [1] [1] = 'kontur.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [0] = '��������� ������, �����';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [2] [1] = 'asina.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [0] = '������� ���� ������';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [3] [1] = 'agent_report.php';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [0] = '����������� �����';
	$menu [IDX_CAT::TOP] [IDX_SUBCAT::NONE] [4] [1] = 'set_mark.php';


	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [0] [0] = '�������� �����-����';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [0] [1] = 'prle.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [1] [0] = '�����-���� ��� ����������� ������� � ������� Excel';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [1] [1] = 'prlk.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [2] [0] = '������������ ���.������ ������� �� �������';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [2] [1] = 'org_maxdis.php';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [3] [0] = '����� ������� �� ������';
	// $menu [IDX_CAT::PRICE_LIST] [IDX_SUBCAT::NONE] [3] [1] = 'addr_act.php';
	
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [0] [0] = '������������ �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [0] [1] = 'org_short.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [1] [0] = '���������� �� �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [1] [1] = 'reclame.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [2] [0] = '�������� ������ �� ������������/����������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [2] [1] = 'sale_dir0.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [3] [0] = '�������� ������ �� ��������� ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [3] [1] = 'sale_grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [4] [0] = '�������� �������� �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [4] [1] = 'dyn_ag.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [5] [0] = '�������� ����� ���������� - ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [5] [1] = 'ag_plan.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [6] [0] = '�������� ����� ���������� (�� ��������)';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [6] [1] = 'ag_plans.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [7] [0] = '�������� ����� ���������� (�� ��������) 2';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [7] [1] = 'ag_plans2.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [8] [0] = '�������� ����� ���������� (����� �������) 2';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [8] [1] = 'ag_plans2_n.php';
	$menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [9] [0] = '���������-�����������';
	$menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [9] [1] = 'ag_plans_calendar.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [10] [0] = '����� - ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [10] [1] = 'ag_visitp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [11] [0] = '������� �� ���������� ��������/������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [11] [1] = 'lev_specdiv.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [12] [0] = '�������� ������� �� 6 �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [12] [1] = 'sale_cmp6grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [13] [0] = '������������� ���� ������� - ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [13] [1] = 'org_fixp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [14] [0] = '������������� ���� ������� - �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [14] [1] = 'org_fixp_sold.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [15] [0] = '������� ������� ���������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [15] [1] = 'sale_org.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [16] [0] = '������ ���� ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [16] [1] = 'sales_book_check.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [17] [0] = '��� �������� ����� ���������� (�� ��������)';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [17] [1] = 'ag_plans_all.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [18] [0] = '������ ��������� �������������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [18] [1] = 'slr_tp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [19] [0] = '������ ��������� ������������� 2014�';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [19] [1] = 'slr_tp2.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [20] [0] = '������������ �� ������� ��� ����������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [20] [1] = 'ag_coef.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [21] [0] = '������� � ������ : ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [21] [1] = 'lev_brands.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [22] [0] = '������� � ������ : ���������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [22] [1] = 'lev_tovs.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [23] [0] = '������� ������� ��������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [23] [1] = 'sale_net.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [24] [0] = '������� �� ������,id';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [24] [1] = 'sale_pat.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [25] [0] = '�������� ������� �� 2 �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [25] [1] = 'sale_cmp2grp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [26] [0] = '��������� - �������� �� �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [26] [1] = 'sale_agd.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [27] [0] = '������� ���� ���';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [27] [1] = 'sale_smp.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [28] [0] = '������� �� ������� � 2� ��������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [28] [1] = 'org_nac_2p.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [29] [0] = '�������� �� ���� �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [29] [1] = 'sale_gd.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [30] [0] = '������� �� ������� ��������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [30] [1] = 'tov_rep.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [31] [0] = '������ - ������ �� �������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [31] [1] = 'bongrp_bal.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [32] [0] = '������ - ������ �� ��������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [32] [1] = 'bonorg_bal.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [33] [0] = '������� �� ��������� � ������� ��������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [33] [1] = 'org_coef.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [34] [0] = '������� �� ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [34] [1] = 'sale_adr.php';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [35] [0] = '������� ���.������� �� ������';
	// $menu [IDX_CAT::SALE_REPORTS] [IDX_SUBCAT::NONE] [35] [1] = 'sale_it.php';
	
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [0] [0] = '�������� �������� ������ �� ������';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [0] [1] = 'bonus_emu.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [1] [0] = '�������� �����: �������� - �����';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [1] [1] = 'ori_diff.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [2] [0] = '�������� �� ��������������: �������� - �����';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [2] [1] = 'ori_difemps.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [3] [0] = '���������� ���������� �� ������� ��� �������';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [3] [1] = 'gen_order.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [4] [0] = '���� ����� ���������';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [4] [1] = 'act_ori.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [5] [0] = '������ ����������';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [5] [1] = 'list_docs.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [6] [0] = '���� �����';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [6] [1] = 'plan_plat.php';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [7] [0] = '��������� �� ����������';
	// $menu [IDX_CAT::DOCS] [IDX_SUBCAT::NONE] [7] [1] = 'emp_doc_up.php';
	
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [0] = '��������� ������� �������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [1] = 'cred_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [0] = '��������� ������� �� ���������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [1] = 'ag_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [0] = '�������� ������ ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [1] = 'clnt_tovgrps.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [0] = '������ �������� �� �������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [1] = 'edit_discl.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [0] = '������� ������-������� �������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [1] = 'cr_hist.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [0] = '����������� �������� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [1] = 'org_misattr.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [6] [0] = '������ ����� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [6] [1] = 'debts_big.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [7] [0] = '������������ �������� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [7] [1] = 'org_agr_ovd.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [8] [0] = '������ � ���������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [8] [1] = 'sverka.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [9] [0] = '������� ��� ������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [9] [1] = 'sverka_no.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [10] [0] = '����� �������� ��������� �� ����';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [10] [1] = 'ag_debts.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [11] [0] = '������ ��������� ������� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [11] [1] = 'org_lim.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [12] [0] = '���������� ��������� ������ �� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [12] [1] = 'org_ordtyps.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [13] [0] = '��������� �������/��������� �����������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [13] [1] = 'au_org.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [14] [0] = '�������� ��������� ����.�����';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [14] [1] = 'org_adcrd.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [15] [0] = '������ ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [15] [1] = 'list_projects.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [16] [0] = '������ ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [16] [1] = 'pay_cache.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [17] [0] = '�����������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [17] [1] = 'pay_cache_exped.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [18] [0] = '������ ��������� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [18] [1] = 'jrn_ret_cli.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [19] [0] = '������ ������� ������� ��������� ��������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [19] [1] = 'tel_list.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [20] [0] = '����������� : ������������ ����������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [20] [1] = 'org_hand_lst.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [21] [0] = '�������������� �������� �� ������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [21] [1] = 'org_ban.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [22] [0] = '������������� ���';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [22] [1] = 'org_smp_coef.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [23] [0] = '�������������� ������� ��� ���������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [23] [1] = 'ag_ex_orgs.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [24] [0] = '������� ����������� ������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [24] [1] = 'div_ex_orgs.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [25] [0] = '����������� � �� �������������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [25] [1] = 'org_hand.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [26] [0] = '������ ������� �� ����������';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [26] [1] = 'ven_adrs_ls.php';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [27] [0] = '������ ������� �� ���������� (���������)';
	// $menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [27] [1] = 'ven_adrs_ls2.php';

	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [0] = '������ �� ��������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [0] [1] = 'emp_work.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [0] = '���������� ������� ��� ���������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [1] [1] = 'oper_tasks_tt.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [0] = '������ ����������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [2] [1] = 'oper_tasks.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [0] = '������������ ������������� �� ��������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [3] [1] = 'month_report.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [0] = '������������ ������������� �� �����������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [4] [1] = 'month_report.php';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [0] = '�������� �������';
	$menu [IDX_CAT::CLIENTS] [IDX_SUBCAT::NONE] [5] [1] = 'org_work.php';
	

	$menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [0] = '�������� ������� ������������';
	$menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [1] = 'topol.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [0] = '����� ������ �� ���������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [0] [1] = 'tov_by_bc.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [1] [0] = '������ � ����������� ����������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [1] [1] = 'tov_mbars.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [2] [0] = '������ � �������������� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [2] [1] = 'tov_bugs.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [3] [0] = '����� � ��������� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [3] [1] = 'tov_fic_sec.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [4] [0] = '�������� � ��.�������� ������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [4] [1] = 'tov_des.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [5] [0] = '�������� ������ ��� ��������-��������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [5] [1] = 'tov_names.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [6] [0] = '����� ��� ��������� ��������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [6] [1] = 'tpromo.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [7] [0] = '��������� �� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [7] [1] = 'tov_nliq.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [8] [0] = '�������� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [8] [1] = 'tov_hier.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [9] [0] = '������� ������ � �������� 0';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [9] [1] = 'tov_hid.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [10] [0] = '����������� ���-�� ������ � �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [10] [1] = 'tov_limiter.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [11] [0] = '�������� ������������ � ������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [11] [1] = 'tov_certs.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [12] [0] = '������ � ������� �����-������ (���� ����,���,����)';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [12] [1] = 'edit_bp.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [13] [0] = '�������������� ����, ������� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [13] [1] = 'tov_ves.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [14] [0] = '�������� ������� ��� ���������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [14] [1] = 'mtov_attr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [15] [0] = '������ ������������� ������������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [15] [1] = 'exp_revert.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [16] [0] = '�������� ������ �� ������� (�����-������-�������)';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [16] [1] = 'grp_qual.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [17] [0] = '�������� �������� �����';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [17] [1] = 'grp_attr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [18] [0] = '������ � ��������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [18] [1] = 'mag_tov.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [19] [0] = '������� �� ������ �� ����';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [19] [1] = 'tov_ost_gr.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [20] [0] = '���������� �������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [20] [1] = 'bonus.php';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [21] [0] = '���������� �������� ���������';
	// $menu [IDX_CAT::TOVAR] [IDX_SUBCAT::NONE] [21] [1] = 'tov_hid_mng.php';
	
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [0] [0] = '��������� �������� �� ������ id';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [0] [1] = 'addr_id.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [1] [0] = '����� ��������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [1] [1] = 'trips.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [2] [0] = '�������� �� ���������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [2] [1] = 'ag_deliv.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [3] [0] = '����� �� ������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [3] [1] = 'trip_list.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [4] [0] = '������ ������������ + ������������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [4] [1] = 'trans_ex.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [5] [0] = '�������� ������ �� ������������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::TRANSPORT] [5] [1] = 'sale_dir.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [0] [0] = '����� �������� �� ������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [0] [1] = 'tov_vtill.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [1] [0] = '��������� �� ���. �����';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [1] [1] = 'hoz_needs.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [2] [0] = '����� �������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [2] [1] = 'sk_pick.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [3] [0] = '�������� ������ �� �������� - 2010';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [3] [1] = 'sk_pickq10.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [4] [0] = '�������� ������ �� ��������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [4] [1] = 'sk_pickq.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [5] [0] = '�������� �������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [5] [1] = 'sk_chkq.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [6] [0] = '�������� �������� ����������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [6] [1] = 'sk_pk_out.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [7] [0] = '����� �������� ������ � ��������� ���';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [7] [1] = 'tv_term.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [8] [0] = '���������� ��������������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [8] [1] = 'inv_res.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [9] [0] = '��������������� � � � � � � � � � � � � � �';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [9] [1] = 'inv.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [10] [0] = '�������� ��������������';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [10] [1] = 'inv_vrfy.php';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [11] [0] = '���������� � �������������� 2';
	// $menu [IDX_CAT::DOSTAVKA] [IDX_SUBCAT_DOSTAVKA::SKLAD] [11] [1] = 'inv2_res.php';
	
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [0] [0] = '���������, �����';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [0] [1] = 'prd_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [1] [0] = '�����������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [1] [1] = 'present_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [2] [0] = '���������� ���';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [2] [1] = 'competitor_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [3] [0] = '����������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [3] [1] = 'provider.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [4] [0] = '��������� ������������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [4] [1] = 'org_doc_ls.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [5] [0] = '���������� ������/������/���� � ��';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [5] [1] = 'cloud_instruct.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [6] [0] = '����������� ������ ���������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [6] [1] = 'cloud_official_let.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [7] [0] = '����������� ������ ��������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [7] [1] = 'cloud_official_let_inb.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [8] [0] = '��������� ���������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [8] [1] = 'cloud_claim.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [9] [0] = '��������� ��������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [9] [1] = 'cloud_claim_inb.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [10] [0] = '������� ���������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [10] [1] = 'cloud_law.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [11] [0] = '������� �����������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [11] [1] = 'cloud_compa_injun.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [12] [0] = '������������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::MAIN] [12] [1] = 'cloud_authority.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [0] [0] = '������� ���������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [0] [1] = 'buch_docs.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [1] [0] = '��������� ������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [1] [1] = 'cloud_buch_prepay.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [2] [0] = 'Z-�����';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [2] [1] = 'cloud_buch_z.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [3] [0] = '����� ������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [3] [1] = 'cloud_book_sale.php';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [4] [0] = '����� �������';
	// $menu [IDX_CAT::FILE_STORAGE] [IDX_SUBCAT_FILE_STORAGE::BUH] [4] [1] = 'cloud_book_purch.php';
	
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [0] = '��������';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [1] = 'exch.php';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [0] = '������ ��������';
	// $menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [1] = 'vac.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [0] = '������������ ����������';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [0] [1] = 'document_barcode_upload.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [0] = '���� �������� �������';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [1] [1] = 'wrk_tab.php';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [2] [0] = '���� �����';
	$menu [IDX_CAT::BUH] [IDX_SUBCAT::NONE] [2] [1] = 'plan_plat.php';
	
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [0] [0] = '����������� ��������� �������';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [0] [1] = 'addr_chk_coord.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [1] [0] = '����� ��������';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [1] [1] = 'list_addrs.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [2] [0] = '������ ��� ����������';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [2] [1] = 'addr_to_coord.php';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [3] [0] = '�����, �����������';
	// $menu [IDX_CAT::MAP] [IDX_SUBCAT::NONE] [3] [1] = 'present_addrs.php';
	
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [0] [0] = '������ ��������� ���������';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [0] [1] = 'mobile_dev_chk.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [1] [0] = '�������-���� �����';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [1] [1] = 'gps_report.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [2] [0] = '�������-���� ������';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [2] [1] = 'gps_draw.php';
	// $menu [IDX_CAT::TRACK] [IDX_SUBCAT::NONE] [3] [0] = '��� �����';
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
