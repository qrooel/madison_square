<tr>
	<td style="text-align: justify" align="left" valign="top"><font
		size="+1"><b>{trans}TXT_WELCOME{/trans}</b></font>
		<p>{trans}TXT_CLIENT_REGISTRATION_CONTENT{/trans}</p>
	</td>
</tr>
<tr>
	<td>
	{if isset($activelink) && $activelink != ''}
	<h3><a href="{$URL}{seo controller=registrationcart}/{$activelink}">{trans}TXT_ACTIVATE_CLIENT_ACCOUNT{/trans}</a></h3>
	{/if}
	<p><font color="#f6b900"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />
	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>
	{trans}TXT_SURNAME{/trans} : {$address.surname} <br>
	{trans}TXT_LOG{/trans} : {$address.email}<br>
	{trans}TXT_PHONE{/trans} : {$address.phone}<br>
	{trans}TXT_PASSWORD{/trans} : {if isset	($address.password)}{$address.password}{else}{$password}{/if}<br>
	</p>
	
	</td>
</tr>
