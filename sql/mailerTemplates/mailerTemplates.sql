/*!40100 DEFAULT CHARACTER SET latin1 */;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `transmail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmail` (
  `idtransmail` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `transmailactionid` int(10) unsigned NOT NULL,
  `contenttxt` varchar(5000) DEFAULT NULL,
  `contenthtml` varchar(10000) DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `active` tinyint(3) unsigned DEFAULT '0',
  `transmailheaderid` int(10) unsigned DEFAULT NULL,
  `transmailfooterid` int(10) unsigned DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idtransmail`),
  KEY `FK_transmail_addid` (`addid`),
  KEY `FK_transmail_editid` (`editid`),
  KEY `FK_transmail_viewid` (`viewid`),
  KEY `FK_transmail_transmailheaderid` (`transmailheaderid`),
  KEY `FK_transmail_transmailfooterid` (`transmailfooterid`),
  CONSTRAINT `FK_transmail_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmail_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmail_transmailfooterid` FOREIGN KEY (`transmailfooterid`) REFERENCES `transmailfooter` (`idtransmailfooter`),
  CONSTRAINT `FK_transmail_transmailheaderid` FOREIGN KEY (`transmailheaderid`) REFERENCES `transmailheader` (`idtransmailheader`),
  CONSTRAINT `FK_transmail_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `transmail` DISABLE KEYS */;
INSERT INTO `transmail` VALUES (1,'Aktywacja klienta przez panel administracyjny',1,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ACCOUNT_CLIENT_ACTIVATION{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>',1,'2011-05-29 18:20:31',1,'2011-03-09 22:58:37',NULL,NULL,0,1,1,NULL,NULL),(2,'Dodanie adresu do książki adresowej klienta',2,' ','<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ADDRESS_ADD_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-16 19:57:12',1,'2011-03-16 20:57:12',NULL,NULL,1,1,1,NULL,NULL),(3,'Rejestracja klienta w sklepie',3,'','<tr>\r\n	<td style=\"text-align: justify\" align=\"left\" valign=\"top\"><font\r\n		size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	<p>{trans}TXT_CLIENT_REGISTRATION_CONTENT{/trans}</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	<p><font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />\r\n	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n	{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n	{trans}TXT_LOG{/trans} : {$address.email}<br>\r\n	{trans}TXT_PHONE{/trans} : {$address.phone}<br>\r\n	{trans}TXT_PASSWORD{/trans} : {if isset\r\n	($address.password)}{$address.password} {else}{$password}{/if}<br>\r\n	</p>\r\n	</td>\r\n</tr>\r\n',1,'2011-05-10 19:28:20',1,'2011-05-10 21:28:20',NULL,NULL,1,1,1,NULL,NULL),(4,'Dodanie klienta przez panel administracyjny',4,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font></td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$personal_data.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$personal_data.surname} <br>\r\n			{trans}TXT_LOG{/trans} : {$personal_data.email}<br>\r\n			{trans}TXT_PASSWORD{/trans} : {$personal_data.password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$personal_data.phone}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:08',1,'2011-03-09 22:59:08',NULL,NULL,1,1,1,NULL,NULL),(5,'Dodanie klienta do newsletter',5,NULL,'{if isset($newsletterlink)}\r\n	      <tr>\r\n	        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        	<p>\r\n					{trans}TXT_CLIENT_REGISTRATION_NEWSLETTER{/trans}<br/>\r\n					\r\n					<font color=\"red\"><strong><a href=\"{$URL}newsletter/index/{$newsletterlink}\">{trans}TXT_ACTIVE_NEWSLETTER_LINK{/trans}</a></strong></font><br/></br>\r\n					<a href=\"{$URL}newsletter/index/{$unwantednewsletterlink}\">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>\r\n				</p>\r\n	        </td>\r\n	      </tr>\r\n	   {else}\r\n		   <tr>\r\n	       <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>Wypisujesz się z newsletter</b></font>\r\n	       	<p>					\r\n				<a href=\"{$URL}newsletter/index/{$unwantednewsletterlink}\">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>\r\n			</p>\r\n	       </td>\r\n	     </tr>\r\n	   {/if}',1,'2011-03-09 21:59:15',1,'2011-03-09 22:59:15',NULL,NULL,1,1,1,NULL,NULL),(6,'Kontakt- kopia treści wiadomości formularza kontaktowego',6,'{$CONTACT_CONTENT}\r\n\r\n{$firstname} {$surname} \r\n{$email}\r\n{$phone}','<tr>\r\n    <td>{$CONTACT_CONTENT}</td>\r\n  </tr>\r\n <tr>\r\n    	<td>\r\n	{$firstname} {$surname} <br />\r\n	{$email}<br />\r\n	{$phone}<br />\r\n	</td>\r\n  </tr>',1,'2011-04-29 14:56:18',1,'2011-04-29 16:56:18',NULL,NULL,1,1,1,NULL,NULL),(7,'Edycja adresu przez klienta',7,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ADDRESS_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:27',1,'2011-03-09 22:59:27',NULL,NULL,1,1,1,NULL,NULL),(8,'Edycja adresu E-mail (loginu) przez klienta',8,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_EMAIL_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n			{trans}TXT_OLD_EMAIL{/trans} : {$clientdata.email} <br>\r\n			{trans}TXT_NEW_EMAIL{/trans} : {$EMAIL_NEW}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:33',1,'2011-03-09 22:59:33',NULL,NULL,1,1,1,NULL,NULL),(9,'Edycja hasła przez klienta',9,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$PASS_NEW}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:38',1,'2011-03-09 22:59:38',NULL,NULL,1,1,1,NULL,NULL),(10,'Edycja hasła użytkownika panelu administracyjnego',10,NULL,'\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_EDIT_USER{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_EDIT_PASSWORD_USER{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:44',1,'2011-03-09 22:59:44',NULL,NULL,1,1,1,NULL,NULL),(11,'Wygenerowanie nowego hasła dla klienta sklepu (zapomniane hasło)',11,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_FORGOT_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password} <br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 21:59:53',1,'2011-03-09 22:59:53',NULL,NULL,1,1,1,NULL,NULL),(12,'Przypomnienie loginu użytkownikowi panelu administracyjnego',12,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_FORGOT_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_USERS{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:00:03',1,'2011-03-09 23:00:03',NULL,NULL,1,1,1,NULL,NULL),(13,'Wygenerowanie nowego hasła dla użytkownika panelu',13,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_NEW_USER{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_USER{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$users.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$users.surname} <br> \r\n			{trans}TXT_EMAIL{/trans} : {$users.email} <br> \r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:00:12',1,'2011-03-09 23:00:12',NULL,NULL,1,1,1,NULL,NULL),(14,'Wysłanie newslettera do klientów sklepu ze strony panelu administracyj',14,NULL,'{$newsletter.htmlform}',1,'2011-03-09 22:00:20',1,'2011-03-09 23:00:20',NULL,NULL,1,1,1,NULL,NULL),(15,'Potwierdzenie złożenia zamówienia przez klienta w sklepie',15,'','      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n        		<font color=\"#f6b900\"><b>{trans}TXT_CUSTOMER{/trans}: {$order.clientaddress.firstname} {$order.clientaddress.surname} </b></font><br/>\r\n				{trans}TXT_EMAIL_ORDER_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n        	<font color=\"#f6b900\"><b>{trans}TXT_ORDER{/trans}: </b></font><br/>\r\n			{trans}TXT_COST_OF_DELIVERY{/trans} : {$order.dispatchmethod.dispatchmethodcost} {$currencySymbol}<br>\r\n			{trans}TXT_METHOD_OF_DELIVERY{/trans} : {$order.dispatchmethod.dispatchmethodname}<br>\r\n	      	{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}<br>\r\n	      	</p>\r\n	      	\r\n	      	<p>\r\n	      		{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />\r\n	      		<a href=\"{$URL}confirmation/index/{$orderlink}\">{$URL}confirmation/index/{$orderlink}</a>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n				       <tr>\r\n				      	<td>\r\n				      	<font color=\"#f6b900\"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br/>\r\n						<table>\r\n							<thead>\r\n								<tr>\r\n									<th class=\"name\">{trans}TXT_PRODUCT_NAME{/trans}:</th>\r\n									<th class=\"price\">{trans}TXT_PRODUCT_PRICE{/trans}:</th>\r\n									<th class=\"quantity\">{trans}TXT_QUANTITY{/trans}:</th>\r\n									<th class=\"subtotal\">{trans}TXT_VALUE{/trans}:</th>\r\n								</tr>\r\n							</thead>\r\n							<tbody>\r\n							{foreach name=outer item=product from=$order.cart}\r\n								{if isset($product.name)}\r\n								<tr>\r\n									<th>{$product.name}</th>\r\n							      	<td> {$product.newprice} {$currencySymbol}</td>\r\n							      	<td> {$product.qty} {trans}TXT_QTY{/trans}</td>\r\n							      	<td>{$product.qtyprice}  {$currencySymbol}</td>\r\n						        </tr> \r\n						       	{/if}\r\n\r\n								{foreach name=inner item=attributes from=$product.attributes}\r\n									{if isset($attributes.name)}\r\n									<tr>\r\n										<th>{$attributes.name}</th>\r\n								      	<td> {$attributes.newprice} {$currencySymbol}</td>\r\n								      	<td> {$attributes.qty} {trans}TXT_QTY{/trans}</td>\r\n								      	<td>{$attributes.qtyprice}  {$currencySymbol}</td>\r\n							        </tr> \r\n					         		{/if}\r\n								{/foreach}\r\n					       	{/foreach} 	\r\n							</tbody>\r\n						</table>\r\n				      	</td>\r\n				      </tr>\r\n	  <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_PRICE{/trans}: </b></font><br/>\r\n			\r\n	        {if isset($order.priceWithDispatchMethodPromo) && $order.priceWithDispatchMethodPromo >0}\r\n				{trans}TXT_GLOBAL_PRICE{/trans} : \r\n					<s style=\"color: black\">{$order.globalPrice} {$currencySymbol}</s>\r\n					<font style=\"color: red;\">{$order.globalPricePromo} <span>{$currencySymbol}</font></strong><br>\r\n				{trans}TXT_PRICE_WITH_DISPATCHMETHOD{/trans} :	\r\n					<s style=\"color: black\">{$order.priceWithDispatchMethod} {$currencySymbol}</s>\r\n					<font style=\"color: red;\">{$order.priceWithDispatchMethodPromo} <span>{$currencySymbol}</font></strong><br>\r\n					\r\n			{else}\r\n				{trans}TXT_GLOBAL_PRICE{/trans} : {$order.globalPrice} {$currencySymbol}<br>\r\n				{trans}TXT_PRICE_WITH_DISPATCHMETHOD{/trans} : {$order.priceWithDispatchMethod} {$currencySymbol}<br>\r\n			{/if}\r\n			{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>			      \r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	        {if $order.clientaddress.companyname != \'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname} <br>{/if}\r\n	        {if $order.clientaddress.nip != \'\'}{trans}TXT_NIP{/trans} : {$order.clientaddress.nip} <br>{/if}\r\n			{trans}TXT_FIRSTNAME{/trans} : {$order.clientaddress.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$order.clientaddress.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}<br>\r\n	      	{trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}<br>\r\n	      	{trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br/>\r\n			{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}<br>\r\n	      	{trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}<br>\r\n	      	{trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n<tr>\r\n<td>\r\n<font color=\"#f6b900\"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br/>\r\n<p>{$order.customeropinion}</p>\r\n</td>\r\n</tr>\r\n',1,'2011-05-29 18:20:31',1,'2011-05-29 20:20:31',NULL,NULL,1,1,1,NULL,NULL),(16,'Potwierdzenie zmiany statusu zamówienia klienta',16,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_VIEW_ORDER_HISTORY{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n		        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: {$orderhistory.firstname} {$orderhistory.surname}</b></font><br/>\r\n				{trans}TXT_COMMENT{/trans} : {$orderhistory.content} <br> \r\n				{trans}TXT_STATUS{/trans} : {$orderhistory.orderstatusname} <br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:00:44',1,'2011-03-09 23:00:44',NULL,NULL,1,1,1,NULL,NULL),(17,'Kopia złożenia zamówienia klienta wysłana do administratora sklepu',17,'','      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n        		<font color=\"#f6b900\"><b>{trans}TXT_CUSTOMER{/trans}: {$order.clientdata.firstname} {$order.clientdata.surname} </b></font><br/>\r\n				{trans}TXT_EMAIL_ORDER_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n        	<font color=\"#f6b900\"><b>{trans}TXT_ORDER{/trans}: </b></font><br/>\r\n			{trans}TXT_COST_OF_DELIVERY{/trans} : {$order.dispatchmethod.dispatchmethodcost} {trans}TXT_CURRENCY{/trans}<br>\r\n			{trans}TXT_METHOD_OF_DELIVERY{/trans} : {$order.dispatchmethod.dispatchmethodname}<br>\r\n	      	{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n				       <tr>\r\n				      	<td>\r\n				      	<font color=\"#f6b900\"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br/>\r\n						<table>\r\n							<thead>\r\n								<tr>\r\n									<th class=\"name\">{trans}TXT_PRODUCT_NAME{/trans}:</th>\r\n									<th class=\"price\">{trans}TXT_PRODUCT_PRICE{/trans}:</th>\r\n									<th class=\"quantity\">{trans}TXT_QUANTITY{/trans}:</th>\r\n									<th class=\"subtotal\">{trans}TXT_VALUE{/trans}:</th>\r\n									<th class=\"ean\">{trans}TXT_EAN{/trans}:</th>\r\n								</tr>\r\n							</thead>\r\n							<tbody>\r\n							{foreach name=outer item=product from=$order.cart}\r\n								{if isset($product.name)}\r\n								<tr>\r\n									<th>{$product.name}</th>\r\n							      	<td> {$product.newprice} {trans}TXT_CURRENCY{/trans}</td>\r\n							      	<td> {$product.qty} {trans}TXT_QTY{/trans}</td>\r\n							      	<td>{$product.qtyprice}  {trans}TXT_CURRENCY{/trans}</td>\r\n					      			<td>{$product.ean}  </td>\r\n						        </tr> \r\n						       	{/if}\r\n\r\n								{foreach name=inner item=attributes from=$product.attributes}\r\n									{if isset($attributes.name)}\r\n									<tr>\r\n										<th>{$attributes.name}</th>\r\n								      	<td> {$attributes.newprice} {trans}TXT_CURRENCY{/trans}</td>\r\n								      	<td> {$attributes.qty} {trans}TXT_QTY{/trans}</td>\r\n								      	<td>{$attributes.qtyprice}  {trans}TXT_CURRENCY{/trans}</td>\r\n					      				<td>{$attributes.ean}  </td>\r\n							        </tr> \r\n					         		{/if}\r\n								{/foreach}\r\n					       	{/foreach} 	\r\n							</tbody>\r\n						</table>\r\n				      	</td>\r\n				      </tr>\r\n	  <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_PRICE{/trans}: </b></font><br/>\r\n			{trans}TXT_GLOBAL_PRICE{/trans} : {$order.globalPrice} {trans}TXT_CURRENCY{/trans}<br>\r\n			{trans}TXT_PRICE_WITH_DISPATCHMETHOD{/trans} : {$order.priceWithDispatchMethod} {trans}TXT_CURRENCY{/trans}<br>\r\n			{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>			      \r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n                {if $order.clientdata.companyname != \'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientdata.companyname} <br>{/if}\r\n	        {if $order.clientdata.nip != \'\'}{trans}TXT_NIP{/trans} : {$order.clientdata.nip} <br>{/if}\r\n			{trans}TXT_FIRSTNAME{/trans} : {$order.clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$order.clientdata.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$order.clientdata.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$order.clientdata.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$order.clientdata.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$order.clientdata.streetno}<br>\r\n	      	{trans}TXT_PLACENO{/trans} : {$order.clientdata.placeno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$order.clientdata.phone}<br>\r\n	      	{trans}TXT_EMAIL{/trans} : {$order.clientdata.email}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br/>\r\n			{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}<br>\r\n	      	{trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}<br>\r\n	      	{trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-04-29 19:00:41',1,'2011-04-29 21:00:41',NULL,NULL,1,1,1,NULL,NULL),(18,'Poleć znajomemu',18,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				<strong>{$client.firstname} {$client.surname} </strong>{trans}TXT_RECOMMEND_THIS_SITE{/trans}\r\n			</p>\r\n        </td>\r\n       </tr>\r\n       <tr>  \r\n        <td>\r\n        	<p>\r\n				{$addressURL}\r\n			</p>\r\n        </td>\r\n      </tr>',1,'2011-03-09 22:01:08',1,'2011-03-09 23:01:08',NULL,NULL,1,1,1,NULL,NULL),(19,'Żagiel- potwierdzenie złożenia wniosku o kredyt',20,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        <p>\r\n	    		<font color=\"#f6b900\"><b>{trans}TXT_CUSTOMER{/trans}: {$clientOrder.firstname} {$clientOrder.surname} </b></font>\r\n	    		<br/><br/>\r\n	        	Zarejestrowany został wniosek o kredyt ratalny w systemie Żagiel. <br/><br/>\r\n	        	Administrator sklepu potwierdzi rezerwację towaru w możliwie najszybszym czasie.\r\n	        	Proszę czekać na kontakt z konsultantem systemu ratalnego Żagiel.\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      <td height=\"20\" align=\"left\" valign=\"top\" style=\"text-align:justify\">Pamiętaj numer zamówienia: <strong> {$idorder} </strong></td>\r\n      </tr>',1,'2011-03-09 22:01:18',1,'2011-03-09 23:01:18',NULL,NULL,1,1,1,NULL,NULL),(20,'Żagiel- rezygnacja ze złożenia wniosku o kredyt',21,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        <p>\r\n	        	Zrezygnowałeś ze złożenia wniosku w systemie ratalnym Żagiel. Aby dokończyć zamówienie,\r\n	        	skontaktuj się z administratorem sklepu w celu wybrania innej metody płatności. \r\n	        	Twój numer zamówienia: <strong> {$idorder} </strong>\r\n			</p>\r\n        </td>\r\n      </tr>',1,'2011-03-09 22:01:28',1,'2011-03-09 23:01:28',NULL,NULL,1,1,1,NULL,NULL);
/*!40000 ALTER TABLE `transmail` ENABLE KEYS */;
DROP TABLE IF EXISTS `transmailheader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmailheader` (
  `idtransmailheader` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `contenthtml` varchar(5000) NOT NULL,
  `contenttxt` varchar(5000) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtransmailheader`),
  KEY `FK_transmailheader_addid` (`addid`),
  KEY `FK_transmailheader_editid` (`editid`),
  KEY `FK_transmailheader_viewid` (`viewid`),
  CONSTRAINT `FK_transmailheader_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmailheader_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmailheader_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `transmailheader` DISABLE KEYS */;
INSERT INTO `transmailheader` VALUES (1,'Domyślny szablon nagłówka','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>{trans}MAIL_TITLE{/trans}</title>\r\n	<style type=\"text/css\">\r\n	{literal}\r\n		body,td,th {\r\n			font-size: 11px;\r\n			color: #575656;\r\n			font-family: Arial;\r\n		}\r\n		body {\r\n			margin-left: 0px;\r\n			margin-top: 0px;\r\n			margin-right: 0px;\r\n			margin-bottom: 0px;\r\n		}\r\n		a:link {\r\n			color: #969696;\r\n			text-decoration: none;\r\n		}\r\n		a:visited {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n		a:hover {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n		a:active {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n	{/literal}\r\n	</style>\r\n</head>\r\n\r\n<body>\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td>&nbsp;</td>\r\n    <td width=\"500\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n      <tr>\r\n        <td height=\"96\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n          <tr>\r\n            <td height=\"96\" align=\"left\" valign=\"middle\"><img src=\'cid:logo\' alt=\"Logo Sklep Internetowy\"/></td>\r\n            <td width=\"60\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=mainside}\" target=\"_blank\">{trans}TXT_MAINSIDE{/trans}</a></font></td>\r\n            <td width=\"79\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=clientsettings}\" target=\"_blank\">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>\r\n            <td width=\"75\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=contact}\" target=\"_blank\">{trans}TXT_CONTACT{/trans}</a></font></td>\r\n          </tr>\r\n        </table></td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"23\" align=\"left\" valign=\"top\"><hr noshade=\"noshade\" size=\"1\" color=\"#e8e8e8\"/></td>\r\n      </tr>\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+2\"><b>{$SHOP_NAME}</b></font>\r\n   		<br/>{trans}TXT_HEADER_INFO{/trans}\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"38\" align=\"left\" valign=\"top\">&nbsp;</td>\r\n      </tr>','{trans}TXT_WELCOME{/trans}',1,'2011-02-05 10:07:31',1,NULL,NULL);
/*!40000 ALTER TABLE `transmailheader` ENABLE KEYS */;
DROP TABLE IF EXISTS `transmailfooter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmailfooter` (
  `idtransmailfooter` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `contenthtml` varchar(5000) NOT NULL,
  `contenttxt` varchar(5000) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtransmailfooter`),
  KEY `FK_transmailfooter_addid` (`addid`),
  KEY `FK_transmailfooter_editid` (`editid`),
  KEY `FK_transmailfooter_viewid` (`viewid`),
  CONSTRAINT `FK_transmailfooter_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmailfooter_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_transmailfooter_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `transmailfooter` DISABLE KEYS */;
INSERT INTO `transmailfooter` VALUES (1,'Domyślny szablon stopki',' <tr>\r\n        <td height=\"20\" align=\"left\" valign=\"top\" style=\"text-align:justify\">&nbsp;</td>\r\n      </tr>\r\n    </table>\r\n   </td>\r\n    <td>&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td width=\"500\" height=\"10\" align=\"left\" valign=\"top\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n    <td width=\"500\" height=\"70\" align=\"center\" valign=\"middle\" bgcolor=\"#2c2c2c\">\r\n    <font color=\"#b1b1b1\">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>','{$SHOPNAME}',1,'2011-01-13 18:31:44',NULL,NULL,NULL);
/*!40000 ALTER TABLE `transmailfooter` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
