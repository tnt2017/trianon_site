<?php
// authorization stuff,  5.12.14
//  10.06.15  add auth_right2() with bits check
//  30.11.15  add auth_expired()


function auth_expired($db)
{
  $db->parse("select case when EXPIRY_DATE < sysdate+14 then".
           " to_char(EXPIRY_DATE,'dd.mm.yyyy hh24:mi') else '' end EX_DT ".
           "from USER_USERS");
  $db->execute();
  if( $row= $db->fetch() ) {
    if( isset($row['EX_DT']) )
      echo "<p class=err>���� �������� ������ �������� $row[EX_DT]</p>";
  }
  return 0;
}

function auth_parse()
{
  $usr= isset($_REQUEST['cbUser']) ? $_REQUEST['cbUser'] : "";
  $arr= explode(";", $usr);
  $_SESSION['OraLogin']= $arr[1];
  $_SESSION['OraPwd']= isset($_REQUEST['sPwd']) ? $_REQUEST['sPwd'] : "";
}


function auth_right( &$db, $right, $edge, $info= 1)
{
  auth_expired($db);
  $db->parse("begin SSEC.AUTH_PATH.GET_CUR_USER_RIGHT(:rt,:val); end;");
  $db->bind(":rt", $right, SQLT_INT);
  $db->bind(":val", $val, SQLT_CHR, 16);
  $db->execute();
  if( $val > $edge )
    return  $val;

  if( $info )
    echo "<p><span class=err>������������ ����! </span></p>\n";
  return 0;
}

function auth_right2( &$db, $right, $bits, $info= 1)
{
  auth_expired($db);
  $db->parse("begin SSEC.AUTH_PATH.GET_CUR_USER_RIGHT(:rt,:val); end;");
  $db->bind(":rt", $right, SQLT_INT);
  $db->bind(":val", $val, SQLT_CHR, 16);
  $db->execute();
  if( ($val & $bits) == $bits )
    return  $val;

  if( $info )
    echo "<p><span class=err>������������ ����! </span></p>\n";
  return 0;
}


function auth_dlg( &$db )
{
  // show list of users
  $db->connect("NNN","registr","trn");
  $db->parse("begin SSEC.AUTH_PATH.LIST_USERBASE(:ret); end;");
  $db->bind(":ret", $cur, OCI_B_CURSOR);
  $db->execute();
  $db->execute_cursor($cur);

  echo "<p>��� ������� � ������ ������� ��� � ������ ��� �����������...".
    "<p>  <input type=hidden name=sInit value=1 >".
    "<table border=0 width='70%'>".
    "<tr><td>��������� / ����:  <td> <select name=cbUser size=1>";

  while( $row= $db->fetch_cursor($cur) ) {
    echo "<option value='$row[ID];$row[BASENAME]' > $row[USERDESCR] $row[BASEDESCR]";
  }

  echo "  </select>".
    "<tr>".
    "  <td>������: <td> <input type=password name=sPwd >".
    "<tr>  <td>  <td>".
    "<tr> <td> <td> <input type=submit value='�����������'>".
    "</table>\n";
}

?>
