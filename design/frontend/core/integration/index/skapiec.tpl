<?xml version="1.0" encoding="UTF-8" ?>
<xmldata>
	<version>12.0</version>
	<header>
		<name><![CDATA[{$SHOP_NAME}]]></name>
		<www><![CDATA[{$URL}]]></www>
		<time>{$smarty.now|date_format:"%Y-%m-%d"}</time>
	</header>
	<category>
	{section name=c loop=$skapieccategories}
		<catitem>
			<catid>{$skapieccategories[c].catid}</catid>
			<catname><![CDATA[{$skapieccategories[c].catname}]]></catname>
		</catitem>
	{/section}
	</category>
	<data>
	{if ($productList) > 0}
	{section name=i loop=$productList}
		<item>
			<compid><![CDATA[{$productList[i].productid}]]></compid>
			<vendor><![CDATA[{$productList[i].producername}]]></vendor>
			<name><![CDATA[{$productList[i].name}]]></name>
			<price>{$productList[i].sellprice}</price>
			<catid><![CDATA[{$productList[i].categoryid}]]></catid>
			<foto><![CDATA[{$productList[i].photo}]]></foto>
			<desclong><![CDATA[{$productList[i].shortdescription}]]></desclong>
			<url><![CDATA[{$URL}{seo controller=productcart}/{$productList[i].seo}]]></url>
		</item>
	{/section}
	{/if}
	</data>
</xmldata>
