<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{trans}MAIL_TITLE{/trans}</title>
	<style type="text/css">
	{literal}
		body,td,th {
			font-size: 11px;
			color: #575656;
			font-family: Arial;
		}
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
		a:link {
			color: #BF3131;
			text-decoration: none;
		}
		a:visited {
			text-decoration: none;
			color: #BF3131;
		}
		a:hover {
			text-decoration: none;
			color: #BF3131;
		}
		a:active {
			text-decoration: none;
			color: #BF3131;
		}
	{/literal}
	</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td width="500" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="96" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          	{if isset($FRONTEND_URL)}
            <td height="96" align="left" valign="middle"><img src='cid:logo' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {else}
           	<td height="96" align="left" valign="middle"><img src='cid:logo' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {/if}
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="23" align="left" valign="top"><hr noshade="noshade" size="1" color="#e8e8e8"/></td>
      </tr>
      <tr>
        <td style="text-align:justify" align="left" valign="top"><font size="+2"><b>{$SHOP_NAME}</b></font>
   		<br/>{trans}TXT_HEADER_INFO{/trans}
        </td>
      </tr>
      <tr>
        <td height="38" align="left" valign="top">&nbsp;</td>
      </tr>fsdfdsf <tr>
        <td height="20" align="left" valign="top" style="text-align:justify">&nbsp;</td>
      </tr>
    </table>
   </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
    <td width="500" height="10" align="left" valign="top" bgcolor="#3d3d3d">&nbsp;</td>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
  </tr>
  <tr>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
    <td width="500" height="70" align="center" valign="middle" bgcolor="#2c2c2c">
    <font color="#b1b1b1">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
  </tr>
</table>
</body>
</html>