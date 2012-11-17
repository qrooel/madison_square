<!-- begin: Footer -->
				{*
				<div id="footer">
						<ul>
						{if isset($contentcategory)}
						
							{section name=cat loop=$contentcategory}
							{if $contentcategory[cat].footer == 1}
							<li class="top">
								<h4><span>{$contentcategory[cat].name}</span></h4>
								<ul>
								{if count($contentcategory[cat].children) > 0}
								{section name=under loop=$contentcategory[cat].children}
								{if $contentcategory[cat].children[under].footer == 1}
									<li><a href="{$URL}{seo controller=staticcontent}/{$contentcategory[cat].children[under].id}/{$contentcategory[cat].children[under].seo}">{$contentcategory[cat].children[under].name}</a></li>
								{/if}
								{/section}
								{/if}
								
								{section name=page loop=$contentcategory[cat].page}
									<li><a href="{$URL}{seo controller=staticcontent}/{$contentcategory[cat].page[page].id}/{$contentcategory[cat].page[page].seo}">{$contentcategory[cat].page[page].topic}</a></li>
								{/section}
								</ul>
							</li>
							{/if}
							{/section}
						{/if}
						{if $catalogmode == 0}
							<li class="top">
								<h4><span>{trans}TXT_YOUR_ACCOUNT{/trans}</span></h4>
								<ul>
								{if isset($clientdata)}
									<li><a href="{$URL}{seo controller=clientsettings}/">{trans}TXT_SETTINGS{/trans}</a></li>
									<li><a href="{$URL}{seo controller=clientorder}/">{trans}TXT_ORDERS{/trans}</a></li>
									<li><a href="{$URL}{seo controller=clientaddress}/">{trans}TXT_CLIENT_ADDRESS{/trans}</a></li>								
								{else}
									<li><span class="{seo_js controller=clientlogin}">{trans}TXT_LOGIN_TO_YOUR_ACCOUNT{/trans}</span></li>
									<li><span class="{seo_js controller=registrationcart}">{trans}TXT_REGISTER{/trans}</span></li>
								{/if}
								</ul>
							</li>
						{/if}
							<li class="top">
								<h4><span>{trans}TXT_IMPORTANT_LINKS{/trans}</span></h4>
								<ul>
									<li><span class="{seo_js controller=contact}">{trans}TXT_CONTACT{/trans}</span></li>
									<li><a href="{$URL}{seo controller=sitemap}">{trans}TXT_SITEMAP{/trans}</a></li>
									<li><span class="{seo_js controller=newsletter}">{trans}TXT_NEWSLETTER{/trans}</span></li>
								</ul>
							</li>
						</ul>
				</div>
				*}
			<!-- end: Footer -->
			
			<!-- begin: Copyright bar -->
				<div id="copyright-bar">
					<p class="copyright">
						madison-square.pl © 2012
					</p>
				</div>
			<!-- end: Copyright bar -->
		</div>
		<script type="text/javascript">  
		{literal}
			$(document).ready(function(){
				var container = $('#footer').width();
				var cols = $('li.top').length;
				$('li.top').width(container / cols);
			});
		{/literal}
		</script>
		{$footerJS}
		{if $gacode != ''}
		<script type="text/javascript">
		{literal}
		    (function() {
		    	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		    })();
		{/literal}
		</script>
		{/if}
	</body>
</html>