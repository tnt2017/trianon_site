<?php
header("Content-Type: application/json; charset=windows-1251");
session_start();
set_time_limit(90);
require_once "extras/orasql.php";
require_once "extras/utils.php";

function get_desc($db, $base, $idTov, $idStore, $idGrp, $nGrp)
{
    $db->parse("begin $base.DIRS.TOV_GET(:cur, :idTov, :idStore); end;");
    $db->bind(":cur", $cur, OCI_B_CURSOR);
    $db->bind(":idTov", $idTov, SQLT_INT);
    $db->bind(":idStore", $idStore, SQLT_INT);
    $db->execute();
    $db->execute_cursor($cur);

    $row = $db->fetch_cursor($cur);

    $ret = iconv('cp1251', 'utf-8', $row['TXT'])."\n(".$idGrp.")".$nGrp;

    return $ret;
}

if ( isset ($_SESSION ['OraLogin']) && isset ($_SESSION ['OraPwd'])) {
    $idTov = $_POST['id_tov'];
    $nGrp = $_POST['n_grp'];
    $idGrp = $_POST['id_grp'];
    
    try {
        $db = new CMyDb ();
        $db->connect($_SESSION ['OraLogin'], $_SESSION ['OraPwd'], "trn");

        $arlg = explode("$", $_SESSION ['OraLogin']);
        $ret_arr = '';
        $ret_arr = get_desc($db, $arlg[1], $idTov, 4,$idGrp,$nGrp);
        echo json_encode($ret_arr);
    } catch (Exception $e) {
        if (!array_key_exists('DEBUG', $ret_arr)) {
            $ret_arr ['DEBUG'] = array();
        }
        $ret_arr ['DEBUG'] [] = "exception:" + $e->getMessage();
        $ret_arr ['DEBUG1'] [] = $e;
        echo json_encode($ret_arr);
    }
} else {
    header("HTTP/1.1 502 Too less parameters");
}
?>