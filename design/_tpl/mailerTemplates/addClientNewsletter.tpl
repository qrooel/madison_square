{if isset($newsletterlink)}
	      <tr>
	        <td style="text-align:justify" align="left" valign="top"><font size="+1"><b>{trans}TXT_WELCOME{/trans}</b></font>
	        	<p>
					{trans}TXT_CLIENT_REGISTRATION_NEWSLETTER{/trans}<br/>
					
					<font color="red"><strong><a href="{$URL}newsletter/index/{$newsletterlink}">{trans}TXT_ACTIVE_NEWSLETTER_LINK{/trans}</a></strong></font><br/></br>
					<a href="{$URL}newsletter/index/{$unwantednewsletterlink}">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>
				</p>
	        </td>
	      </tr>
	   {else}
		   <tr>
	       <td style="text-align:justify" align="left" valign="top"><font size="+1"><b>Wypisujesz się z newsletter</b></font>
	       	<p>					
				<a href="{$URL}newsletter/index/{$unwantednewsletterlink}">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>
			</p>
	       </td>
	     </tr>
	   {/if}