{if (isset($errlink) || (isset($activelink)) || (isset($inactivelink)))}
	{if (isset($errlink) && $errlink ==1)}
		<p class="error"><strong>{trans}TXT_INVALID_LINK{/trans}</strong></p>
	{elseif (isset($inactivelink) && $inactivelink ==1)}
		<p>{trans}TXT_DELETE_CLIENT_FROM_NEWSLETTER{/trans}</p>
	{else}
		<p>{trans}TXT_CLIENT_REGISTRATION_NEWSLETTER{/trans}</p>
	{/if}
{else}
		<p>{trans}TXT_NEWSLETTER_INFO_FRONTEND{/trans}</p>
		<div class="field-text">
			<label for="newsletterformphrase">{trans}TXT_EMAIL{/trans}</label>
			<span class="field">
				<input id="newsletterformphrase" name="mail" type="text" value="{$email}"/>
			</span>
		</div>
		
		<div id="info"></div>
		<a href="#" class="button" onclick="xajax_addNewsletter($('#newsletterformphrase').val());return false;"><span>{trans}TXT_SAVE{/trans}</span></a>
		<a href="#" class="button" onclick="xajax_deleteNewsletter($('#newsletterformphrase').val());return false;"><span>{trans}TXT_DELETE{/trans}</span></a>
{/if}