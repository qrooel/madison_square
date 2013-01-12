<?xml version="1.0" encoding="UTF-8"?>
<offers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1">
{if ($productList)>0}
{section name=i loop=$productList}
<o id="{$productList[i].productid}" url="{$URL}{seo controller=productcart}/{$productList[i].seo}?utm_source=ceneo&amp;utm_medium=CPC&amp;utm_campaign={$smarty.now|date_format:'%Y%m'}" price="{$productList[i].sellprice}" avail="{$productList[i].avail}" weight="{$productList[i].weight}" stock="{$productList[i].stock}">
	<cat>
		<![CDATA[{$productList[i].ceneo}]]>
	</cat>
    <name>
      <![CDATA[{$productList[i].name}]]>
    </name>
    <imgs>
      <main url="{$productList[i].photo}"/>
    </imgs>
    <desc>
      <![CDATA[{$productList[i].shortdescription}]]>
    </desc>
    <attrs>
      <a name="Producent">
        <![CDATA[{$productList[i].producername}]]>
      </a>
      <a name="EAN">
        <![CDATA[{$productList[i].ean}]]>
      </a>
    </attrs>    
  </o>  
{/section}
{/if}
</offers>
