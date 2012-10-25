{if isset($newslist[0])}	
	<ul class="list">
	{section name=i loop=$newslist}
	<li>
		<h4>
			<a href="{$URL}{seo controller=news}/{$newslist[i].idnews}/{$newslist[i].seo}">
				<span class="name">{$newslist[i].topic}</span>
				<span class="date">{$newslist[i].adddate}</span>
			</a>
		</h4>
		<div class="description">
			{if isset($newslist[i].mainphoto.small) &&  $newslist[i].mainphoto.small !=''}
				<a class="read-more" href="{$URL}{seo controller=news}/{$newslist[i].idnews}/{$newslist[i].seo}"><img src="{$newslist[i].mainphoto.small}" alt="{$newslist[i].topic}" class="mainphoto" /></a>
			{/if}
			{$newslist[i].summary}
			<p><a href="{$URL}{seo controller=news}/{$newslist[i].idnews}/{$newslist[i].seo}">{trans}TXT_READ_MORE{/trans}</a></p>
		</div>
	</li>
	{/section}
	</ul>
	
{else}
	<p>{trans}ERR_EMPTY_NEWS{/trans}</p>
{/if}
<div class="layout-box-footer">
	<p>{if $enablerss == 1}<span class="rss"><a href="{$URL}feeds/news"><img src="{$DESIGNPATH}_images_frontend/core/icons/rss.png" title="RSS - {trans}TXT_NEWS{/trans}"></a></span>{/if}<span style="text-align: right;margin-right: 10px;float: right;margin-bottom: 10px;"><a href="{$URL}{seo controller=news}">{trans}TXT_SHOW_ALL{/trans}</a></span></p>
</div>