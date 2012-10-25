{if isset($news)}

{if isset($news.mainphoto.small) && $news.mainphoto.small != ''}
	<a rel="news" class="fancy" href="{$news.mainphoto.orginal}" title="{$news.topic}"><img src="{$news.mainphoto.small}" alt="{$news.topic}" style="float: left;margin: 0 10px 10px 0;"/></a>
{/if}
{$news.content}
<h4 >{trans}TXT_ADDDATE{/trans}: <strong>{$news.adddate}</strong></h4>
<div class="thumbs" style="float: left;clear: both;">
{section name=i loop=$news.otherphoto}
	<a rel="news" href="{$news.otherphoto[i].orginal}" title="{$news.topic}"><img src="{$news.otherphoto[i].small}" alt="{$news.topic}"/></a>
{/section}
</div>

<div class="buttons" style="float:left;margin-left:5px;clear:both;">
	<a href="javascript:history.back();" class="button"><span><span>{trans}TXT_BACK{/trans}</span></span></a>
</div>
{else}
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
{/if}
