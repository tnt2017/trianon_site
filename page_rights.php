<?php

function is_user_oper($db) { // generalize to any division/position
	define ( 'POSITION_MANAGER_OPERATOR', 'Менеджер торг.зала' );
	define ( 'POSITION_OPERATOR', 'Оператор' );
	
	$db->parse ( "begin :n:=SSEC.AUTH_PATH.GET_CUR_USER_KATSOTR_ID; end;" );
	$db->bind ( ":n", $idEmp, SQLT_INT );
	$db->execute ();
	
	$div_operator = false;

	$db->parse ( "begin NSK08.STAFF.LIST_EMAILS(:cur,:idEmp); end;" );
	$db->bind ( ":cur", $cur, OCI_B_CURSOR );
	$db->bind ( ":idEmp", $idEmp, SQLT_INT );
	$db->execute ();
	$db->execute_cursor ( $cur );

	while ( $row = $db->fetch_cursor ( $cur ) ) {
		if (isset ( $row ['EMP_POS'] )) {
			if ($row ['EMP_POS'] == POSITION_OPERATOR || $row ['EMP_POS'] == POSITION_MANAGER_OPERATOR) {
				if ($row ['ID'] == $idEmp) {
					$div_operator = true;
					break;
				}
			}
		}
	}
	return $div_operator;
}

function check_page_rights($page, $rights, $db) {
	$ret = false;
	switch ($page) {
		//case 'oper_task_tt.php' :  // pass-through 27.01.2020
		case 'grp_sales.php' :  // pass-through 27.01.2020
		case 'firm_news.php' : // pass-through
		case 'firm_news2.php' : // pass-through
		case 'firm_dirc.php' : // pass-through
		case 'email_list.php' : // pass-through
		case 'prle.php' : // pass-through
		case 'prlk.php' : // pass-through
		case 'org_maxdis.php' : // pass-through
		case 'addr_act.php' : // pass-through
		case 'org_short.php' : // pass-through
		case 'reclame.php' : // pass-through
		case 'sale_dir0.php' :
		case 'sale_grp.php' :
		case 'dyn_ag.php' :
		case 'ag_plan.php' :
		case 'ag_plans.php' :
		case 'ag_plans2.php' :
		case 'ag_plans2_n.php' :
		case 'ag_plans_calendar.php' :
		case 'ag_visitp.php' :
		case 'lev_specdiv.php' :
		case 'sale_cmp6grp.php' :
		case 'org_fixp.php' :
		case 'org_fixp_sold.php' :
		case 'sale_org.php' :
		case 'sales_book_check.php' :
		case 'bonus_emu.php' :
		case 'ori_diff.php' :
		case 'ori_difemps.php' :
		case 'gen_order.php' :
		case 'act_ori.php' :
		case 'list_docs.php' :
		case 'cred_hist.php' :
		case 'ag_hist.php' :
		case 'clnt_tovgrps.php' :
		case 'edit_discl.php' :
		case 'cr_hist.php' :
		case 'org_misattr.php' :
		case 'debts_big.php' :
		case 'org_agr_ovd.php' :
		case 'sverka.php' :
		case 'sverka_no.php' :
		case 'ag_debts.php' :
		case 'org_lim.php' :
		case 'org_ordtyps.php' :
		case 'au_org.php' :
		case 'org_adcrd.php' :
		case 'list_projects.php' :
		case 'pay_cache.php' :
		case 'pay_cache_exped.php' :
		case 'jrn_ret_cli.php' :
		case 'tel_list.php' :
		case 'tov_by_bc.php' :
		case 'tov_mbars.php' :
		case 'tov_bugs.php' :
		case 'tov_fic_sec.php' :
		case 'tov_des.php' :
		case 'tov_names.php' :
		case 'tpromo.php' :
		case 'tov_nliq.php' :
		case 'addr_id.php' :
		case 'trips.php' :
		case 'ag_deliv.php' :
		case 'tov_vtill.php' :
		case 'hoz_needs.php' :
		case 'prd_docs.php' :
		case 'present_docs.php' :
		case 'competitor_docs.php' :
		case 'provider.php' :
		case 'org_doc_ls.php' :
		case 'cloud_instruct.php' :
		case 'cloud_official_let.php' :
		case 'cloud_official_let_inb.php' :
		case 'cloud_claim.php' :
		case 'cloud_claim_inb.php' :
		case 'cloud_law.php' :
		case 'exch.php' :
		case 'vac.php' :
		case 'addr_chk_coord.php' :
		case 'list_addrs.php' :
		case 'mobile_dev_chk.php' :
		case 'gps_report.php' :
		case 'gps_draw.php' :
		case 'gps_draw_lm.php' :
		case 'buch_docs.php' :
		case 'addr_to_coord.php' :
		case 'wrk_tab.php' :
		case 'kontur.php' :
		case 'asina.php' :
		case 'oper_tasks_tt.php' :
        case 'month_report.php' :
		case 'agent_report.php' :
		case 'emp_work.php' :
		case 'org_work.php' :
		case 'worknotes_tabs.php' :
		case 'document_barcode_upload.php' :
		case 'topol.php' :
			if (isset ( $rights [102] ) && ($rights [102] > 0)) {
				$ret = true;
			}
			break;
		case 'set_mark.php' :
			if (isset ( $rights [511])) {
				$ret = true;
			}
			break;
		case 'edit_bp.php' :
			if (isset ( $rights [104] ) && ($rights [104] > 0)) {
				$ret = true;
			}
			break;
		case 'tov_hier.php' :
		case 'tov_hid.php' :
		case 'tov_limiter.php' :
			if (isset ( $rights [105] ) && ($rights [105] > 1)) {
				$ret = true;
			}
			break;
		case 'tov_ves.php' :
			if (isset ( $rights [107] ) && ($rights [107] > 0)) {
				$ret = true;
			}
			break;
		case 'mtov_attr.php' :
			if (isset ( $rights [108] ) && ($rights [108] > 0)) {
				$ret = true;
			}
			break;
		case 'sk_pick.php' :
		case 'sk_pickq10.php' :
		case 'sk_pickq.php' :
		case 'sk_chkq.php' :
		case 'sk_pk_out.php' :
			if (isset ( $rights [111] ) && ($rights [111] > 0)) {
				$ret = true;
			}
			break;
		case 'org_hand_lst.php' :
		case 'org_ban.php' :
			if (isset ( $rights [113] ) && ($rights [113] > 0)) {
				$ret = true;
			}
			break;
		case 'org_smp_coef.php' :
			if (isset ( $rights [113] ) && ($rights [113] & 0x8)) {
				$ret = true;
			}
			break;
		case 'tov_certs.php' :
			if (isset ( $rights [118] )) {
				$ret = true;
			}
			break;
		case 'ag_ex_orgs.php' :
		case 'div_ex_orgs.php' :
			if (isset ( $rights [121] ) && ($rights [121] > 0)) {
				$ret = true;
			}
			break;
		case 'ag_plans_all.php' :
			if (isset ( $rights [121] ) && ($rights [121] & 0x4)) {
				$ret = true;
			}
			break;
		case 'exp_revert.php' :
		case 'trip_list.php' :
			if (isset ( $rights [124] ) && ($rights [124] > 0)) {
				$ret = true;
			}
			break;
		case 'cloud_book_sale.php' :
		case 'cloud_book_purch.php' :
			if ((isset ( $rights [201] ) && ($rights [201] > 0)) || (isset ( $rights [206] ) && ($rights [206]))) {
				$ret = true;
			}
			break;
		case 'cloud_authority.php' :
			if ((isset ( $rights [201] ) && ($rights [201] > 0)) || (isset ( $rights [207] ) && ($rights [207] > 0)) || (isset ( $rights [509] ) && ($rights [509]))) {
				$ret = true;
			}
			break;
		case 'cloud_compa_injun.php' :
		case 'cloud_buch_prepay.php' :
		case 'cloud_buch_z.php' :
		case 'plan_plat.php' :
			if ((isset ( $rights [201] ) && ($rights [201] > 0)) || (isset ( $rights [509] ) && ($rights [509]))) {
				$ret = true;
			}
			break;
		case 'present_addr.php' :
			if (isset ( $rights [206] ) && ($rights [206] > 0)) {
				$ret = true;
			}
			break;
		case 'emp_doc_up.php' :
		case 'org_hand.php' :
			if (isset ( $rights [206] ) && ($rights [206] > 1)) {
				$ret = true;
			}
			break;
		case 'slr_tp.php' :
		case 'slr_tp2.php' :
		case 'ag_coef.php' :
			if (isset ( $rights [501] ) && ($rights [501] > 0)) {
				$ret = true;
			}
			break;
		case 'lev_brands.php' :
		case 'lev_tovs.php' :
		case 'bonus.php' :
		case 'ven_adrs_ls.php' :
		case 'ven_adrs_ls2.php' :
			if (isset ( $rights [502] ) && ($rights [502] > 0)) {
				$ret = true;
			}
			break;
		case 'sale_net.php' :
		case 'grp_qual.php' :
		case 'grp_attr.php' :
		case 'tov_ost_gr.php' :
		case 'mag_tov.php' :
			if (isset ( $rights [504] ) && ($rights [504] > 0)) {
				$ret = true;
			}
			break;
		case 'sale_pat.php' :
		case 'sale_cmp2grp.php' :
			if (isset ( $rights [504] ) && ($rights [504] > 1)) {
				$ret = true;
			}
			break;
		case 'sale_agd.php' :
		case 'sale_smp.php' :
		case 'org_nac_2p.php' :
			if (isset ( $rights [505] ) && ($rights [505] > 0)) {
				$ret = true;
			}
			break;
		case 'sale_gd.php' :
		case 'tov_rep.php' :
		case 'bongrp_bal.php' :
		case 'bonorg_bal.php' :
		case 'trans_ex.php' :
			if (isset ( $rights [507] ) && ($rights [507] > 0)) {
				$ret = true;
			}
			break;
		case 'sale_dir.php' :
			if (isset ( $rights [507] ) && ($rights [507] > 1)) {
				$ret = true;
			}
			break;
		case 'sale_adr.php' :
		case 'sale_it.php' :
			if (isset ( $rights [508] ) && ($rights [508] > 0)) {
				$ret = true;
			}
			break;
		case 'tov_hid_mng.php' :
			if (isset ( $rights [509] ) && ($rights [509] > 0)) {
				$ret = true;
			}
			break;
		case 'org_coef.php' :
			if (isset ( $rights [510] ) && ($rights [510] > 0)) {
				$ret = true;
			}
			break;
		case 'tv_term.php' :
		case 'inv_res.php' :
		case 'inv.php' :
		case 'inv_vrfy.php' :
		case 'inv2_res.php' :
			if (isset ( $rights [512] ) && ($rights [512] > 0)) {
				$ret = true;
			}
			break;
		case 'oper_tasks.php' :
			if (isset ( $rights [209] ) && ($rights [209] > 0) || is_user_oper($db)) {
				$ret = true;
			}
			break;
		default :
			$ret = false;
			break;
	}
	
	return $ret;
}

?>
