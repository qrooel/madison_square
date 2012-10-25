<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title>{$SHOP_NAME} : {trans}TXT_PRODUCT_PROMOTION{/trans}</title>
    <link>{$URL}</link>
    <description>{$SHOP_NAME} : {trans}TXT_PRODUCT_PROMOTION{/trans}</description>
    <dc:language></dc:language>
    <dc:creator>{$SHOP_NAME}</dc:creator>
    <dc:rights></dc:rights>
    <dc:date>{$date_generated}</dc:date>
    <admin:generatorAgent rdf:resource="http://gekosale.pl/" />
    
{section name=i loop=$dataset}
    <item>
      <title>{$dataset[i].name}</title>
      <link>{$URL}{seo controller=productcart}/{$dataset[i].id}/{$dataset[i].seo}</link>
      <guid>{$URL}{seo controller=productcart}/{$dataset[i].id}/{$dataset[i].seo}</guid>
      <description>
      {$dataset[i].shortdescription}
      </description>
      <dc:subject>{$dataset[i].name}</dc:subject>
      <dc:date>{$dataset[i].adddate}</dc:date>
    </item>
{/section}
    
    </channel>
</rss>
