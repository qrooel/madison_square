<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE nokaut SYSTEM "http://www.nokaut.pl/integracja/nokaut.dtd">
<nokaut generator="Gekosale">
<offers>
  {if ($productList)>0}
	{section name=i loop=$productList}
    <offer>
		<id>{$productList[i].productid}</id>
		<name><![CDATA[{$productList[i].name}]]></name>
		<description><![CDATA[{$productList[i].shortdescription}]]></description>
		<url>{$URL}{seo controller=productcart}/{$productList[i].seo}</url>
		<image><![CDATA[{$productList[i].photo}]]></image>
		<price>{$productList[i].sellprice}</price>
		<category><![CDATA[{$productList[i].categoryname}]]></category>
		<producer><![CDATA[{$productList[i].producername}]]></producer>
    </offer>
    {/section}
    {/if}
</offers>
</nokaut>