{if isset($enableregistration) && $enableregistration == 1}
{if isset($clientdata)}
	<strong>{trans}TXT_USER_LOGIN{/trans}:</strong><br><br>
	<strong> {$clientdata.firstname} {$clientdata.surname} </strong>
{else}
	{if isset($registrationok)}
	<p class="error"><strong>
		{trans}{$registrationok}{/trans}
		{trans}TXT_CHECK_PRIVATE_MAIL{/trans}
	</strong></p>
	{else}
		{if !isset($facebookRegister)}
			{fe_form form=$form render_mode="JS"} 
			{if isset($facebooklogin) && $facebooklogin != ''}
			<div style="margin: 20px;">
			<p>{trans}TXT_REGISTER_WITH_FACEBOOK{/trans}</p>
			<a href="{$facebooklogin}"><img src="{$DESIGNPATH}_images_frontend/core/buttons/facebook.png" /></a>
			</div>
			{/if}
		{/if}
	{/if}
{/if}

{if isset($facebookRegister)}
<div id="fb-root"></div>
{literal}
<div class="listing" style="margin-left: 7px;margin-bottom: 20px;">
<fb:registration fields="[{'name':'name'},{'name':'email'}, {'name':'phone','description':'{/literal}{trans}TXT_PHONE{/trans}{literal}','type':'text'}]" redirect-uri="{/literal}{$URL}{seo controller=registrationcart}{literal}"></fb:registration>
</div> 
{/literal}
<script>
{literal}
	window.fbAsyncInit = function() {
    	FB.init({
          appId: '{/literal}{$faceboookappid}{literal}',
          cookie: true,
          xfbml: true,
          oauth: true
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/pl_PL/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
      {/literal}
    </script>
{/if}	
{else}
<p>{trans}TXT_REGISTRATION_DISABLED_HELP{/trans}</p>
{/if}