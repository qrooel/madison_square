/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `answervolunteered`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answervolunteered` (
  `idanswervolunteered` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollanswersid` int(10) unsigned NOT NULL,
  `clientid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned DEFAULT NULL,
  `pollid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idanswervolunteered`),
  KEY `FK_answervolunteered_pollanswersid` (`pollanswersid`),
  KEY `FK_answervolunteered_clientid` (`clientid`),
  KEY `FK_answervolunteered_viewid` (`viewid`),
  KEY `FK_answervolunteered_pollid` (`pollid`),
  CONSTRAINT `FK_answervolunteered_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_answervolunteered_pollanswersid` FOREIGN KEY (`pollanswersid`) REFERENCES `pollanswers` (`idpollanswers`),
  CONSTRAINT `FK_answervolunteered_pollid` FOREIGN KEY (`pollid`) REFERENCES `poll` (`idpoll`),
  CONSTRAINT `FK_answervolunteered_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `assigntogroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assigntogroup` (
  `idassigntogroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` decimal(16,2) DEFAULT NULL,
  `to` decimal(16,2) DEFAULT NULL,
  `clientgroupid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idassigntogroup`),
  KEY `FK_assigntogroup_viewid` (`viewid`),
  KEY `FK_assigntogroup_addid` (`addid`),
  KEY `FK_assigntogroup_editid` (`editid`),
  KEY `FK_assigntogroup_clientgroupid` (`clientgroupid`),
  CONSTRAINT `FK_assigntogroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_assigntogroup_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_assigntogroup_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_assigntogroup_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `assigntogroup` (`idassigntogroup`, `from`, `to`, `clientgroupid`, `viewid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (272,0.00,0.00,10,3,1,'2012-09-06 23:16:11',NULL,NULL);
DROP TABLE IF EXISTS `attributegroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributegroup` (
  `idattributegroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attributegroupnameid` int(10) unsigned NOT NULL,
  `attributeproductid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idattributegroup`),
  KEY `FK_attributegroup_attributegroupnameid` (`attributegroupnameid`),
  KEY `FK_attributegroup_attributeproductid` (`attributeproductid`),
  KEY `FK_attributegroup_addid` (`addid`),
  KEY `FK_attributegroup_editid` (`editid`),
  CONSTRAINT `FK_attributegroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_attributegroup_attributegroupnameid` FOREIGN KEY (`attributegroupnameid`) REFERENCES `attributegroupname` (`idattributegroupname`),
  CONSTRAINT `FK_attributegroup_attributeproductid` FOREIGN KEY (`attributeproductid`) REFERENCES `attributeproduct` (`idattributeproduct`),
  CONSTRAINT `FK_attributegroup_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `attributegroupname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributegroupname` (
  `idattributegroupname` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idattributegroupname`),
  UNIQUE KEY `UNIQUE_name` (`name`),
  KEY `FK_attributegroupname_addid` (`addid`),
  KEY `FK_attributegroupname_editid` (`editid`),
  CONSTRAINT `FK_attributegroupname_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_attributegroupname_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `attributeproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributeproduct` (
  `idattributeproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idattributeproduct`),
  KEY `FK_attributeproduct_addid` (`addid`),
  KEY `FK_attributeproduct_editid` (`editid`),
  CONSTRAINT `FK_attributeproduct_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_attributeproduct_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `attributeproductvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributeproductvalue` (
  `idattributeproductvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `attributeproductid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idattributeproductvalue`),
  KEY `FK_attributeproductvalue_addid` (`addid`),
  KEY `FK_attributeproductvalue_editid` (`editid`),
  KEY `FK_attributeproductvalue_attributeproductid` (`attributeproductid`),
  CONSTRAINT `FK_attributeproductvalue_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_attributeproductvalue_attributeproductid` FOREIGN KEY (`attributeproductid`) REFERENCES `attributeproduct` (`idattributeproduct`),
  CONSTRAINT `FK_attributeproductvalue_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `idcategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `photoid` int(10) unsigned DEFAULT NULL,
  `discount` decimal(15,2) unsigned DEFAULT '0.00',
  `distinction` tinyint(3) unsigned DEFAULT '0',
  `categoryid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `enable` int(10) unsigned NOT NULL DEFAULT '1',
  `migrationid` int(11) DEFAULT NULL,
  `migrationparentid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcategory`),
  KEY `FK_category_photoid` (`photoid`),
  KEY `FK_category_addid` (`addid`),
  KEY `FK_category_editid` (`editid`),
  KEY `FK_category_categoryid` (`categoryid`),
  CONSTRAINT `FK_category_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_category_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_category_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_category_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `categoryattributeproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoryattributeproduct` (
  `idcategoryattributeproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(10) unsigned NOT NULL,
  `attributeproductid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `attributegroupnameid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idcategoryattributeproduct`),
  KEY `UNIQUE_categoryattributeproduct_categoryid_attributeproductid` (`categoryid`,`attributeproductid`),
  KEY `FK_categoryattributeproduct_attributeproductid` (`attributeproductid`),
  KEY `FK_categoryattributeproduct_addid` (`addid`),
  KEY `FK_categoryattributeproduct_editid` (`editid`),
  KEY `FK_categoryattributeproduct_attributegroupnameid` (`attributegroupnameid`),
  CONSTRAINT `FK_categoryattributeproduct_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_categoryattributeproduct_attributegroupnameid` FOREIGN KEY (`attributegroupnameid`) REFERENCES `attributegroupname` (`idattributegroupname`),
  CONSTRAINT `FK_categoryattributeproduct_attributeproductid` FOREIGN KEY (`attributeproductid`) REFERENCES `attributeproduct` (`idattributeproduct`),
  CONSTRAINT `FK_categoryattributeproduct_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_categoryattributeproduct_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `categorypath`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorypath` (
  `idcategorypath` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(10) unsigned NOT NULL,
  `ancestorcategoryid` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idcategorypath`),
  KEY `FK_categorypath_categoryid` (`categoryid`),
  KEY `FK_categorypath_ancestorcategoryid` (`ancestorcategoryid`),
  KEY `FK_categorypath_order` (`order`),
  CONSTRAINT `FK_categorypath_ancestorcategoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_categorypath_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `categorytranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorytranslation` (
  `idcategorytranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `shortdescription` varchar(3000) DEFAULT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `categoryid` int(10) unsigned DEFAULT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `seo` varchar(128) DEFAULT NULL,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` text,
  `keyword_description` text,
  PRIMARY KEY (`idcategorytranslation`),
  UNIQUE KEY `UNIQUE_categorytranslation_name_categoryid` (`name`,`categoryid`,`languageid`),
  KEY `FK_categorytranslation_categoryid` (`categoryid`),
  KEY `FK_categorytranslation_languageid` (`languageid`),
  CONSTRAINT `FK_categorytranslation_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_categorytranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `idclient` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `disable` int(10) unsigned NOT NULL DEFAULT '1',
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `facebookid` varchar(255) DEFAULT NULL,
  `activelink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idclient`),
  UNIQUE KEY `UNIQUE_client_login_view` (`login`,`viewid`),
  KEY `FK_client_viewid` (`viewid`),
  CONSTRAINT `FK_client_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientaddress` (
  `idclientaddress` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `street` blob NOT NULL,
  `streetno` blob NOT NULL,
  `placeno` blob,
  `postcode` blob NOT NULL,
  `companyname` blob,
  `firstname` blob,
  `surname` blob,
  `clientid` int(10) unsigned NOT NULL,
  `regon` blob,
  `nip` blob,
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `placename` blob NOT NULL,
  `main` int(10) unsigned NOT NULL DEFAULT '1',
  `countryid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idclientaddress`),
  UNIQUE KEY `UNIQUE_clientaddress_clientid_clientaddresstypeid` (`clientid`,`main`) USING BTREE,
  KEY `FK_clientaddress_clientid` (`clientid`),
  KEY `FK_clientaddress_addid` (`addid`),
  KEY `FK_clientaddress_editid` (`editid`),
  KEY `FK_clientaddress_countryid` (`countryid`),
  CONSTRAINT `FK_clientaddress_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientaddress_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_clientaddress_countryid` FOREIGN KEY (`countryid`) REFERENCES `country` (`idcountry`),
  CONSTRAINT `FK_clientaddress_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientdata` (
  `idclientdata` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` blob NOT NULL,
  `surname` blob NOT NULL,
  `email` blob NOT NULL,
  `description` blob,
  `phone` blob NOT NULL,
  `newsletter` int(10) unsigned DEFAULT '1',
  `clientgroupid` int(10) unsigned DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT '0.00',
  `clientid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idclientdata`),
  UNIQUE KEY `UNIQUE_clientdata_clientid` (`clientid`),
  KEY `FK_clientdata_addid` (`addid`),
  KEY `FK_clientdata_editid` (`editid`),
  KEY `FK_clientdata_clientgroupid` (`clientgroupid`),
  CONSTRAINT `FK_clientdata_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientdata_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_clientdata_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_clientdata_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientgroup` (
  `idclientgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idclientgroup`),
  KEY `FK_clientgroup_addid` (`addid`),
  KEY `FK_clientgroup_editid` (`editid`),
  CONSTRAINT `FK_clientgroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientgroup_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `clientgroup` (`idclientgroup`, `addid`, `adddate`, `editid`, `editdate`) VALUES (10,1,'2010-12-06 08:45:15',1,NULL),(14,1,'2010-09-23 11:06:18',NULL,NULL),(116,1,'2010-09-23 11:06:18',NULL,NULL);
DROP TABLE IF EXISTS `clientgroupnewsletterhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientgroupnewsletterhistory` (
  `idclientgroupnewsletterhistory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientgroupid` int(10) unsigned DEFAULT NULL,
  `newsletterid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idclientgroupnewsletterhistory`),
  UNIQUE KEY `UNIQUE_clientgroupnewsletterhistory_clientgroupid_newsletterid` (`clientgroupid`,`newsletterid`),
  KEY `FK_clientgroupnewsletterhistory_addid` (`addid`),
  KEY `FK_clientgroupnewsletterhistory_editid` (`editid`),
  KEY `FK_clientgroupnewsletterhistory_newsletterid` (`newsletterid`),
  CONSTRAINT `FK_clientgroupnewsletterhistory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientgroupnewsletterhistory_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_clientgroupnewsletterhistory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientgroupnewsletterhistory_newsletterid` FOREIGN KEY (`newsletterid`) REFERENCES `newsletter` (`idnewsletter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientgrouptranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientgrouptranslation` (
  `idclientgrouptranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `clientgroupid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idclientgrouptranslation`),
  UNIQUE KEY `UNIQUE_clientgrouptranslation_languageid_name` (`languageid`,`name`),
  KEY `FK_clientgrouptranslation_languageid` (`languageid`),
  KEY `FK_clientgrouptranslation_clientgroupid` (`clientgroupid`),
  CONSTRAINT `FK_clientgrouptranslation_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_clientgrouptranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `clientgrouptranslation` (`idclientgrouptranslation`, `name`, `clientgroupid`, `languageid`) VALUES (33,'Grupa srebrna',116,1),(41,'Grupa złota',14,1),(49,'Grupa brązowa',10,1);
DROP TABLE IF EXISTS `clienthistorylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clienthistorylog` (
  `idclienthistorylog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientid` int(10) unsigned DEFAULT NULL,
  `URL` varchar(225) NOT NULL,
  `sessionid` varchar(225) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idclienthistorylog`),
  KEY `FK_clienthistorylog_viewid` (`viewid`),
  CONSTRAINT `FK_clienthistorylog_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientnewsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientnewsletter` (
  `idclientnewsletter` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editdate` datetime DEFAULT NULL,
  `active` int(10) unsigned NOT NULL DEFAULT '0',
  `viewid` int(10) unsigned DEFAULT NULL,
  `activelink` varchar(50) DEFAULT NULL,
  `inactivelink` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idclientnewsletter`),
  UNIQUE KEY `UNIQUE_clientnewsletter_email` (`email`),
  KEY `FK_clientnewsletter_viewid` (`viewid`),
  CONSTRAINT `FK_clientnewsletter_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientnewsletterhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientnewsletterhistory` (
  `idclientnewsletterhistory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientnewsletterid` int(10) unsigned DEFAULT NULL,
  `newsletterid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idclientnewsletterhistory`),
  KEY `FK_clientnewsletterhistory_newsletterid` (`newsletterid`),
  KEY `FK_clientnewsletterhistory_clientnewsletterid` (`clientnewsletterid`),
  KEY `FK_clientnewsletterhistory_addid` (`addid`),
  KEY `FK_clientnewsletterhistory_editid` (`editid`),
  CONSTRAINT `FK_clientnewsletterhistory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientnewsletterhistory_clientnewsletterid` FOREIGN KEY (`clientnewsletterid`) REFERENCES `clientnewsletter` (`idclientnewsletter`),
  CONSTRAINT `FK_clientnewsletterhistory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientnewsletterhistory_newsletterid` FOREIGN KEY (`newsletterid`) REFERENCES `newsletter` (`idnewsletter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `clientnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientnotes` (
  `idclientnotes` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clientid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idclientnotes`),
  KEY `FK_clientnotes_clientid` (`clientid`),
  KEY `FK_clientnotes_addid` (`addid`),
  CONSTRAINT `FK_clientnotes_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_clientnotes_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `idcontact` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `publish` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`idcontact`),
  KEY `FK_contact_editid` (`editid`),
  KEY `FK_contact_addid` (`addid`),
  CONSTRAINT `FK_contact_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_contact_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `contacttranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacttranslation` (
  `idcontacttranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `street` varchar(45) DEFAULT NULL,
  `streetno` varchar(10) DEFAULT NULL,
  `placeno` varchar(10) DEFAULT NULL,
  `placename` varchar(45) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `contactid` int(10) unsigned DEFAULT NULL,
  `countryid` int(10) unsigned DEFAULT NULL,
  `businesshours` text,
  PRIMARY KEY (`idcontacttranslation`),
  UNIQUE KEY `UNIQUE_contact_name_languageid` (`name`,`languageid`),
  KEY `FK_contacttranslation_languageid` (`languageid`),
  KEY `FK_contacttranslation_contactid` (`contactid`),
  KEY `FK_contacttranslation_countryid` (`countryid`),
  CONSTRAINT `FK_contacttranslation_contactid` FOREIGN KEY (`contactid`) REFERENCES `contact` (`idcontact`),
  CONSTRAINT `FK_contacttranslation_countryid` FOREIGN KEY (`countryid`) REFERENCES `country` (`idcountry`),
  CONSTRAINT `FK_contacttranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `contactview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactview` (
  `idcontactview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contactid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcontactview`),
  KEY `FK_contactview_contactid` (`contactid`),
  KEY `FK_contactview_viewid` (`viewid`),
  KEY `FK_contactview_addid` (`addid`),
  CONSTRAINT `FK_contactview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_contactview_contactid` FOREIGN KEY (`contactid`) REFERENCES `contact` (`idcontact`),
  CONSTRAINT `FK_contactview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `contentcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentcategory` (
  `idcontentcategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentcategoryid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` varchar(45) DEFAULT NULL,
  `hierarchy` int(10) unsigned DEFAULT '0',
  `footer` int(10) unsigned NOT NULL DEFAULT '1',
  `header` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idcontentcategory`),
  KEY `FK_contentcategory_addid` (`addid`),
  KEY `FK_contentcategory_editid` (`editid`),
  KEY `FK_contentcategory_contentcategoryid` (`contentcategoryid`),
  CONSTRAINT `FK_contentcategory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_contentcategory_contentcategoryid` FOREIGN KEY (`contentcategoryid`) REFERENCES `contentcategory` (`idcontentcategory`),
  CONSTRAINT `FK_contentcategory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `contentcategory` (`idcontentcategory`, `contentcategoryid`, `addid`, `adddate`, `editid`, `editdate`, `hierarchy`, `footer`, `header`) VALUES (1,NULL,1,'2011-08-07 21:07:55',1,NULL,0,0,1),(2,NULL,1,'2011-04-21 20:05:57',1,NULL,0,1,1),(3,NULL,1,'2011-10-28 22:43:16',1,NULL,0,1,1),(4,1,1,'2010-11-14 19:16:19',1,NULL,0,1,1),(5,1,1,'2011-10-24 22:02:02',1,NULL,0,1,1),(6,1,1,'2011-10-28 22:43:27',1,NULL,0,1,1),(7,2,1,'2011-04-21 20:04:18',1,NULL,0,1,1),(10,3,1,'2011-04-15 21:24:17',NULL,NULL,0,1,1),(11,3,1,'2011-10-28 22:43:11',1,NULL,0,1,1),(12,3,1,'2011-07-31 12:32:32',1,NULL,0,1,1),(15,2,1,'2012-01-20 11:37:10',1,NULL,0,1,0),(16,2,1,'2011-04-21 19:59:56',1,NULL,0,1,1);
DROP TABLE IF EXISTS `contentcategorytranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentcategorytranslation` (
  `idcontentcategorytranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `contentcategoryid` int(10) unsigned DEFAULT NULL,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `keyword_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idcontentcategorytranslation`),
  KEY `FK_contentcategorytranslation_languageid` (`languageid`),
  KEY `FK_contentcategorytranslation_contentcategoryid` (`contentcategoryid`),
  CONSTRAINT `FK_contentcategorytranslation_contentcategoryid` FOREIGN KEY (`contentcategoryid`) REFERENCES `contentcategory` (`idcontentcategory`),
  CONSTRAINT `FK_contentcategorytranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `contentcategorytranslation` (`idcontentcategorytranslation`, `name`, `languageid`, `contentcategoryid`, `keyword_title`, `keyword`, `keyword_description`) VALUES (171,'Nagrody i wyróżnienia',1,10,NULL,NULL,NULL),(193,'Poradniki',1,7,NULL,NULL,NULL),(201,'Informacje',1,1,NULL,NULL,NULL),(211,'Dostępność produktów',1,5,'','',''),(212,'Praca',1,12,'','',''),(213,'Czas realizacji',1,4,'','',''),(214,'Informacje o firmie',1,11,'','',''),(215,'O firmie',1,3,'','',''),(216,'Bezpieczeństwo',1,16,'','',''),(217,'Koszt dostawy',1,6,'','',''),(218,'Pomoc',1,2,'','',''),(219,'Regulamin',1,15,'','','');
DROP TABLE IF EXISTS `contentcategoryview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contentcategoryview` (
  `idcontentcategoryview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentcategoryid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcontentcategoryview`),
  KEY `FK_contentcategoryview_contentcategoryid` (`contentcategoryid`),
  KEY `FK_contentcategoryview_viewid` (`viewid`),
  KEY `FK_contentcategoryview_addid` (`addid`),
  CONSTRAINT `FK_contentcategoryview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_contentcategoryview_contentcategoryid` FOREIGN KEY (`contentcategoryid`) REFERENCES `contentcategory` (`idcontentcategory`),
  CONSTRAINT `FK_contentcategoryview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `contentcategoryview` (`idcontentcategoryview`, `contentcategoryid`, `viewid`, `addid`, `adddate`) VALUES (44,5,3,1,'2011-10-28 22:42:56'),(45,12,3,1,'2011-10-28 22:43:01'),(46,4,3,1,'2011-10-28 22:43:06'),(47,11,3,1,'2011-10-28 22:43:11'),(48,3,3,1,'2011-10-28 22:43:16'),(49,16,3,1,'2011-10-28 22:43:22'),(50,6,3,1,'2011-10-28 22:43:27'),(51,2,3,1,'2011-10-28 22:44:31'),(52,15,3,1,'2012-01-20 11:37:10');
DROP TABLE IF EXISTS `controller`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controller` (
  `idcontroller` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `version` varchar(45) NOT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `enable` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `mode` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcontroller`),
  UNIQUE KEY `UNIQUE_controller_name_mode` (`name`,`mode`),
  KEY `FK_controller_addid` (`addid`),
  KEY `FK_controller_editid` (`editid`),
  CONSTRAINT `FK_controller_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_controller_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=908 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `controller` (`idcontroller`, `name`, `version`, `description`, `enable`, `addid`, `adddate`, `editid`, `editdate`, `mode`) VALUES (1,'users','1','TXT_CONTROLLER_USERS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(2,'mainside','1','TXT_CONTROLLER_MAINSIDE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(3,'client','1','TXT_CONTROLLER_CLIENT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(4,'product','1','TXT_CONTROLLER_PRODUCT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(5,'category','1','TXT_CONTROLLER_CATEGORY',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(6,'news','1','TXT_CONTROLLER_NEWS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(9,'groups','1','TXT_CONTROLLER_GROUPS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(29,'clientgroup','1','TXT_CONTROLLER_CLIENTGROUP',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(30,'paymentmethod','1','TXT_CONTROLLER_PAYMENTMETHOD',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(31,'dispatchmethod','1','TXT_CONTROLLER_DISPATCHMETHOD',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(35,'attributeproduct','1','TXT_CONTROLLER_ATTRIBUTEPRODUCT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(36,'producer','1','TXT_CONTROLLER_PRODUCER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(37,'deliverer','1','TXT_CONTROLLER_DELIVERER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(38,'orderstatus','1','TXT_CONTROLLER_ORDERSTATUS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(39,'vat','1','TXT_CONTROLLER_VAT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(40,'productcombination','1','TXT_CONTROLLER_PRODUCTCOMBINATION',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(42,'language','1','TXT_CONTROLLER_LANGUAGE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(43,'translation','1','TXT_CONTROLLER_TRANSLATION',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(44,'userhistorylog','1','TXT_CONTROLLER_USERHISTORYLOG',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(45,'globalsettings','1','TXT_CONTROLLER_GLOBALSETTINGS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(46,'order','1','TXT_CONTROLLER_ORDER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(47,'productstatus','1','TXT_CONTROLLER_PRODUCTSTATUS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(48,'crosssell','1','TXT_CONTROLLER_CROSSSELL',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(49,'upsell','1','TXT_CONTROLLER_UPSELL',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(50,'similarproduct','1','TXT_CONTROLLER_SIMILARPRODUCT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(51,'countrieslist','1','TXT_CONTROLLER_COUNTRIESLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(53,'range','1','TXT_CONTROLLER_RANGE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(54,'rangetype','1','TXT_CONTROLLER_RANGETYPE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(55,'productrange','1','TXT_CONTROLLER_PRODUCTRANGE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(56,'staticblocks','1','TXT_CONTROLLER_STATICBLOCKS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(57,'contentcategory','1','TXT_CONTROLLER_CONTENTCATEGORY',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(58,'productpromotion','1','TXT_CONTROLLER_PRODUCTPROMOTION',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(59,'productnews','1','TXT_CONTROLLER_PRODUCTNEWS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(60,'files','1','TXT_CONTROLLER_FILES',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(61,'contact','1','TXT_CONTROLLER_CONTACT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(62,'integration','1','TXT_CONTROLLER_INTEGRATION',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(63,'tags','1','TXT_CONTROLLER_TAGS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(64,'statssales','1','TXT_CONTROLLER_STATSSALES',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(65,'statsclients','1','TXT_CONTROLLER_STATSCLIENTS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(66,'statsproducts','1','TXT_CONTROLLER_STATSPRODUCTS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(67,'mostsearch','1','TXT_CONTROLLER_MOSTSEARCH',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(69,'poll','1','TXT_CONTROLLER_POLL',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(70,'wishlist','1','TXT_CONTROLLER_WISHLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(71,'clientnewsletter','1','TXT_CONTROLLER_CLIENTNEWSLETTER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(72,'newsletter','1 ','TXT_CONTROLLER_NEWSLETTER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(73,'unitmeasure','1','TXT_CONTROLLER_UNITMEASURE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(74,'empty','1','TXT_CONTROLLER_EMPTY',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(76,'attributegroup','1','TXT_CONTROLLER_ATTRIBUTEGROUP',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(77,'promotionrule','1','TXT_CONTROLLER_PROMOTIONRULE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(81,'invoice','1','TXT_CONTROLLER_INVOICE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(82,'updater','1','TXT_CONTROLLER_UPDATER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(83,'checkpoint','1','TXT_CONTROLLER_CHECKPOINT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(86,'recipientlist','1','TXT_CONTROLLER_RECIPIENTLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(88,'clienthistorylog','1','TXT_CONTROLLER_CLIENTHISTORYLOG',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(89,'mostviewed','1','TXT_CONTROLLER_MOSTVIEWED',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(90,'gekolab','1','TXT_CONTROLLER_GEKOLAB',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(91,'buyalso','1','TXT_CONTROLLER_BUYALSO',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(92,'sendnewsletter','1','TXT_CONTROLLER_SENDNEWSLETTER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(93,'orderstatusgroups','1','TXT_CONTROLLER_ORDERSTATUSGROUPS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(94,'invoicetype','1','TXT_CONTROLLER_INVOICETYPE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(103,'store','1','TXT_CONTROLLER_STORE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(104,'view','1','TXT_CONTROLLER_VIEW',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(105,'layoutbox','1','TXT_CONTROLLER_LAYOUTBOX',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(106,'layoutboxscheme','1','TXT_CONTROLLER_LAYOUTBOXSCHEME',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(107,'pagescheme','1','TXT_CONTROLLER_PAGESCHEME',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(108,'subpagelayout','1','TXT_CONTROLLER_SUBPAGELAYOUT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(109,'invoiceexport','1','TXT_CONTROLLER_INVOICEEXPORT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(110,'cssgenerator','1','TXT_CONTROLLER_CSSGENERATOR',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(111,'period','1','TXT_CONTROLLER_PERIOD',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(112,'rulescart','1','TXT_CONTROLLER_RULESCART',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(113,'rulescatalog','1','TXT_CONTROLLER_RULESCATALOG',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(805,'cart','1','TXT_CONTROLLER_CART',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(806,'categorylist','1','TXT_CONTROLLER_CATEGORYLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(808,'client','1','TXT_CONTROLLER_CLIENT',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(809,'clientaddress','1','TXT_CONTROLLER_CLIENTADDRESS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(810,'clientlogin','1','TXT_CONTROLLER_CLIENTLOGIN',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(811,'clientorder','1','TXT_CONTROLLER_CLIENTORDER',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(812,'clientsettings','1','TXT_CONTROLLER_CLIENTSETTINGS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(813,'confirmation','1','TXT_CONTROLLER_CONFIRMATION',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(814,'contact','1','TXT_CONTROLLER_CONTACT',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(816,'error','1','TXT_CONTROLLER_ERROR',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(819,'forgotlogin','1','TXT_CONTROLLER_FORGOTLOGIN',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(820,'forgotpassword','1','TXT_CONTROLLER_FORGOTPASSWORD',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(824,'install','1','TXT_CONTROLLER_INSTALL',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(825,'integration','1','TXT_CONTROLLER_INTEGRATION',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(826,'invoice','1','TXT_CONTROLLER_INVOICE',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(828,'login','1','TXT_CONTROLLER_LOGIN',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(829,'mainside','1','TXT_CONTROLLER_MAINSIDE',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(831,'mostsearch','1','TXT_CONTROLLER_MOSTSEARCH',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(832,'news','1','TXT_CONTROLLER_NEWS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(833,'newsletter','1','TXT_CONTROLLER_NEWSLETTER',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(836,'order','1','TXT_CONTROLLER_ORDER',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(837,'payment','1','TXT_CONTROLLER_PAYMENT',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(841,'poll','1','TXT_CONTROLLER_POLL',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(842,'product','1','TXT_CONTROLLER_PRODUCT',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(843,'productcart','1','TXT_CONTROLLER_PRODUCTCART',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(844,'productcombination','1','TXT_CONTROLLER_PRODUCTCOMBINATION',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(845,'productfiltration','1','TXT_CONTROLLER_PRODUCTFILTRATION',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(846,'productlist','1','TXT_CONTROLLER_PRODUCTLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(847,'productnews','1','TXT_CONTROLLER_PRODUCTNEWS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(848,'productpromotion','1','TXT_CONTROLLER_PRODUCTPROMOTION',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(849,'productreview','1','TXT_CONTROLLER_PRODUCTREVIEW',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(850,'productsearch','1','TXT_CONTROLLER_PRODUCTSEARCH',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(851,'producttags','1','TXT_CONTROLLER_PRODUCTTAGS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(853,'registrationcart','1','TXT_CONTROLLER_REGISTRATIONCART',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(854,'searchresults','1','TXT_CONTROLLER_SEARCHRESULTS',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(855,'staticcontent','1','TXT_CONTROLLER_STATICCONTENT',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(856,'wishlist','1','TXT_CONTROLLER_WISHLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(858,'exchange','1','TXT_CONTROLLER_EXCHANGE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(859,'virtualproduct','1','TXT_CONTROLLER_VIRTUALPRODUCT',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(860,'sitemaps','1','TXT_CONTROLLER_SITEMAPS',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(861,'sitemap','1','TXT_CONTROLLER_SITEMAP',1,1,'2010-12-03 18:13:28',NULL,NULL,0),(862,'controllerseo','1','TXT_CONTROLLER_CONTROLLERSEO',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(870,'currencieslist','1','TXT_CONTROLLER_CURRENCIESLIST',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(871,'transmailtemplates','1','TXT_CONTROLLER_TRANSMAILTEMPLATES',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(872,'transmailheader','1','TXT_CONTROLLER_TRANSMAILHEADER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(873,'transmailfooter','1','TXT_CONTROLLER_TRANSMAILFOOTER',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(880,'substitutedservice','1','TXT_CONTROLLER_SUBSTITUTEDSERVICE',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(881,'substitutedservicesend','1','TXT_CONTROLLER_SUBSTITUTEDSERVICESEND',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(885,'substitutedservicetemplates','1','TXT_CONTROLLER_SUBSTITUTEDSERVICETEMPLATES',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(886,'backup','1','TXT_CONTROLLER_BACKUP',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(887,'translationsync','1','TXT_CONTROLLER_TRANSLATIONSYNC',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(890,'spy','1','TXT_CONTROLLER_SPY',1,1,'2010-12-03 18:13:28',NULL,NULL,1),(891,'conditions','1','TXT_TERMS_AND_CONDITIONS_BOX',1,1,'2010-12-11 15:35:05',NULL,NULL,0),(892,'privacy','1','TXT_PRIVACY_POLICY_BOX',1,1,'2010-12-11 15:43:37',NULL,NULL,0),(893,'producerlist','1','TXT_CONTROLLER_PRODUCERLIST',1,1,'2011-07-11 15:35:01',NULL,NULL,0),(894,'action','1','Integracja Action',1,1,'2011-08-14 12:14:50',NULL,NULL,1),(895,'migration','1','Migracja z innych systemów',1,1,'2011-08-15 20:23:48',NULL,NULL,1),(898,'actionquick','1','Integracja Action',1,1,'2011-08-22 11:28:42',NULL,NULL,1),(899,'actioncategory','1','Integracja Action',1,1,'2011-08-22 11:29:15',NULL,NULL,1),(900,'ticket','1','System ticketowy',1,1,'2011-08-22 20:23:12',NULL,NULL,1),(901,'ticketcategory','1','Kategorie zgłoszeń',1,1,'2011-08-22 20:23:12',NULL,NULL,1),(902,'ticketstatus','1','Statusy zgłoszeń',1,1,'2011-08-22 20:23:12',NULL,NULL,1),(903,'ticketreply','1','Szablony odpowiedzi',1,1,'2011-08-22 20:23:12',NULL,NULL,1),(904,'coupons','1','Kupony rabatowe',1,1,'2011-09-27 09:22:33',NULL,NULL,1),(905,'couponsregistry','1','Rejestr kuponów',1,1,'2011-09-27 09:22:40',NULL,NULL,1),(906,'staticattribute','1','Cechy statyczne',1,1,'2011-11-21 11:12:23',NULL,NULL,1),(907,'inpost','1','Integracja Inpost',1,1,'2012-03-08 13:54:50',NULL,NULL,1);
DROP TABLE IF EXISTS `controllerseo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controllerseo` (
  `idcontrollerseo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `controllerid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idcontrollerseo`),
  KEY `UNIQUE_controllerseo_name` (`name`),
  KEY `FK_controllerseo_addid` (`addid`),
  KEY `FK_controllerseo_editid` (`editid`),
  KEY `FK_controllerseo_controllerid` (`controllerid`),
  CONSTRAINT `FK_controllerseo_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_controllerseo_controllerid` FOREIGN KEY (`controllerid`) REFERENCES `controller` (`idcontroller`),
  CONSTRAINT `FK_controllerseo_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `controllerseo` (`idcontrollerseo`, `name`, `languageid`, `addid`, `adddate`, `editid`, `editdate`, `controllerid`) VALUES (4,'koszyk',1,1,'2011-01-04 14:02:00',NULL,NULL,805),(5,'kategoria',1,1,'2011-01-04 14:02:14',NULL,NULL,806),(6,'klient',1,1,'2011-01-04 14:02:19',NULL,NULL,808),(7,'adresy',1,1,'2011-01-04 14:02:22',NULL,NULL,809),(8,'logowanie',1,1,'2011-01-04 14:02:26',NULL,NULL,810),(9,'zamowienia',1,1,'2011-01-04 14:02:31',NULL,NULL,811),(10,'ustawienia',1,1,'2011-01-04 14:02:35',NULL,NULL,812),(11,'potwierdzenie',1,1,'2011-01-04 14:02:39',NULL,NULL,813),(13,'error',1,1,'2011-01-04 14:02:46',NULL,NULL,816),(14,'zapomnianehaslo',1,1,'2011-01-04 14:02:54',NULL,NULL,820),(15,'integracja',1,1,'2011-01-04 14:02:59',NULL,NULL,825),(16,'faktura',1,1,'2011-01-04 14:03:03',NULL,NULL,826),(17,'home',1,1,'2011-01-04 14:03:21',NULL,NULL,829),(18,'szukanefrazy',1,1,'2011-01-04 14:03:27',NULL,NULL,831),(19,'newsy',1,1,'2011-01-04 14:03:31',NULL,NULL,832),(20,'newsletter',1,1,'2011-01-04 14:03:34',NULL,NULL,833),(21,'zamowienie',1,1,'2011-01-04 14:03:38',NULL,NULL,836),(22,'platnosc',1,1,'2011-01-04 14:03:42',NULL,NULL,837),(23,'ankieta',1,1,'2011-01-04 14:03:45',NULL,NULL,841),(27,'listaproduktow',1,1,'2011-01-04 14:04:14',NULL,NULL,846),(29,'nowosci',1,1,'2011-01-04 14:05:16',NULL,NULL,847),(30,'promocje',1,1,'2011-01-04 14:05:21',NULL,NULL,848),(31,'wyszukiwarka',1,1,'2011-01-04 14:05:29',NULL,NULL,850),(32,'rejestracja',1,1,'2011-01-04 14:05:33',NULL,NULL,853),(33,'informacja',1,1,'2011-01-04 14:05:41',NULL,NULL,855),(34,'mapastrony',1,1,'2011-01-04 14:05:47',NULL,NULL,861),(41,'prod',1,1,'2011-02-25 19:05:28',NULL,NULL,842),(42,'produkt',1,1,'2011-02-25 19:05:38',NULL,NULL,843),(43,'producent',1,1,'2011-07-11 18:52:14',NULL,NULL,893),(46,'kontakt',1,1,'2012-03-06 13:46:54',NULL,NULL,814);
DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `idcountry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `translation` varchar(128) DEFAULT NULL,
  `symbol` varchar(45) DEFAULT NULL,
  `currencyid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idcountry`),
  UNIQUE KEY `UNIQUE_country_name` (`name`),
  UNIQUE KEY `UNIQUE_country_translation` (`translation`),
  KEY `FK_country_addid` (`addid`),
  KEY `FK_country_editid` (`editid`),
  CONSTRAINT `FK_country_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_country_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `country` (`idcountry`, `name`, `translation`, `symbol`, `currencyid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (107,'Afganistan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(108,'Albania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(109,'Alderneyl',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(110,'Algieria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(111,'Andora',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(112,'Antyle Holenderskie',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(113,'Arabia Saudyjska',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(114,'Argentyna',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(115,'Australia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(116,'Austria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(117,'Bahamy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(118,'Bahrajn',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(119,'Barbados',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(120,'Belgia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(121,'Belize',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(122,'Benin',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(124,'Birma',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(126,'Brazylia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(127,'Brunei',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(129,'Burundi',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(130,'Czile',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(131,'Chiny',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(132,'Chorwacja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(134,'Cypr',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(135,'Czechy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(136,'Dania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(137,'Dominika',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(138,'Dominikana',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(139,'Egipt',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(140,'Ekwador',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(141,'Estonia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(142,'Etiopia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(144,'Filipiny',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(145,'Finlandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(146,'Francja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(147,'Gambia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(148,'Ghana',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(149,'Gibraltar',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(150,'Grecja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(151,'Grenada',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(152,'Guernsey',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(153,'Gujana',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(154,'Gwatemala',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(155,'Haiti',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(156,'Hiszpania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(157,'Holandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(158,'Hong Kong',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(159,'Indie',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(160,'Indonezja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(161,'Irak',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(162,'Iran',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(163,'Irlandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(164,'Islandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(165,'Isle of Man',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(166,'Izrael',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(167,'Jamajka',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(168,'Japonia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(169,'Jemen',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(170,'Jersej',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(171,'Jordania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(174,'Kanada',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(175,'Kenia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(176,'Kolumbia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(177,'Kongo',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(178,'Korea',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(179,'Kuba',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(180,'Kuwejt',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(181,'Laos',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(182,'Lesotho',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(183,'Liban',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(184,'Liberia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(185,'Libia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(186,'Lichtenstain',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(187,'Litwa',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(188,'Luksemburg',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(190,'Macedonia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(191,'Madagaskar',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(192,'Malawi',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(193,'Malezja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(194,'Mali',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(195,'Malta',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(196,'Maroko',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(197,'Mauretania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(198,'Mauritius',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(199,'Meksyk',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(201,'Monako',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(202,'Namibia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(203,'Niemcy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(204,'Nigeria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(205,'Nikaragua',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(206,'Norwegia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(207,'Nowa Zelandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(208,'Pakistan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(209,'Panama',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(210,'Paragwaj',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(211,'Peru',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(213,'Portugalia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(216,'Rosja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(217,'Rumunia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(218,'Rwanda',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(219,'Saint Lucia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(220,'Saint Vincent',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(221,'Salwador',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(222,'Samoa',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(223,'San Marino',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(224,'Senegal',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(225,'Seszele',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(226,'Sierra Leone',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(227,'Singapur',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(230,'Sri Lanka',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(232,'Suazi, Królestwo Suazi',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(233,'Surinam',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(234,'Syria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(235,'Szwajcaria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(236,'Szwecja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(237,'Tajlandia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(238,'Tajwan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(239,'Tanzania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(240,'Togo',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(241,'Trinidad i Tobago, Republika Trynidad i Tobago',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(242,'Tunezja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(243,'Turcja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(244,'Uganda',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(245,'Ukraina',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(246,'Urugwaj',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(247,'Watykan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(248,'Wenezuela',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(250,'Wielka Brytania',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(251,'Wietnam',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(254,'Zair',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(255,'Zambia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(256,'Zimbabwe',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(261,'Polska',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(262,'Angola',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(263,'Anguilla',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(264,'Antigua and Barbuda',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(265,'Aruba',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(266,'Armenia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(267,'Azerbejdżan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(268,'Bangladesz',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(269,'Białoruś',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(270,'Bermudy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(271,'Butan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(272,'Boliwia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(273,'Bośnia i Hercegowina',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(274,'Bostwana',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(275,'Brytyjskie Wyspy Dziewicze',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(276,'Bułgaria',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(277,'Burkina Faso',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(278,'Kambodża ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(279,'Kamerun',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(280,'Wyspy Zielonego Przylądka',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(281,'Kajmany',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(282,'Republika Środkowoafrykańska',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(283,'Czad',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(284,'Komory',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(285,'Wyspy Cooka ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(286,'Kostaryka ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(287,'Wybrzeże Kości Słoniowej ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(288,'Dżibuti',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(289,'Equatorial Guinea',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(290,'Erytrea',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(291,'Wyspy Falklandzkie, Falklandy ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(292,'Fidżi',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(293,'Gwinea Francuska',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(294,'Polinezja Francuska',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(295,'Gabon',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(296,'Gruzja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(297,'Grenlandia ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(298,'Gwadelupa ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(299,'Guam',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(300,'Gwinea ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(301,'Gwinea Bissau',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(302,'Honduras',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(303,'Węgry',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(304,'Włochy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(305,'Jan Mayen',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(306,'Jugosławia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(307,'Kazakhstan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(308,'Kiribati, Republika Kiribati',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(309,'Kirgistan ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(311,'Macao ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(312,'Wyspy Marshalla',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(313,'Malediwy',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(314,'Martynika',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(315,'Majotta',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(316,'Mołdawia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(317,'Mongolia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(318,'Montserrat ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(319,'Mozambik',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(320,'Nauru, Republika Nauru',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(321,'Nepal',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(322,'Nowa Kaledonia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(323,'Niger',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(324,'Niue',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(325,'Oman',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(326,'Palau',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(327,'Papua Nowa Gwinea',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(328,'Portoryko',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(329,'Katar',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(330,'Wyspa Świętej Heleny',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(331,'Federacja Saint Kitts i Nevis',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(332,'Saint Pierre i Miquelon',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(333,'Saint Vincent i Grenadyny',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(334,'Słowacja',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(335,'Słowenia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(336,'Wyspy Salomona',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(337,'Somalia',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(338,'Południowa Afryka, Republika Południowej Afryki',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(339,'Svalbard ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(340,'Taiti',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(341,'Tadżykistan ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(342,'Tonga, Królestwo Tonga',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(343,'Turks i Caicos, Wyspy Turks i Caicos',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(344,'Turkmenistan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(345,'Tuvalu',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(346,'Zjednoczone Emiraty Arabskie',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(347,'Stany Zjednoczone Ameryki',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(348,'Uzbekistan',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(349,'Vanuatu, Republika Vanuatu',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(350,'Wallis i Futuna',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(351,'Wyspy Dziewicze Stanów Zjednoczonych',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(352,'Zachodnia Sahara ',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(353,'Zachodnia Samoa',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(354,'APO/FPO',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(355,'Mikronezja, Federalne Stany Mikronezji',NULL,NULL,NULL,1,'2010-09-23 11:06:26',NULL,NULL),(356,'Łotwa',NULL,NULL,NULL,1,'2012-02-21 17:32:15',NULL,'0000-00-00 00:00:00');
DROP TABLE IF EXISTS `crosssell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crosssell` (
  `idcrosssell` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `relatedproductid` int(10) unsigned NOT NULL,
  `productattributesetid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idcrosssell`),
  UNIQUE KEY `UNIQUE_crosssell_productid_relatedproductid` (`productid`,`relatedproductid`),
  KEY `FK_crosssell_addid` (`addid`),
  KEY `FK_crosssell_editid` (`editid`),
  KEY `FK_crosssell_productattributesetid` (`productattributesetid`),
  KEY `FK_crosssell_relatedproductid` (`relatedproductid`),
  CONSTRAINT `FK_crosssell_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_crosssell_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_crosssell_productattributesetid` FOREIGN KEY (`productattributesetid`) REFERENCES `productattributeset` (`idproductattributeset`),
  CONSTRAINT `FK_crosssell_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_crosssell_relatedproductid` FOREIGN KEY (`relatedproductid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `idcurrency` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `currencyname` varchar(45) NOT NULL,
  `currencysymbol` varchar(15) NOT NULL,
  `decimalseparator` varchar(10) DEFAULT NULL,
  `thousandseparator` varchar(10) DEFAULT NULL,
  `positivepreffix` varchar(10) DEFAULT NULL,
  `positivesuffix` varchar(10) DEFAULT NULL,
  `negativepreffix` varchar(10) DEFAULT NULL,
  `negativesuffix` varchar(10) DEFAULT NULL,
  `decimalcount` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcurrency`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `currency` (`idcurrency`, `currencyname`, `currencysymbol`, `decimalseparator`, `thousandseparator`, `positivepreffix`, `positivesuffix`, `negativepreffix`, `negativesuffix`, `decimalcount`) VALUES (9,'euro','EUR','.','','','EUR','-','EUR',2),(28,'złoty','PLN','.',' ','',' PLN','-','PLN',2),(198,'dolar','USD','.','','','USD','-','USD',2);
DROP TABLE IF EXISTS `currencyrates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencyrates` (
  `idcurrencyrates` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `currencyfrom` int(10) unsigned NOT NULL,
  `currencyto` int(10) unsigned NOT NULL,
  `exchangerate` decimal(15,4) NOT NULL,
  PRIMARY KEY (`idcurrencyrates`),
  KEY `IDX_currencyrates_currencyto` (`currencyto`)
) ENGINE=InnoDB AUTO_INCREMENT=820 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `currencyrates` (`idcurrencyrates`, `currencyfrom`, `currencyto`, `exchangerate`) VALUES (811,9,9,1.0000),(812,9,198,1.2638),(813,9,28,4.1594),(814,28,9,0.2404),(815,28,198,0.3038),(816,28,28,1.0000),(817,198,9,0.7913),(818,198,198,1.0000),(819,198,28,3.2912);
DROP TABLE IF EXISTS `currencyview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencyview` (
  `idcurrencyview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `currencyid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idcurrencyview`),
  KEY `FK_currencyview_currencyid` (`currencyid`),
  KEY `FK_currencyview_viewid` (`viewid`),
  KEY `FK_currencyview_addid` (`addid`),
  KEY `FK_currencyview_editid` (`editid`),
  CONSTRAINT `FK_currencyview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_currencyview_currencyid` FOREIGN KEY (`currencyid`) REFERENCES `currency` (`idcurrency`),
  CONSTRAINT `FK_currencyview_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_currencyview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `currencyview` (`idcurrencyview`, `currencyid`, `viewid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (2,28,3,1,'2012-01-20 11:17:20',NULL,NULL);
DROP TABLE IF EXISTS `deliverer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverer` (
  `iddeliverer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `photoid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`iddeliverer`),
  KEY `FK_deliverer_addid` (`addid`),
  KEY `FK_deliverer_editid` (`editid`),
  KEY `FK_deliverer_photoid` (`photoid`),
  CONSTRAINT `FK_deliverer_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_deliverer_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_deliverer_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `deliverertranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverertranslation` (
  `iddeliverertranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivererid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `www` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `languageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`iddeliverertranslation`),
  KEY `UNIQUE_deliverertranslation_name_languageid` (`name`,`languageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `dispatchmethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchmethod` (
  `iddispatchmethod` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `photo` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `type` int(10) unsigned NOT NULL DEFAULT '1',
  `maximumweight` decimal(15,4) DEFAULT NULL,
  `freedelivery` decimal(15,4) DEFAULT NULL,
  `countryids` varchar(255) NOT NULL,
  `currencyid` int(11) NOT NULL DEFAULT '28',
  `hierarchy` int(11) DEFAULT '0',
  PRIMARY KEY (`iddispatchmethod`),
  UNIQUE KEY `unique_dispatchmethod_name` (`name`),
  KEY `FK_dispatchmethod_addid` (`addid`),
  KEY `FK_dispatchmethod_editid` (`editid`),
  KEY `FK_dispatchmethod_viewid` (`viewid`),
  KEY `FK_dispatchmethod_parentid` (`parentid`),
  CONSTRAINT `FK_dispatchmethod_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethod_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethod_parentid` FOREIGN KEY (`parentid`) REFERENCES `dispatchmethod` (`iddispatchmethod`),
  CONSTRAINT `FK_dispatchmethod_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `dispatchmethod` (`iddispatchmethod`, `name`, `addid`, `adddate`, `editid`, `editdate`, `description`, `photo`, `viewid`, `parentid`, `type`, `maximumweight`, `freedelivery`, `countryids`, `currencyid`, `hierarchy`) VALUES (15,'Kurier Standard',1,'2012-09-01 18:56:34',NULL,NULL,'',12,NULL,NULL,1,20.0000,123.0000,'',28,3),(25,'Poczta Polska',1,'2012-03-12 14:00:08',NULL,NULL,'',0,NULL,NULL,1,NULL,NULL,'',28,2);
DROP TABLE IF EXISTS `dispatchmethodpaymentmethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchmethodpaymentmethod` (
  `iddispatchmethodpaymentmethod` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dispatchmethodid` int(10) unsigned NOT NULL,
  `paymentmethodid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`iddispatchmethodpaymentmethod`),
  UNIQUE KEY `UNIQUE_dmp_dispatchmethodid_paymentmethodid` (`dispatchmethodid`,`paymentmethodid`),
  KEY `FK_dispatchmethodpaymentmethod_paymentmethodid` (`paymentmethodid`),
  KEY `FK_dispatchmethodpaymentmethod_addid` (`addid`),
  KEY `FK_dispatchmethodpaymentmethod_editid` (`editid`),
  CONSTRAINT `FK_dispatchmethodpaymentmethod_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethodpaymentmethod_dispatchmethodid` FOREIGN KEY (`dispatchmethodid`) REFERENCES `dispatchmethod` (`iddispatchmethod`),
  CONSTRAINT `FK_dispatchmethodpaymentmethod_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethodpaymentmethod_paymentmethodid` FOREIGN KEY (`paymentmethodid`) REFERENCES `paymentmethod` (`idpaymentmethod`)
) ENGINE=InnoDB AUTO_INCREMENT=939 DEFAULT CHARSET=utf8 COMMENT='UNIQUE_dmp_dispatchmethodid_paymentmethodid named shortly';
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `dispatchmethodpaymentmethod` (`iddispatchmethodpaymentmethod`, `dispatchmethodid`, `paymentmethodid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (884,25,2,1,'2011-11-05 20:50:40',NULL,NULL),(885,25,4,1,'2011-11-05 20:50:40',NULL,NULL),(886,25,5,1,'2011-11-05 20:50:40',NULL,NULL),(887,25,6,1,'2011-11-05 20:50:40',NULL,NULL),(888,25,8,1,'2011-11-05 20:50:40',NULL,NULL),(935,15,5,1,'2012-09-01 19:04:14',NULL,NULL),(936,15,6,1,'2012-09-01 19:04:14',NULL,NULL),(937,15,8,1,'2012-09-01 19:04:14',NULL,NULL);
DROP TABLE IF EXISTS `dispatchmethodprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchmethodprice` (
  `iddispatchmethodprice` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dispatchmethodid` int(10) unsigned DEFAULT NULL,
  `from` decimal(16,2) DEFAULT '0.00',
  `to` decimal(16,2) DEFAULT '0.00',
  `dispatchmethodcost` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `vat` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`iddispatchmethodprice`),
  KEY `FK_dispatchmethodprice_dispatchmethodid` (`dispatchmethodid`),
  KEY `FK_dispatchmethodprice_addid` (`addid`),
  KEY `FK_dispatchmethodprice_editid` (`editid`),
  CONSTRAINT `FK_dispatchmethodprice_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethodprice_dispatchmethodid` FOREIGN KEY (`dispatchmethodid`) REFERENCES `dispatchmethod` (`iddispatchmethod`),
  CONSTRAINT `FK_dispatchmethodprice_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=517 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `dispatchmethodprice` (`iddispatchmethodprice`, `dispatchmethodid`, `from`, `to`, `dispatchmethodcost`, `addid`, `adddate`, `editid`, `editdate`, `vat`) VALUES (478,25,0.00,1000.00,15.0000,1,'2011-11-05 20:50:40',NULL,NULL,2),(479,25,1000.01,0.00,25.0000,1,'2011-11-05 20:50:40',NULL,NULL,2),(514,15,0.00,200.00,20.3252,1,'2012-09-01 19:04:14',NULL,NULL,2),(515,15,200.01,8000.00,14.6341,1,'2012-09-01 19:04:14',NULL,NULL,2),(516,15,8000.01,0.00,5.0000,1,'2012-09-01 19:04:14',NULL,NULL,2);
DROP TABLE IF EXISTS `dispatchmethodview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchmethodview` (
  `iddispatchmethodview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dispatchmethodid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`iddispatchmethodview`),
  KEY `FK_dispatchmethodview_dispatchmethodid` (`dispatchmethodid`),
  KEY `FK_dispatchmethodview_viewid` (`viewid`),
  CONSTRAINT `FK_dispatchmethodview_dispatchmethodid` FOREIGN KEY (`dispatchmethodid`) REFERENCES `dispatchmethod` (`iddispatchmethod`),
  CONSTRAINT `FK_dispatchmethodview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=861 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `dispatchmethodview` (`iddispatchmethodview`, `dispatchmethodid`, `viewid`, `addid`, `adddate`) VALUES (859,15,3,1,'2012-09-06 23:16:11'),(860,25,3,1,'2012-09-06 23:16:11');
DROP TABLE IF EXISTS `dispatchmethodweight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchmethodweight` (
  `iddispatchmethodweight` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` decimal(16,4) DEFAULT NULL,
  `to` decimal(16,4) DEFAULT NULL,
  `cost` decimal(16,4) NOT NULL,
  `dispatchmethodid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdata` datetime DEFAULT NULL,
  `vat` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`iddispatchmethodweight`),
  KEY `FK_dispatchmethodweight_dispatchmethodid` (`dispatchmethodid`),
  KEY `FK_dispatchmethodweight_editid` (`editid`),
  KEY `FK_dispatchmethodweight_addid` (`addid`),
  CONSTRAINT `FK_dispatchmethodweight_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_dispatchmethodweight_dispatchmethodid` FOREIGN KEY (`dispatchmethodid`) REFERENCES `dispatchmethod` (`iddispatchmethod`),
  CONSTRAINT `FK_dispatchmethodweight_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=585 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `dispatchmethodweight` (`iddispatchmethodweight`, `from`, `to`, `cost`, `dispatchmethodid`, `addid`, `adddate`, `editid`, `editdata`, `vat`) VALUES (554,0.0000,1.0000,27.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(555,1.0100,5.0000,33.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(556,5.0100,10.0000,36.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(557,10.0100,20.0000,41.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(558,20.0100,31.5000,48.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(559,31.5100,0.0000,53.0000,15,1,'2011-02-04 21:53:51',NULL,NULL,NULL),(584,0.0000,0.0000,0.0000,25,1,'2011-10-28 13:33:08',NULL,NULL,NULL);
DROP TABLE IF EXISTS `eratysettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eratysettings` (
  `ideratysettings` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numersklepu` varchar(10) DEFAULT NULL,
  `wariantsklepu` varchar(30) DEFAULT NULL,
  `typproduktu` int(2) unsigned DEFAULT NULL,
  `char` varchar(5) DEFAULT NULL,
  `paymentmethodid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`ideratysettings`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `eratysettings` (`ideratysettings`, `numersklepu`, `wariantsklepu`, `typproduktu`, `char`, `paymentmethodid`, `adddate`, `editid`, `editdate`) VALUES (1,'28019999','1',0,'UTF',8,'2011-08-11 12:40:07',NULL,NULL);
DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `idevent` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `model` varchar(45) NOT NULL,
  `method` varchar(45) NOT NULL,
  `module` varchar(64) NOT NULL,
  PRIMARY KEY (`idevent`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `event` (`idevent`, `name`, `model`, `method`, `module`) VALUES (1,'admin.product.add','productsearch','addProductToSearch','Gekosale'),(2,'admin.product.edit','productsearch','updateProductSearch','Gekosale'),(3,'admin.product.renderForm','tierpricing','addFields','Gekosale_TierPricing'),(4,'admin.product.populateForm','tierpricing','populateFields','Gekosale_TierPricing'),(5,'admin.product.add','tierpricing','saveSettings','Gekosale_TierPricing'),(6,'admin.product.edit','tierpricing','saveSettings','Gekosale_TierPricing'),(7,'frontend.productbox.assign','tierpricing','productBoxAssign','Gekosale_TierPricing');
DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file` (
  `idfile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `filetypeid` int(10) unsigned NOT NULL,
  `fileextensionid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `visible` int(10) unsigned DEFAULT '0',
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idfile`),
  KEY `FK_file_addid` (`addid`),
  KEY `FK_file_editid` (`editid`),
  KEY `FK_file_filetypeid` (`filetypeid`),
  KEY `FK_file_fileextensionid` (`fileextensionid`),
  CONSTRAINT `FK_file_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_file_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `file` (`idfile`, `name`, `filetypeid`, `fileextensionid`, `addid`, `adddate`, `editid`, `editdate`, `visible`, `viewid`) VALUES (1,'1.png',2,3,1,'2011-07-14 09:04:08',NULL,NULL,0,0);
DROP TABLE IF EXISTS `fileextension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fileextension` (
  `idfileextension` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `active` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idfileextension`),
  UNIQUE KEY `UNIQUE_fileextension_name` (`name`),
  KEY `FK_fileextension_addid` (`addid`),
  KEY `FK_fileextension_editid` (`editid`),
  CONSTRAINT `FK_fileextension_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_fileextension_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `fileextension` (`idfileextension`, `name`, `addid`, `adddate`, `editid`, `editdate`, `active`) VALUES (1,'jpg',1,'2010-09-23 11:06:34',NULL,NULL,1),(2,'jpeg',1,'2010-09-23 11:06:34',NULL,NULL,1),(3,'png',1,'2010-09-23 11:06:34',NULL,NULL,1),(4,'bmp',1,'2010-09-23 11:06:34',NULL,NULL,1),(5,'gif',1,'2010-09-23 11:06:34',NULL,NULL,1),(6,'swf',1,'2010-09-23 11:06:34',NULL,NULL,1),(7,'csv',1,'2010-09-23 11:06:34',NULL,NULL,1),(9,'psd',1,'2010-09-23 11:06:34',NULL,NULL,1),(10,'evy',1,'2010-09-23 11:06:34',NULL,NULL,1),(11,'fif',1,'2010-09-23 11:06:34',NULL,NULL,1),(12,'spl',1,'2010-09-23 11:06:34',NULL,NULL,1),(13,'hta',1,'2010-09-23 11:06:34',NULL,NULL,1),(14,'acx',1,'2010-09-23 11:06:34',NULL,NULL,1),(15,'hqx',1,'2010-09-23 11:06:34',NULL,NULL,1),(16,'doc',1,'2010-09-23 11:06:34',NULL,NULL,1),(17,'dot',1,'2010-09-23 11:06:34',NULL,NULL,1),(18,'',1,'2010-09-23 11:06:34',NULL,NULL,1),(19,'bin',1,'2010-09-23 11:06:34',NULL,NULL,1),(20,'class',1,'2010-09-23 11:06:34',NULL,NULL,1),(21,'dms',1,'2010-09-23 11:06:34',NULL,NULL,1),(22,'exe',1,'2010-09-23 11:06:34',NULL,NULL,1),(23,'lha',1,'2010-09-23 11:06:34',NULL,NULL,1),(24,'lzh',1,'2010-09-23 11:06:34',NULL,NULL,1),(25,'oda',1,'2010-09-23 11:06:34',NULL,NULL,1),(26,'axs',1,'2010-09-23 11:06:34',NULL,NULL,1),(27,'pdf',1,'2010-09-23 11:06:34',NULL,NULL,1),(28,'prf',1,'2010-09-23 11:06:34',NULL,NULL,1),(29,'p10',1,'2010-09-23 11:06:34',NULL,NULL,1),(30,'crl',1,'2010-09-23 11:06:34',NULL,NULL,1),(31,'ai',1,'2010-09-23 11:06:34',NULL,NULL,1),(32,'eps',1,'2010-09-23 11:06:34',NULL,NULL,1),(33,'ps',1,'2010-09-23 11:06:34',NULL,NULL,1),(34,'rtf',1,'2010-09-23 11:06:34',NULL,NULL,1),(35,'setpay',1,'2010-09-23 11:06:34',NULL,NULL,1),(36,'setreg',1,'2010-09-23 11:06:34',NULL,NULL,1),(37,'xla',1,'2010-09-23 11:06:34',NULL,NULL,1),(38,'xlc',1,'2010-09-23 11:06:34',NULL,NULL,1),(39,'xlm',1,'2010-09-23 11:06:34',NULL,NULL,1),(40,'xls',1,'2010-09-23 11:06:34',NULL,NULL,1),(41,'xlt',1,'2010-09-23 11:06:34',NULL,NULL,1),(42,'xlw',1,'2010-09-23 11:06:34',NULL,NULL,1),(43,'msg',1,'2010-09-23 11:06:34',NULL,NULL,1),(44,'sst',1,'2010-09-23 11:06:34',NULL,NULL,1),(45,'cat',1,'2010-09-23 11:06:34',NULL,NULL,1),(46,'stl',1,'2010-09-23 11:06:34',NULL,NULL,1),(47,'pot',1,'2010-09-23 11:06:34',NULL,NULL,1),(48,'pps',1,'2010-09-23 11:06:34',NULL,NULL,1),(49,'ppt',1,'2010-09-23 11:06:34',NULL,NULL,1),(50,'mpp',1,'2010-09-23 11:06:34',NULL,NULL,1),(51,'wcm',1,'2010-09-23 11:06:34',NULL,NULL,1),(52,'wdb',1,'2010-09-23 11:06:34',NULL,NULL,1),(53,'wks',1,'2010-09-23 11:06:34',NULL,NULL,1),(54,'wps',1,'2010-09-23 11:06:34',NULL,NULL,1),(55,'hlp',1,'2010-09-23 11:06:34',NULL,NULL,1),(56,'bcpio',1,'2010-09-23 11:06:34',NULL,NULL,1),(57,'cdf',1,'2010-09-23 11:06:34',NULL,NULL,1),(58,'z',1,'2010-09-23 11:06:34',NULL,NULL,1),(59,'tgz',1,'2010-09-23 11:06:34',NULL,NULL,1),(60,'cpio',1,'2010-09-23 11:06:34',NULL,NULL,1),(61,'csh',1,'2010-09-23 11:06:34',NULL,NULL,1),(62,'dcr',1,'2010-09-23 11:06:34',NULL,NULL,1),(63,'dir',1,'2010-09-23 11:06:34',NULL,NULL,1),(64,'dxr',1,'2010-09-23 11:06:34',NULL,NULL,1),(65,'dvi',1,'2010-09-23 11:06:34',NULL,NULL,1),(66,'gtar',1,'2010-09-23 11:06:34',NULL,NULL,1),(67,'gz',1,'2010-09-23 11:06:34',NULL,NULL,1),(68,'hdf',1,'2010-09-23 11:06:34',NULL,NULL,1),(69,'ins',1,'2010-09-23 11:06:34',NULL,NULL,1),(70,'isp',1,'2010-09-23 11:06:34',NULL,NULL,1),(71,'iii',1,'2010-09-23 11:06:34',NULL,NULL,1),(72,'js',1,'2010-09-23 11:06:34',NULL,NULL,1),(73,'latex',1,'2010-09-23 11:06:34',NULL,NULL,1),(74,'mdb',1,'2010-09-23 11:06:34',NULL,NULL,1),(75,'crd',1,'2010-09-23 11:06:34',NULL,NULL,1),(76,'clp',1,'2010-09-23 11:06:34',NULL,NULL,1),(77,'dll',1,'2010-09-23 11:06:34',NULL,NULL,1),(78,'m13',1,'2010-09-23 11:06:34',NULL,NULL,1),(79,'m14',1,'2010-09-23 11:06:34',NULL,NULL,1),(80,'mvb',1,'2010-09-23 11:06:34',NULL,NULL,1),(81,'wmf',1,'2010-09-23 11:06:34',NULL,NULL,1),(82,'mny',1,'2010-09-23 11:06:34',NULL,NULL,1),(83,'pub',1,'2010-09-23 11:06:34',NULL,NULL,1),(84,'scd',1,'2010-09-23 11:06:34',NULL,NULL,1),(85,'trm',1,'2010-09-23 11:06:34',NULL,NULL,1),(86,'wri',1,'2010-09-23 11:06:34',NULL,NULL,1),(87,'nc',1,'2010-09-23 11:06:34',NULL,NULL,1),(88,'pma',1,'2010-09-23 11:06:34',NULL,NULL,1),(89,'pmc',1,'2010-09-23 11:06:34',NULL,NULL,1),(90,'pml',1,'2010-09-23 11:06:34',NULL,NULL,1),(91,'pmr',1,'2010-09-23 11:06:34',NULL,NULL,1),(92,'pmw',1,'2010-09-23 11:06:34',NULL,NULL,1),(93,'p12',1,'2010-09-23 11:06:34',NULL,NULL,1),(94,'pfx',1,'2010-09-23 11:06:34',NULL,NULL,1),(95,'p7b',1,'2010-09-23 11:06:34',NULL,NULL,1),(96,'spc',1,'2010-09-23 11:06:34',NULL,NULL,1),(97,'p7r',1,'2010-09-23 11:06:34',NULL,NULL,1),(98,'p7c',1,'2010-09-23 11:06:34',NULL,NULL,1),(99,'p7m',1,'2010-09-23 11:06:34',NULL,NULL,1),(100,'p7s',1,'2010-09-23 11:06:34',NULL,NULL,1),(101,'sh',1,'2010-09-23 11:06:34',NULL,NULL,1),(102,'shar',1,'2010-09-23 11:06:34',NULL,NULL,1),(103,'sit',1,'2010-09-23 11:06:34',NULL,NULL,1),(104,'sv4cpio',1,'2010-09-23 11:06:34',NULL,NULL,1),(105,'sv4crc',1,'2010-09-23 11:06:34',NULL,NULL,1),(106,'tar',1,'2010-09-23 11:06:34',NULL,NULL,1),(107,'tcl',1,'2010-09-23 11:06:34',NULL,NULL,1),(108,'tex',1,'2010-09-23 11:06:34',NULL,NULL,1),(109,'texi',1,'2010-09-23 11:06:34',NULL,NULL,1),(110,'texinfo',1,'2010-09-23 11:06:34',NULL,NULL,1),(111,'roff',1,'2010-09-23 11:06:34',NULL,NULL,1),(112,'t',1,'2010-09-23 11:06:34',NULL,NULL,1),(113,'tr',1,'2010-09-23 11:06:34',NULL,NULL,1),(114,'man',1,'2010-09-23 11:06:34',NULL,NULL,1),(115,'me',1,'2010-09-23 11:06:34',NULL,NULL,1),(116,'ms',1,'2010-09-23 11:06:34',NULL,NULL,1),(117,'ustar',1,'2010-09-23 11:06:34',NULL,NULL,1),(118,'src',1,'2010-09-23 11:06:34',NULL,NULL,1),(119,'cer',1,'2010-09-23 11:06:34',NULL,NULL,1),(120,'crt',1,'2010-09-23 11:06:34',NULL,NULL,1),(121,'der',1,'2010-09-23 11:06:34',NULL,NULL,1),(122,'pko',1,'2010-09-23 11:06:34',NULL,NULL,1),(123,'zip',1,'2010-09-23 11:06:34',NULL,NULL,1),(124,'au',1,'2010-09-23 11:06:34',NULL,NULL,1),(125,'snd',1,'2010-09-23 11:06:34',NULL,NULL,1),(126,'mid',1,'2010-09-23 11:06:34',NULL,NULL,1),(127,'rmi',1,'2010-09-23 11:06:34',NULL,NULL,1),(128,'mp3',1,'2010-09-23 11:06:34',NULL,NULL,1),(129,'aif',1,'2010-09-23 11:06:34',NULL,NULL,1),(130,'aifc',1,'2010-09-23 11:06:34',NULL,NULL,1),(131,'aiff',1,'2010-09-23 11:06:34',NULL,NULL,1),(132,'m3u',1,'2010-09-23 11:06:34',NULL,NULL,1),(133,'ra',1,'2010-09-23 11:06:34',NULL,NULL,1),(134,'ram',1,'2010-09-23 11:06:34',NULL,NULL,1),(135,'wav',1,'2010-09-23 11:06:34',NULL,NULL,1),(136,'cod',1,'2010-09-23 11:06:34',NULL,NULL,1),(137,'ief',1,'2010-09-23 11:06:34',NULL,NULL,1),(138,'jpe',1,'2010-09-23 11:06:34',NULL,NULL,1),(139,'jfif',1,'2010-09-23 11:06:34',NULL,NULL,1),(140,'svg',1,'2010-09-23 11:06:34',NULL,NULL,1),(141,'tif',1,'2010-09-23 11:06:34',NULL,NULL,1),(142,'tiff',1,'2010-09-23 11:06:34',NULL,NULL,1),(143,'ras',1,'2010-09-23 11:06:34',NULL,NULL,1),(144,'cmx',1,'2010-09-23 11:06:34',NULL,NULL,1),(145,'ico',1,'2010-09-23 11:06:34',NULL,NULL,1),(146,'pnm',1,'2010-09-23 11:06:34',NULL,NULL,1),(147,'pbm',1,'2010-09-23 11:06:34',NULL,NULL,1),(148,'pgm',1,'2010-09-23 11:06:34',NULL,NULL,1),(149,'ppm',1,'2010-09-23 11:06:34',NULL,NULL,1),(150,'rgb',1,'2010-09-23 11:06:34',NULL,NULL,1),(151,'xbm',1,'2010-09-23 11:06:34',NULL,NULL,1),(152,'xpm',1,'2010-09-23 11:06:34',NULL,NULL,1),(153,'xwd',1,'2010-09-23 11:06:34',NULL,NULL,1),(154,'mht',1,'2010-09-23 11:06:34',NULL,NULL,1),(155,'mhtml',1,'2010-09-23 11:06:34',NULL,NULL,1),(156,'nws',1,'2010-09-23 11:06:34',NULL,NULL,1),(157,'css',1,'2010-09-23 11:06:34',NULL,NULL,1),(158,'323',1,'2010-09-23 11:06:34',NULL,NULL,1),(159,'htm',1,'2010-09-23 11:06:34',NULL,NULL,1),(160,'html',1,'2010-09-23 11:06:34',NULL,NULL,1),(161,'stm',1,'2010-09-23 11:06:34',NULL,NULL,1),(162,'uls',1,'2010-09-23 11:06:34',NULL,NULL,1),(163,'bas',1,'2010-09-23 11:06:34',NULL,NULL,1),(164,'c',1,'2010-09-23 11:06:34',NULL,NULL,1),(165,'h',1,'2010-09-23 11:06:34',NULL,NULL,1),(166,'txt',1,'2010-09-23 11:06:34',NULL,NULL,1),(167,'rtx',1,'2010-09-23 11:06:34',NULL,NULL,1),(168,'sct',1,'2010-09-23 11:06:34',NULL,NULL,1),(169,'tsv',1,'2010-09-23 11:06:34',NULL,NULL,1),(170,'htt',1,'2010-09-23 11:06:34',NULL,NULL,1),(171,'htc',1,'2010-09-23 11:06:34',NULL,NULL,1),(172,'etx',1,'2010-09-23 11:06:34',NULL,NULL,1),(173,'vcf',1,'2010-09-23 11:06:34',NULL,NULL,1),(174,'mp2',1,'2010-09-23 11:06:34',NULL,NULL,1),(175,'mpa',1,'2010-09-23 11:06:34',NULL,NULL,1),(176,'mpe',1,'2010-09-23 11:06:34',NULL,NULL,1),(177,'mpeg',1,'2010-09-23 11:06:34',NULL,NULL,1),(178,'mpg',1,'2010-09-23 11:06:34',NULL,NULL,1),(179,'mpv2',1,'2010-09-23 11:06:34',NULL,NULL,1),(180,'mov',1,'2010-09-23 11:06:34',NULL,NULL,1),(181,'qt',1,'2010-09-23 11:06:34',NULL,NULL,1),(182,'lsf',1,'2010-09-23 11:06:34',NULL,NULL,1),(183,'lsx',1,'2010-09-23 11:06:34',NULL,NULL,1),(184,'asf',1,'2010-09-23 11:06:34',NULL,NULL,1),(185,'asr',1,'2010-09-23 11:06:34',NULL,NULL,1),(186,'asx',1,'2010-09-23 11:06:34',NULL,NULL,1),(187,'avi',1,'2010-09-23 11:06:34',NULL,NULL,1),(188,'movie',1,'2010-09-23 11:06:34',NULL,NULL,1),(189,'flr',1,'2010-09-23 11:06:34',NULL,NULL,1),(190,'vrml',1,'2010-09-23 11:06:34',NULL,NULL,1),(191,'wrl',1,'2010-09-23 11:06:34',NULL,NULL,1),(192,'wrz',1,'2010-09-23 11:06:34',NULL,NULL,1),(193,'xaf',1,'2010-09-23 11:06:34',NULL,NULL,1),(194,'xof',1,'2010-09-23 11:06:34',NULL,NULL,1);
DROP TABLE IF EXISTS `filetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filetype` (
  `idfiletype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `active` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idfiletype`),
  UNIQUE KEY `UNIQUE_filetype_name` (`name`),
  KEY `FK_filetype_addid` (`addid`),
  KEY `FK_filetype_editid` (`editid`),
  CONSTRAINT `FK_filetype_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_filetype_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `filetype` (`idfiletype`, `name`, `addid`, `adddate`, `editid`, `editdate`, `active`) VALUES (2,'image',1,'2010-09-23 11:06:35',NULL,NULL,1),(3,'text',1,'2010-09-23 11:06:35',NULL,NULL,1),(4,'application',1,'2010-09-23 11:06:35',NULL,NULL,1),(5,'application/envoy',1,'2010-09-23 11:06:35',NULL,NULL,1),(6,'application/fractals',1,'2010-09-23 11:06:35',NULL,NULL,1),(7,'application/futuresplash',1,'2010-09-23 11:06:35',NULL,NULL,1),(8,'application/hta',1,'2010-09-23 11:06:35',NULL,NULL,1),(9,'application/internet-property-stream',1,'2010-09-23 11:06:35',NULL,NULL,1),(10,'application/mac-binhex40',1,'2010-09-23 11:06:35',NULL,NULL,1),(11,'application/msword',1,'2010-09-23 11:06:35',NULL,NULL,1),(12,'application/octet-stream',1,'2010-09-23 11:06:35',NULL,NULL,1),(13,'application/oda',1,'2010-09-23 11:06:35',NULL,NULL,1),(14,'application/olescript',1,'2010-09-23 11:06:35',NULL,NULL,1),(15,'application/pdf',1,'2010-09-23 11:06:35',NULL,NULL,1),(16,'application/pics-rules',1,'2010-09-23 11:06:35',NULL,NULL,1),(17,'application/pkcs10',1,'2010-09-23 11:06:35',NULL,NULL,1),(18,'application/pkix-crl',1,'2010-09-23 11:06:35',NULL,NULL,1),(19,'application/postscript',1,'2010-09-23 11:06:35',NULL,NULL,1),(20,'application/rtf',1,'2010-09-23 11:06:35',NULL,NULL,1),(21,'application/set-payment-initiation',1,'2010-09-23 11:06:35',NULL,NULL,1),(22,'application/set-registration-initiation',1,'2010-09-23 11:06:35',NULL,NULL,1),(23,'application/vnd.ms-excel',1,'2010-09-23 11:06:35',NULL,NULL,1),(24,'application/vnd.ms-outlook',1,'2010-09-23 11:06:35',NULL,NULL,1),(25,'application/vnd.ms-pkicertstore',1,'2010-09-23 11:06:35',NULL,NULL,1),(26,'application/vnd.ms-pkiseccat',1,'2010-09-23 11:06:35',NULL,NULL,1),(27,'application/vnd.ms-pkistl',1,'2010-09-23 11:06:35',NULL,NULL,1),(28,'application/vnd.ms-powerpoint',1,'2010-09-23 11:06:35',NULL,NULL,1),(29,'application/vnd.ms-project',1,'2010-09-23 11:06:35',NULL,NULL,1),(30,'application/vnd.ms-works',1,'2010-09-23 11:06:35',NULL,NULL,1),(31,'application/winhlp',1,'2010-09-23 11:06:35',NULL,NULL,1),(32,'application/x-bcpio',1,'2010-09-23 11:06:35',NULL,NULL,1),(33,'application/x-cdf',1,'2010-09-23 11:06:35',NULL,NULL,1),(34,'application/x-compress',1,'2010-09-23 11:06:35',NULL,NULL,1),(35,'application/x-compressed',1,'2010-09-23 11:06:35',NULL,NULL,1),(36,'application/x-cpio',1,'2010-09-23 11:06:35',NULL,NULL,1),(37,'application/x-csh',1,'2010-09-23 11:06:35',NULL,NULL,1),(38,'application/x-director',1,'2010-09-23 11:06:35',NULL,NULL,1),(39,'application/x-dvi',1,'2010-09-23 11:06:35',NULL,NULL,1),(40,'application/x-gtar',1,'2010-09-23 11:06:35',NULL,NULL,1),(41,'application/x-gzip',1,'2010-09-23 11:06:35',NULL,NULL,1),(42,'application/x-hdf',1,'2010-09-23 11:06:35',NULL,NULL,1),(43,'application/x-internet-signup',1,'2010-09-23 11:06:35',NULL,NULL,1),(44,'application/x-iphone',1,'2010-09-23 11:06:35',NULL,NULL,1),(45,'application/x-javascript',1,'2010-09-23 11:06:35',NULL,NULL,1),(46,'application/x-latex',1,'2010-09-23 11:06:35',NULL,NULL,1),(47,'application/x-msaccess',1,'2010-09-23 11:06:35',NULL,NULL,1),(48,'application/x-mscardfile',1,'2010-09-23 11:06:35',NULL,NULL,1),(49,'application/x-msclip',1,'2010-09-23 11:06:35',NULL,NULL,1),(50,'application/x-msdownload',1,'2010-09-23 11:06:35',NULL,NULL,1),(51,'application/x-msmediaview',1,'2010-09-23 11:06:35',NULL,NULL,1),(52,'application/x-msmetafile',1,'2010-09-23 11:06:35',NULL,NULL,1),(53,'application/x-msmoney',1,'2010-09-23 11:06:35',NULL,NULL,1),(54,'application/x-mspublisher',1,'2010-09-23 11:06:35',NULL,NULL,1),(55,'application/x-msschedule',1,'2010-09-23 11:06:35',NULL,NULL,1),(56,'application/x-msterminal',1,'2010-09-23 11:06:35',NULL,NULL,1),(57,'application/x-mswrite',1,'2010-09-23 11:06:35',NULL,NULL,1),(58,'application/x-netcdf',1,'2010-09-23 11:06:35',NULL,NULL,1),(59,'application/x-perfmon',1,'2010-09-23 11:06:35',NULL,NULL,1),(60,'application/x-pkcs12',1,'2010-09-23 11:06:35',NULL,NULL,1),(61,'application/x-pkcs7-certificates',1,'2010-09-23 11:06:35',NULL,NULL,1),(62,'application/x-pkcs7-certreqresp',1,'2010-09-23 11:06:35',NULL,NULL,1),(63,'application/x-pkcs7-mime',1,'2010-09-23 11:06:35',NULL,NULL,1),(64,'application/x-pkcs7-signature',1,'2010-09-23 11:06:35',NULL,NULL,1),(65,'application/x-sh',1,'2010-09-23 11:06:35',NULL,NULL,1),(66,'application/x-shar',1,'2010-09-23 11:06:35',NULL,NULL,1),(67,'application/x-shockwave-flash',1,'2010-09-23 11:06:35',NULL,NULL,1),(68,'application/x-stuffit',1,'2010-09-23 11:06:35',NULL,NULL,1),(69,'application/x-sv4cpio',1,'2010-09-23 11:06:35',NULL,NULL,1),(70,'application/x-sv4crc',1,'2010-09-23 11:06:35',NULL,NULL,1),(71,'application/x-tar',1,'2010-09-23 11:06:35',NULL,NULL,1),(72,'application/x-tcl',1,'2010-09-23 11:06:35',NULL,NULL,1),(73,'application/x-tex',1,'2010-09-23 11:06:35',NULL,NULL,1),(74,'application/x-texinfo',1,'2010-09-23 11:06:35',NULL,NULL,1),(75,'application/x-troff',1,'2010-09-23 11:06:35',NULL,NULL,1),(76,'application/x-troff-man',1,'2010-09-23 11:06:35',NULL,NULL,1),(77,'application/x-troff-me',1,'2010-09-23 11:06:35',NULL,NULL,1),(78,'application/x-troff-ms',1,'2010-09-23 11:06:35',NULL,NULL,1),(79,'application/x-ustar',1,'2010-09-23 11:06:35',NULL,NULL,1),(80,'application/x-wais-source',1,'2010-09-23 11:06:35',NULL,NULL,1),(81,'application/x-x509-ca-cert',1,'2010-09-23 11:06:35',NULL,NULL,1),(82,'application/ynd.ms-pkipko',1,'2010-09-23 11:06:35',NULL,NULL,1),(83,'application/zip',1,'2010-09-23 11:06:35',NULL,NULL,1),(84,'audio/basic',1,'2010-09-23 11:06:35',NULL,NULL,1),(85,'audio/mid',1,'2010-09-23 11:06:35',NULL,NULL,1),(86,'audio/mpeg',1,'2010-09-23 11:06:35',NULL,NULL,1),(87,'audio/x-aiff',1,'2010-09-23 11:06:35',NULL,NULL,1),(88,'audio/x-mpegurl',1,'2010-09-23 11:06:35',NULL,NULL,1),(89,'audio/x-pn-realaudio',1,'2010-09-23 11:06:35',NULL,NULL,1),(90,'audio/x-wav',1,'2010-09-23 11:06:35',NULL,NULL,1),(91,'image/bmp',1,'2010-09-23 11:06:35',NULL,NULL,1),(92,'image/cis-cod',1,'2010-09-23 11:06:35',NULL,NULL,1),(93,'image/gif',1,'2010-09-23 11:06:35',NULL,NULL,1),(94,'image/ief',1,'2010-09-23 11:06:35',NULL,NULL,1),(95,'image/jpeg',1,'2010-09-23 11:06:35',NULL,NULL,1),(96,'image/pipeg',1,'2010-09-23 11:06:35',NULL,NULL,1),(97,'image/png',1,'2010-09-23 11:06:35',NULL,NULL,1),(98,'image/svg+xml',1,'2010-09-23 11:06:35',NULL,NULL,1),(99,'image/tiff',1,'2010-09-23 11:06:35',NULL,NULL,1),(100,'image/x-cmu-raster',1,'2010-09-23 11:06:35',NULL,NULL,1),(101,'image/x-cmx',1,'2010-09-23 11:06:35',NULL,NULL,1),(102,'image/x-icon',1,'2010-09-23 11:06:35',NULL,NULL,1),(103,'image/x-portable-anymap',1,'2010-09-23 11:06:35',NULL,NULL,1),(104,'image/x-portable-bitmap',1,'2010-09-23 11:06:35',NULL,NULL,1),(105,'image/x-portable-graymap',1,'2010-09-23 11:06:35',NULL,NULL,1),(106,'image/x-portable-pixmap',1,'2010-09-23 11:06:35',NULL,NULL,1),(107,'image/x-rgb',1,'2010-09-23 11:06:35',NULL,NULL,1),(108,'image/x-xbitmap',1,'2010-09-23 11:06:35',NULL,NULL,1),(109,'image/x-xpixmap',1,'2010-09-23 11:06:35',NULL,NULL,1),(110,'image/x-xwindowdump',1,'2010-09-23 11:06:35',NULL,NULL,1),(111,'message/rfc822',1,'2010-09-23 11:06:35',NULL,NULL,1),(112,'text/css',1,'2010-09-23 11:06:35',NULL,NULL,1),(113,'text/h323',1,'2010-09-23 11:06:35',NULL,NULL,1),(114,'text/html',1,'2010-09-23 11:06:35',NULL,NULL,1),(115,'text/iuls',1,'2010-09-23 11:06:35',NULL,NULL,1),(116,'text/plain',1,'2010-09-23 11:06:35',NULL,NULL,1),(117,'text/richtext',1,'2010-09-23 11:06:35',NULL,NULL,1),(118,'text/scriptlet',1,'2010-09-23 11:06:35',NULL,NULL,1),(119,'text/tab-separated-values',1,'2010-09-23 11:06:35',NULL,NULL,1),(120,'text/webviewhtml',1,'2010-09-23 11:06:35',NULL,NULL,1),(121,'text/x-component',1,'2010-09-23 11:06:35',NULL,NULL,1),(122,'text/x-setext',1,'2010-09-23 11:06:35',NULL,NULL,1),(123,'text/x-vcard',1,'2010-09-23 11:06:35',NULL,NULL,1),(124,'video/mpeg',1,'2010-09-23 11:06:35',NULL,NULL,1),(125,'video/quicktime',1,'2010-09-23 11:06:35',NULL,NULL,1),(126,'video/x-la-asf',1,'2010-09-23 11:06:35',NULL,NULL,1),(127,'video/x-ms-asf',1,'2010-09-23 11:06:35',NULL,NULL,1),(128,'video/x-msvideo',1,'2010-09-23 11:06:35',NULL,NULL,1),(129,'video/x-sgi-movie',1,'2010-09-23 11:06:35',NULL,NULL,1),(130,'x-world/x-vrml',1,'2010-09-23 11:06:35',NULL,NULL,1);
DROP TABLE IF EXISTS `gallerysettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallerysettings` (
  `idgallerysettings` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `method` varchar(255) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `keepproportion` int(10) unsigned NOT NULL DEFAULT '1',
  `staticpath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idgallerysettings`),
  UNIQUE KEY `UNIQUE_gallerysettings_method` (`method`),
  KEY `FK_gallerysettings_addid` (`addid`),
  KEY `FK_gallerysettings_editid` (`editid`),
  CONSTRAINT `FK_gallerysettings_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_gallerysettings_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `gallerysettings` (`idgallerysettings`, `width`, `height`, `method`, `addid`, `adddate`, `editid`, `editdate`, `keepproportion`, `staticpath`) VALUES (1,100,100,'getSmallImageById',1,'2011-08-15 22:12:31',NULL,NULL,1,NULL),(2,300,300,'getNormalImageById',1,'2010-09-23 11:06:35',NULL,NULL,1,NULL),(3,NULL,NULL,'getOrginalImageById',1,'2010-09-23 11:06:35',NULL,NULL,0,'_orginal'),(4,NULL,NULL,'getScalledImageById',1,'2010-09-23 11:06:35',NULL,NULL,1,'_orginal'),(5,200,200,'getMediumImageById',1,'2010-09-23 11:06:35',NULL,NULL,1,NULL);
DROP TABLE IF EXISTS `globalsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `globalsettings` (
  `idglobalsettings` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idglobalsettings`),
  UNIQUE KEY `UNIQUE_globalsettings_param_type` (`param`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `globalsettings` (`idglobalsettings`, `param`, `value`, `type`) VALUES (2,'datagrid_rows_per_page','50','interface'),(5,'datagrid_show_context_menu','0','interface'),(9,'datagrid_click_row_action','edit','interface'),(10,'wysiwyg','cke','interface');
DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `idgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idgroup`),
  UNIQUE KEY `UNIQUE_group_name` (`name`),
  KEY `FK_group_addid` (`addid`),
  KEY `FK_group_editid` (`editid`),
  CONSTRAINT `FK_group_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_group_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='User groups';
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `group` (`idgroup`, `name`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,'Administracja',1,'2010-09-23 11:06:37',1,NULL),(2,'Handlowiec',1,'2011-07-24 21:39:33',1,NULL);
DROP TABLE IF EXISTS `integration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integration` (
  `idintegration` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(32) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `installed` tinyint(1) unsigned DEFAULT NULL,
  `updateurl` varchar(128) DEFAULT NULL,
  `md5check` varchar(45) DEFAULT NULL,
  `relationtable` varchar(45) DEFAULT NULL,
  `datatable` varchar(45) DEFAULT NULL,
  `storeid` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idintegration`),
  UNIQUE KEY `UNIQUE_integration_symbol` (`symbol`),
  UNIQUE KEY `UNIQUE_integration_name` (`name`),
  KEY `FK_integration_addid` (`addid`),
  KEY `FK_integration_editid` (`editid`),
  KEY `FK_integration_storeid` (`storeid`),
  KEY `FK_integration_viewid` (`viewid`),
  KEY `FK_integration_parentid` (`parentid`),
  CONSTRAINT `FK_integration_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_integration_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_integration_parentid` FOREIGN KEY (`parentid`) REFERENCES `integration` (`idintegration`),
  CONSTRAINT `FK_integration_storeid` FOREIGN KEY (`storeid`) REFERENCES `store` (`idstore`),
  CONSTRAINT `FK_integration_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `integrationwhitelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integrationwhitelist` (
  `idintegrationwhitelist` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `integrationid` int(11) unsigned NOT NULL,
  `ipaddress` varchar(64) NOT NULL,
  PRIMARY KEY (`idintegrationwhitelist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `idinvoice` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(128) NOT NULL,
  `invoicedate` date NOT NULL,
  `salesdate` date NOT NULL,
  `paymentduedate` date NOT NULL,
  `salesperson` varchar(128) NOT NULL,
  `invoicetype` int(11) NOT NULL,
  `comment` text,
  `contentoriginal` longblob NOT NULL,
  `contentcopy` longblob NOT NULL,
  `orderid` int(11) NOT NULL,
  `totalpayed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `viewid` int(11) DEFAULT NULL,
  `externalid` int(11) DEFAULT NULL,
  `contenttype` varchar(5) DEFAULT 'html',
  PRIMARY KEY (`idinvoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `idlanguage` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `translation` varchar(256) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `currencyid` int(10) unsigned DEFAULT NULL,
  `flag` varchar(45) NOT NULL,
  PRIMARY KEY (`idlanguage`),
  KEY `UNIQUE_language_name` (`name`(255)),
  KEY `UNIQUE_language_translation` (`translation`(255)),
  KEY `FK_language_addid` (`addid`),
  KEY `FK_language_editid` (`editid`),
  KEY `FK_language_parentid` (`parentid`),
  KEY `FK_language_currencyid` (`currencyid`),
  CONSTRAINT `FK_language_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_language_currencyid` FOREIGN KEY (`currencyid`) REFERENCES `currency` (`idcurrency`),
  CONSTRAINT `FK_language_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_language_parentid` FOREIGN KEY (`parentid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `language` (`idlanguage`, `name`, `translation`, `addid`, `adddate`, `editid`, `editdate`, `parentid`, `currencyid`, `flag`) VALUES (1,'pl_PL','TXT_POLISH',1,'2010-09-23 11:06:38',1,NULL,NULL,28,'pl_PL.png');
DROP TABLE IF EXISTS `languageview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languageview` (
  `idlanguageview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idlanguageview`),
  KEY `FK_languageview_languageid` (`languageid`),
  KEY `FK_languageview_viewid` (`viewid`),
  KEY `FK_languageview_addid` (`addid`),
  CONSTRAINT `FK_languageview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_languageview_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_languageview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `languageview` (`idlanguageview`, `languageid`, `viewid`, `addid`, `adddate`) VALUES (1,1,3,1,'2011-11-04 09:11:49');
DROP TABLE IF EXISTS `layoutbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutbox` (
  `idlayoutbox` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `layoutboxschemeid` int(10) unsigned DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` varchar(2000) DEFAULT NULL,
  `controller` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`idlayoutbox`),
  KEY `FK_layoutbox_addid` (`addid`),
  KEY `FK_layoutbox_editid` (`editid`),
  KEY `FK_layoutbox_layoutboxschemeid` (`layoutboxschemeid`),
  CONSTRAINT `FK_layoutbox_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_layoutbox_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_layoutbox_layoutboxschemeid` FOREIGN KEY (`layoutboxschemeid`) REFERENCES `layoutboxscheme` (`idlayoutboxscheme`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutbox` (`idlayoutbox`, `name`, `addid`, `adddate`, `editid`, `editdate`, `viewid`, `parentid`, `layoutboxschemeid`, `title`, `content`, `controller`) VALUES (29,'Kontakt',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Dane kontaktowe',NULL,'ContactBox'),(30,'Podgląd wiadomości',1,'2011-07-28 14:23:00',1,NULL,NULL,NULL,NULL,'Podgląd pojedynczej wiadomości sklepu.',NULL,'NewsBox'),(35,'Promocje w sklepie',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Promocje',NULL,'ProductPromotionsBox'),(36,'Nowości w sklepie',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Nowości',NULL,'ProductNewsBox'),(38,'Kategorie',1,'2011-07-28 14:24:25',1,NULL,NULL,NULL,NULL,'Nawigacja',NULL,'CategoriesBox'),(39,'Ankieta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Ankieta',NULL,'PollBox'),(40,'Lista newsów',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Aktualności',NULL,'NewsListBox'),(41,'Lista tagów',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Tagi',NULL,'TagsBox'),(42,'Lista tagów klienta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Twoje tagi',NULL,'ClientTagsBox'),(43,'Lista życzeń klienta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Twoja lista życzeń',NULL,'WishlistBox'),(44,'Bestsellery',1,'2011-07-28 14:21:29',1,NULL,NULL,NULL,NULL,'Bestsellery',NULL,'ProductBestsellersBox'),(46,'Koszyk',1,'2011-07-28 14:25:50',1,NULL,NULL,NULL,NULL,'Koszyk',NULL,'CartBox'),(49,'Produkt',1,'2011-07-28 14:22:33',1,NULL,NULL,NULL,NULL,'Produkt',NULL,'ProductBox'),(50,'Lista produktów w kategorii',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Produkty w tej kategorii',NULL,'ProductsInCategoryBox'),(51,'Rejestracja',1,'2011-07-28 14:22:18',1,NULL,NULL,NULL,NULL,'Rejestracja',NULL,'RegistrationCartBox'),(52,'Potwierdzenie płatności',1,'2011-07-28 14:24:10',1,NULL,NULL,NULL,NULL,'Dostępne płatności',NULL,'PaymentBox'),(54,'Fromularz logowania',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Formularza logowania',NULL,'ClientLoginBox'),(55,'Przypomnij hasło',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Formularza przypomnienia hasla',NULL,'ForgotPasswordBox'),(56,'Ustawienia klienta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Ustawienia klienta',NULL,'ClientSettingsBox'),(57,'Adresy klienta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Adresy klienta',NULL,'ClientAddressBox'),(58,'Zamówienia klienta',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Zamowienia klienta',NULL,'ClientOrderBox'),(62,'Lista nowości w sklepie',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Lista nowości w sklepie',NULL,'ProductNewsBox'),(63,'Lista promocji w sklepie',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Lista promocji w sklepie',NULL,'ProductPromotionsBox'),(64,'Lista otagowanych produktów',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Lista otagowanych produktów',NULL,'ProductTagsListBox'),(65,'Wyszukiwarka',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Wyszukiwarka',NULL,'ProductSearchListBox'),(66,'Podgląd koszyka',1,'2011-07-28 14:26:17',1,NULL,NULL,NULL,NULL,'Zawartość koszyka',NULL,'CartPreviewBox'),(73,'Poleć znajomemu',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Poleć znajomemu',NULL,'RecommendFriendBox'),(81,'Newsletter',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Newsletter',NULL,'NewsletterBox'),(82,'CMS',1,'2011-07-28 14:22:07',1,NULL,NULL,NULL,NULL,'CMS',NULL,'CmsBox'),(83,'Showcase',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Showcase',NULL,'ShowcaseBox'),(86,'Sprzedaż krzyżowa',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Sprzedaż krzyżowa',NULL,'ProductsCrossSellBox'),(87,'Produkty podobne',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Produkty podobne',NULL,'ProductsSimilarBox'),(88,'Akcesoria do produktów',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Akcesoria do produktów',NULL,'ProductsUpSellBox'),(89,'Kupiono również',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,'Kupiono również',NULL,'ProductBuyAlsoBox'),(98,'Infolinia',1,'2011-07-04 11:06:37',1,NULL,NULL,NULL,NULL,NULL,NULL,'GraphicsBox'),(99,'Mapa sklepu',1,'2011-07-28 14:23:41',1,NULL,NULL,NULL,NULL,NULL,NULL,'SitemapBox'),(100,'Reklama',1,'2011-07-28 14:21:56',1,NULL,NULL,NULL,NULL,NULL,NULL,'GraphicsBox'),(103,'Filtry',1,'2011-07-06 11:48:39',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'LayeredNavigationBox'),(105,'Producenci',1,'2011-07-11 17:22:42',1,NULL,NULL,NULL,NULL,NULL,NULL,'ProducerBox'),(106,'Lista produktów producenta',1,'2011-07-11 18:47:38',1,NULL,NULL,NULL,NULL,NULL,NULL,'ProducerListBox'),(109,'Facebook',1,'2011-09-14 10:00:03',1,NULL,NULL,NULL,NULL,NULL,NULL,'FacebookLikeBox'),(110,'Slideshow',1,'2011-10-20 10:51:57',1,NULL,NULL,NULL,NULL,NULL,NULL,'SlideShowBox'),(111,'Najczęściej szukano',1,'2011-12-19 21:27:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'MostSearchedBox'),(112,'Wybrane produkty z oferty',1,'2012-01-20 10:08:42',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'CustomProductListBox');
DROP TABLE IF EXISTS `layoutboxcontentspecificvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxcontentspecificvalue` (
  `idlayoutboxcontentspecificvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layoutboxid` int(10) unsigned NOT NULL,
  `variable` varchar(45) NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`idlayoutboxcontentspecificvalue`),
  KEY `FK_layoutboxcontentspecificvalue_layoutboxid` (`layoutboxid`),
  KEY `languageid` (`languageid`),
  CONSTRAINT `layoutboxcontentspecificvalue_ibfk_1` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `layoutboxcontentspecificvalue_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1523 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutboxcontentspecificvalue` (`idlayoutboxcontentspecificvalue`, `layoutboxid`, `variable`, `languageid`, `value`) VALUES (566,35,'productsCount',NULL,'3'),(567,35,'view',NULL,'1'),(568,35,'orderBy',NULL,'opinions'),(569,35,'orderDir',NULL,'desc'),(570,35,'pagination',NULL,'0'),(663,36,'productsCount',NULL,'5'),(664,36,'view',NULL,'1'),(665,36,'orderBy',NULL,'adddate'),(666,36,'orderDir',NULL,'desc'),(667,36,'pagination',NULL,'0'),(1003,106,'productsCount',NULL,'0'),(1004,106,'view',NULL,'1'),(1005,106,'orderBy',NULL,'id'),(1006,106,'orderDir',NULL,'asc'),(1007,106,'pagination',NULL,'1'),(1008,106,'showphoto',NULL,'1'),(1009,106,'showdescription',NULL,'1'),(1015,100,'image',NULL,'design/_images_frontend/upload/sony.jpg'),(1016,100,'height',NULL,'142'),(1017,100,'align',NULL,'center center'),(1018,100,'url',NULL,'promocje'),(1020,99,'categoryTreeLevels',NULL,'3'),(1200,109,'url',NULL,'http://www.facebook.com/Gekosale'),(1201,109,'width',NULL,'180'),(1202,109,'height',NULL,'556'),(1203,109,'scheme',NULL,'light'),(1204,109,'faces',NULL,'true'),(1205,109,'stream',NULL,'false'),(1206,109,'header',NULL,'true'),(1213,49,'tabbed',NULL,'1'),(1284,83,'productsCount',NULL,'10'),(1285,83,'orderBy',NULL,'random'),(1286,83,'orderDir',NULL,'asc'),(1287,83,'statusId',NULL,'5'),(1406,88,'productsCount',NULL,'0'),(1407,88,'view',NULL,'1'),(1408,88,'orderBy',NULL,'random'),(1409,88,'orderDir',NULL,'asc'),(1431,62,'productsCount',NULL,'10'),(1432,62,'view',NULL,'1'),(1433,62,'orderBy',NULL,'name'),(1434,62,'orderDir',NULL,'desc'),(1435,62,'pagination',NULL,'1'),(1441,63,'productsCount',NULL,'10'),(1442,63,'view',NULL,'1'),(1443,63,'orderBy',NULL,'id'),(1444,63,'orderDir',NULL,'asc'),(1445,63,'pagination',NULL,'1'),(1446,87,'productsCount',NULL,'0'),(1447,87,'view',NULL,'1'),(1448,87,'orderBy',NULL,'random'),(1449,87,'orderDir',NULL,'asc'),(1450,86,'productsCount',NULL,'10'),(1451,86,'view',NULL,'1'),(1452,86,'orderBy',NULL,'random'),(1453,86,'orderDir',NULL,'desc'),(1454,65,'productsCount',NULL,'10'),(1455,65,'view',NULL,'1'),(1456,65,'orderBy',NULL,'id'),(1457,65,'orderDir',NULL,'asc'),(1458,65,'pagination',NULL,'1'),(1469,112,'productsCount',NULL,'0'),(1470,112,'view',NULL,'0'),(1471,112,'orderBy',NULL,'id'),(1472,112,'orderDir',NULL,'asc'),(1473,112,'products',NULL,'1'),(1478,98,'image',NULL,'design/_images_frontend/upload/masz-pytanie.jpg'),(1479,98,'height',NULL,'224'),(1480,98,'align',NULL,'center center'),(1481,98,'url',NULL,'kontakt'),(1487,50,'productsCount',NULL,'50'),(1488,50,'view',NULL,'1'),(1489,50,'orderBy',NULL,'price'),(1490,50,'orderDir',NULL,'asc'),(1491,50,'pagination',NULL,'1'),(1492,38,'showcount',NULL,'1'),(1493,38,'hideempty',NULL,'0'),(1494,38,'showall',NULL,'1'),(1495,38,'categoryIds',NULL,'4'),(1496,110,'image1',NULL,'design/_images_frontend/upload/sony.jpg'),(1497,110,'height1',NULL,'152'),(1498,110,'url1',NULL,''),(1499,110,'caption1',NULL,''),(1500,110,'image2',NULL,'design/_images_frontend/upload/sony.jpg'),(1501,110,'height2',NULL,'152'),(1502,110,'url2',NULL,''),(1503,110,'caption2',NULL,''),(1512,105,'view',NULL,'1'),(1513,105,'producers',NULL,'1'),(1518,40,'newsCount',NULL,'5'),(1519,44,'productsCount',NULL,'3'),(1520,44,'view',NULL,'0'),(1521,44,'orderBy',NULL,'total'),(1522,44,'orderDir',NULL,'desc');
DROP TABLE IF EXISTS `layoutboxcss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxcss` (
  `idlayoutboxcss` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(100) DEFAULT NULL,
  `selector` varchar(255) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `layoutboxid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idlayoutboxcss`),
  KEY `FK_layoutboxcss_layoutboxid` (`layoutboxid`),
  CONSTRAINT `layoutboxcss_ibfk_1` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=469 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutboxcss` (`idlayoutboxcss`, `class`, `selector`, `attribute`, `layoutboxid`) VALUES (389,NULL,'#layout-box-66.layout-box','border',66),(390,NULL,'#layout-box-66.layout-box','border-radius',66),(391,NULL,'#layout-box-66 .layout-box-header h3, #layout-box-66 .layout-box-header, #layout-box-66 .layout-box-icons','line-height',66),(392,NULL,'#layout-box-66 .layout-box-header','background',66),(393,NULL,'#layout-box-66 .layout-box-content','background',66),(394,NULL,'#layout-box-66 .layout-box-header h3','font',66),(395,NULL,'#layout-box-66 .layout-box-header, #layout-box-66 .layout-box-header h3','text-align',66),(396,NULL,'#layout-box-66 .layout-box-content, #layout-box-66 .layout-box-content p','font',66),(397,NULL,'#layout-box-66 .layout-box-content, #layout-box-66 .layout-box-content p','text-align',66),(398,NULL,'#layout-box-66 .layout-box-icons .layout-box-close','icon',66),(399,NULL,'#layout-box-66 .layout-box-icons .layout-box-collapse','icon',66),(400,NULL,'#layout-box-66 .layout-box-icons .layout-box-uncollapse','icon',66),(401,NULL,'#layout-box-66 .layout-box-content','border-radius',66),(402,NULL,'#layout-box-66 .layout-box-header','border-radius',66),(403,NULL,'#layout-box-66.layout-box-collapsed .layout-box-header, #layout-box-66.layout-box-option-header-false .layout-box-content','border-radius',66),(404,NULL,'#layout-box-66 .layout-box-icons .layout-box-icon','height',66),(437,NULL,'#layout-box-57.layout-box','border',57),(438,NULL,'#layout-box-57.layout-box','border-radius',57),(439,NULL,'#layout-box-57 .layout-box-header h3, #layout-box-57 .layout-box-header, #layout-box-57 .layout-box-icons','line-height',57),(440,NULL,'#layout-box-57 .layout-box-header','background',57),(441,NULL,'#layout-box-57 .layout-box-content','background',57),(442,NULL,'#layout-box-57 .layout-box-header h3','font',57),(443,NULL,'#layout-box-57 .layout-box-header, #layout-box-57 .layout-box-header h3','text-align',57),(444,NULL,'#layout-box-57 .layout-box-content, #layout-box-57 .layout-box-content p','font',57),(445,NULL,'#layout-box-57 .layout-box-content, #layout-box-57 .layout-box-content p','text-align',57),(446,NULL,'#layout-box-57 .layout-box-icons .layout-box-close','icon',57),(447,NULL,'#layout-box-57 .layout-box-icons .layout-box-collapse','icon',57),(448,NULL,'#layout-box-57 .layout-box-icons .layout-box-uncollapse','icon',57),(449,NULL,'#layout-box-57 .layout-box-content','border-radius',57),(450,NULL,'#layout-box-57 .layout-box-header','border-radius',57),(451,NULL,'#layout-box-57.layout-box-collapsed .layout-box-header, #layout-box-57.layout-box-option-header-false .layout-box-content','border-radius',57),(452,NULL,'#layout-box-57 .layout-box-icons .layout-box-icon','height',57),(453,NULL,'#layout-box-38.layout-box','border',38),(454,NULL,'#layout-box-38.layout-box','border-radius',38),(455,NULL,'#layout-box-38 .layout-box-header h3, #layout-box-38 .layout-box-header h2, #layout-box-38 .layout-box-header h1, #layout-box-38 .layout-box-header, #layout-box-38 .layout-box-icons','line-height',38),(456,NULL,'#layout-box-38 .layout-box-header','background',38),(457,NULL,'#layout-box-38 .layout-box-content','background',38),(458,NULL,'#layout-box-38 .layout-box-header h3, #layout-box-38 .layout-box-header h2, #layout-box-38 .layout-box-header h1','font',38),(459,NULL,'#layout-box-38 .layout-box-header, #layout-box-38 .layout-box-header h3, #layout-box-38 .layout-box-header h2, #layout-box-38 .layout-box-header h1','text-align',38),(460,NULL,'#layout-box-38 .layout-box-content, #layout-box-38 .layout-box-content p','font',38),(461,NULL,'#layout-box-38 .layout-box-content, #layout-box-38 .layout-box-content p','text-align',38),(462,NULL,'#layout-box-38 .layout-box-icons .layout-box-close','icon',38),(463,NULL,'#layout-box-38 .layout-box-icons .layout-box-collapse','icon',38),(464,NULL,'#layout-box-38 .layout-box-icons .layout-box-uncollapse','icon',38),(465,NULL,'#layout-box-38 .layout-box-content','border-radius',38),(466,NULL,'#layout-box-38 .layout-box-header','border-radius',38),(467,NULL,'#layout-box-38.layout-box-collapsed .layout-box-header, #layout-box-38.layout-box-option-header-false .layout-box-content','border-radius',38),(468,NULL,'#layout-box-38 .layout-box-icons .layout-box-icon','height',38);
DROP TABLE IF EXISTS `layoutboxcssvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxcssvalue` (
  `idlayoutboxcssvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layoutboxid` int(10) unsigned NOT NULL,
  `layoutboxcssid` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `2ndvalue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idlayoutboxcssvalue`),
  KEY `FK_layoutboxcssvalue_layoutboxid` (`layoutboxid`),
  KEY `fk_layoutboxcssid_idlayoutboxcss` (`layoutboxcssid`),
  CONSTRAINT `layoutboxcssvalue_ibfk_1` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `layoutboxcssvalue_ibfk_2` FOREIGN KEY (`layoutboxcssid`) REFERENCES `layoutboxcss` (`idlayoutboxcss`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1648 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutboxcssvalue` (`idlayoutboxcssvalue`, `layoutboxid`, `layoutboxcssid`, `name`, `value`, `2ndvalue`) VALUES (1392,66,389,'top','size','4'),(1393,66,389,'top','colour','7F0000'),(1394,66,389,'right','size','4'),(1395,66,389,'right','colour','7F0000'),(1396,66,389,'bottom','size','4'),(1397,66,389,'bottom','colour','7F0000'),(1398,66,389,'left','size','4'),(1399,66,389,'left','colour','7F0000'),(1400,66,390,'value','4px',NULL),(1401,66,390,'top-right','value','4px'),(1402,66,390,'top-left','value','4px'),(1403,66,390,'bottom-right','value','4px'),(1404,66,390,'bottom-left','value','4px'),(1405,66,391,'value','34',NULL),(1406,66,392,'type','2',NULL),(1407,66,392,'start','f9f9f9',NULL),(1408,66,392,'end','ededed',NULL),(1409,66,392,'gradient_height','34',NULL),(1410,66,392,'position','0 0',NULL),(1411,66,392,'repeat','repeat-x',NULL),(1412,66,393,'type','3',NULL),(1413,66,393,'start','ffffff',NULL),(1414,66,393,'file','layout-box-inner-shadow.png',NULL),(1415,66,393,'position','0 0',NULL),(1416,66,393,'repeat','repeat-x',NULL),(1417,66,394,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1418,66,394,'colour','000000',NULL),(1419,66,394,'bold','1',NULL),(1420,66,394,'italic','0',NULL),(1421,66,394,'underline','0',NULL),(1422,66,394,'uppercase','0',NULL),(1423,66,394,'size','13',NULL),(1424,66,395,'value','left',NULL),(1425,66,396,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1426,66,396,'colour','5f5f5f',NULL),(1427,66,396,'bold','0',NULL),(1428,66,396,'italic','0',NULL),(1429,66,396,'underline','0',NULL),(1430,66,396,'uppercase','0',NULL),(1431,66,396,'size','11',NULL),(1432,66,397,'value','center',NULL),(1433,66,398,'file','icon-close.png',NULL),(1434,66,399,'file','icon-collapse.png',NULL),(1435,66,400,'file','icon-uncollapse.png',NULL),(1436,66,401,'bottom-right','value','3px'),(1437,66,401,'bottom-left','value','3px'),(1438,66,402,'top-right','value','3px'),(1439,66,402,'top-left','value','3px'),(1440,66,403,'top-right','value','3px'),(1441,66,403,'top-left','value','3px'),(1442,66,403,'bottom-right','value','3px'),(1443,66,403,'bottom-left','value','3px'),(1444,66,404,'value','34',NULL),(1545,57,437,'top','size','1'),(1546,57,437,'top','colour','d6d6d6'),(1547,57,437,'right','size','1'),(1548,57,437,'right','colour','d6d6d6'),(1549,57,437,'bottom','size','1'),(1550,57,437,'bottom','colour','d6d6d6'),(1551,57,437,'left','size','1'),(1552,57,437,'left','colour','d6d6d6'),(1553,57,438,'value','4px',NULL),(1554,57,438,'top-right','value','4px'),(1555,57,438,'top-left','value','4px'),(1556,57,438,'bottom-right','value','4px'),(1557,57,438,'bottom-left','value','4px'),(1558,57,439,'value','34',NULL),(1559,57,440,'type','2',NULL),(1560,57,440,'start','f9f9f9',NULL),(1561,57,440,'end','ededed',NULL),(1562,57,440,'gradient_height','34',NULL),(1563,57,440,'position','0 0',NULL),(1564,57,440,'repeat','repeat-x',NULL),(1565,57,441,'type','1',NULL),(1566,57,441,'start','ffffff',NULL),(1567,57,442,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1568,57,442,'colour','000000',NULL),(1569,57,442,'bold','1',NULL),(1570,57,442,'italic','0',NULL),(1571,57,442,'underline','0',NULL),(1572,57,442,'uppercase','0',NULL),(1573,57,442,'size','13',NULL),(1574,57,443,'value','left',NULL),(1575,57,444,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1576,57,444,'colour','5f5f5f',NULL),(1577,57,444,'bold','0',NULL),(1578,57,444,'italic','0',NULL),(1579,57,444,'underline','0',NULL),(1580,57,444,'uppercase','0',NULL),(1581,57,444,'size','11',NULL),(1582,57,445,'value','left',NULL),(1583,57,446,'file','icon-close.png',NULL),(1584,57,447,'file','icon-collapse.png',NULL),(1585,57,448,'file','icon-uncollapse.png',NULL),(1586,57,449,'bottom-right','value','3px'),(1587,57,449,'bottom-left','value','3px'),(1588,57,450,'top-right','value','3px'),(1589,57,450,'top-left','value','3px'),(1590,57,451,'top-right','value','3px'),(1591,57,451,'top-left','value','3px'),(1592,57,451,'bottom-right','value','3px'),(1593,57,451,'bottom-left','value','3px'),(1594,57,452,'value','34',NULL),(1595,38,453,'top','size','4'),(1596,38,453,'top','colour','7F0000'),(1597,38,453,'right','size','4'),(1598,38,453,'right','colour','7F0000'),(1599,38,453,'bottom','size','4'),(1600,38,453,'bottom','colour','7F0000'),(1601,38,453,'left','size','4'),(1602,38,453,'left','colour','7F0000'),(1603,38,454,'value','4px',NULL),(1604,38,454,'top-right','value','4px'),(1605,38,454,'top-left','value','4px'),(1606,38,454,'bottom-right','value','4px'),(1607,38,454,'bottom-left','value','4px'),(1608,38,455,'value','34',NULL),(1609,38,456,'type','2',NULL),(1610,38,456,'start','f9f9f9',NULL),(1611,38,456,'end','ededed',NULL),(1612,38,456,'gradient_height','34',NULL),(1613,38,456,'position','0 0',NULL),(1614,38,456,'repeat','repeat-x',NULL),(1615,38,457,'type','3',NULL),(1616,38,457,'start','ffffff',NULL),(1617,38,457,'file','layout-box-inner-shadow.png',NULL),(1618,38,457,'position','0 0',NULL),(1619,38,457,'repeat','repeat-x',NULL),(1620,38,458,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1621,38,458,'colour','000000',NULL),(1622,38,458,'bold','1',NULL),(1623,38,458,'italic','0',NULL),(1624,38,458,'underline','0',NULL),(1625,38,458,'uppercase','0',NULL),(1626,38,458,'size','13',NULL),(1627,38,459,'value','left',NULL),(1628,38,460,'family','Arial,Arial,Helvetica,sans-serif',NULL),(1629,38,460,'colour','5f5f5f',NULL),(1630,38,460,'bold','0',NULL),(1631,38,460,'italic','0',NULL),(1632,38,460,'underline','0',NULL),(1633,38,460,'uppercase','0',NULL),(1634,38,460,'size','11',NULL),(1635,38,461,'value','left',NULL),(1636,38,462,'file','icon-close.png',NULL),(1637,38,463,'file','icon-collapse.png',NULL),(1638,38,464,'file','icon-uncollapse.png',NULL),(1639,38,465,'bottom-right','value','3px'),(1640,38,465,'bottom-left','value','3px'),(1641,38,466,'top-right','value','3px'),(1642,38,466,'top-left','value','3px'),(1643,38,467,'top-right','value','3px'),(1644,38,467,'top-left','value','3px'),(1645,38,467,'bottom-right','value','3px'),(1646,38,467,'bottom-left','value','3px'),(1647,38,468,'value','34',NULL);
DROP TABLE IF EXISTS `layoutboxjsvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxjsvalue` (
  `idlayoutboxjsvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layoutboxid` int(10) unsigned NOT NULL,
  `variable` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`idlayoutboxjsvalue`),
  KEY `FK_layoutboxjsvalue_layoutboxid` (`layoutboxid`),
  CONSTRAINT `layoutboxjsvalue_ibfk_1` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1708 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutboxjsvalue` (`idlayoutboxjsvalue`, `layoutboxid`, `variable`, `value`) VALUES (186,42,'iDefaultSpan',''),(187,43,'iDefaultSpan',''),(235,55,'iDefaultSpan',''),(236,56,'iDefaultSpan','1'),(260,64,'iDefaultSpan','1'),(272,41,'iDefaultSpan',''),(289,73,'iDefaultSpan','1'),(301,81,'iDefaultSpan','1'),(321,89,'iDefaultSpan','1'),(348,39,'iDefaultSpan',''),(358,58,'iDefaultSpan','1'),(431,35,'iDefaultSpan','1'),(478,54,'iDefaultSpan',''),(525,35,'bClosingProhibited','1'),(528,39,'bClosingProhibited','1'),(530,41,'bClosingProhibited','1'),(531,42,'bClosingProhibited','1'),(532,43,'bClosingProhibited','1'),(541,54,'bClosingProhibited','1'),(542,55,'bClosingProhibited','1'),(543,56,'bClosingProhibited','1'),(545,58,'bClosingProhibited','1'),(549,64,'bClosingProhibited','1'),(553,73,'bClosingProhibited','1'),(556,81,'bClosingProhibited','1'),(562,89,'bClosingProhibited','1'),(605,36,'bClosingProhibited','1'),(606,36,'iDefaultSpan',''),(671,29,'bClosingProhibited','1'),(672,29,'iDefaultSpan',''),(850,103,'iDefaultSpan','1'),(895,106,'iDefaultSpan','1'),(898,100,'bFixedPosition','1'),(899,100,'bClosingProhibited','1'),(900,100,'bNoHeader','1'),(901,100,'bCollapsingProhibited','1'),(902,100,'bExpandingProhibited','1'),(903,100,'iDefaultSpan','1'),(904,82,'bClosingProhibited','1'),(905,82,'iDefaultSpan','1'),(906,51,'bClosingProhibited','1'),(907,51,'iDefaultSpan',''),(911,30,'bClosingProhibited','1'),(912,30,'iDefaultSpan',''),(913,99,'bClosingProhibited','1'),(914,99,'iDefaultSpan','1'),(915,52,'bClosingProhibited','1'),(916,52,'iDefaultSpan',''),(922,46,'bClosingProhibited','1'),(923,46,'iDefaultSpan',''),(1120,109,'bFixedPosition','1'),(1121,109,'bClosingProhibited','1'),(1122,109,'bNoHeader','1'),(1123,109,'bCollapsingProhibited','1'),(1124,109,'bExpandingProhibited','1'),(1125,109,'iDefaultSpan','1'),(1126,109,'iEnableBox','0'),(1134,66,'bFixedPosition','1'),(1135,66,'bClosingProhibited','1'),(1136,66,'bNoHeader','0'),(1137,66,'bCollapsingProhibited','1'),(1138,66,'bExpandingProhibited','1'),(1139,66,'iDefaultSpan','1'),(1140,66,'iEnableBox','0'),(1148,49,'bFixedPosition','1'),(1149,49,'bClosingProhibited','1'),(1150,49,'bNoHeader','0'),(1151,49,'bCollapsingProhibited','1'),(1152,49,'bExpandingProhibited','1'),(1153,49,'iDefaultSpan',''),(1154,49,'iEnableBox','0'),(1260,83,'bFixedPosition','1'),(1261,83,'bClosingProhibited','1'),(1262,83,'bNoHeader','1'),(1263,83,'bCollapsingProhibited','1'),(1264,83,'bExpandingProhibited','1'),(1265,83,'iDefaultSpan','1'),(1266,83,'iEnableBox','0'),(1442,88,'bFixedPosition','0'),(1443,88,'bClosingProhibited','1'),(1444,88,'bNoHeader','0'),(1445,88,'bCollapsingProhibited','0'),(1446,88,'bExpandingProhibited','0'),(1447,88,'iDefaultSpan','1'),(1448,88,'iEnableBox','0'),(1484,62,'bFixedPosition','0'),(1485,62,'bClosingProhibited','1'),(1486,62,'bNoHeader','0'),(1487,62,'bCollapsingProhibited','0'),(1488,62,'bExpandingProhibited','0'),(1489,62,'iDefaultSpan','1'),(1490,62,'iEnableBox','0'),(1498,63,'bFixedPosition','0'),(1499,63,'bClosingProhibited','1'),(1500,63,'bNoHeader','0'),(1501,63,'bCollapsingProhibited','0'),(1502,63,'bExpandingProhibited','0'),(1503,63,'iDefaultSpan','1'),(1504,63,'iEnableBox','0'),(1505,87,'bFixedPosition','0'),(1506,87,'bClosingProhibited','1'),(1507,87,'bNoHeader','0'),(1508,87,'bCollapsingProhibited','0'),(1509,87,'bExpandingProhibited','0'),(1510,87,'iDefaultSpan','1'),(1511,87,'iEnableBox','0'),(1512,86,'bFixedPosition','0'),(1513,86,'bClosingProhibited','1'),(1514,86,'bNoHeader','0'),(1515,86,'bCollapsingProhibited','0'),(1516,86,'bExpandingProhibited','0'),(1517,86,'iDefaultSpan','1'),(1518,86,'iEnableBox','0'),(1519,65,'bFixedPosition','1'),(1520,65,'bClosingProhibited','1'),(1521,65,'bNoHeader','0'),(1522,65,'bCollapsingProhibited','1'),(1523,65,'bExpandingProhibited','1'),(1524,65,'iDefaultSpan','1'),(1525,65,'iEnableBox','0'),(1547,111,'bFixedPosition','1'),(1548,111,'bClosingProhibited','1'),(1549,111,'bNoHeader','0'),(1550,111,'bCollapsingProhibited','1'),(1551,111,'bExpandingProhibited','1'),(1552,111,'iDefaultSpan','1'),(1553,111,'iEnableBox','0'),(1554,112,'bFixedPosition','0'),(1555,112,'bClosingProhibited','0'),(1556,112,'bNoHeader','0'),(1557,112,'bCollapsingProhibited','0'),(1558,112,'bExpandingProhibited','0'),(1559,112,'iDefaultSpan','1'),(1560,112,'iEnableBox','0'),(1568,98,'bFixedPosition','1'),(1569,98,'bClosingProhibited','1'),(1570,98,'bNoHeader','1'),(1571,98,'bCollapsingProhibited','1'),(1572,98,'bExpandingProhibited','1'),(1573,98,'iDefaultSpan','1'),(1574,98,'iEnableBox','0'),(1582,50,'bFixedPosition','1'),(1583,50,'bClosingProhibited','1'),(1584,50,'bNoHeader','0'),(1585,50,'bCollapsingProhibited','1'),(1586,50,'bExpandingProhibited','1'),(1587,50,'iDefaultSpan',''),(1588,50,'iEnableBox','0'),(1589,38,'bFixedPosition','1'),(1590,38,'bClosingProhibited','1'),(1591,38,'bNoHeader','0'),(1592,38,'bCollapsingProhibited','1'),(1593,38,'bExpandingProhibited','1'),(1594,38,'iDefaultSpan','1'),(1595,38,'iEnableBox','0'),(1596,110,'bFixedPosition','1'),(1597,110,'bClosingProhibited','1'),(1598,110,'bNoHeader','1'),(1599,110,'bCollapsingProhibited','1'),(1600,110,'bExpandingProhibited','1'),(1601,110,'iDefaultSpan','1'),(1602,110,'iEnableBox','0'),(1610,105,'bFixedPosition','0'),(1611,105,'bClosingProhibited','1'),(1612,105,'bNoHeader','0'),(1613,105,'bCollapsingProhibited','1'),(1614,105,'bExpandingProhibited','1'),(1615,105,'iDefaultSpan','1'),(1616,105,'iEnableBox','0'),(1687,57,'bFixedPosition','0'),(1688,57,'bClosingProhibited','1'),(1689,57,'bNoHeader','0'),(1690,57,'bCollapsingProhibited','0'),(1691,57,'bExpandingProhibited','0'),(1692,57,'iDefaultSpan','1'),(1693,57,'iEnableBox','0'),(1694,40,'bFixedPosition','0'),(1695,40,'bClosingProhibited','1'),(1696,40,'bNoHeader','0'),(1697,40,'bCollapsingProhibited','0'),(1698,40,'bExpandingProhibited','0'),(1699,40,'iDefaultSpan',''),(1700,40,'iEnableBox','0'),(1701,44,'bFixedPosition','1'),(1702,44,'bClosingProhibited','1'),(1703,44,'bNoHeader','0'),(1704,44,'bCollapsingProhibited','1'),(1705,44,'bExpandingProhibited','1'),(1706,44,'iDefaultSpan',''),(1707,44,'iEnableBox','0');
DROP TABLE IF EXISTS `layoutboxscheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxscheme` (
  `idlayoutboxscheme` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `edittadte` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idlayoutboxscheme`),
  KEY `FK_layoutboxscheme_viewid` (`viewid`),
  KEY `FK_layoutboxscheme_parentid` (`parentid`),
  CONSTRAINT `FK_layoutboxscheme_parentid` FOREIGN KEY (`parentid`) REFERENCES `layoutboxscheme` (`idlayoutboxscheme`),
  CONSTRAINT `FK_layoutboxscheme_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `layoutboxschemecss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxschemecss` (
  `idlayoutboxschemecss` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(60) DEFAULT NULL,
  `selector` varchar(255) NOT NULL,
  `attribute` varchar(45) NOT NULL,
  `layoutboxschemeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idlayoutboxschemecss`),
  KEY `FK_layoutboxschemecss_layoutboxschemeid` (`layoutboxschemeid`),
  CONSTRAINT `FK_layoutboxschemecss_layoutboxschemeid` FOREIGN KEY (`layoutboxschemeid`) REFERENCES `layoutboxscheme` (`idlayoutboxscheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `layoutboxschemecssvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxschemecssvalue` (
  `idlayoutboxschemecssvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layoutboxschemeid` int(10) unsigned NOT NULL,
  `layoutboxschemecssid` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `2ndvalue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idlayoutboxschemecssvalue`),
  KEY `FK_layoutboxschemecssvalue_layoutboxschemeid` (`layoutboxschemeid`),
  KEY `FK_layoutboxschemecssvalue_layoutboxschemecssid` (`layoutboxschemecssid`),
  CONSTRAINT `FK_layoutboxschemecssvalue_layoutboxschemecssid` FOREIGN KEY (`layoutboxschemecssid`) REFERENCES `layoutboxschemecss` (`idlayoutboxschemecss`),
  CONSTRAINT `FK_layoutboxschemecssvalue_layoutboxschemeid` FOREIGN KEY (`layoutboxschemeid`) REFERENCES `layoutboxscheme` (`idlayoutboxscheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `layoutboxtranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `layoutboxtranslation` (
  `idlayoutboxtranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layoutboxid` int(10) unsigned DEFAULT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`idlayoutboxtranslation`),
  KEY `FK_layoutboxtranslation_languageid` (`languageid`),
  KEY `FK_layoutboxtranslation_layoutboxid` (`layoutboxid`),
  CONSTRAINT `layoutboxtranslation_ibfk_1` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `layoutboxtranslation_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=624 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `layoutboxtranslation` (`idlayoutboxtranslation`, `layoutboxid`, `languageid`, `title`) VALUES (9,41,1,'Tagi'),(10,42,1,'Twoje tagi'),(11,43,1,'Twoja lista życzeń'),(23,55,1,'Formularza przypomnienia hasla'),(24,56,1,'Ustawienia klienta'),(30,64,1,'Lista otagowanych produktów'),(35,73,1,'Poleć znajomemu'),(43,81,1,'Newsletter'),(51,89,1,'Kupiono również'),(60,39,1,'Ankieta'),(76,58,1,'Zamowienia klienta'),(226,35,1,'Promocje'),(317,54,1,'Formularz logowania'),(372,36,1,'Nowości'),(386,29,1,'Dane kontaktowe'),(441,103,1,'Filtry'),(473,106,1,'Lista produktów producenta'),(475,100,1,'Reklama'),(476,82,1,'CMS'),(477,51,1,'Rejestracja'),(479,30,1,'Podgląd wiadomości'),(480,99,1,'Mapa sklepu'),(481,52,1,'Potwierdzenie płatności'),(483,46,1,'Koszyk'),(531,109,1,'Facebook'),(533,66,1,'Koszyk'),(535,49,1,'Produkt'),(553,83,1,'Showcase'),(586,88,1,'Akcesoria do produktów'),(592,62,1,'Nowości'),(594,63,1,'Promocje'),(595,87,1,'Produkty podobne'),(596,86,1,'Sprzedaż krzyżowa'),(597,65,1,'Wyszukiwarka'),(601,111,1,'Najczęściej szukano'),(602,112,1,'Wybrane produkty z oferty'),(604,98,1,'Masz pytania ?'),(606,50,1,'Produkty w tej kategorii'),(607,38,1,'Kategorie'),(608,110,1,'Slideshow'),(610,105,1,'Producenci'),(621,57,1,'Adresy klienta'),(622,40,1,'Aktualności'),(623,44,1,'Bestsellery');
DROP TABLE IF EXISTS `missingcart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `missingcart` (
  `idmissingcart` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clientmail` varchar(45) NOT NULL,
  `sessionid` varchar(45) NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idmissingcart`),
  KEY `FK_missingcart_clientid` (`clientid`),
  KEY `FK_missingcart_viewid` (`viewid`),
  CONSTRAINT `FK_missingcart_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_missingcart_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `missingcartproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `missingcartproduct` (
  `idmissingcartproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `missingcartid` int(10) unsigned NOT NULL,
  `productid` int(10) unsigned NOT NULL,
  `stock` int(10) unsigned NOT NULL,
  `qty` int(10) unsigned NOT NULL,
  `productattributesetid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idmissingcartproduct`),
  KEY `FK_missingcartsproducts_productid` (`productid`),
  KEY `FK_missingcartsproducts_missingcartid` (`missingcartid`),
  KEY `FK_missingcartproduct_viewid` (`viewid`),
  CONSTRAINT `FK_missingcartproduct_missingcartid` FOREIGN KEY (`missingcartid`) REFERENCES `missingcart` (`idmissingcart`),
  CONSTRAINT `FK_missingcartproduct_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`),
  CONSTRAINT `FK_missingcartsproducts_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `modulesettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulesettings` (
  `idmodulesettings` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `viewid` int(11) DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idmodulesettings`),
  UNIQUE KEY `UNIQUE_modulesettings_param_module_viewid` (`param`,`module`,`viewid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `mostsearch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mostsearch` (
  `idmostsearch` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `textcount` int(10) unsigned NOT NULL DEFAULT '1',
  `viewid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idmostsearch`),
  KEY `FK_mostsearch_viewid` (`viewid`),
  CONSTRAINT `FK_mostsearch_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `idnews` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publish` int(10) unsigned NOT NULL DEFAULT '1',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `featured` int(11) NOT NULL,
  PRIMARY KEY (`idnews`),
  KEY `FK_news_addid` (`addid`),
  KEY `FK_news_editid` (`editid`),
  KEY `FK_news_parentid` (`parentid`),
  KEY `FK_news_viewid` (`viewid`),
  CONSTRAINT `FK_news_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_news_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_news_parentid` FOREIGN KEY (`parentid`) REFERENCES `news` (`idnews`),
  CONSTRAINT `FK_news_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `idnewsletter` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `htmlform` varchar(5000) NOT NULL,
  `textform` varchar(5000) NOT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idnewsletter`),
  UNIQUE KEY `UNIQUE_newsletter_name` (`name`),
  KEY `FK_newsletter_addid` (`addid`),
  KEY `FK_newsletter_editid` (`editid`),
  KEY `FK_newsletter_parentid` (`parentid`),
  CONSTRAINT `FK_newsletter_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_newsletter_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_newsletter_parentid` FOREIGN KEY (`parentid`) REFERENCES `newsletter` (`idnewsletter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `newsphoto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsphoto` (
  `idnewsphoto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `mainphoto` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idnewsphoto`),
  UNIQUE KEY `UNIQUE_newsphoto_newsid_photoid` (`newsid`,`photoid`),
  KEY `FK_newsphoto_photoid` (`photoid`),
  KEY `FK_newsphoto_addid` (`addid`),
  KEY `FK_newsphoto_editid` (`editid`),
  CONSTRAINT `FK_newsphoto_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_newsphoto_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_newsphoto_newsid` FOREIGN KEY (`newsid`) REFERENCES `news` (`idnews`),
  CONSTRAINT `FK_newsphoto_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `newstranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newstranslation` (
  `idnewstranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL,
  `topic` varchar(128) NOT NULL,
  `summary` text,
  `content` text NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `seo` varchar(255) DEFAULT NULL,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `keyword_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idnewstranslation`),
  UNIQUE KEY `UNIQUE_newstranslation_newsid_languageid` (`newsid`,`languageid`),
  KEY `FK_newstranslation_newsid` (`newsid`),
  CONSTRAINT `FK_newstranslation_newsid` FOREIGN KEY (`newsid`) REFERENCES `news` (`idnews`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `newsview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsview` (
  `idnewsview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idnewsview`),
  UNIQUE KEY `UNIQUE_newsview_newsid_viewid` (`newsid`,`viewid`),
  KEY `FK_newsview_newsid` (`newsid`),
  KEY `FK_newsview_viewid` (`viewid`),
  KEY `FK_newsview_addid` (`addid`),
  CONSTRAINT `FK_newsview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_newsview_newsid` FOREIGN KEY (`newsid`) REFERENCES `news` (`idnews`),
  CONSTRAINT `FK_newsview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `idorder` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `price` decimal(16,2) NOT NULL,
  `dispatchmethodprice` decimal(16,2) NOT NULL,
  `globalprice` decimal(16,2) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `orderstatusid` int(10) unsigned NOT NULL,
  `dispatchmethodname` varchar(64) DEFAULT NULL,
  `paymentmethodname` varchar(64) DEFAULT NULL,
  `globalqty` int(10) NOT NULL,
  `dispatchmethodid` int(10) unsigned NOT NULL,
  `paymentmethodid` int(10) unsigned NOT NULL,
  `clientid` int(10) DEFAULT NULL,
  `globalpricenetto` decimal(16,2) NOT NULL,
  `addid` int(10) unsigned DEFAULT NULL,
  `activelink` varchar(100) DEFAULT NULL,
  `customeropinion` varchar(5000) DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `pricebeforepromotion` decimal(10,2) DEFAULT NULL,
  `eratyproposal` varchar(45) DEFAULT NULL,
  `eratyaccept` int(10) unsigned DEFAULT NULL,
  `eratycancel` int(10) unsigned DEFAULT NULL,
  `currencyid` int(10) unsigned NOT NULL,
  `currencysymbol` varchar(5) NOT NULL,
  `currencyrate` decimal(10,4) DEFAULT NULL,
  `rulescartid` int(10) unsigned DEFAULT NULL,
  `sessionid` varchar(255) NOT NULL,
  `couponcode` varchar(45) DEFAULT NULL,
  `coupondiscount` decimal(10,4) DEFAULT NULL,
  `couponfreedelivery` int(10) DEFAULT NULL,
  `couponid` int(10) DEFAULT NULL,
  `orderid` int(11) DEFAULT NULL,
  `paczkomat` varchar(45) DEFAULT NULL,
  `inpostpackage` varchar(45) DEFAULT NULL,
  `packagestatus` varchar(45) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`idorder`),
  KEY `FK_order_orderstatusid` (`orderstatusid`),
  KEY `FK_order_viewid` (`viewid`),
  CONSTRAINT `FK_order_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderclientdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderclientdata` (
  `idorderclientdata` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` blob NOT NULL,
  `surname` blob NOT NULL,
  `companyname` blob,
  `NIP` blob,
  `street` blob NOT NULL,
  `streetno` blob NOT NULL,
  `placeno` blob,
  `postcode` blob NOT NULL,
  `place` blob NOT NULL,
  `placeid` int(10) unsigned DEFAULT NULL,
  `phone` blob,
  `email` blob,
  `orderid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clientid` int(10) unsigned DEFAULT '0',
  `countryid` int(10) DEFAULT NULL,
  PRIMARY KEY (`idorderclientdata`),
  KEY `FK_orderclientdata_orderid` (`orderid`),
  CONSTRAINT `FK_orderclientdata_orderid` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderclientdeliverydata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderclientdeliverydata` (
  `idorderclientdeliverydata` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` blob NOT NULL,
  `surname` blob NOT NULL,
  `companyname` blob,
  `NIP` blob,
  `street` blob NOT NULL,
  `streetno` blob NOT NULL,
  `placeno` blob,
  `postcode` blob NOT NULL,
  `place` blob NOT NULL,
  `placeid` int(10) unsigned DEFAULT NULL,
  `phone` blob,
  `email` blob,
  `orderid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `countryid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idorderclientdeliverydata`),
  KEY `FK_orderclientdeliverydata_orderid` (`orderid`),
  CONSTRAINT `FK_orderclientdeliverydata_orderid` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderfiles` (
  `idorderfiles` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`idorderfiles`),
  KEY `FK_orderfiles_orderid` (`orderid`),
  CONSTRAINT `FK_orderfiles_orderid` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderhistory` (
  `idorderhistory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(5000) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `orderstatusid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `inform` int(10) unsigned DEFAULT '0',
  `storeid` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idorderhistory`),
  KEY `FK_orderhistory_addid` (`addid`),
  KEY `FK_orderhistory_editid` (`editid`),
  KEY `FK_orderhistory_orderstatusid` (`orderstatusid`),
  KEY `FK_orderhistory_orderid` (`orderid`),
  CONSTRAINT `FK_orderhistory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderhistory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderhistory_orderid` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`),
  CONSTRAINT `FK_orderhistory_orderstatusid` FOREIGN KEY (`orderstatusid`) REFERENCES `orderstatus` (`idorderstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `ordernotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordernotes` (
  `idordernotes` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idordernotes`),
  KEY `FK_ordernotes_addid` (`addid`),
  KEY `FK_ordernotes_orderid` (`orderid`),
  CONSTRAINT `FK_ordernotes_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_ordernotes_orderid` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderproduct` (
  `idorderproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `qty` decimal(16,4) unsigned NOT NULL,
  `qtyprice` decimal(16,2) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `orderid` int(10) unsigned NOT NULL,
  `productid` int(10) unsigned DEFAULT NULL,
  `productattributesetid` int(10) unsigned DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `editid` int(10) unsigned DEFAULT NULL,
  `vat` decimal(16,2) NOT NULL,
  `pricenetto` decimal(16,4) NOT NULL,
  PRIMARY KEY (`idorderproduct`),
  KEY `FK_orderproduct_orderid` (`orderid`),
  KEY `FK_orderproduct_productid` (`productid`),
  KEY `FK_orderproduct_editid` (`editid`),
  CONSTRAINT `FK_orderproduct_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `orderproduct_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `order` (`idorder`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orderproduct_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderproductattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderproductattribute` (
  `idorderproductattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `orderproductid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idorderproductattribute`),
  KEY `FK_orderproductattribute_orderproductid` (`orderproductid`),
  CONSTRAINT `FK_orderproductattribute_orderproductid` FOREIGN KEY (`orderproductid`) REFERENCES `orderproduct` (`idorderproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderproductnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderproductnotes` (
  `idorderproductnotes` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `content` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idorderproductnotes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orderstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatus` (
  `idorderstatus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `default` int(10) unsigned NOT NULL DEFAULT '0',
  `editable` int(10) unsigned NOT NULL DEFAULT '1',
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idorderstatus`),
  KEY `FK_orderstatus_addid` (`addid`),
  KEY `FK_orderstatus_editid` (`editid`),
  KEY `FK_orderstatus_parentid` (`parentid`),
  CONSTRAINT `FK_orderstatus_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderstatus_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderstatus_parentid` FOREIGN KEY (`parentid`) REFERENCES `orderstatus` (`idorderstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `orderstatus` (`idorderstatus`, `addid`, `adddate`, `editid`, `editdate`, `default`, `editable`, `parentid`) VALUES (6,1,'2011-08-21 20:00:07',1,NULL,1,0,NULL),(7,1,'2011-03-03 22:19:36',NULL,NULL,0,0,NULL),(8,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(9,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(10,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(11,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(12,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(13,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(16,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(17,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(18,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(19,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(20,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(21,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(22,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(23,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(24,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(25,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(26,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(27,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(28,1,'2010-09-23 11:06:45',NULL,NULL,0,0,NULL),(29,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(30,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(31,1,'2010-09-23 11:06:45',NULL,NULL,0,1,NULL),(32,1,'2011-04-19 19:49:19',NULL,NULL,0,1,NULL);
DROP TABLE IF EXISTS `orderstatusgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatusgroups` (
  `idorderstatusgroups` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idorderstatusgroups`),
  KEY `FK_orderstatusgroups_addid` (`addid`),
  KEY `FK_orderstatusgroups_editid` (`editid`),
  CONSTRAINT `FK_orderstatusgroups_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderstatusgroups_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `orderstatusgroups` (`idorderstatusgroups`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,1,'2010-06-25 10:23:58',1,NULL),(2,1,'2010-06-25 10:23:58',1,NULL),(3,1,'2010-06-25 10:23:58',1,NULL),(4,1,'2010-06-25 10:23:58',1,NULL),(5,1,'2010-09-23 11:07:52',1,NULL),(6,1,'2010-09-23 11:07:52',1,NULL);
DROP TABLE IF EXISTS `orderstatusgroupstranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatusgroupstranslation` (
  `idorderstatusgroupstranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `orderstatusgroupsid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idorderstatusgroupstranslation`),
  KEY `FK_orderstatusgroups_orderstatusgroupsid` (`orderstatusgroupsid`),
  KEY `FK_orderstatusgroups_languageid` (`languageid`),
  CONSTRAINT `FK_orderstatusgroups_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_orderstatusgroups_orderstatusgroupsid` FOREIGN KEY (`orderstatusgroupsid`) REFERENCES `orderstatusgroups` (`idorderstatusgroups`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `orderstatusgroupstranslation` (`idorderstatusgroupstranslation`, `name`, `orderstatusgroupsid`, `languageid`) VALUES (3,'Brak potwierdzenia',1,1),(5,'Oczekuje na płatność',2,1),(7,'Oczekuje na wysyłkę',3,1),(9,'Zrealizowane',4,1),(10,'System ratalny- wniosek przyjęty',5,1),(12,'System ratalny- wniosek anulowany lub rezygnacja',6,1);
DROP TABLE IF EXISTS `orderstatusorderstatusgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatusorderstatusgroups` (
  `idorderstatusorderstatusgroups` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderstatusid` int(10) unsigned NOT NULL,
  `orderstatusgroupsid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idorderstatusorderstatusgroups`),
  KEY `FK_orderstatusorderstatusgroups_addid` (`addid`),
  KEY `FK_orderstatusorderstatusgroups_editid` (`editid`),
  KEY `FK_orderstatusorderstatusgroups_orderstatusid` (`orderstatusid`),
  KEY `FK_orderstatusorderstatusgroups_orderstatusgroupsid` (`orderstatusgroupsid`),
  CONSTRAINT `FK_orderstatusorderstatusgroups_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderstatusorderstatusgroups_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_orderstatusorderstatusgroups_orderstatusgroupsid` FOREIGN KEY (`orderstatusgroupsid`) REFERENCES `orderstatusgroups` (`idorderstatusgroups`),
  CONSTRAINT `FK_orderstatusorderstatusgroups_orderstatusid` FOREIGN KEY (`orderstatusid`) REFERENCES `orderstatus` (`idorderstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `orderstatusorderstatusgroups` (`idorderstatusorderstatusgroups`, `orderstatusid`, `orderstatusgroupsid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (57,7,1,1,'2010-01-26 13:50:38',1,NULL),(58,8,2,1,'2010-01-26 13:50:49',1,NULL),(59,9,1,1,'2010-01-26 13:50:59',1,NULL),(60,10,3,1,'2010-01-26 13:51:10',1,NULL),(61,11,1,1,'2010-01-26 13:51:18',1,NULL),(62,12,1,1,'2010-01-26 13:51:26',1,NULL),(63,13,1,1,'2010-01-26 13:51:36',1,NULL),(64,16,1,1,'2010-01-26 13:51:45',1,NULL),(65,17,1,1,'2010-01-26 13:51:54',1,NULL),(66,18,2,1,'2010-01-26 13:52:09',1,NULL),(67,19,1,1,'2010-01-26 13:52:16',1,NULL),(68,20,1,1,'2010-01-26 13:52:24',1,NULL),(69,21,1,1,'2010-01-26 13:52:35',1,NULL),(70,22,1,1,'2010-01-26 13:52:44',1,NULL),(71,23,1,1,'2010-01-26 13:52:53',1,NULL),(72,24,1,1,'2010-01-26 13:53:03',1,NULL),(73,25,1,1,'2010-01-26 13:53:12',1,NULL),(74,26,1,1,'2010-01-26 13:53:20',1,NULL),(75,27,1,1,'2010-01-26 13:53:30',1,NULL),(76,28,1,1,'2010-01-26 13:53:41',1,NULL),(77,11,4,1,'2010-03-18 12:53:38',1,NULL),(78,12,4,1,'2010-03-18 12:53:38',1,NULL),(79,23,4,1,'2010-03-18 12:53:38',1,NULL),(80,25,4,1,'2010-03-18 12:53:38',1,NULL),(81,26,4,1,'2010-03-18 12:53:38',1,NULL),(82,27,4,1,'2010-03-18 12:53:38',1,NULL),(83,28,4,1,'2010-03-18 12:53:38',1,NULL),(84,29,5,1,'2010-07-09 10:28:40',1,NULL),(85,30,6,1,'2010-07-09 10:29:22',1,NULL),(86,31,6,1,'2010-07-09 10:29:58',1,NULL),(87,32,4,1,'2011-04-19 19:49:19',NULL,NULL),(88,6,1,1,'2011-08-21 20:00:07',NULL,NULL);
DROP TABLE IF EXISTS `orderstatustranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderstatustranslation` (
  `idorderstatustranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `orderstatusid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`idorderstatustranslation`),
  UNIQUE KEY `UNIQUE_orderstatustranslation_languageid_name` (`languageid`,`name`),
  KEY `FK_orderstatustranslation_orderstatusid` (`orderstatusid`),
  KEY `FK_orderstatustranslation_languageid` (`languageid`),
  CONSTRAINT `FK_orderstatus_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_orderstatus_orderstatusid` FOREIGN KEY (`orderstatusid`) REFERENCES `orderstatus` (`idorderstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `orderstatustranslation` (`idorderstatustranslation`, `name`, `orderstatusid`, `languageid`, `comment`) VALUES (3,'Oczekuje na wybór płatności online',7,1,NULL),(5,'Oczekuje na odbiór osobisty. Płatność przy odbiorze',8,1,NULL),(7,'Oczekuje na płatność przelewem. Przelew bankowym',9,1,NULL),(9,'Przygotowane do wysyłki',10,1,NULL),(11,'Opłacone online',11,1,NULL),(13,'Wysłane',12,1,NULL),(15,'Anulowane',13,1,NULL),(17,'Platnosci.pl [nowa]',16,1,NULL),(19,'Platnosci.pl [anulowana]',17,1,NULL),(21,'Platnosci.pl [odrzucona]',18,1,NULL),(23,'Platnosci.pl [rozpoczeta]',19,1,NULL),(25,'Platnosci.pl [oczekuje na odbior]',20,1,NULL),(27,'Platnosci.pl [autoryzacja odmowna]',21,1,NULL),(29,'Platnosci.pl [srodki odrzucone]',22,1,NULL),(31,'Platnosci.pl [zakonczona]',23,1,NULL),(33,'Platnosci.pl [bledny status]',24,1,NULL),(35,'Zamówienie potwierdzone. Płatność za pobraniem.',25,1,NULL),(37,'Zapłacono.',26,1,NULL),(39,'Zapłacono przelewem bankowym. Zamówienie w trakcie realizacji.',27,1,NULL),(41,'Zamówienie potwierdzone. Płatność gotówką przy odbiorze osobistym.',28,1,NULL),(42,'Żagiel [Zapisany]',29,1,NULL),(44,'Żagiel [Anulowany]',30,1,NULL),(46,'Żagiel [Rezygnacja]',31,1,NULL),(47,'Dostarczone',32,1,NULL),(48,'Brak potwierdzenia zamówienia',6,1,'Witaj, Twoje zamówienie nie jest potwierdzone');
DROP TABLE IF EXISTS `pagescheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagescheme` (
  `idpagescheme` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `default` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idpagescheme`),
  KEY `FK_pagescheme_viewid` (`viewid`),
  KEY `FK_pagescheme_addid` (`addid`),
  KEY `FK_pagescheme_editid` (`editid`),
  CONSTRAINT `FK_pagescheme_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_pagescheme_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_pagescheme_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `pagescheme` (`idpagescheme`, `name`, `viewid`, `addid`, `adddate`, `editid`, `default`) VALUES (19,'Szablon domyślny',NULL,1,'2010-08-23 10:30:36',1,1);
DROP TABLE IF EXISTS `pageschemecss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pageschemecss` (
  `idpageschemecss` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(45) DEFAULT NULL,
  `selector` varchar(255) NOT NULL,
  `attribute` varchar(45) DEFAULT NULL,
  `pageschemeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idpageschemecss`),
  KEY `pageschemeid` (`pageschemeid`)
) ENGINE=InnoDB AUTO_INCREMENT=28018 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `pageschemecss` (`idpageschemecss`, `class`, `selector`, `attribute`, `pageschemeid`) VALUES (25111,NULL,'#main-container','width',20),(25112,NULL,'body','font',20),(25113,NULL,'body','background',20),(25114,NULL,'p','margin-bottom',20),(25115,NULL,'p','line-height',20),(25116,NULL,'a','font',20),(25117,NULL,'a:hover, a:focus, a:active','font',20),(25118,NULL,'#header h1, #header h1 a','font',20),(25119,NULL,'#header','font',20),(25120,NULL,'#header-cart-summary h2, #header-cart-summary h2 a, #header-cart-summary h2 a:hover, #header-cart-summary h2 a:focus, #header-cart-summary h2 a:active','font',20),(25121,NULL,'#header-cart-summary a','font',20),(25122,NULL,'#header-cart-summary a:hover, #header-cart-summary a:focus, #header-cart-summary a:active','font',20),(25123,NULL,'#cart-preview','background',20),(25124,NULL,'#cart-preview a','font',20),(25125,NULL,'#cart-preview a:hover, #cart-preview a:focus, #cart-preview a:active','font',20),(25126,NULL,'#horizontal-navigation ul li a','height',20),(25127,NULL,'#horizontal-navigation','border',20),(25128,NULL,'#horizontal-navigation, #horizontal-navigation ul li a','border-radius',20),(25129,NULL,'#horizontal-navigation','background',20),(25130,NULL,'#horizontal-navigation li','background',20),(25131,NULL,'#horizontal-navigation ul li.active a','background',20),(25132,NULL,'#horizontal-navigation ul li a:hover, #horizontal-navigation ul li a:focus, #horizontal-navigation ul li a:active','background',20),(25133,NULL,'#horizontal-navigation ul li a','font',20),(25134,NULL,'#horizontal-navigation ul li.active a','font',20),(25135,NULL,'#horizontal-navigation ul li a:hover, #horizontal-navigation ul li a:focus, #horizontal-navigation ul li a:active','font',20),(25136,NULL,'#footer','border-radius',20),(25137,NULL,'#footer','border',20),(25138,NULL,'#footer','background',20),(25139,NULL,'#footer','font',20),(25140,NULL,'#footer a','font',20),(25141,NULL,'#footer a:hover, #footer a:focus, #footer a:active','font',20),(25142,NULL,'#footer h4','font',20),(25143,NULL,'#footer h4','border',20),(25144,NULL,'#copyright-bar','font',20),(25145,NULL,'#copyright-bar a','font',20),(25146,NULL,'#copyright-bar a:hover, #copyright-bar a:focus, #copyright-bar a:active','font',20),(25147,NULL,'.layout-box-type-product-list .pagination li a','font',20),(25148,NULL,'.layout-box-type-product-list .pagination li a:hover, .layout-box-type-product-list .pagination li a:focus, .layout-box-type-product-list .pagination li a:active','font',20),(25149,NULL,'.layout-box-type-product-list .pagination li.active a','background',20),(25150,NULL,'.layout-box-type-product-list .pagination li.disabled a','font',20),(25151,NULL,'.layout-box.layout-box','border',20),(25152,NULL,'.layout-box.layout-box','border-radius',20),(25153,NULL,'.layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1, .layout-box .layout-box-header, .layout-box .layout-box-icons','line-height',20),(25154,NULL,'.layout-box .layout-box-header','background',20),(25155,NULL,'.layout-box .layout-box-content','background',20),(25156,NULL,'.layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1','font',20),(25157,NULL,'.layout-box .layout-box-header, .layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1','text-align',20),(25158,NULL,'.layout-box .layout-box-content, .layout-box .layout-box-content p','font',20),(25159,NULL,'.layout-box .layout-box-content, .layout-box .layout-box-content p','text-align',20),(25160,NULL,'.layout-box .layout-box-icons .layout-box-close','icon',20),(25161,NULL,'.layout-box .layout-box-icons .layout-box-collapse','icon',20),(25162,NULL,'.layout-box .layout-box-icons .layout-box-uncollapse','icon',20),(25163,NULL,'.layout-box-content','border-radius',20),(25164,NULL,'.layout-box-header','border-radius',20),(25165,NULL,'.layout-box-collapsed .layout-box-header, .layout-box-option-header-false .layout-box-content','border-radius',20),(25166,NULL,'#horizontal-navigation ul li a','line-height',20),(25167,NULL,'.layout-box-icons .layout-box-icon','height',20),(27961,NULL,'#main-container','width',19),(27962,NULL,'body','font',19),(27963,NULL,'body','background',19),(27964,NULL,'p','margin-bottom',19),(27965,NULL,'p','line-height',19),(27966,NULL,'a','font',19),(27967,NULL,'a:hover, a:focus, a:active','font',19),(27968,NULL,'#header h1, #header h1 a','font',19),(27969,NULL,'#header','font',19),(27970,NULL,'#header-cart-summary h2, #header-cart-summary h2 a, #header-cart-summary h2 a:hover, #header-cart-summary h2 a:focus, #header-cart-summary h2 a:active','font',19),(27971,NULL,'#header-cart-summary a','font',19),(27972,NULL,'#header-cart-summary a:hover, #header-cart-summary a:focus, #header-cart-summary a:active','font',19),(27973,NULL,'#cart-preview','background',19),(27974,NULL,'#cart-preview a','font',19),(27975,NULL,'#cart-preview a:hover, #cart-preview a:focus, #cart-preview a:active','font',19),(27976,NULL,'#horizontal-navigation ul li a','height',19),(27977,NULL,'#horizontal-navigation','border',19),(27978,NULL,'#horizontal-navigation, #horizontal-navigation ul li a','border-radius',19),(27979,NULL,'#horizontal-navigation','background',19),(27980,NULL,'#horizontal-navigation li','background',19),(27981,NULL,'#horizontal-navigation ul li.active a','background',19),(27982,NULL,'#horizontal-navigation ul li a:hover, #horizontal-navigation ul li a:focus, #horizontal-navigation ul li a:active','background',19),(27983,NULL,'#horizontal-navigation ul li a','font',19),(27984,NULL,'#horizontal-navigation ul li.active a','font',19),(27985,NULL,'#horizontal-navigation ul li a:hover, #horizontal-navigation ul li a:focus, #horizontal-navigation ul li a:active','font',19),(27986,NULL,'#footer','border-radius',19),(27987,NULL,'#footer','border',19),(27988,NULL,'#footer','background',19),(27989,NULL,'#footer','font',19),(27990,NULL,'#footer a','font',19),(27991,NULL,'#footer a:hover, #footer a:focus, #footer a:active','font',19),(27992,NULL,'#footer h4','font',19),(27993,NULL,'#footer h4','border',19),(27994,NULL,'#copyright-bar','font',19),(27995,NULL,'#copyright-bar a','font',19),(27996,NULL,'#copyright-bar a:hover, #copyright-bar a:focus, #copyright-bar a:active','font',19),(27997,NULL,'.layout-box-type-product-list .pagination li a','font',19),(27998,NULL,'.layout-box-type-product-list .pagination li a:hover, .layout-box-type-product-list .pagination li a:focus, .layout-box-type-product-list .pagination li a:active','font',19),(27999,NULL,'.layout-box-type-product-list .pagination li.active a','background',19),(28000,NULL,'.layout-box-type-product-list .pagination li.disabled a','font',19),(28001,NULL,'.layout-box.layout-box','border',19),(28002,NULL,'.layout-box.layout-box','border-radius',19),(28003,NULL,'.layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1, .layout-box .layout-box-header, .layout-box .layout-box-icons','line-height',19),(28004,NULL,'.layout-box .layout-box-header','background',19),(28005,NULL,'.layout-box .layout-box-content','background',19),(28006,NULL,'.layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1','font',19),(28007,NULL,'.layout-box .layout-box-header, .layout-box .layout-box-header h3, .layout-box .layout-box-header h2, .layout-box .layout-box-header h1','text-align',19),(28008,NULL,'.layout-box .layout-box-content, .layout-box .layout-box-content p','font',19),(28009,NULL,'.layout-box .layout-box-content, .layout-box .layout-box-content p','text-align',19),(28010,NULL,'.layout-box .layout-box-icons .layout-box-close','icon',19),(28011,NULL,'.layout-box .layout-box-icons .layout-box-collapse','icon',19),(28012,NULL,'.layout-box .layout-box-icons .layout-box-uncollapse','icon',19),(28013,NULL,'.layout-box-content','border-radius',19),(28014,NULL,'.layout-box-header','border-radius',19),(28015,NULL,'.layout-box-collapsed .layout-box-header, .layout-box-option-header-false .layout-box-content','border-radius',19),(28016,NULL,'#horizontal-navigation ul li a','line-height',19),(28017,NULL,'.layout-box-icons .layout-box-icon','height',19);
DROP TABLE IF EXISTS `pageschemecssvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pageschemecssvalue` (
  `idpageschemecssvalue` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pageschemeid` int(10) unsigned NOT NULL,
  `pageschemecssid` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `2ndvalue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idpageschemecssvalue`),
  KEY `FK_pageschemecssvalue_pageschemeid` (`pageschemeid`),
  KEY `FK_pageschemecssvalue_pageschemecssid` (`pageschemecssid`),
  CONSTRAINT `FK_pageschemecssvalue_pageschemecssid` FOREIGN KEY (`pageschemecssid`) REFERENCES `pageschemecss` (`idpageschemecss`),
  CONSTRAINT `FK_pageschemecssvalue_pageschemeid` FOREIGN KEY (`pageschemeid`) REFERENCES `pagescheme` (`idpagescheme`)
) ENGINE=InnoDB AUTO_INCREMENT=137910 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `pageschemecssvalue` (`idpageschemecssvalue`, `pageschemeid`, `pageschemecssid`, `name`, `value`, `2ndvalue`) VALUES (137619,19,27961,'value','959',NULL),(137620,19,27962,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137621,19,27962,'colour','333434',NULL),(137622,19,27962,'bold','0',NULL),(137623,19,27962,'italic','0',NULL),(137624,19,27962,'underline','0',NULL),(137625,19,27962,'uppercase','0',NULL),(137626,19,27962,'size','11',NULL),(137627,19,27963,'type','2',NULL),(137628,19,27963,'start','ffffff',NULL),(137629,19,27963,'end','f2f2f2',NULL),(137630,19,27963,'gradient_height','200',NULL),(137631,19,27963,'position','0 0',NULL),(137632,19,27963,'repeat','repeat-x',NULL),(137633,19,27964,'value','10px',NULL),(137634,19,27965,'value','1.5em',NULL),(137635,19,27966,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137636,19,27966,'colour','bf3131',NULL),(137637,19,27966,'bold','0',NULL),(137638,19,27966,'italic','0',NULL),(137639,19,27966,'underline','0',NULL),(137640,19,27966,'uppercase','0',NULL),(137641,19,27966,'size','11',NULL),(137642,19,27967,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137643,19,27967,'colour','bf3131',NULL),(137644,19,27967,'bold','0',NULL),(137645,19,27967,'italic','0',NULL),(137646,19,27967,'underline','1',NULL),(137647,19,27967,'uppercase','0',NULL),(137648,19,27967,'size','11',NULL),(137649,19,27968,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137650,19,27968,'colour','000000',NULL),(137651,19,27968,'bold','1',NULL),(137652,19,27968,'italic','0',NULL),(137653,19,27968,'underline','0',NULL),(137654,19,27968,'uppercase','1',NULL),(137655,19,27968,'size','24',NULL),(137656,19,27969,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137657,19,27969,'colour','000000',NULL),(137658,19,27969,'bold','0',NULL),(137659,19,27969,'italic','0',NULL),(137660,19,27969,'underline','0',NULL),(137661,19,27969,'uppercase','0',NULL),(137662,19,27969,'size','11',NULL),(137663,19,27970,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137664,19,27970,'colour','000000',NULL),(137665,19,27970,'bold','0',NULL),(137666,19,27970,'italic','0',NULL),(137667,19,27970,'underline','0',NULL),(137668,19,27970,'uppercase','0',NULL),(137669,19,27970,'size','11',NULL),(137670,19,27971,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137671,19,27971,'colour','bf3131',NULL),(137672,19,27971,'bold','0',NULL),(137673,19,27971,'italic','0',NULL),(137674,19,27971,'underline','0',NULL),(137675,19,27971,'uppercase','0',NULL),(137676,19,27971,'size','11',NULL),(137677,19,27972,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137678,19,27972,'colour','bf3131',NULL),(137679,19,27972,'bold','0',NULL),(137680,19,27972,'italic','0',NULL),(137681,19,27972,'underline','1',NULL),(137682,19,27972,'uppercase','0',NULL),(137683,19,27972,'size','11',NULL),(137684,19,27973,'type','3',NULL),(137685,19,27973,'start','ffffff',NULL),(137686,19,27973,'file','cart.png',NULL),(137687,19,27973,'position','0 0',NULL),(137688,19,27973,'repeat','no-repeat',NULL),(137689,19,27974,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137690,19,27974,'colour','000000',NULL),(137691,19,27974,'bold','0',NULL),(137692,19,27974,'italic','0',NULL),(137693,19,27974,'underline','0',NULL),(137694,19,27974,'uppercase','0',NULL),(137695,19,27974,'size','11',NULL),(137696,19,27975,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137697,19,27975,'colour','000000',NULL),(137698,19,27975,'bold','0',NULL),(137699,19,27975,'italic','0',NULL),(137700,19,27975,'underline','1',NULL),(137701,19,27975,'uppercase','0',NULL),(137702,19,27975,'size','11',NULL),(137703,19,27976,'value','42',NULL),(137704,19,27977,'top','size','1'),(137705,19,27977,'top','colour','7f0000'),(137706,19,27977,'right','size','1'),(137707,19,27977,'right','colour','7f0000'),(137708,19,27977,'bottom','size','1'),(137709,19,27977,'bottom','colour','7f0000'),(137710,19,27977,'left','size','1'),(137711,19,27977,'left','colour','7f0000'),(137712,19,27978,'value','4px',NULL),(137713,19,27978,'top-right','value','4px'),(137714,19,27978,'top-left','value','4px'),(137715,19,27978,'bottom-right','value','4px'),(137716,19,27978,'bottom-left','value','4px'),(137717,19,27979,'type','2',NULL),(137718,19,27979,'start','b00000',NULL),(137719,19,27979,'end','850000',NULL),(137720,19,27979,'gradient_height','30',NULL),(137721,19,27979,'position','0 0',NULL),(137722,19,27979,'repeat','repeat-x',NULL),(137723,19,27980,'type','3',NULL),(137724,19,27980,'start','8d0000',NULL),(137725,19,27980,'file','horizontal-navigation-item.png',NULL),(137726,19,27980,'position','0 0',NULL),(137727,19,27980,'repeat','no-repeat',NULL),(137728,19,27981,'type','2',NULL),(137729,19,27981,'start','8d0000',NULL),(137730,19,27981,'end','660000',NULL),(137731,19,27981,'gradient_height','30',NULL),(137732,19,27981,'position','0 0',NULL),(137733,19,27981,'repeat','repeat-x',NULL),(137734,19,27982,'type','2',NULL),(137735,19,27982,'start','8d0000',NULL),(137736,19,27982,'end','660000',NULL),(137737,19,27982,'gradient_height','30',NULL),(137738,19,27982,'position','0 0',NULL),(137739,19,27982,'repeat','repeat-x',NULL),(137740,19,27983,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137741,19,27983,'colour','f9f9f9',NULL),(137742,19,27983,'bold','1',NULL),(137743,19,27983,'italic','0',NULL),(137744,19,27983,'underline','0',NULL),(137745,19,27983,'uppercase','0',NULL),(137746,19,27983,'size','14',NULL),(137747,19,27984,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137748,19,27984,'colour','f9f9f9',NULL),(137749,19,27984,'bold','1',NULL),(137750,19,27984,'italic','0',NULL),(137751,19,27984,'underline','0',NULL),(137752,19,27984,'uppercase','0',NULL),(137753,19,27984,'size','14',NULL),(137754,19,27985,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137755,19,27985,'colour','f9f9f9',NULL),(137756,19,27985,'bold','1',NULL),(137757,19,27985,'italic','0',NULL),(137758,19,27985,'underline','0',NULL),(137759,19,27985,'uppercase','0',NULL),(137760,19,27985,'size','14',NULL),(137761,19,27986,'value','4px',NULL),(137762,19,27986,'top-right','value','4px'),(137763,19,27986,'top-left','value','4px'),(137764,19,27986,'bottom-right','value','4px'),(137765,19,27986,'bottom-left','value','4px'),(137766,19,27987,'top','size','1'),(137767,19,27987,'top','colour','d5d5d5'),(137768,19,27987,'right','size','1'),(137769,19,27987,'right','colour','d5d5d5'),(137770,19,27987,'bottom','size','1'),(137771,19,27987,'bottom','colour','d5d5d5'),(137772,19,27987,'left','size','1'),(137773,19,27987,'left','colour','d5d5d5'),(137774,19,27988,'type','1',NULL),(137775,19,27988,'start','f9f9f9',NULL),(137776,19,27989,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137777,19,27989,'colour','5f5f5f',NULL),(137778,19,27989,'bold','0',NULL),(137779,19,27989,'italic','0',NULL),(137780,19,27989,'underline','0',NULL),(137781,19,27989,'uppercase','0',NULL),(137782,19,27989,'size','11',NULL),(137783,19,27990,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137784,19,27990,'colour','5f5f5f',NULL),(137785,19,27990,'bold','0',NULL),(137786,19,27990,'italic','0',NULL),(137787,19,27990,'underline','0',NULL),(137788,19,27990,'uppercase','0',NULL),(137789,19,27990,'size','11',NULL),(137790,19,27991,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137791,19,27991,'colour','5f5f5f',NULL),(137792,19,27991,'bold','0',NULL),(137793,19,27991,'italic','0',NULL),(137794,19,27991,'underline','1',NULL),(137795,19,27991,'uppercase','0',NULL),(137796,19,27991,'size','11',NULL),(137797,19,27992,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137798,19,27992,'colour','5f5f5f',NULL),(137799,19,27992,'bold','1',NULL),(137800,19,27992,'italic','0',NULL),(137801,19,27992,'underline','0',NULL),(137802,19,27992,'uppercase','0',NULL),(137803,19,27992,'size','11',NULL),(137804,19,27993,'top','size','0'),(137805,19,27993,'top','colour','000000'),(137806,19,27993,'right','size','0'),(137807,19,27993,'right','colour','000000'),(137808,19,27993,'bottom','size','0'),(137809,19,27993,'bottom','colour','000000'),(137810,19,27993,'left','size','0'),(137811,19,27993,'left','colour','000000'),(137812,19,27994,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137813,19,27994,'colour','c4c4c4',NULL),(137814,19,27994,'bold','0',NULL),(137815,19,27994,'italic','0',NULL),(137816,19,27994,'underline','0',NULL),(137817,19,27994,'uppercase','0',NULL),(137818,19,27994,'size','11',NULL),(137819,19,27995,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137820,19,27995,'colour','c4c4c4',NULL),(137821,19,27995,'bold','0',NULL),(137822,19,27995,'italic','0',NULL),(137823,19,27995,'underline','0',NULL),(137824,19,27995,'uppercase','0',NULL),(137825,19,27995,'size','11',NULL),(137826,19,27996,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137827,19,27996,'colour','c4c4c4',NULL),(137828,19,27996,'bold','0',NULL),(137829,19,27996,'italic','0',NULL),(137830,19,27996,'underline','0',NULL),(137831,19,27996,'uppercase','0',NULL),(137832,19,27996,'size','11',NULL),(137833,19,27997,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137834,19,27997,'colour','979797',NULL),(137835,19,27997,'bold','0',NULL),(137836,19,27997,'italic','0',NULL),(137837,19,27997,'underline','0',NULL),(137838,19,27997,'uppercase','0',NULL),(137839,19,27997,'size','11',NULL),(137840,19,27998,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137841,19,27998,'colour','000000',NULL),(137842,19,27998,'bold','0',NULL),(137843,19,27998,'italic','0',NULL),(137844,19,27998,'underline','0',NULL),(137845,19,27998,'uppercase','0',NULL),(137846,19,27998,'size','11',NULL),(137847,19,27999,'type','1',NULL),(137848,19,27999,'start','d5d5d5',NULL),(137849,19,28000,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137850,19,28000,'colour','d5d5d5',NULL),(137851,19,28000,'bold','0',NULL),(137852,19,28000,'italic','0',NULL),(137853,19,28000,'underline','0',NULL),(137854,19,28000,'uppercase','0',NULL),(137855,19,28000,'size','11',NULL),(137856,19,28001,'top','size','1'),(137857,19,28001,'top','colour','d6d6d6'),(137858,19,28001,'right','size','1'),(137859,19,28001,'right','colour','d6d6d6'),(137860,19,28001,'bottom','size','1'),(137861,19,28001,'bottom','colour','d6d6d6'),(137862,19,28001,'left','size','1'),(137863,19,28001,'left','colour','d6d6d6'),(137864,19,28002,'value','4px',NULL),(137865,19,28002,'top-right','value','4px'),(137866,19,28002,'top-left','value','4px'),(137867,19,28002,'bottom-right','value','4px'),(137868,19,28002,'bottom-left','value','4px'),(137869,19,28003,'value','34',NULL),(137870,19,28004,'type','2',NULL),(137871,19,28004,'start','f9f9f9',NULL),(137872,19,28004,'end','ededed',NULL),(137873,19,28004,'gradient_height','34',NULL),(137874,19,28004,'position','0 0',NULL),(137875,19,28004,'repeat','repeat-x',NULL),(137876,19,28005,'type','3',NULL),(137877,19,28005,'start','ffffff',NULL),(137878,19,28005,'file','layout-box-inner-shadow.png',NULL),(137879,19,28005,'position','0 0',NULL),(137880,19,28005,'repeat','repeat-x',NULL),(137881,19,28006,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137882,19,28006,'colour','000000',NULL),(137883,19,28006,'bold','1',NULL),(137884,19,28006,'italic','0',NULL),(137885,19,28006,'underline','0',NULL),(137886,19,28006,'uppercase','0',NULL),(137887,19,28006,'size','13',NULL),(137888,19,28007,'value','left',NULL),(137889,19,28008,'family','Arial,Arial,Helvetica,sans-serif',NULL),(137890,19,28008,'colour','5f5f5f',NULL),(137891,19,28008,'bold','0',NULL),(137892,19,28008,'italic','0',NULL),(137893,19,28008,'underline','0',NULL),(137894,19,28008,'uppercase','0',NULL),(137895,19,28008,'size','11',NULL),(137896,19,28009,'value','left',NULL),(137897,19,28010,'file','icon-close.png',NULL),(137898,19,28011,'file','icon-collapse.png',NULL),(137899,19,28012,'file','icon-uncollapse.png',NULL),(137900,19,28013,'bottom-right','value','3px'),(137901,19,28013,'bottom-left','value','3px'),(137902,19,28014,'top-right','value','3px'),(137903,19,28014,'top-left','value','3px'),(137904,19,28015,'top-right','value','3px'),(137905,19,28015,'top-left','value','3px'),(137906,19,28015,'bottom-right','value','3px'),(137907,19,28015,'bottom-left','value','3px'),(137908,19,28016,'value','42px',NULL),(137909,19,28017,'value','34',NULL);
DROP TABLE IF EXISTS `paymentmethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paymentmethod` (
  `idpaymentmethod` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `controller` varchar(64) NOT NULL,
  `online` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `maximumamount` decimal(15,4) DEFAULT NULL,
  `hierarchy` int(11) DEFAULT '0',
  PRIMARY KEY (`idpaymentmethod`),
  UNIQUE KEY `UNIQUE_paymentmethod_name` (`name`),
  KEY `FK_paymentmethod_addid` (`addid`),
  KEY `FK_paymentmethod_editid` (`editid`),
  CONSTRAINT `FK_paymentmethod_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_paymentmethod_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `paymentmethod` (`idpaymentmethod`, `name`, `addid`, `adddate`, `editid`, `editdate`, `controller`, `online`, `active`, `maximumamount`, `hierarchy`) VALUES (2,'Platnosci.pl',1,'2012-02-07 18:15:15',NULL,NULL,'platnosci',1,0,NULL,0),(4,'Przelew bankowy',1,'2012-02-07 18:15:16',NULL,NULL,'banktransfer',0,1,NULL,0),(5,'Płatność za pobraniem',1,'2012-02-07 18:17:52',NULL,NULL,'ondelivery',0,1,NULL,0),(6,'Płatność przy odbiorze',1,'2012-02-07 18:17:54',NULL,NULL,'pickup',0,1,NULL,0),(8,'Żagiel',1,'2011-08-11 18:58:42',NULL,NULL,'eraty',1,0,NULL,0);
DROP TABLE IF EXISTS `paymentmethodview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paymentmethodview` (
  `idpaymentmethodview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paymentmethodid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpaymentmethodview`),
  KEY `FK_paymentmethodview_paymentmethodid` (`paymentmethodid`),
  KEY `FK_paymentmethodview_viewid` (`viewid`),
  KEY `FK_paymentmethodview_addid` (`addid`),
  CONSTRAINT `FK_paymentmethodview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_paymentmethodview_paymentmethodid` FOREIGN KEY (`paymentmethodid`) REFERENCES `paymentmethod` (`idpaymentmethod`),
  CONSTRAINT `FK_paymentmethodview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=1287 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `paymentmethodview` (`idpaymentmethodview`, `paymentmethodid`, `viewid`, `addid`, `adddate`) VALUES (1282,2,3,1,'2012-09-06 23:16:11'),(1283,4,3,1,'2012-09-06 23:16:11'),(1284,5,3,1,'2012-09-06 23:16:11'),(1285,6,3,1,'2012-09-06 23:16:11'),(1286,8,3,1,'2012-09-06 23:16:11');
DROP TABLE IF EXISTS `paypalsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paypalsettings` (
  `idpaypalsettings` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business` varchar(255) NOT NULL,
  `apiusername` varchar(255) NOT NULL,
  `apipassword` varchar(255) NOT NULL,
  `apisignature` varchar(255) NOT NULL,
  `sandbox` int(1) NOT NULL,
  `positiveorderstatusid` int(10) unsigned NOT NULL,
  `negativeorderstatusid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpaypalsettings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `period` (
  `idperiod` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `timeinterval` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `intervalsql` varchar(45) NOT NULL,
  PRIMARY KEY (`idperiod`),
  KEY `FK_period_addid` (`addid`),
  CONSTRAINT `FK_period_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `period` (`idperiod`, `name`, `timeinterval`, `addid`, `adddate`, `intervalsql`) VALUES (1,'Miesiąc','-1 month',1,'2010-05-31 11:29:07','1 MONTH'),(2,'2 Miesiące','-2 month',1,'2010-05-31 11:29:07','2 MONTH'),(3,'Rok','-1 year',1,'2010-05-31 11:29:07','1 YEAR'),(4,'Dzień','-1 day',1,'2010-05-31 11:29:07','1 DAY'),(8,'5 Dni','-5 day',1,'2010-05-31 11:29:07','5 DAY'),(9,'Tydzień','-7 day',1,'2010-05-31 11:29:07','7 DAY'),(10,'2 tygodnie','-14 day',1,'2010-05-31 11:29:07','14 DAY'),(11,'3 Miesiące','-3 month',1,'2010-05-31 11:29:08','3 MONTH'),(12,'6 Miesięcy','-6 month',1,'2010-05-31 11:32:53','6 MONTH');
DROP TABLE IF EXISTS `platnoscisettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platnoscisettings` (
  `idplatnoscisettings` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpos` int(10) unsigned DEFAULT NULL,
  `firstmd5` varchar(32) DEFAULT NULL,
  `secondmd5` varchar(32) DEFAULT NULL,
  `authkey` varchar(32) DEFAULT NULL,
  `viewid` int(11) DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idplatnoscisettings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `platnoscistatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platnoscistatus` (
  `idplatnoscistatus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idstatus` int(10) unsigned DEFAULT NULL,
  `idplatnosci` varchar(32) DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `paymentmethodid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idplatnoscistatus`),
  KEY `FK_platnoscistatus_paymentmethodid` (`paymentmethodid`),
  CONSTRAINT `FK_platnoscistatus_paymentmethodid` FOREIGN KEY (`paymentmethodid`) REFERENCES `paymentmethod` (`idpaymentmethod`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `platnoscistatus` (`idplatnoscistatus`, `idstatus`, `idplatnosci`, `adddate`, `editid`, `editdate`, `paymentmethodid`, `storeid`, `viewid`, `parentid`) VALUES (1,16,'1','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(2,17,'2','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(3,18,'3','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(4,19,'4','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(5,20,'5','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(6,21,'6','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(7,22,'7','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(8,23,'99','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL),(9,24,'888','2009-11-12 10:12:38',NULL,NULL,2,NULL,NULL,NULL);
DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `idpoll` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publish` int(10) unsigned NOT NULL DEFAULT '1',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpoll`),
  KEY `FK_poll_addid` (`addid`),
  KEY `FK_poll_editid` (`editid`),
  KEY `FK_poll_parentid` (`parentid`),
  CONSTRAINT `FK_poll_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_poll_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_poll_parentid` FOREIGN KEY (`parentid`) REFERENCES `poll` (`idpoll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB; (`clientid`) REFER `mvc/client`(`idclient';
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `pollanswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollanswers` (
  `idpollanswers` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `votes` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `pollid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpollanswers`),
  KEY `FK_pollanswers_addid` (`addid`),
  KEY `FK_pollanswers_editid` (`editid`),
  KEY `FK_pollanswers_pollid` (`pollid`),
  KEY `FK_pollanswers_languageid` (`languageid`),
  CONSTRAINT `FK_pollanswers_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_pollanswers_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_pollanswers_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_pollanswers_pollid` FOREIGN KEY (`pollid`) REFERENCES `poll` (`idpoll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `polltranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polltranslation` (
  `idpolltranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pollid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idpolltranslation`),
  KEY `FK_polltranslation_languageid` (`languageid`),
  KEY `FK_polltranslation_addid` (`addid`),
  KEY `FK_polltranslation_pollid` (`pollid`),
  CONSTRAINT `FK_polltranslation_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_polltranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_polltranslation_pollid` FOREIGN KEY (`pollid`) REFERENCES `poll` (`idpoll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `pollview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollview` (
  `idpollview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpollview`),
  KEY `FK_pollview_pollid` (`pollid`),
  KEY `FK_pollview_viewid` (`viewid`),
  KEY `FK_pollview_addid` (`addid`),
  CONSTRAINT `FK_pollview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_pollview_pollid` FOREIGN KEY (`pollid`) REFERENCES `poll` (`idpoll`),
  CONSTRAINT `FK_pollview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producer` (
  `idproducer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `photoid` int(10) unsigned DEFAULT NULL,
  `migrationid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idproducer`),
  KEY `FK_producer_addid` (`addid`),
  KEY `FK_producer_editid` (`editid`),
  KEY `FK_producer_fileid` (`photoid`),
  CONSTRAINT `FK_producer_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_producer_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_producer_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producerdeliverer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producerdeliverer` (
  `idproducerdeliverer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producerid` int(10) unsigned NOT NULL,
  `delivererid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idproducerdeliverer`),
  UNIQUE KEY `UNIQUE_producerdeliverer_producerid_delivererid` (`producerid`,`delivererid`),
  KEY `FK_producerdeliverer_delivererid` (`delivererid`),
  KEY `FK_producerdeliverer_addid` (`addid`),
  KEY `FK_producerdeliverer_editid` (`editid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producertranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producertranslation` (
  `idproducertranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `producerid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `seo` varchar(255) DEFAULT NULL,
  `description` text,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `keyword_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idproducertranslation`),
  UNIQUE KEY `UNIQUE_producertranslation_name_languageid` (`name`,`languageid`),
  KEY `FK_producertranslation_producerid` (`producerid`),
  CONSTRAINT `FK_producertranslation_producerid` FOREIGN KEY (`producerid`) REFERENCES `producer` (`idproducer`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producerview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producerview` (
  `idproducerview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producerid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idproducerview`),
  KEY `FK_producerview_producerid` (`producerid`),
  KEY `FK_producerview_viewid` (`viewid`),
  KEY `FK_producerview_addid` (`addid`),
  CONSTRAINT `FK_producerview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_producerview_producerid` FOREIGN KEY (`producerid`) REFERENCES `producer` (`idproducer`),
  CONSTRAINT `FK_producerview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `idproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivelercode` varchar(45) DEFAULT NULL,
  `ean` varchar(45) DEFAULT NULL,
  `barcode` varchar(32) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT NULL,
  `buyprice` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `sellprice` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `producerid` int(10) unsigned DEFAULT NULL,
  `vatid` int(10) unsigned NOT NULL,
  `stock` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `weight` decimal(16,3) DEFAULT NULL,
  `buycurrencyid` int(10) unsigned DEFAULT NULL,
  `sellcurrencyid` int(10) unsigned DEFAULT NULL,
  `technicaldatasetid` int(10) unsigned DEFAULT NULL,
  `trackstock` int(10) unsigned DEFAULT '1',
  `enable` int(10) unsigned NOT NULL DEFAULT '1',
  `promotion` int(10) unsigned DEFAULT '0',
  `discountprice` decimal(15,4) DEFAULT '0.0000',
  `promotionstart` date DEFAULT NULL,
  `promotionend` date DEFAULT NULL,
  `viewed` int(11) DEFAULT '0',
  `migrationid` int(11) DEFAULT NULL,
  `width` decimal(15,4) DEFAULT NULL,
  `height` decimal(15,4) DEFAULT NULL,
  `deepth` decimal(15,4) DEFAULT NULL,
  `unit` int(11) NOT NULL DEFAULT '1',
  `shippingcost` decimal(15,4) DEFAULT NULL,
  PRIMARY KEY (`idproduct`),
  KEY `FK_product_producerid` (`producerid`),
  KEY `FK_product_vatid` (`vatid`),
  KEY `FK_product_addid` (`addid`),
  KEY `FK_product_editid` (`editid`),
  KEY `FK_product_sellcurrencyid` (`sellcurrencyid`),
  KEY `FK_product_buycurrencyid` (`buycurrencyid`),
  KEY `FK_product_technicaldatasetid` (`technicaldatasetid`),
  KEY `IDX_product_enable` (`enable`),
  CONSTRAINT `FK_product_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_product_buycurrencyid` FOREIGN KEY (`buycurrencyid`) REFERENCES `currency` (`idcurrency`),
  CONSTRAINT `FK_product_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_product_producerid` FOREIGN KEY (`producerid`) REFERENCES `producer` (`idproducer`),
  CONSTRAINT `FK_product_sellcurrencyid` FOREIGN KEY (`sellcurrencyid`) REFERENCES `currency` (`idcurrency`),
  CONSTRAINT `FK_product_vatid` FOREIGN KEY (`vatid`) REFERENCES `vat` (`idvat`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`technicaldatasetid`) REFERENCES `technicaldataset` (`idtechnicaldataset`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productattributeset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productattributeset` (
  `idproductattributeset` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `suffixtypeid` int(10) unsigned NOT NULL,
  `value` decimal(16,2) NOT NULL DEFAULT '0.00',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `stock` int(10) unsigned NOT NULL DEFAULT '0',
  `attributegroupnameid` int(10) unsigned DEFAULT NULL,
  `attributeprice` decimal(15,4) DEFAULT NULL,
  `discountprice` decimal(15,4) DEFAULT NULL,
  `symbol` varchar(128) DEFAULT NULL,
  `weight` decimal(15,3) DEFAULT '0.000',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `photoid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idproductattributeset`),
  KEY `FK_productattributeset_productid` (`productid`),
  KEY `FK_productattributeset_addid` (`addid`),
  KEY `FK_productattributeset_editid` (`editid`),
  KEY `FK_productattributeset_suffixtypeid` (`suffixtypeid`),
  KEY `FK_productattributeset_attributegroupnameid` (`attributegroupnameid`),
  CONSTRAINT `FK_productattributeset_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productattributeset_attributegroupnameid` FOREIGN KEY (`attributegroupnameid`) REFERENCES `attributegroupname` (`idattributegroupname`),
  CONSTRAINT `FK_productattributeset_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productattributeset_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productattributevalueset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productattributevalueset` (
  `idproductattributevalueset` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attributeproductvalueid` int(10) unsigned NOT NULL,
  `productattributesetid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idproductattributevalueset`),
  UNIQUE KEY `UNIQUE_pavs_productattributevalueid_productattributesetid` (`attributeproductvalueid`,`productattributesetid`),
  KEY `FK_productattributevalueset_productattributesetid` (`productattributesetid`),
  KEY `FK_productattributevalueset_addid` (`addid`),
  KEY `FK_productattributevalueset_editid` (`editid`),
  CONSTRAINT `FK_productattributevalueset_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productattributevalueset_attributeproductvalueid` FOREIGN KEY (`attributeproductvalueid`) REFERENCES `attributeproductvalue` (`idattributeproductvalue`),
  CONSTRAINT `FK_productattributevalueset_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productattributevalueset_productattributesetid` FOREIGN KEY (`productattributesetid`) REFERENCES `productattributeset` (`idproductattributeset`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productcategory` (
  `idproductcategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `categoryid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idproductcategory`),
  KEY `UNIQUE_productcategory_productid_categoryid` (`productid`,`categoryid`),
  KEY `FK_productcategory_categoryid` (`categoryid`),
  KEY `FK_productcategory_addid` (`addid`),
  KEY `FK_productcategory_editid` (`editid`),
  KEY `IDX_productcategory_productid_categoryid` (`productid`,`categoryid`),
  CONSTRAINT `FK_productcategory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productcategory_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_productcategory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productcategory_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productdeliverer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productdeliverer` (
  `idproductdeliverer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `delivererid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idproductdeliverer`),
  UNIQUE KEY `UNIQUE_productdeliverer_productid_delivererid` (`productid`,`delivererid`),
  KEY `FK_productdeliverer_delivererid` (`delivererid`),
  KEY `FK_productdeliverer_addid` (`addid`),
  KEY `FK_productdeliverer_editid` (`editid`),
  CONSTRAINT `FK_productdeliverer_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productdeliverer_delivererid` FOREIGN KEY (`delivererid`) REFERENCES `deliverer` (`iddeliverer`),
  CONSTRAINT `FK_productdeliverer_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productdeliverer_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productfile` (
  `idproductfile` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `fileid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idproductfile`),
  KEY `FK_productfile_fileid` (`fileid`),
  KEY `FK_productfile_productid` (`productid`),
  KEY `FK_productfile_addid` (`addid`),
  KEY `FK_productfile_editid` (`editid`),
  CONSTRAINT `FK_productfile_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productfile_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productfile_fileid` FOREIGN KEY (`fileid`) REFERENCES `file` (`idfile`),
  CONSTRAINT `FK_productfile_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productgroupprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productgroupprice` (
  `idproductgroupprice` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientgroupid` int(10) unsigned NOT NULL,
  `productid` int(10) unsigned NOT NULL,
  `groupprice` int(10) unsigned DEFAULT '0',
  `sellprice` decimal(15,4) NOT NULL,
  `promotion` int(10) unsigned DEFAULT '0',
  `discountprice` decimal(15,4) DEFAULT NULL,
  `promotionstart` date DEFAULT NULL,
  `promotionend` date DEFAULT NULL,
  PRIMARY KEY (`idproductgroupprice`),
  UNIQUE KEY `UNIQUE_productgroupprice_productid_clientgroupid` (`clientgroupid`,`productid`),
  KEY `FK_productgroupprice_productid` (`productid`),
  KEY `FK_productgroupprice_clientgroupid` (`clientgroupid`),
  CONSTRAINT `FK_productgroupprice_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_productgroupprice_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productnew`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productnew` (
  `idproductnew` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `enddate` datetime DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `active` int(10) unsigned DEFAULT '1',
  PRIMARY KEY (`idproductnew`),
  KEY `FK_new_addid` (`addid`),
  KEY `FK_new_editid` (`editid`),
  KEY `FK_new_productid` (`productid`),
  CONSTRAINT `FK_new_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_new_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_new_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productphoto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productphoto` (
  `idproductphoto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `mainphoto` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idproductphoto`),
  UNIQUE KEY `UNIQUE_productphoto_productid_photoid` (`productid`,`photoid`),
  KEY `FK_productphoto_photoid` (`photoid`),
  KEY `FK_productphoto_addid` (`addid`),
  KEY `FK_productphoto_editid` (`editid`),
  CONSTRAINT `FK_productphoto_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productphoto_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_productphoto_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`),
  CONSTRAINT `FK_productphoto_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productrange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productrange` (
  `idproductrange` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `rangetypeid` int(10) unsigned NOT NULL,
  `productreviewid` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idproductrange`),
  KEY `FK_productrange_productid` (`productid`),
  KEY `FK_productrange_rangetypeid` (`rangetypeid`),
  KEY `FK_productrange_productreviewid` (`productreviewid`),
  CONSTRAINT `FK_productrange_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_productrange_productreviewid` FOREIGN KEY (`productreviewid`) REFERENCES `productreview` (`idproductreview`),
  CONSTRAINT `FK_productrange_rangetypeid` FOREIGN KEY (`rangetypeid`) REFERENCES `rangetype` (`idrangetype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productreview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productreview` (
  `idproductreview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `clientid` int(10) unsigned NOT NULL,
  `review` text NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nick` varchar(45) DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idproductreview`),
  KEY `FK_productreview_productid` (`productid`),
  KEY `FK_productreview_clientid` (`clientid`),
  KEY `FK_productreview_viewid` (`viewid`),
  CONSTRAINT `FK_productreview_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_productreview_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_productreview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productsearch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productsearch` (
  `idproductsearch` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text,
  `attributes` text,
  `productid` int(10) unsigned NOT NULL,
  `shortdescription` text,
  `producername` text,
  `enable` int(10) unsigned NOT NULL DEFAULT '1',
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `languageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idproductsearch`),
  UNIQUE KEY `UNIQUE_productsearch_productid` (`productid`,`languageid`),
  FULLTEXT KEY `name` (`name`,`description`,`shortdescription`,`producername`,`attributes`)
) ENGINE=MyISAM AUTO_INCREMENT=584 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `productstaticattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productstaticattribute` (
  `idproductstaticattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `staticgroupid` int(10) unsigned NOT NULL,
  `staticattributeid` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`idproductstaticattribute`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `productstaticattribute` (`idproductstaticattribute`, `productid`, `staticgroupid`, `staticattributeid`) VALUES (3,1,1,1);
DROP TABLE IF EXISTS `productstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productstatus` (
  `idproductstatus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idproductstatus`),
  UNIQUE KEY `UNIQUE_productstatus_name` (`name`),
  KEY `FK_productstatus_add` (`addid`),
  CONSTRAINT `FK_productstatus_add` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `productstatus` (`idproductstatus`, `name`, `addid`, `adddate`) VALUES (5,'Polecany',1,'2009-03-25 13:02:05'),(6,'Zakup na raty',1,'2009-03-25 13:02:05');
DROP TABLE IF EXISTS `producttags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttags` (
  `idproducttags` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagsid` int(10) unsigned NOT NULL,
  `clientid` int(10) unsigned NOT NULL,
  `productid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idproducttags`),
  UNIQUE KEY `UNIQUE_producttags_tagsid_clientid_productid` (`tagsid`,`clientid`,`productid`),
  KEY `FK_producttags_clientid` (`clientid`),
  KEY `FK_producttags_productid` (`productid`),
  KEY `FK_producttags_viewid` (`viewid`),
  CONSTRAINT `FK_producttags_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_producttags_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producttechnicaldatagroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttechnicaldatagroup` (
  `idproducttechnicaldatagroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `technicaldatagroupid` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`idproducttechnicaldatagroup`),
  KEY `productid` (`productid`),
  KEY `technicaldatagroupid` (`technicaldatagroupid`),
  CONSTRAINT `producttechnicaldatagroup_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `producttechnicaldatagroup_ibfk_2` FOREIGN KEY (`technicaldatagroupid`) REFERENCES `technicaldatagroup` (`idtechnicaldatagroup`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producttechnicaldatagroupattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttechnicaldatagroupattribute` (
  `idproducttechnicaldatagroupattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producttechnicaldatagroupid` int(10) unsigned NOT NULL,
  `technicaldataattributeid` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned DEFAULT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`idproducttechnicaldatagroupattribute`),
  KEY `producttechnicaldatagroupid` (`producttechnicaldatagroupid`),
  KEY `technicaldataattributeid` (`technicaldataattributeid`),
  CONSTRAINT `producttechnicaldatagroupattribute_ibfk_1` FOREIGN KEY (`producttechnicaldatagroupid`) REFERENCES `producttechnicaldatagroup` (`idproducttechnicaldatagroup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `producttechnicaldatagroupattribute_ibfk_2` FOREIGN KEY (`technicaldataattributeid`) REFERENCES `technicaldataattribute` (`idtechnicaldataattribute`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producttechnicaldatagroupattributetranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttechnicaldatagroupattributetranslation` (
  `idproducttechnicaldatagroupattributetranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producttechnicaldatagroupattributeid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`idproducttechnicaldatagroupattributetranslation`),
  KEY `producttechnicaldatagroupattributeid` (`producttechnicaldatagroupattributeid`),
  KEY `languageid` (`languageid`),
  CONSTRAINT `producttechnicaldatagroupattributetranslation_ibfk_1` FOREIGN KEY (`producttechnicaldatagroupattributeid`) REFERENCES `producttechnicaldatagroupattribute` (`idproducttechnicaldatagroupattribute`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `producttechnicaldatagroupattributetranslation_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `producttierprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttierprice` (
  `idproducttierprice` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientgroupid` int(10) unsigned NOT NULL,
  `productid` int(10) unsigned NOT NULL,
  `qtymin` int(10) unsigned DEFAULT '0',
  `qtymax` int(10) unsigned DEFAULT '0',
  `tierprice` decimal(15,4) NOT NULL,
  `viewid` int(11) NOT NULL,
  PRIMARY KEY (`idproducttierprice`),
  KEY `FK_producttierprice_productid` (`productid`),
  KEY `FK_producttierprice_clientgroupid` (`clientgroupid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `producttierprice` (`idproducttierprice`, `clientgroupid`, `productid`, `qtymin`, `qtymax`, `tierprice`, `viewid`) VALUES (4,0,1,4,5,5.0000,3),(5,0,1,6,10,10.0000,3),(6,0,1,11,0,15.0000,3);
DROP TABLE IF EXISTS `producttranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producttranslation` (
  `idproducttranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `shortdescription` text,
  `description` text,
  `languageid` int(10) unsigned DEFAULT NULL,
  `seo` varchar(255) NOT NULL,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` text,
  `keyword_description` text,
  `longdescription` text,
  PRIMARY KEY (`idproducttranslation`),
  KEY `FK_producttranslation_productid` (`productid`),
  KEY `FK_producttranslation_languageid` (`languageid`),
  CONSTRAINT `FK_producttranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_producttranslation_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rangetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rangetype` (
  `idrangetype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idrangetype`),
  KEY `FK_rangetype_addid` (`addid`),
  KEY `FK_rangetype_editid` (`editid`),
  CONSTRAINT `FK_rangetype_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rangetype_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rangetypecategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rangetypecategory` (
  `idrangetypecategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(10) unsigned NOT NULL,
  `rangetypeid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idrangetypecategory`),
  UNIQUE KEY `UNIQUE_rangetypecategory_categoryid_rangetypeid` (`categoryid`,`rangetypeid`),
  KEY `FK_rangetypecategory_rangetypeid` (`rangetypeid`),
  KEY `FK_rangetypecategory_addid` (`addid`),
  KEY `FK_rangetypecategory_editid` (`editid`),
  CONSTRAINT `FK_rangetypecategory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rangetypecategory_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_rangetypecategory_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rangetypecategory_rangetypeid` FOREIGN KEY (`rangetypeid`) REFERENCES `rangetype` (`idrangetype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rangetypetranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rangetypetranslation` (
  `idrangetypetranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `languageid` int(10) unsigned DEFAULT NULL,
  `rangetypeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idrangetypetranslation`),
  UNIQUE KEY `UNIQUE_rangetypetranslation_name_languageid` (`name`,`languageid`),
  KEY `FK_rangetypetranslation_languageid` (`languageid`),
  KEY `FK_rangetypetranslation_rangetypeid` (`rangetypeid`),
  CONSTRAINT `FK_rangetypetranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_rangetypetranslation_rangetypeid` FOREIGN KEY (`rangetypeid`) REFERENCES `rangetype` (`idrangetype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `recipientclientgrouplist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipientclientgrouplist` (
  `idrecipientclientgrouplist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientgroupid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `editid` int(10) unsigned DEFAULT NULL,
  `recipientlistid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrecipientclientgrouplist`),
  KEY `FK_recipientclientgrouplist_clientgroupid` (`clientgroupid`),
  KEY `FK_recipientclientgrouplist_addid` (`addid`),
  KEY `FK_recipientclientgrouplist_editid` (`editid`),
  KEY `FK_recipientclientgrouplist_recipientlistid` (`recipientlistid`),
  CONSTRAINT `FK_recipientclientgrouplist_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientclientgrouplist_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_recipientclientgrouplist_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientclientgrouplist_recipientlistid` FOREIGN KEY (`recipientlistid`) REFERENCES `recipientlist` (`idrecipientlist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `recipientclientlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipientclientlist` (
  `idrecipientclientlist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipientlistid` int(10) unsigned NOT NULL,
  `clientid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idrecipientclientlist`),
  KEY `FK_recepientclientlist_recepientlistid` (`recipientlistid`),
  KEY `FK_recipientclientlist_clientid` (`clientid`),
  KEY `FK_recipientclientlist_addid` (`addid`),
  KEY `FK_recipientclientlist_editid` (`editid`),
  CONSTRAINT `FK_recipientclientlist_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientclientlist_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_recipientclientlist_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientclientlist_recipientlistid` FOREIGN KEY (`recipientlistid`) REFERENCES `recipientlist` (`idrecipientlist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `recipientlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipientlist` (
  `idrecipientlist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idrecipientlist`),
  UNIQUE KEY `UNIQUE_recipientlist_name` (`name`),
  KEY `FK_recipientlist_addid` (`addid`),
  KEY `FK_recipientlist_editid` (`editid`),
  CONSTRAINT `FK_recipientlist_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientlist_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `recipientnewsletterlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipientnewsletterlist` (
  `idrecipientnewsletterlist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientnewsletterid` int(10) unsigned NOT NULL,
  `recipientlistid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `editid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrecipientnewsletterlist`),
  KEY `FK_recipientnewsletterlist_addid` (`addid`),
  KEY `FK_recipientnewsletterlist_editid` (`editid`),
  KEY `FK_recipientnewsletterlist_clientnewsletterid` (`clientnewsletterid`),
  KEY `FK_recipientnewsletterlist_recipientlistid` (`recipientlistid`),
  CONSTRAINT `FK_recipientnewsletterlist_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientnewsletterlist_clientnewsletterid` FOREIGN KEY (`clientnewsletterid`) REFERENCES `clientnewsletter` (`idclientnewsletter`),
  CONSTRAINT `FK_recipientnewsletterlist_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_recipientnewsletterlist_recipientlistid` FOREIGN KEY (`recipientlistid`) REFERENCES `recipientlist` (`idrecipientlist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `right`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `right` (
  `idright` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controllerid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idright`),
  UNIQUE KEY `UNIQUE_right_controllerid_groupid_storeid` (`controllerid`,`groupid`,`storeid`),
  KEY `FK_right_addid` (`addid`),
  KEY `FK_right_editid` (`editid`),
  KEY `FK_right_groupid` (`groupid`),
  KEY `FK_right_storeid` (`storeid`),
  CONSTRAINT `CDELETE_group_groupid` FOREIGN KEY (`groupid`) REFERENCES `group` (`idgroup`) ON DELETE CASCADE,
  CONSTRAINT `FK_right_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_right_controllerid` FOREIGN KEY (`controllerid`) REFERENCES `controller` (`idcontroller`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_right_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_right_groupid` FOREIGN KEY (`groupid`) REFERENCES `group` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_right_storeid` FOREIGN KEY (`storeid`) REFERENCES `store` (`idstore`)
) ENGINE=InnoDB AUTO_INCREMENT=2190 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `right` (`idright`, `controllerid`, `groupid`, `permission`, `storeid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,1,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(3,4,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(4,5,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(7,6,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(16,9,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(28,3,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(29,29,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(69,31,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(70,30,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(81,35,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(82,36,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(83,37,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(86,38,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(87,39,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(89,40,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(93,42,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(94,43,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(96,44,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(98,46,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(99,45,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(100,47,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(101,48,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(102,49,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(103,50,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(104,51,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(106,53,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(107,54,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(111,55,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(112,56,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(113,57,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(114,58,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(115,59,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(116,60,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(117,61,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(118,62,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(119,63,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(120,64,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(121,65,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(122,66,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(123,67,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(125,69,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(126,70,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(128,71,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(129,72,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(130,73,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(131,74,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(133,76,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(134,77,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(250,81,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(251,82,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(252,83,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(255,86,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(257,88,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(258,89,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(259,90,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(260,91,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(261,92,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(332,93,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(333,94,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(342,103,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(343,104,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(344,105,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(345,106,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(346,107,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(347,108,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(348,109,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(349,2,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1132,110,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1137,111,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1142,112,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1143,113,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1158,858,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1278,859,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1279,860,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1280,862,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1328,870,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1329,871,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1330,872,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1331,873,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1333,880,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1350,881,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1359,885,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1362,886,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1368,887,1,1,NULL,1,'2010-09-23 11:07:13',1,NULL),(1932,890,1,127,NULL,1,'2010-09-23 11:07:13',1,NULL),(1948,76,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1949,35,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1950,886,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1951,91,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1952,5,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1954,83,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1955,3,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1956,29,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1957,88,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1958,71,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1960,61,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1962,57,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1963,862,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1964,51,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1967,48,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1968,110,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1969,870,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1970,37,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1971,31,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1972,74,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1973,858,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1975,60,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1976,90,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1977,45,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1979,9,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1980,62,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1981,81,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1982,109,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1983,94,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1985,42,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1986,105,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1987,106,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1989,2,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1990,67,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1991,89,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1992,6,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1993,72,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1995,46,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1996,38,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1997,93,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(1999,107,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2000,30,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2002,111,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2005,69,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2006,36,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2007,4,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2008,40,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2009,59,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2010,58,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2011,55,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2012,47,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2014,77,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2015,53,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2016,54,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2017,86,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2018,112,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2019,113,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2021,92,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2022,50,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2023,860,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2025,890,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2026,56,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2027,65,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2028,66,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2029,64,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2030,103,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2031,108,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2032,880,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2033,881,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2034,885,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2035,63,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2036,43,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2037,887,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2038,873,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2039,872,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2040,871,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2041,73,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2042,82,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2043,49,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2044,44,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2045,1,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2046,39,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2047,104,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2048,859,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2049,70,1,127,1,1,'2010-10-28 20:55:01',NULL,NULL),(2050,3,2,127,NULL,1,'2011-07-24 21:39:34',1,NULL),(2051,46,2,126,NULL,1,'2011-07-24 21:39:34',1,NULL),(2052,76,2,0,NULL,1,'2012-03-06 15:32:36',1,NULL),(2053,35,2,0,NULL,1,'2012-03-06 15:32:36',1,NULL),(2054,886,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2055,91,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2056,5,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2057,83,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2058,29,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2059,88,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2060,71,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2061,61,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2062,57,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2063,862,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2064,51,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2065,48,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2066,110,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2067,870,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2068,37,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2069,31,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2070,74,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2071,858,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2072,60,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2073,90,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2074,45,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2075,9,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2076,62,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2077,81,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2078,109,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2079,94,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2080,42,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2081,105,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2082,106,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2083,2,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2084,67,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2085,89,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2086,6,2,0,NULL,1,'2012-03-06 15:32:37',1,NULL),(2087,72,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2088,38,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2089,93,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2090,107,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2091,30,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2092,111,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2093,69,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2094,36,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2095,4,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2096,40,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2097,59,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2098,58,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2099,55,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2100,47,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2101,77,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2102,53,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2103,54,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2104,86,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2105,112,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2106,113,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2107,92,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2108,50,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2109,860,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2110,890,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2111,56,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2112,65,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2113,66,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2114,64,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2115,103,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2116,108,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2117,880,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2118,881,2,0,NULL,1,'2012-03-06 15:32:38',1,NULL),(2119,885,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2120,63,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2121,43,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2122,887,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2123,873,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2124,872,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2125,871,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2126,73,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2127,82,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2128,49,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2129,44,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2130,1,2,4,NULL,1,'2012-03-06 15:32:39',1,NULL),(2131,39,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2132,104,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2133,859,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2134,70,2,0,NULL,1,'2012-03-06 15:32:39',1,NULL),(2136,894,1,127,NULL,1,'2011-08-14 12:16:21',NULL,NULL),(2137,895,1,127,NULL,1,'2011-08-15 20:27:04',NULL,NULL),(2139,898,1,127,NULL,1,'2011-08-22 11:28:42',NULL,NULL),(2140,899,1,127,NULL,1,'2011-08-22 11:29:15',NULL,NULL),(2145,894,1,127,NULL,1,'2011-08-27 17:48:07',NULL,NULL),(2146,898,1,127,NULL,1,'2011-08-27 17:48:07',NULL,NULL),(2147,899,1,127,NULL,1,'2011-08-27 17:48:07',NULL,NULL),(2148,894,1,127,NULL,1,'2011-08-27 17:48:31',NULL,NULL),(2149,898,1,127,NULL,1,'2011-08-27 17:48:31',NULL,NULL),(2150,899,1,127,NULL,1,'2011-08-27 17:48:31',NULL,NULL),(2151,894,1,127,NULL,1,'2011-08-27 17:49:42',NULL,NULL),(2152,898,1,127,NULL,1,'2011-08-27 17:49:42',NULL,NULL),(2153,899,1,127,NULL,1,'2011-08-27 17:49:42',NULL,NULL),(2154,894,1,127,NULL,1,'2011-08-27 17:52:21',NULL,NULL),(2155,898,1,127,NULL,1,'2011-08-27 17:52:21',NULL,NULL),(2156,899,1,127,NULL,1,'2011-08-27 17:52:21',NULL,NULL),(2157,894,1,127,NULL,1,'2011-08-27 18:05:41',NULL,NULL),(2158,898,1,127,NULL,1,'2011-08-27 18:05:41',NULL,NULL),(2159,899,1,127,NULL,1,'2011-08-27 18:05:41',NULL,NULL),(2160,894,1,127,NULL,1,'2011-08-27 18:53:41',NULL,NULL),(2161,898,1,127,NULL,1,'2011-08-27 18:53:41',NULL,NULL),(2162,899,1,127,NULL,1,'2011-08-27 18:53:41',NULL,NULL),(2163,894,1,127,NULL,1,'2011-08-27 18:54:42',NULL,NULL),(2164,898,1,127,NULL,1,'2011-08-27 18:54:42',NULL,NULL),(2165,899,1,127,NULL,1,'2011-08-27 18:54:42',NULL,NULL),(2166,894,1,127,NULL,1,'2011-08-27 18:55:43',NULL,NULL),(2167,898,1,127,NULL,1,'2011-08-27 18:55:43',NULL,NULL),(2168,899,1,127,NULL,1,'2011-08-27 18:55:43',NULL,NULL),(2169,894,1,127,NULL,1,'2011-08-27 18:57:02',NULL,NULL),(2170,898,1,127,NULL,1,'2011-08-27 18:57:02',NULL,NULL),(2171,899,1,127,NULL,1,'2011-08-27 18:57:02',NULL,NULL),(2172,894,1,127,NULL,1,'2011-08-27 18:57:22',NULL,NULL),(2173,898,1,127,NULL,1,'2011-08-27 18:57:22',NULL,NULL),(2174,899,1,127,NULL,1,'2011-08-27 18:57:22',NULL,NULL),(2175,904,1,127,NULL,1,'2011-09-27 09:22:49',NULL,NULL),(2176,905,1,127,NULL,1,'2011-09-27 09:22:58',NULL,NULL),(2177,906,1,127,NULL,1,'2011-11-21 11:12:32',NULL,NULL),(2178,894,2,0,NULL,1,'2012-03-06 15:32:36',NULL,NULL),(2179,899,2,0,NULL,1,'2012-03-06 15:32:36',NULL,NULL),(2180,898,2,0,NULL,1,'2012-03-06 15:32:36',NULL,NULL),(2181,904,2,0,NULL,1,'2012-03-06 15:32:37',NULL,NULL),(2182,905,2,0,NULL,1,'2012-03-06 15:32:37',NULL,NULL),(2183,895,2,0,NULL,1,'2012-03-06 15:32:37',NULL,NULL),(2184,906,2,0,NULL,1,'2012-03-06 15:32:38',NULL,NULL),(2185,900,2,0,NULL,1,'2012-03-06 15:32:39',NULL,NULL),(2186,901,2,0,NULL,1,'2012-03-06 15:32:39',NULL,NULL),(2187,903,2,0,NULL,1,'2012-03-06 15:32:39',NULL,NULL),(2188,902,2,0,NULL,1,'2012-03-06 15:32:39',NULL,NULL),(2189,907,1,127,NULL,1,'2012-03-08 13:54:57',NULL,NULL);
DROP TABLE IF EXISTS `rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rule` (
  `idrule` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `tablereferer` varchar(45) DEFAULT NULL,
  `primarykeyreferer` varchar(45) DEFAULT NULL,
  `columnreferer` varchar(45) DEFAULT NULL,
  `rulekindofid` int(10) unsigned NOT NULL,
  `field` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idrule`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `rule` (`idrule`, `name`, `tablereferer`, `primarykeyreferer`, `columnreferer`, `rulekindofid`, `field`) VALUES (1,'TXT_CATEGORIES','category','idcategory','idcategory',1,NULL),(2,'TXT_PRODUCERS','producer','idproducer','idproducer',1,NULL),(3,'TXT_PRODUCTS','product','idproduct','idproduct',1,NULL),(4,'TXT_ATTRIBUTES','attributeproductvalue','idattributeproductvalue','idattributeproductvalue',1,NULL),(5,'TXT_DELIVERERS','deliverer','iddeliverer','iddeliverer',1,NULL),(6,'TXT_PRODUCT_NEWS','productnew','productid','idproductnew',1,NULL),(7,'TXT_PRODUCT_PRICE_FROM','product','idproduct','sellprice',1,NULL),(8,'TXT_PRODUCT_PRICE_TO','product','idproduct','sellprice',1,NULL),(9,'TXT_DELIVERERS','dispatchmethod','iddispatchmethod','iddispatchmethod',2,NULL),(10,'TXT_PAYMENTMETHOD','paymentmethod','idpaymentmethod','idpaymentmethod',2,NULL),(11,'TXT_FINAL_CART_PRICE_FROM',NULL,NULL,NULL,2,'globalpricefrom'),(12,'TXT_FINAL_CART_PRICE_TO',NULL,NULL,NULL,2,'globalpriceto'),(13,'TXT_FINAL_CART_PRICE_WITH_WITH_DELIVERY_FROM',NULL,NULL,NULL,2,'globalpricewithdispatchmethodfrom'),(14,'TXT_FINAL_CART_PRICE_WITH_WITH_DELIVERY_TO',NULL,NULL,NULL,2,'globalpricewithdispatchmethodto');
DROP TABLE IF EXISTS `rulekindof`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rulekindof` (
  `idrulekindof` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idrulekindof`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `rulekindof` (`idrulekindof`, `name`) VALUES (1,'TXT_RULES_CATALOG'),(2,'TXT_RULES_CART');
DROP TABLE IF EXISTS `rulescart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rulescart` (
  `idrulescart` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `distinction` tinyint(3) unsigned DEFAULT '0',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `suffixtypeid` int(10) unsigned DEFAULT NULL,
  `discount` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `datefrom` date DEFAULT NULL,
  `dateto` date DEFAULT NULL,
  `discountforall` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idrulescart`),
  KEY `FK_rulescart_addid` (`addid`),
  KEY `FK_rulescart_editid` (`editid`),
  KEY `FK_rulescart_suffixtypeid` (`suffixtypeid`),
  CONSTRAINT `FK_rulescart_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rulescart_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rulescart_suffixtypeid` FOREIGN KEY (`suffixtypeid`) REFERENCES `suffixtype` (`idsuffixtype`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rulescartclientgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rulescartclientgroup` (
  `idrulescartclientgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rulescartid` int(10) unsigned NOT NULL,
  `clientgroupid` int(10) unsigned NOT NULL,
  `suffixtypeid` int(10) unsigned NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idrulescartclientgroup`),
  KEY `FK_rulescartclientgroup_rulescartid` (`rulescartid`),
  KEY `FK_rulescartclientgroup_clientgroupid` (`clientgroupid`),
  KEY `FK_rulescartclientgroup_suffixtypeid` (`suffixtypeid`),
  KEY `FK_rulescartclientgroup_addid` (`addid`),
  CONSTRAINT `FK_rulescartclientgroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_rulescartclientgroup_clientgroupid` FOREIGN KEY (`clientgroupid`) REFERENCES `clientgroup` (`idclientgroup`),
  CONSTRAINT `FK_rulescartclientgroup_rulescartid` FOREIGN KEY (`rulescartid`) REFERENCES `rulescart` (`idrulescart`),
  CONSTRAINT `FK_rulescartclientgroup_suffixtypeid` FOREIGN KEY (`suffixtypeid`) REFERENCES `suffixtype` (`idsuffixtype`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rulescartrule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rulescartrule` (
  `idrulescartrule` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruleid` int(10) unsigned NOT NULL,
  `rulescartid` int(10) unsigned NOT NULL,
  `pkid` int(10) unsigned DEFAULT NULL,
  `pricefrom` decimal(10,2) DEFAULT NULL,
  `priceto` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idrulescartrule`),
  KEY `FK_rulescartrule_ruleid` (`ruleid`),
  KEY `FK_rulescartrule_rulescartid` (`rulescartid`),
  CONSTRAINT `FK_rulescartrule_ruleid` FOREIGN KEY (`ruleid`) REFERENCES `rule` (`idrule`),
  CONSTRAINT `FK_rulescartrule_rulescartid` FOREIGN KEY (`rulescartid`) REFERENCES `rulescart` (`idrulescart`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `rulescartview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rulescartview` (
  `idrulescartview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rulescartid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idrulescartview`),
  KEY `FK_rulescartview_rulescartid` (`rulescartid`),
  KEY `FK_rulescartview_viewid` (`viewid`),
  CONSTRAINT `FK_rulescartview_rulescartid` FOREIGN KEY (`rulescartid`) REFERENCES `rulescart` (`idrulescart`),
  CONSTRAINT `FK_rulescartview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sessionhandler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessionhandler` (
  `idsessionhandler` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sessioncontent` longtext,
  `sessionid` varchar(32) NOT NULL,
  `expiredate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clientid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  `ismobile` int(11) DEFAULT '0',
  `isbot` int(11) DEFAULT '0',
  `ipaddress` char(15) DEFAULT '000.000.000.000',
  `globalprice` decimal(15,2) DEFAULT '0.00',
  `cartcurrency` char(3) DEFAULT NULL,
  `browser` varchar(64) DEFAULT NULL,
  `platform` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cart` longtext,
  PRIMARY KEY (`idsessionhandler`),
  UNIQUE KEY `UNIQUE_sessionhandler_sessionid` (`sessionid`),
  KEY `FK_sessionhandler_viewid` (`viewid`),
  CONSTRAINT `FK_sessionhandler_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=1664 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `similarproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `similarproduct` (
  `idsimilarproduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `relatedproductid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idsimilarproduct`),
  UNIQUE KEY `UNIQUE_similarproduct_productid_relatedproductid` (`productid`,`relatedproductid`) USING BTREE,
  KEY `FK_similarproduct_addid` (`addid`),
  KEY `FK_similarproduct_editid` (`editid`),
  KEY `FK_similarproduct_relatedproductid` (`relatedproductid`),
  CONSTRAINT `FK_similarproduct_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_similarproduct_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_similarproduct_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_similarproduct_relatedproductid` FOREIGN KEY (`relatedproductid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sitemaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitemaps` (
  `idsitemaps` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `publishforcategories` int(10) unsigned NOT NULL DEFAULT '1',
  `priorityforcategories` char(3) DEFAULT NULL,
  `publishforproducts` int(10) unsigned NOT NULL DEFAULT '1',
  `priorityforproducts` char(3) DEFAULT NULL,
  `publishforproducers` int(10) unsigned NOT NULL DEFAULT '1',
  `priorityforproducers` char(3) DEFAULT NULL,
  `publishfornews` int(10) unsigned NOT NULL DEFAULT '1',
  `priorityfornews` char(3) DEFAULT NULL,
  `publishforpages` int(10) unsigned NOT NULL DEFAULT '1',
  `priorityforpages` char(3) DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `lastupdate` datetime DEFAULT NULL,
  `pingserver` varchar(255) NOT NULL,
  PRIMARY KEY (`idsitemaps`),
  KEY `FK_sitemaps_addid` (`addid`),
  KEY `FK_sitemaps_editid` (`editid`),
  CONSTRAINT `FK_sitemaps_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_sitemaps_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sitemaps` (`idsitemaps`, `name`, `publishforcategories`, `priorityforcategories`, `publishforproducts`, `priorityforproducts`, `publishforproducers`, `priorityforproducers`, `publishfornews`, `priorityfornews`, `publishforpages`, `priorityforpages`, `addid`, `adddate`, `editid`, `editdate`, `lastupdate`, `pingserver`) VALUES (1,'Bing',1,'0.5',1,'0.8',1,'0.5',1,'0.5',1,'0.5',1,'2011-12-19 15:41:47',1,NULL,'2010-07-13 12:21:44','http://www.bing.com/webmaster/ping.aspx?siteMap={SITEMAP_URL}'),(2,'Google',1,'0.5',1,'0',1,'0.5',1,'0.5',1,'0.5',1,'2010-04-13 09:10:47',1,NULL,'2010-07-13 12:21:26','http://www.google.com/webmasters/sitemaps/ping?sitemap={SITEMAP_URL}'),(3,'Yahoo',1,'0.5',1,'0',1,'0.5',1,'0.5',1,'0.5',1,'2010-04-13 09:12:53',1,NULL,'2010-07-13 12:21:41','http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url={SITEMAP_URL}');
DROP TABLE IF EXISTS `staticattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticattribute` (
  `idstaticattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staticgroupid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idstaticattribute`),
  KEY `FK_staticattribute_addid` (`addid`),
  KEY `FK_staticattribute_staticgroupid` (`staticgroupid`),
  CONSTRAINT `FK_staticattribute_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_staticattribute_staticgroupid` FOREIGN KEY (`staticgroupid`) REFERENCES `staticgroup` (`idstaticgroup`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticattribute` (`idstaticattribute`, `staticgroupid`, `addid`, `adddate`) VALUES (1,1,1,'2012-09-01 19:45:20');
DROP TABLE IF EXISTS `staticattributetranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticattributetranslation` (
  `idstaticattributetranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `staticattributeid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`idstaticattributetranslation`),
  KEY `FK_staticattributetranslation_languageid` (`languageid`),
  CONSTRAINT `FK_staticattributetranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticattributetranslation` (`idstaticattributetranslation`, `name`, `staticattributeid`, `languageid`, `description`, `file`) VALUES (1,'Tak',1,1,'','');
DROP TABLE IF EXISTS `staticcontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticcontent` (
  `idstaticcontent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentcategoryid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `publish` int(10) unsigned NOT NULL DEFAULT '1',
  `hierarchy` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`idstaticcontent`),
  KEY `FK_staticcontent_contentcategoryid` (`contentcategoryid`),
  KEY `FK_staticcontent_addid` (`addid`),
  KEY `FK_staticcontent_editid` (`editid`),
  CONSTRAINT `FK_staticcontent_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_staticcontent_contentcategoryid` FOREIGN KEY (`contentcategoryid`) REFERENCES `contentcategory` (`idcontentcategory`),
  CONSTRAINT `FK_staticcontent_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticcontent` (`idstaticcontent`, `contentcategoryid`, `addid`, `adddate`, `editid`, `editdate`, `publish`, `hierarchy`) VALUES (1,4,1,'2011-11-07 22:33:05',1,NULL,1,0),(2,5,1,'2011-11-07 22:33:16',1,NULL,1,0),(3,6,1,'2011-11-07 22:33:21',1,NULL,1,0),(4,7,1,'2011-05-08 17:11:35',1,NULL,1,0),(7,10,1,'2011-11-07 22:33:36',1,NULL,1,0),(8,11,1,'2011-09-07 19:30:02',1,NULL,1,0),(10,15,1,'2011-11-07 22:32:59',1,NULL,1,0),(11,16,1,'2011-11-07 22:33:11',1,NULL,1,0),(12,12,1,'2011-11-28 12:35:53',NULL,NULL,1,0);
DROP TABLE IF EXISTS `staticcontenttranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticcontenttranslation` (
  `idstaticcontenttranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `staticcontentid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idstaticcontenttranslation`),
  KEY `FK_staticcontenttranslation_languageid` (`languageid`),
  CONSTRAINT `FK_staticcontenttranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticcontenttranslation` (`idstaticcontenttranslation`, `topic`, `content`, `staticcontentid`, `languageid`) VALUES (29,'Regulamin','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',10,1),(30,'Czas realizacji','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',1,1),(31,'Bezpieczeństwo','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',11,1),(32,'Dostępność produktów','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',2,1),(33,'Koszt dostawy','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',3,1),(34,'Poradniki','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n',4,1),(35,'Nagrody i wyróżnienia','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',7,1),(36,'Informacje o firmie','<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque lorem arcu, posuere quis rhoncus in, suscipit a est. Praesent laoreet condimentum felis non ultricies. Duis nec cursus nisi. Quisque eu nulla lacus, in pretium massa. Quisque vitae dolor in nisl pulvinar fermentum vitae ac elit. Ut sit amet diam ut mi molestie eleifend sed sed sem. Ut tempus sagittis lorem, eget elementum sapien dictum id. Nam tellus tortor, tincidunt at cursus sed, hendrerit et lectus. Duis feugiat felis elit. Morbi scelerisque commodo volutpat. Fusce auctor augue in ligula euismod fermentum. Nullam non quam eleifend lorem ultrices feugiat in a metus. In sed placerat urna. Donec sed nisl leo, sit amet cursus nulla. Nulla facilisi.</p>\r\n<p>\r\n	Aliquam imperdiet faucibus felis, ut laoreet quam bibendum non. Praesent pretium dapibus mi nec sodales. Cras sapien orci, bibendum ullamcorper congue eget, elementum eget leo. Fusce turpis erat, tristique ac consequat vitae, gravida ac dolor. Proin dictum sodales nisi, sit amet hendrerit metus convallis quis. Fusce lacinia dui at metus lobortis ultrices. Proin felis justo, tristique at ultrices vel, convallis ut turpis. Ut pulvinar pulvinar bibendum. Praesent vitae lacus et lacus gravida lobortis. Phasellus libero felis, dapibus non molestie id, ornare at arcu. Nullam malesuada tempus ipsum, non blandit enim scelerisque nec. Donec at tortor orci. Sed luctus accumsan ligula in consequat.</p>\r\n<p>\r\n	Proin pellentesque urna ac risus adipiscing vel sollicitudin urna scelerisque. Aenean in velit velit. In eu ante in sem viverra porta. Nunc varius, velit in lobortis condimentum, leo turpis lacinia augue, ut lacinia felis neque non ipsum. Nullam urna mauris, dapibus vitae adipiscing nec, aliquet vitae diam. Aliquam erat volutpat. Nullam at nisl vel lacus congue sagittis. Morbi sit amet pellentesque nisl. In interdum, neque at varius ultrices, nisi odio consequat elit, vel aliquet lorem nisl et augue. Integer ut massa mauris. In scelerisque luctus sem, non consequat erat tristique eu. Nam ac felis eros, eget aliquam neque. Sed commodo dapibus dui, ac accumsan ipsum accumsan quis. Sed hendrerit porta quam, et sollicitudin turpis ultricies et. Cras dictum, lectus viverra tincidunt auctor, purus dui porttitor turpis, a tincidunt massa magna sed sapien. Integer sed cursus mauris.</p>\r\n<p>\r\n	Pellentesque bibendum viverra tempus. Quisque dictum vehicula neque, et ultrices magna faucibus sit amet. Aenean mattis, massa id fringilla adipiscing, erat erat blandit neque, sit amet faucibus sem metus eget purus. Morbi gravida facilisis porttitor. Donec et mollis est. Nam pellentesque varius fringilla. Nunc arcu arcu, pulvinar id molestie id, pharetra a ante. Suspendisse ornare sodales augue, sed dictum felis egestas non. Nulla non elit dolor. Aliquam convallis pretium aliquam. Fusce dignissim molestie sem, vitae vulputate risus molestie eget. Cras non neque id magna sollicitudin sodales. Sed rutrum lorem sed diam tempus vehicula. Sed viverra pretium lectus et sagittis. Proin eu nulla ac risus dignissim ullamcorper sit amet viverra risus. Phasellus in enim vulputate lorem consectetur adipiscing eget in orci.</p>\r\n<p>\r\n	Donec blandit diam a nibh tristique auctor. Duis vestibulum tempus elit, vitae faucibus lacus tincidunt ut. Vestibulum fermentum sem non erat mollis sed vulputate erat mollis. Mauris vitae turpis ut eros fringilla tristique. Pellentesque lobortis lacus et nunc laoreet semper. Nulla ac nunc magna, eget blandit diam. In condimentum semper lacus, eget iaculis est venenatis eget. Praesent mollis, enim convallis feugiat dictum, velit velit sagittis nisl, sed laoreet massa lorem non metus. Morbi sed vulputate sapien. Ut porttitor varius ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tortor augue, mollis a sodales a, imperdiet nec nulla. Pellentesque egestas, magna non feugiat egestas, lacus lorem vestibulum magna, sit amet volutpat lectus urna in quam. Aenean dictum erat non enim tincidunt placerat. Maecenas sollicitudin feugiat orci quis auctor. Sed id eros blandit urna pellentesque facilisis in non nulla.</p>\r\n<p>\r\n	&nbsp;</p>\r\n',8,1),(37,'Praca','',12,1);
DROP TABLE IF EXISTS `staticcontentview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticcontentview` (
  `idstaticcontentview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staticcontentid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idstaticcontentview`),
  KEY `FK_staticcontentview_staticcontentid` (`staticcontentid`),
  KEY `FK_staticcontentview_viewid` (`viewid`),
  KEY `FK_staticcontentview_addid` (`addid`),
  CONSTRAINT `FK_staticcontentview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_staticcontentview_staticcontentid` FOREIGN KEY (`staticcontentid`) REFERENCES `staticcontent` (`idstaticcontent`),
  CONSTRAINT `FK_staticcontentview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticcontentview` (`idstaticcontentview`, `staticcontentid`, `viewid`, `addid`, `adddate`) VALUES (2,10,3,1,'2011-11-07 22:32:59'),(3,1,3,1,'2011-11-07 22:33:05'),(4,11,3,1,'2011-11-07 22:33:11'),(5,2,3,1,'2011-11-07 22:33:16'),(6,3,3,1,'2011-11-07 22:33:21'),(7,4,3,1,'2011-11-07 22:33:26'),(8,7,3,1,'2011-11-07 22:33:36'),(9,8,3,1,'2011-11-07 22:33:41'),(10,12,3,1,'2011-11-28 12:35:53');
DROP TABLE IF EXISTS `staticgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticgroup` (
  `idstaticgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idstaticgroup`),
  KEY `FK_staticgroup_addid` (`addid`),
  CONSTRAINT `FK_staticgroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticgroup` (`idstaticgroup`, `addid`, `adddate`) VALUES (1,1,'2012-09-01 19:45:20');
DROP TABLE IF EXISTS `staticgrouptranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staticgrouptranslation` (
  `idstaticgrouptranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `staticgroupid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idstaticgrouptranslation`),
  UNIQUE KEY `UNIQUE_staticgrouptranslation_name_languageid_staticgroupid` (`name`,`languageid`,`staticgroupid`),
  KEY `FK_staticgrouptranslation_languageid` (`languageid`),
  CONSTRAINT `FK_staticgrouptranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `staticgrouptranslation` (`idstaticgrouptranslation`, `name`, `staticgroupid`, `languageid`) VALUES (1,'Odpis od podatku',1,1);
DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store` (
  `idstore` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `countryid` int(10) unsigned DEFAULT NULL,
  `currencyid` int(10) unsigned DEFAULT NULL,
  `defaultphotoid` int(10) unsigned DEFAULT '1',
  `bankname` varchar(500) DEFAULT NULL,
  `banknr` varchar(50) DEFAULT NULL,
  `krs` varchar(45) DEFAULT NULL,
  `nip` varchar(45) DEFAULT NULL,
  `companyname` varchar(255) DEFAULT NULL,
  `shortcompanyname` varchar(255) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `placename` varchar(45) DEFAULT NULL,
  `street` varchar(45) DEFAULT NULL,
  `streetno` varchar(45) DEFAULT NULL,
  `placeno` varchar(45) DEFAULT NULL,
  `province` varchar(45) DEFAULT NULL,
  `invoiceshopslogan` varchar(100) NOT NULL,
  `isinvoiceshopslogan` tinyint(3) unsigned NOT NULL,
  `isinvoiceshopname` tinyint(3) unsigned NOT NULL,
  `invoicephotoid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idstore`),
  KEY `countryid` (`countryid`),
  KEY `FK_store_defaultphotoid` (`defaultphotoid`),
  CONSTRAINT `FK_store_countryid` FOREIGN KEY (`countryid`) REFERENCES `country` (`idcountry`),
  CONSTRAINT `FK_store_defaultphotoid` FOREIGN KEY (`defaultphotoid`) REFERENCES `file` (`idfile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `store` (`idstore`, `name`, `countryid`, `currencyid`, `defaultphotoid`, `bankname`, `banknr`, `krs`, `nip`, `companyname`, `shortcompanyname`, `postcode`, `placename`, `street`, `streetno`, `placeno`, `province`, `invoiceshopslogan`, `isinvoiceshopslogan`, `isinvoiceshopname`, `invoicephotoid`) VALUES (1,'Mediacube',261,28,1,'','','','','Mediacube','Mediacube','','','','','','łódzkie','Dziękujemy za zakupy',1,0,NULL);
DROP TABLE IF EXISTS `subpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subpage` (
  `idsubpage` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  `translation` varchar(128) NOT NULL,
  PRIMARY KEY (`idsubpage`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `subpage` (`idsubpage`, `name`, `description`, `translation`) VALUES (1,'Mainside','Strona główna','TXT_MAINSIDE'),(2,'Product','Karta produktu','TXT_PRODUCT'),(5,'Staticcms','Podstrona statyczna','TXT_STATIC_CMS'),(6,'Contact','Kontakt','TXT_CONTACT'),(7,'News','Wiadomości','TXT_NEWS'),(9,'ProductInCategory','Lista produktów w kategorii','TXT_PRODUCT_IN_CATEGORY'),(11,'Tags','Tagi','TXT_TAGS'),(12,'Search','Wyszukiwarka','TXT_SEARCH'),(13,'Wishlist','Lista życzeń','TXT_WISHLIST'),(14,'Cart','Koszyk','TXT_CART'),(16,'RegistrationCart','Formularz rejestracji','TXT_REGISTRATION_CART'),(17,'Payment','Płatność','TXT_PAYMENT'),(19,'Clientlogin','Formularz logowania','TXT_CLIENT_LOGIN'),(20,'Forgotpassword','Przypomnij hasło','TXT_FORGOT_PASSWORD'),(21,'ClientSettings','Ustawienia konta klienta','TXT_CLIENT_SETTINGS'),(22,'ClientOrder','Zamówienia klienta','TXT_CLIENT_ORDER'),(23,'ClientAddress','Adres klienta','TXT_CLIENT_ADDRESS'),(24,'ProductPromotionList','Lista promocji w sklepie','TXT_PRODUCT_PROMOTION_LIST'),(25,'ProductNewsList','Lista nowości w sklepie','TXT_PRODUCT_NEWS_LIST'),(26,'ProductTagsList','Lista otagowanych produktów','TXT_PRODUCT_TAGS_LIST'),(27,'ProductSearchList','Wyszukiwarka','TXT_PRODUCT_SEARCH_LIST'),(32,'PrivacyPolicy','Polityka prywatności','TXT_PRIVACY_POLICY'),(35,'Newsletter','Newsletter','TXT_NEWSLETTER'),(38,'Sitemap','Mapa strony','TXT_SITEMAP'),(40,'Producerlist','Lista produktów producenta','TXT_PRODUCER_LIST');
DROP TABLE IF EXISTS `subpagelayout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subpagelayout` (
  `idsubpagelayout` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subpageid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsubpagelayout`),
  KEY `subpageid` (`subpageid`),
  KEY `viewid` (`viewid`),
  CONSTRAINT `subpagelayout_ibfk_1` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subpagelayout_ibfk_2` FOREIGN KEY (`subpageid`) REFERENCES `subpage` (`idsubpage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `subpagelayout` (`idsubpagelayout`, `subpageid`, `viewid`) VALUES (1,1,NULL),(2,2,NULL),(5,5,NULL),(6,6,NULL),(7,7,NULL),(9,9,NULL),(12,11,NULL),(13,12,NULL),(14,13,NULL),(15,14,NULL),(19,16,NULL),(20,17,NULL),(22,19,NULL),(23,20,NULL),(24,21,NULL),(25,22,NULL),(26,23,NULL),(27,24,NULL),(28,25,NULL),(29,26,NULL),(30,27,NULL),(35,32,NULL),(39,35,NULL),(42,38,NULL),(45,40,NULL);
DROP TABLE IF EXISTS `subpagelayoutcolumn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subpagelayoutcolumn` (
  `idsubpagelayoutcolumn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subpagelayoutid` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsubpagelayoutcolumn`),
  KEY `FK_subpagelayoutcolumn_editid` (`editid`),
  KEY `FK_subpagelayoutcolumn_addid` (`addid`),
  KEY `subpagelayoutid` (`subpagelayoutid`),
  CONSTRAINT `subpagelayoutcolumn_ibfk_1` FOREIGN KEY (`subpagelayoutid`) REFERENCES `subpagelayout` (`idsubpagelayout`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 0 kB; (`adddate`) REFER `gekosale_alpha/user`(`';
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `subpagelayoutcolumn` (`idsubpagelayoutcolumn`, `subpagelayoutid`, `order`, `width`, `addid`, `adddate`, `editid`, `editdate`, `viewid`) VALUES (3,2,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(4,2,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(12,1,1,180,1,'2011-08-13 19:37:35',1,NULL,NULL),(13,1,2,0,1,'2011-05-08 12:37:44',1,NULL,NULL),(21,5,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(22,5,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(26,1,3,180,1,'2011-05-08 12:37:44',1,NULL,NULL),(28,6,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(29,6,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(32,7,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(34,12,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(36,9,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(38,13,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(39,14,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(40,15,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(42,15,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(48,9,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(50,19,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(51,19,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(54,20,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(55,20,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(60,22,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(61,22,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(63,23,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(64,23,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(66,7,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(68,24,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(69,24,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(71,25,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(72,25,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(74,26,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(75,26,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(77,27,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(78,27,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(80,28,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(81,28,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(83,29,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(84,29,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(86,30,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(87,30,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(97,35,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(98,35,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(103,39,1,180,1,'2010-11-13 15:00:04',1,NULL,NULL),(104,39,2,0,1,'2010-06-25 10:24:13',1,NULL,NULL),(120,42,1,180,1,'2010-11-13 18:25:26',1,NULL,NULL),(121,42,2,0,1,'2010-11-13 18:25:26',NULL,NULL,NULL),(122,14,2,0,1,'2011-02-02 23:39:45',NULL,NULL,NULL),(126,45,1,180,1,'2011-07-11 18:49:12',NULL,NULL,NULL),(127,45,2,0,1,'2011-07-11 18:49:12',NULL,NULL,NULL);
DROP TABLE IF EXISTS `subpagelayoutcolumnbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subpagelayoutcolumnbox` (
  `idsubpagelayoutcolumnbox` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subpagelayoutcolumnid` int(10) unsigned NOT NULL,
  `layoutboxid` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `colspan` int(10) unsigned NOT NULL,
  `collapsed` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsubpagelayoutcolumnbox`),
  KEY `subpagelayoutcolumnid` (`subpagelayoutcolumnid`),
  KEY `layoutboxid` (`layoutboxid`),
  CONSTRAINT `subpagelayoutcolumnbox_ibfk_1` FOREIGN KEY (`subpagelayoutcolumnid`) REFERENCES `subpagelayoutcolumn` (`idsubpagelayoutcolumn`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subpagelayoutcolumnbox_ibfk_2` FOREIGN KEY (`layoutboxid`) REFERENCES `layoutbox` (`idlayoutbox`)
) ENGINE=InnoDB AUTO_INCREMENT=2530 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `subpagelayoutcolumnbox` (`idsubpagelayoutcolumnbox`, `subpagelayoutcolumnid`, `layoutboxid`, `order`, `colspan`, `collapsed`) VALUES (940,97,38,1,1,0),(1111,103,38,0,1,0),(1112,104,81,0,1,0),(1170,54,38,0,1,0),(1171,55,52,0,1,0),(1174,71,38,0,1,0),(1175,72,58,0,1,0),(1225,32,38,0,1,0),(1226,66,30,0,1,0),(1248,74,38,0,1,0),(1249,75,57,0,1,0),(1265,83,38,0,1,0),(1266,84,64,0,1,0),(1411,63,38,0,1,0),(1412,63,66,1,1,0),(1413,64,55,0,1,0),(1463,120,38,0,1,0),(1464,120,66,1,1,0),(1465,120,98,2,1,0),(1466,121,99,0,1,0),(1592,50,38,0,1,0),(1593,51,51,0,1,0),(1664,39,38,0,1,0),(1665,122,43,0,1,0),(1690,68,38,0,1,0),(1691,68,42,1,1,0),(1692,68,43,2,1,0),(1693,69,56,0,1,0),(1952,36,38,0,1,0),(1953,36,103,1,1,0),(1954,36,98,2,1,0),(1955,48,50,0,1,0),(2064,126,38,1,1,0),(2065,126,105,2,1,0),(2066,127,106,1,1,0),(2098,3,38,0,1,0),(2099,3,35,1,1,0),(2100,3,41,2,1,0),(2101,3,43,3,1,0),(2102,4,49,0,1,0),(2103,4,88,1,1,0),(2104,4,86,2,1,0),(2105,4,87,3,1,0),(2106,4,89,4,1,0),(2123,86,38,0,1,0),(2124,86,103,1,1,0),(2125,87,65,0,1,0),(2126,77,38,0,1,0),(2127,77,98,1,1,0),(2128,78,63,0,1,0),(2129,80,38,0,1,0),(2130,80,98,1,1,0),(2131,81,62,0,1,0),(2132,21,38,0,1,0),(2133,21,98,1,1,0),(2134,22,82,0,1,0),(2182,40,38,0,1,0),(2183,40,35,1,1,0),(2184,40,36,2,1,0),(2185,42,46,0,1,0),(2186,42,86,1,1,0),(2187,42,88,2,1,0),(2188,42,87,3,1,0),(2197,28,38,0,1,0),(2198,28,98,1,1,0),(2199,29,29,0,1,0),(2512,12,38,0,1,0),(2513,12,105,1,1,0),(2514,12,36,2,1,0),(2515,12,81,3,1,0),(2516,12,111,4,1,0),(2517,13,110,0,1,0),(2518,13,83,1,1,0),(2519,13,40,2,1,0),(2520,13,44,3,1,0),(2521,13,112,4,1,0),(2523,26,35,0,1,0),(2524,26,41,1,1,0),(2525,26,98,2,1,0),(2526,26,39,3,1,0),(2527,60,38,0,1,0),(2528,61,54,0,1,0),(2529,61,51,1,1,0);
DROP TABLE IF EXISTS `substitutedservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substitutedservice` (
  `idsubstitutedservice` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transmailid` int(10) unsigned NOT NULL,
  `actionid` int(10) unsigned NOT NULL,
  `date` datetime DEFAULT NULL,
  `periodid` int(10) unsigned DEFAULT NULL,
  `admin` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsubstitutedservice`),
  KEY `FK_substitutedservice_transmailid` (`transmailid`),
  KEY `FK_substitutedservice_periodid` (`periodid`),
  KEY `FK_substitutedservice_addid` (`addid`),
  KEY `FK_substitutedservice_editid` (`editid`),
  KEY `FK_substitutedservice_viewid` (`viewid`),
  CONSTRAINT `FK_substitutedservice_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_substitutedservice_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_substitutedservice_periodid` FOREIGN KEY (`periodid`) REFERENCES `period` (`idperiod`),
  CONSTRAINT `FK_substitutedservice_transmailid` FOREIGN KEY (`transmailid`) REFERENCES `transmail` (`idtransmail`),
  CONSTRAINT `FK_substitutedservice_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `substitutedservice` (`idsubstitutedservice`, `transmailid`, `actionid`, `date`, `periodid`, `admin`, `name`, `addid`, `adddate`, `editid`, `editdate`, `viewid`) VALUES (1,21,1,NULL,4,1,'Potwierdzenie złożenia zamówienia przez klienta w ',1,'2012-05-01 11:35:53',NULL,NULL,NULL);
DROP TABLE IF EXISTS `substitutedserviceclients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substitutedserviceclients` (
  `idsubstitutedserviceclients` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `substitutedservicesendid` int(10) unsigned NOT NULL,
  `clientid` int(10) unsigned NOT NULL,
  `send` int(2) unsigned DEFAULT '0',
  `error` int(10) unsigned DEFAULT '0',
  `errorInfo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idsubstitutedserviceclients`),
  KEY `FK_substitutedserviceclients_substitutedservicesendid` (`substitutedservicesendid`),
  KEY `FK_substitutedserviceclients_clientid` (`clientid`),
  CONSTRAINT `FK_substitutedserviceclients_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_substitutedserviceclients_substitutedservicesendid` FOREIGN KEY (`substitutedservicesendid`) REFERENCES `substitutedservicesend` (`idsubstitutedservicesend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `substitutedservicesend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `substitutedservicesend` (
  `idsubstitutedservicesend` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `substitutedserviceid` int(10) unsigned DEFAULT NULL,
  `senddate` datetime NOT NULL,
  `sendid` int(10) unsigned NOT NULL,
  `actionid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idsubstitutedservicesend`),
  KEY `FK_substitutedservicesend_substitutedserviceid` (`substitutedserviceid`),
  CONSTRAINT `FK_substitutedservicesend_substitutedserviceid` FOREIGN KEY (`substitutedserviceid`) REFERENCES `substitutedservice` (`idsubstitutedservice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `suffixtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suffixtype` (
  `idsuffixtype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` char(1) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idsuffixtype`),
  KEY `UNIQUE_suffixtype_symbol` (`symbol`),
  KEY `FK_suffixtype_addid` (`addid`),
  KEY `FK_suffixtype_editid` (`editid`),
  CONSTRAINT `FK_suffixtype_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_suffixtype_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `suffixtype` (`idsuffixtype`, `name`, `symbol`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,'TXT_PERCENT','%',1,'2010-09-23 11:07:18',NULL,NULL),(2,'TXT_PLUS','+',1,'2010-09-23 11:07:18',NULL,NULL),(3,'TXT_SUBTRACT','-',1,'2010-09-23 11:07:18',NULL,NULL),(4,'TXT_EQUAL','=',1,'2010-09-23 11:07:18',NULL,NULL);
DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `idtags` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `textcount` int(10) unsigned DEFAULT '1',
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtags`),
  UNIQUE KEY `UNIQUE_tags_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `technicaldataattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldataattribute` (
  `idtechnicaldataattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idtechnicaldataattribute`),
  KEY `addid` (`addid`),
  KEY `editid` (`editid`),
  CONSTRAINT `technicaldataattribute_ibfk_1` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `technicaldataattribute_ibfk_2` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldataattribute` (`idtechnicaldataattribute`, `type`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,4,1,NULL,NULL,NULL),(2,4,1,NULL,NULL,NULL),(3,4,1,NULL,NULL,NULL),(4,0,1,NULL,NULL,NULL),(5,0,1,NULL,NULL,NULL),(6,0,1,NULL,NULL,NULL),(7,0,1,NULL,NULL,NULL),(8,0,1,NULL,NULL,NULL),(9,0,1,NULL,NULL,NULL),(10,0,1,NULL,NULL,NULL),(11,0,1,NULL,NULL,NULL),(12,0,1,NULL,NULL,NULL);
DROP TABLE IF EXISTS `technicaldataattributetranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldataattributetranslation` (
  `idtechnicaldataattributetranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `technicaldataattributeid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`idtechnicaldataattributetranslation`),
  KEY `technicaldataattributeid` (`technicaldataattributeid`),
  KEY `languageid` (`languageid`),
  CONSTRAINT `technicaldataattributetranslation_ibfk_1` FOREIGN KEY (`technicaldataattributeid`) REFERENCES `technicaldataattribute` (`idtechnicaldataattribute`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `technicaldataattributetranslation_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldataattributetranslation` (`idtechnicaldataattributetranslation`, `technicaldataattributeid`, `languageid`, `name`) VALUES (1,1,1,''),(2,2,1,'Surround'),(3,3,1,'HSD'),(5,5,1,'Rozdzielczość'),(7,6,1,'274'),(8,7,1,'23%22'),(9,8,1,'24'),(10,9,1,'25\'\''),(11,10,1,'27\'\''),(12,11,1,'27\'\''),(13,12,1,'28\'\'fsdfds'),(15,4,1,'Rozmiar');
DROP TABLE IF EXISTS `technicaldatagroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldatagroup` (
  `idtechnicaldatagroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idtechnicaldatagroup`),
  KEY `addid` (`addid`),
  KEY `editid` (`editid`),
  CONSTRAINT `technicaldatagroup_ibfk_1` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `technicaldatagroup_ibfk_2` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldatagroup` (`idtechnicaldatagroup`, `addid`, `adddate`, `editid`, `editdate`) VALUES (2,1,'2011-04-12 18:09:41',NULL,NULL),(3,1,'2011-06-10 23:48:48',NULL,NULL),(4,1,'2011-06-10 23:48:48',NULL,NULL);
DROP TABLE IF EXISTS `technicaldatagrouptranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldatagrouptranslation` (
  `idtechnicaldatagrouptranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `technicaldatagroupid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`idtechnicaldatagrouptranslation`),
  KEY `technicaldatagroupid` (`technicaldatagroupid`),
  KEY `languageid` (`languageid`),
  CONSTRAINT `technicaldatagrouptranslation_ibfk_1` FOREIGN KEY (`technicaldatagroupid`) REFERENCES `technicaldatagroup` (`idtechnicaldatagroup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `technicaldatagrouptranslation_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldatagrouptranslation` (`idtechnicaldatagrouptranslation`, `technicaldatagroupid`, `languageid`, `name`) VALUES (2,2,1,'Bool'),(3,3,1,'Matryca'),(4,4,1,'Dźwięk');
DROP TABLE IF EXISTS `technicaldataset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldataset` (
  `idtechnicaldataset` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `addid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idtechnicaldataset`),
  KEY `addid` (`addid`),
  KEY `editid` (`editid`),
  CONSTRAINT `technicaldataset_ibfk_1` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `technicaldataset_ibfk_2` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldataset` (`idtechnicaldataset`, `name`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,'Laptopy',1,'2012-05-24 16:44:18',NULL,NULL);
DROP TABLE IF EXISTS `technicaldatasetgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldatasetgroup` (
  `idtechnicaldatasetgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `technicaldatasetid` int(10) unsigned NOT NULL,
  `technicaldatagroupid` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`idtechnicaldatasetgroup`),
  KEY `technicaldatasetid` (`technicaldatasetid`),
  KEY `technicaldatagroupid` (`technicaldatagroupid`),
  CONSTRAINT `technicaldatasetgroup_ibfk_1` FOREIGN KEY (`technicaldatasetid`) REFERENCES `technicaldataset` (`idtechnicaldataset`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `technicaldatasetgroup_ibfk_2` FOREIGN KEY (`technicaldatagroupid`) REFERENCES `technicaldatagroup` (`idtechnicaldatagroup`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldatasetgroup` (`idtechnicaldatasetgroup`, `technicaldatasetid`, `technicaldatagroupid`, `order`) VALUES (1,1,3,0);
DROP TABLE IF EXISTS `technicaldatasetgroupattribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technicaldatasetgroupattribute` (
  `idtechnicaldatasetgroupattribute` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `technicaldatasetgroupid` int(10) unsigned NOT NULL,
  `technicaldataattributeid` int(10) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`idtechnicaldatasetgroupattribute`),
  KEY `technicaldatasetgroupid` (`technicaldatasetgroupid`),
  KEY `technicaldataattributeid` (`technicaldataattributeid`),
  CONSTRAINT `technicaldatasetgroupattribute_ibfk_1` FOREIGN KEY (`technicaldatasetgroupid`) REFERENCES `technicaldatasetgroup` (`idtechnicaldatasetgroup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `technicaldatasetgroupattribute_ibfk_2` FOREIGN KEY (`technicaldataattributeid`) REFERENCES `technicaldataattribute` (`idtechnicaldataattribute`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `technicaldatasetgroupattribute` (`idtechnicaldatasetgroupattribute`, `technicaldatasetgroupid`, `technicaldataattributeid`, `order`) VALUES (1,1,4,0);
DROP TABLE IF EXISTS `translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation` (
  `idtranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idtranslation`),
  KEY `FK_translation_addid` (`addid`),
  KEY `FK_translation_editid` (`editid`),
  KEY `UNIQUE_name_languageid` (`name`),
  CONSTRAINT `FK_translation_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_translation_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=5536 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `translation` (`idtranslation`, `name`, `addid`, `adddate`, `editid`, `editdate`) VALUES (1,'TXT_LOGOUT',1,'2010-11-21 16:56:33',1,NULL),(2,'TXT_CLIENTS',1,'2010-09-23 11:07:22',NULL,NULL),(3,'TXT_CATALOG',1,'2010-09-23 11:07:22',NULL,NULL),(4,'TXT_ORDERS',1,'2010-09-23 11:07:22',NULL,NULL),(5,'TXT_USERS',1,'2010-09-23 11:07:22',NULL,NULL),(6,'TXT_CONFIGURATION',1,'2010-09-23 11:07:22',NULL,NULL),(7,'TXT_CMS',1,'2010-09-23 11:07:22',NULL,NULL),(12,'TXT_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(13,'TXT_FIRSTNAME_SURNAME',1,'2010-09-23 11:07:22',NULL,NULL),(14,'TXT_SURNAME',1,'2010-09-23 11:07:22',NULL,NULL),(16,'TXT_FIRSTNAME',1,'2010-09-23 11:07:22',NULL,NULL),(18,'TXT_NEWS',1,'2011-09-14 10:31:20',1,NULL),(20,'TXT_STATIC_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(22,'TXT_TOPIC',1,'2010-09-23 11:07:22',NULL,NULL),(23,'TXT_AUTHOR',1,'2010-09-23 11:07:22',NULL,NULL),(24,'TXT_PUBLISH_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(25,'TXT_END_OF_PUBLISH_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(26,'TXT_PUBLISHED',1,'2010-09-23 11:07:22',NULL,NULL),(28,'TXT_NOT_PUBLISHED',1,'2010-09-23 11:07:22',NULL,NULL),(29,'TXT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(30,'TXT_SELECT_ALL',1,'2010-09-23 11:07:22',NULL,NULL),(32,'TXT_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(34,'TXT_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(36,'TXT_NEWS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(38,'TXT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(39,'TXT_USER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(40,'TXT_USER_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(41,'TXT_USER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(42,'TXT_NEWS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(46,'TXT_GROUP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(47,'TXT_GROUP_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(48,'TXT_GROUP_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(49,'TXT_DESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(50,'TXT_ID',1,'2010-09-23 11:07:22',NULL,NULL),(51,'TXT_USERS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(52,'TXT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(53,'TXT_MONDAY',1,'2010-09-23 11:07:22',NULL,NULL),(54,'TXT_TUESDAY',1,'2010-09-23 11:07:22',NULL,NULL),(55,'TXT_WEDNESDAY',1,'2010-09-23 11:07:22',NULL,NULL),(56,'TXT_THURSDAY',1,'2010-09-23 11:07:22',NULL,NULL),(57,'TXT_FRIDAY',1,'2010-09-23 11:07:22',NULL,NULL),(58,'TXT_SATURDAY',1,'2010-09-23 11:07:22',NULL,NULL),(59,'TXT_SUNDAY',1,'2010-09-23 11:07:22',NULL,NULL),(60,'TXT_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(61,'TXT_TIME',1,'2010-09-23 11:07:22',NULL,NULL),(62,'TXT_USER_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(63,'TXT_HOME_PAGE',1,'2010-09-23 11:07:22',NULL,NULL),(65,'TXT_GROUP_PERMISSION',1,'2010-09-23 11:07:22',NULL,NULL),(73,'TXT_CONTROLLER',1,'2010-09-23 11:07:22',NULL,NULL),(78,'TXT_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(79,'TXT_LIST_CONTROLLER',1,'2010-09-23 11:07:22',NULL,NULL),(81,'TXT_CLIENT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(82,'TXT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(83,'TXT_CLIENT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(84,'TXT_CLIENT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(85,'TXT_STREET',1,'2010-09-23 11:07:22',NULL,NULL),(86,'TXT_NR',1,'2010-09-23 11:07:22',NULL,NULL),(87,'TXT_POSTCODE',1,'2010-09-23 11:07:22',NULL,NULL),(88,'TXT_PLACE',1,'2010-09-23 11:07:22',NULL,NULL),(89,'TXT_COMPANYNAME',1,'2010-09-23 11:07:22',NULL,NULL),(90,'TXT_REGON',1,'2010-09-23 11:07:22',NULL,NULL),(91,'TXT_NIP',1,'2010-09-23 11:07:22',NULL,NULL),(92,'TXT_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(94,'TXT_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(95,'TXT_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(96,'TXT_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(97,'TXT_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(98,'TXT_FORGOT_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(99,'TXT_GROUPS_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(100,'TXT_GROUPS_USERS',1,'2010-09-23 11:07:22',NULL,NULL),(102,'TXT_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(103,'TXT_CLIENT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(104,'TXT_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(106,'TXT_DISCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(107,'TXT_DISPATCHMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(108,'TXT_PAYMENTMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(109,'ERR_CLIENT_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(110,'ERR_NO_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(111,'ERR_INVALID_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(112,'ERR_CLIENT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(113,'ERR_CLIENTGROUP_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(114,'ERR_INSERT_FILE',1,'2010-09-23 11:07:22',NULL,NULL),(115,'ERR_FILETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(116,'ERR_FILE_NOT_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(117,'ERR_CLIENTGROUP_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(118,'ERR_CLIENTGROUP_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(119,'ERR_DISPATCHMETHOD_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(120,'ERR_PERMISSION_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(121,'ERR_GROUP_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(122,'ERR_GROUP_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(123,'ERR_PAYMENTMETHOD_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(124,'ERR_USER_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(125,'ERR_LOGINUSER_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(126,'ERR_PASSWORDUSER_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(127,'ERR_USERDATA_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(128,'ERR_USER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(129,'ERR_USER_TO_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(130,'ERR_LOGINTIME',1,'2010-09-23 11:07:22',NULL,NULL),(131,'ERR_SAVE_FILE',1,'2010-09-23 11:07:22',NULL,NULL),(132,'ERR_SEND_FILE',1,'2010-09-23 11:07:22',NULL,NULL),(136,'TXT_DISPATCHMETHOD_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(137,'TXT_PAYMENTMETHOD_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(138,'TXT_DISPATCHMETHOD_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(141,'TXT_PAYMENTMETHOD_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(142,'ERR_GROUP_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(143,'ERR_CLIENT_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(145,'ERR_DISPATCHMETHOD_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(161,'TXT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(162,'TXT_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(165,'TXT_CHOOSE_SELECT',1,'2010-09-23 11:07:22',NULL,NULL),(170,'TXT_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(171,'TXT_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(173,'TXT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(174,'TXT_CATEGORY_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(175,'TXT_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(176,'TXT_PRODUCT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(177,'TXT_SELLPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(178,'TXT_BUYPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(181,'TXT_SHORTDESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(182,'TXT_SEO',1,'2010-09-23 11:07:22',NULL,NULL),(183,'TXT_BARCODE',1,'2010-09-23 11:07:22',NULL,NULL),(184,'TXT_EAN',1,'2010-09-23 11:07:22',NULL,NULL),(185,'TXT_DELIVELERCODE',1,'2010-09-23 11:07:22',NULL,NULL),(188,'TXT_DELIVER',1,'2010-09-23 11:07:22',NULL,NULL),(189,'TXT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(190,'ERR_CATEGORY_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(192,'TXT_DISTINCTION',1,'2010-09-23 11:07:22',NULL,NULL),(193,'ERR_PRODUCT_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(194,'ERR_CLIENTGROUP_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(195,'ERR_CLIENT_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(196,'ERR_GROUPS_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(197,'ERR_PAYMENTMETHOD_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(198,'ERR_DISPATCHMETHOD_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(199,'TXT_PRODUCT_ATTRIBUTES',1,'2010-09-23 11:07:22',NULL,NULL),(200,'TXT_PLACENO',1,'2010-09-23 11:07:22',NULL,NULL),(201,'TXT_STREETNO',1,'2010-09-23 11:07:22',NULL,NULL),(202,'TXT_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(203,'TXT_DELIVERER',1,'2010-09-23 11:07:22',NULL,NULL),(204,'TXT_DELIVERER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(205,'TXT_PRODUCER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(206,'TXT_CLIENTADRESSTYPE',1,'2010-09-23 11:07:22',NULL,NULL),(207,'TXT_WWW',1,'2010-09-23 11:07:22',NULL,NULL),(208,'ERR_BAD_LOGIN_OR_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(209,'TXT_ORDERSTATUS',1,'2010-09-23 11:07:22',NULL,NULL),(210,'TXT_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(211,'TXT_DELETE_CONFIRM',1,'2010-09-23 11:07:22',NULL,NULL),(212,'TXT_ATTRIBUTE_PRODUCT_GROUP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(214,'TXT_ATTRIBUTE_PRODUCT_VALUES',1,'2010-09-23 11:07:22',NULL,NULL),(215,'TXT_ATTRIBUTE_PRODUCTS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(216,'TXT_FILE',1,'2010-09-23 11:07:22',NULL,NULL),(217,'TXT_ATTRIBUTE_PRODUCT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(218,'TXT_PAYMENTMETHOD_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(219,'TXT_DISPATCHMETHOD_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(220,'TXT_DISPATCHMETHOD_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(221,'TXT_PAYMENTMETHOD_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(222,'TXT_DELETE_SELECTED',1,'2010-09-23 11:07:22',NULL,NULL),(223,'TXT_VAT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(224,'TXT_VAT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(225,'TXT_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(226,'TXT_ATTRIBUTE_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(227,'TXT_ORDERSTATUS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(228,'TXT_ORDERSTATUS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(229,'TXT_DESELECT_ALL',1,'2010-09-23 11:07:22',NULL,NULL),(230,'TXT_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(231,'TXT_PRODUCTCOMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(232,'TXT_PRODUCTCOMBINATION_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(233,'TXT_PRODUCTCOMBINATION_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(234,'TXT_PRODUCTCOMBINATION_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(235,'TXT_PRODUCT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(236,'TXT_PRODUCER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(237,'TXT_CATEGORY_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(238,'TXT_DELIVERER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(239,'TXT_DELIVERER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(240,'TXT_ORDERSTATUS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(241,'TXT_VAT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(242,'TXT_CLIENTGROUP_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(243,'TXT_CLIENTGROUP_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(244,'TXT_GROUPS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(245,'TXT_GROUPS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(248,'TXT_INDYVIDUALADRESS',1,'2010-09-23 11:07:22',NULL,NULL),(249,'TXT_COMPANYADRESS',1,'2010-09-23 11:07:22',NULL,NULL),(250,'TXT_MAILINGADRESS',1,'2010-09-23 11:07:22',NULL,NULL),(251,'TXT_NUMBEROFITEM',1,'2010-09-23 11:07:22',NULL,NULL),(258,'TXT_CONTROLLER_NOT_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(259,'TXT_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(260,'TXT_DISTINCTION',1,'2010-09-23 11:07:22',NULL,NULL),(261,'TXT_PARENTCATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(262,'TXT_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(263,'TXT_ADDITIONAL_OPTION',1,'2010-09-23 11:07:22',NULL,NULL),(264,'TXT_ADD_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(265,'TXT_EDIT_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(268,'TXT_LASTLOGGED',1,'2010-09-23 11:07:22',NULL,NULL),(269,'TXT_GROUPS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(270,'TXT_TABLE_PERMISSION',1,'2010-09-23 11:07:22',NULL,NULL),(272,'TXT_CLIENTGROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(273,'TXT_PRODUCER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(274,'TXT_SUFFIXTYPE',1,'2010-09-23 11:07:22',NULL,NULL),(275,'TXT_SELECT_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(276,'TXT_ATTRIBUTESPRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(277,'TXT_PRODUCT_DUPLICATE',1,'2010-09-23 11:07:22',NULL,NULL),(278,'TXT_COMBINATIONSET',1,'2010-09-23 11:07:22',NULL,NULL),(279,'TXT_ATTRIBUTE',1,'2010-09-23 11:07:22',NULL,NULL),(282,'TXT_DETAILS',1,'2010-09-23 11:07:22',NULL,NULL),(284,'TXT_ADD_TO_CART',1,'2010-09-23 11:07:22',NULL,NULL),(285,'TXT_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(286,'TXT_LANGUAGE',1,'2010-09-23 11:07:22',NULL,NULL),(287,'TXT_LANGUAGE_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(288,'TXT_LANGUAGE_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(289,'TXT_LANGUAGE_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(290,'TXT_TRANSLATION',1,'2010-09-23 11:07:22',NULL,NULL),(291,'TXT_TRANSLATION_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(293,'TXT_TRANSLATION_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(295,'TXT_TRANSLATION_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(296,'TXT_URL',1,'2010-09-23 11:07:22',NULL,NULL),(297,'TXT_SESSION',1,'2010-09-23 11:07:22',NULL,NULL),(298,'TXT_HISTORYLOGS',1,'2010-09-23 11:07:22',NULL,NULL),(299,'TXT_USERHISTORYLOGS',1,'2010-09-23 11:07:22',NULL,NULL),(301,'TXT_MAIN_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(302,'TXT_SPECIFICATIONS',1,'2010-09-23 11:07:22',NULL,NULL),(303,'TXT_OPINIONS',1,'2010-09-23 11:07:22',NULL,NULL),(304,'TXT_COMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(306,'TXT_SHOW_ALL',1,'2010-09-23 11:07:22',NULL,NULL),(307,'TXT_CATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(308,'TXT_RESULTS_PER_PAGE',1,'2010-09-23 11:07:22',NULL,NULL),(309,'TXT_GO_TO_PAGE',1,'2010-09-23 11:07:22',NULL,NULL),(310,'TXT_NEXT',1,'2010-09-23 11:07:22',NULL,NULL),(311,'TXT_PREVIOUS',1,'2010-09-23 11:07:22',NULL,NULL),(313,'TXT_LAST_USERS',1,'2010-09-23 11:07:22',NULL,NULL),(314,'TXT_POLISH',1,'2010-09-23 11:07:22',NULL,NULL),(315,'TXT_ENGLISH',1,'2010-09-23 11:07:22',NULL,NULL),(317,'TXT_LAST_ADD_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(318,'TXT_CART',1,'2010-09-23 11:07:22',NULL,NULL),(319,'TXT_PRODUCTS_ON_CART',1,'2010-09-23 11:07:22',NULL,NULL),(320,'TXT_DELETE_FROM_CART',1,'2010-09-23 11:07:22',NULL,NULL),(322,'TXT_PREVIEW',1,'2010-09-23 11:07:22',NULL,NULL),(323,'TXT_SEARCH',1,'2010-09-23 11:07:22',NULL,NULL),(324,'TXT_FROM_ALL_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(325,'TXT_FROM_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(327,'TXT_SEARCH_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(328,'TXT_YOU_ARE_HERE',1,'2010-09-23 11:07:22',NULL,NULL),(329,'TXT_MOST_SHEARCH',1,'2010-09-23 11:07:22',NULL,NULL),(330,'TXT_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(332,'TXT_RELATION_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(333,'TXT_SIMILAR_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(334,'TXT_PRODUCT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(337,'TXT_MAINSIDE',1,'2010-09-23 11:07:22',NULL,NULL),(338,'TXT_DISPLAY_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(339,'TXT_PRODUCT_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(340,'TXT_ADDDATE',1,'2010-09-23 11:07:22',NULL,NULL),(342,'TXT_ADDUSER',1,'2010-09-23 11:07:22',NULL,NULL),(343,'TXT_EDITDATE',1,'2010-09-23 11:07:22',NULL,NULL),(344,'TXT_EDITUSER',1,'2010-09-23 11:07:22',NULL,NULL),(345,'TXT_CHANGE_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(346,'TXT_VALUE_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(347,'TXT_DISPLAY_PRODUCTS_FOR_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(348,'TXT_WEBSITE',1,'2010-09-23 11:07:22',NULL,NULL),(349,'TXT_DISPLAY_VAT_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(350,'TXT_CLIENT_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(351,'TXT_DISPLAY_GROUP_CLIENTS',1,'2010-09-23 11:07:22',NULL,NULL),(352,'TXT_USER_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(353,'TXT_DISPLAY_GROUP_USERS',1,'2010-09-23 11:07:22',NULL,NULL),(354,'TXT_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(355,'F',1,'2010-09-23 11:07:22',NULL,NULL),(356,'M',1,'2010-09-23 11:07:22',NULL,NULL),(357,'TXT_CONTACT',1,'2010-09-23 11:07:22',NULL,NULL),(358,'TXT_SUPPORT_MENU',1,'2010-09-23 11:07:22',NULL,NULL),(359,'TXT_ADDITIONAL_INFORMATION',1,'2010-09-23 11:07:22',NULL,NULL),(361,'TXT_REGULATIONS',1,'2010-09-23 11:07:22',NULL,NULL),(362,'TXT_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(363,'TXT_NEXT_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(364,'TXT_PREVIOUS_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(365,'TXT_ORDER_INTERESTING_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(366,'ERR_EMPTY_FIRSTNAME',1,'2010-09-23 11:07:22',NULL,NULL),(367,'ERR_EMPTY_SURNAME',1,'2010-09-23 11:07:22',NULL,NULL),(368,'ERR_EMPTY_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(369,'ERR_WRONG_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(370,'ERR_EMPTY_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(372,'ERR_EMPTY_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(373,'ERR_EMPTY_DISCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(374,'ERR_EMPTY_STREETNO',1,'2010-09-23 11:07:22',NULL,NULL),(375,'ERR_EMPTY_STREET',1,'2010-09-23 11:07:22',NULL,NULL),(376,'ERR_EMPTY_PLACENO',1,'2010-09-23 11:07:22',NULL,NULL),(377,'ERR_EMPTY_COMPANYNAME',1,'2010-09-23 11:07:22',NULL,NULL),(378,'ERR_EMPTY_PLACE',1,'2010-09-23 11:07:22',NULL,NULL),(379,'ERR_EMPTY_POSTCODE',1,'2010-09-23 11:07:22',NULL,NULL),(380,'ERR_EMPTY_CLIENTADRESSTYPE',1,'2010-09-23 11:07:22',NULL,NULL),(381,'ERR_EMPTY_ATTRIBUTE_PRODUCT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(382,'ERR_EMPTY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(383,'ERR_EMPTY_TRANSLATION',1,'2010-09-23 11:07:22',NULL,NULL),(384,'ERR_EMPTY_SELLPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(386,'ERR_EMPTY_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(387,'ERR_EMPTY_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(388,'ERR_EMPTY_PRIORITY',1,'2010-09-23 11:07:22',NULL,NULL),(389,'ERR_EMPTY_TOPIC',1,'2010-09-23 11:07:22',NULL,NULL),(390,'TXT_PASSWORD_CHANGE',1,'2010-09-23 11:07:22',NULL,NULL),(391,'TXT_PASSWORD_OLD',1,'2010-09-23 11:07:22',NULL,NULL),(392,'TXT_PASSWORD_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(393,'TXT_PASSWORD_REPEAT',1,'2010-09-23 11:07:22',NULL,NULL),(394,'TXT_FINALIZATION',1,'2010-09-23 11:07:22',NULL,NULL),(395,'TXT_PAYMENT',1,'2010-09-23 11:07:22',NULL,NULL),(396,'TXT_BACK',1,'2010-09-23 11:07:22',NULL,NULL),(397,'TXT_CUSTOMER',1,'2010-09-23 11:07:22',NULL,NULL),(398,'TXT_CLIENT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(399,'TXT_CHANGE_ADRESS',1,'2010-09-23 11:07:22',NULL,NULL),(400,'TXT_CHANGE_DELIVERY_ADRESS',1,'2010-09-23 11:07:22',NULL,NULL),(401,'TXT_ATTENTION',1,'2010-09-23 11:07:22',NULL,NULL),(402,'TXT_DELIVERY_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(403,'TXT_DELIVERY_TIME',1,'2010-09-23 11:07:22',NULL,NULL),(404,'TXT_TOTAL_COST',1,'2010-09-23 11:07:22',NULL,NULL),(405,'TXT_COST',1,'2010-09-23 11:07:22',NULL,NULL),(406,'TXT_CURRENCY',1,'2010-09-23 11:07:22',NULL,NULL),(407,'TXT_BACK_TO_SHOPPING',1,'2010-09-23 11:07:22',NULL,NULL),(408,'TXT_ORDER_STAGE',1,'2010-09-23 11:07:22',NULL,NULL),(409,'TXT_PRODUCT_NAME',1,'2011-09-08 10:41:11',1,NULL),(410,'TXT_PRODUCT_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(411,'TXT_PRODUCT_QUANTITY',1,'2010-09-23 11:07:22',NULL,NULL),(412,'TXT_PRODUCT_SUBTOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(413,'TXT_COMFIRM_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(414,'TXT_SEND_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(415,'TXT_SUM_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(416,'TXT_PAYMENT_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(417,'TXT_CONFIMR',1,'2010-09-23 11:07:22',NULL,NULL),(418,'TXT_REGISTRATION',1,'2010-09-23 11:07:22',NULL,NULL),(419,'TXT_LOGIN_PROCESS',1,'2010-09-23 11:07:22',NULL,NULL),(420,'TXT_PLACENR',1,'2010-09-23 11:07:22',NULL,NULL),(421,'TXT_STREETNR',1,'2010-09-23 11:07:22',NULL,NULL),(422,'TXT_ACCEPT',1,'2010-09-23 11:07:22',NULL,NULL),(423,'TXT_PRIVACY_POLICIES',1,'2010-09-23 11:07:22',NULL,NULL),(424,'TXT_THE_RULES',1,'2010-09-23 11:07:22',NULL,NULL),(425,'TXT_REGISTER',1,'2010-09-23 11:07:22',NULL,NULL),(426,'TXT_SEND',1,'2010-09-23 11:07:22',NULL,NULL),(427,'TXT_REQUIRED',1,'2010-09-23 11:07:22',NULL,NULL),(428,'TXT_FIELDS_REQUIRED',1,'2010-09-23 11:07:22',NULL,NULL),(429,'TXT_FIELDS',1,'2010-09-23 11:07:22',NULL,NULL),(430,'TXT_YOU_HAVE_ANY_QUESTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(431,'TXT_EMAIL_SHOP',1,'2010-09-23 11:07:22',NULL,NULL),(434,'TXT_DELIVER_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(435,'TXT_UNABLE_TO_DELETE_RECORD',1,'2010-09-23 11:07:22',NULL,NULL),(436,'TXT_UNABLE_TO_DELETE_RECORD_DESC',1,'2010-09-23 11:07:22',NULL,NULL),(437,'ERR_FOUND_USERS_IN_GROUP_THAT_IS_DUE_TO_BE_DELETED',1,'2010-09-23 11:07:22',NULL,NULL),(438,'TXT_MORE_INTERESTING_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(439,'TXT_YOU_CAN_PRE_PAY_FOR_PACKAGE',1,'2010-09-23 11:07:22',NULL,NULL),(440,'TXT_YOU_CAN_PAY_FOR_INTERNET_TRANSFER',1,'2010-09-23 11:07:22',NULL,NULL),(441,'TXT_YOU_CAN_PAY_FOR_PACKAGE_WITH_CREDIT_CARD',1,'2010-09-23 11:07:22',NULL,NULL),(442,'TXT_YOU_CAN_PAY_CASH_ON_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(443,'TXT_MENU',1,'2010-09-23 11:07:22',NULL,NULL),(444,'TXT_PRODUCTSTATUS',1,'2010-09-23 11:07:22',NULL,NULL),(445,'TXT_PRODUCTSTATUS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(446,'TXT_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(447,'TXT_LOGIN_TO_YOUR_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(448,'TXT_CLICK_HERE',1,'2010-09-23 11:07:22',NULL,NULL),(449,'TXT_CROSSSELL',1,'2010-09-23 11:07:22',NULL,NULL),(450,'TXT_UPSELL',1,'2010-09-23 11:07:22',NULL,NULL),(451,'TXT_SIMILARPRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(452,'TXT_CROSS_SELL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(453,'TXT_UP_SELL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(454,'TXT_SIMILAR_PRODUCT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(455,'TXT_EDIT_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(456,'TXT_KIND_OF_CURRENCY',1,'2010-09-23 11:07:22',NULL,NULL),(457,'TXT_NAME_OF_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(459,'TXT_SHOPNAME',1,'2010-09-23 11:07:22',NULL,NULL),(460,'TXT_GLOBALSETTINGS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(461,'TXT_GLOBALSETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(462,'TXT_EDIT_GLOBALSETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(463,'TXT_SELECT_BASE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(464,'TXT_SELECT_CROSS_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(465,'TXT_BASE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(466,'TXT_GLOBAL_CONFIGURATION',1,'2010-09-23 11:07:22',NULL,NULL),(467,'TXT_CHANGE_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(468,'TXT_CLIENT_PANEL',1,'2010-09-23 11:07:22',NULL,NULL),(469,'TXT_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(470,'TXT_YOUR_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(471,'TXT_CORRECT',1,'2010-09-23 11:07:22',NULL,NULL),(472,'TXT_ERROR',1,'2010-09-23 11:07:22',NULL,NULL),(473,'TXT_CHANGE',1,'2010-09-23 11:07:22',NULL,NULL),(474,'TXT_ERROR_CHANGE',1,'2010-09-23 11:07:22',NULL,NULL),(475,'TXT_ERROR_CHANGE_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(476,'TXT_CHANGE_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(477,'TXT_CHANGE_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(478,'TXT_CONFIRM',1,'2010-09-23 11:07:22',NULL,NULL),(479,'TXT_EMAIL_OLD',1,'2010-09-23 11:07:22',NULL,NULL),(480,'TXT_CONFIMR_EMAIL_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(481,'TXT_CHANGE_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(482,'TXT_AND',1,'2010-09-23 11:07:22',NULL,NULL),(483,'TXT_CHECK_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(485,'TXT_WAITING',1,'2010-09-23 11:07:22',NULL,NULL),(486,'TXT_QUANTITY',1,'2010-09-23 11:07:22',NULL,NULL),(487,'TXT_PRINT',1,'2010-09-23 11:07:22',NULL,NULL),(488,'TXT_HISTORY_ORDERS',1,'2010-09-23 11:07:22',NULL,NULL),(489,'TXT_ORDERS_NR',1,'2010-09-23 11:07:22',NULL,NULL),(490,'TXT_ORDERS_DAY',1,'2010-09-23 11:07:22',NULL,NULL),(491,'TXT_ORDERS_SUBMITTED',1,'2010-09-23 11:07:22',NULL,NULL),(492,'TXT_ALL_ORDERS_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(493,'TXT_DELIVERY_ORDERS_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(494,'TXT_METHOD_OF_PEYMENT',1,'2010-09-23 11:07:22',NULL,NULL),(495,'TXT_METHOD_OF_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(496,'TXT_SEND_YOUR_QUERY',1,'2010-09-23 11:07:22',NULL,NULL),(497,'TXT_HELP',1,'2010-09-23 11:07:22',NULL,NULL),(498,'TXT_DELIVERY_TERMS',1,'2010-09-23 11:07:22',NULL,NULL),(499,'TXT_HELP_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(500,'TXT_DELIVERY_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(501,'TXT_SEARCH_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(502,'TXT_FOUND_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(503,'TXT_SHOW',1,'2010-09-23 11:07:22',NULL,NULL),(504,'TXT_ALL',1,'2010-09-23 11:07:22',NULL,NULL),(505,'TXT_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(506,'TXT_TO',1,'2010-09-23 11:07:22',NULL,NULL),(507,'ERR_DISPATCH_METHOD_BIND_TO_PAYMENT_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(508,'TXT_NEW_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(509,'ERR_EMPTY_BUYPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(510,'TXT_DELIVERERPRICE',1,'2011-01-19 18:48:27',1,NULL),(511,'TXT_DELIVERYCOST',1,'2010-09-23 11:07:22',NULL,NULL),(513,'TXT_RANGE',1,'2010-09-23 11:07:22',NULL,NULL),(515,'TXT_RANGETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(516,'TXT_RANGETYPE_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(517,'ERR_FOUND_CLIENTS_IN_GROUP_THAT_IS_DUE_TO_BE_DELETED',1,'2010-09-23 11:07:22',NULL,NULL),(518,'ERR_VAT_BIND_TO_CLIENTADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(519,'ERR_VAT_BIND_TO_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(520,'TXT_UNABLE_TO_RETRY_OPERATION',1,'2010-09-23 11:07:22',NULL,NULL),(521,'TXT_NOT_ALL_CONFLICTS_HAS_BEEN_RESOLVED',1,'2010-09-23 11:07:22',NULL,NULL),(522,'TXT_PRODUCTRANGE_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(523,'TXT_PRODUCTRANGE',1,'2010-09-23 11:07:22',NULL,NULL),(524,'TXT_MEN',1,'2010-09-23 11:07:22',NULL,NULL),(525,'TXT_WOMAN',1,'2010-09-23 11:07:22',NULL,NULL),(527,'TXT_ADDITIONALPHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(528,'TXT_PHOTOTYPE',1,'2010-09-23 11:07:22',NULL,NULL),(529,'ERR_PRODUCER_BIND_TO_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(531,'TXT_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(532,'TXT_ATTRIBUTENAME',1,'2010-09-23 11:07:22',NULL,NULL),(533,'ERR_CATEGORY_BIND_TO_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(534,'ERR_CATEGORY_BIND_TO_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(535,'TXT_YES',1,'2010-09-23 11:07:22',NULL,NULL),(536,'TXT_NO',1,'2010-09-23 11:07:22',NULL,NULL),(537,'TXT_PUBLISH',1,'2010-09-23 11:07:22',NULL,NULL),(539,'TXT_CONTENTCATEGORY  ',1,'2010-09-23 11:07:22',NULL,NULL),(540,'TXT_ALIAS',1,'2010-09-23 11:07:22',NULL,NULL),(541,'TXT_STATICBLOCKS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(542,'TXT_STATIC_BLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(543,'TXT_CONTENT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(544,'TXT_CONTENTCATEGORY_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(545,'TXT_CONTENTCATEGORY_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(546,'TXT_CONTENTCATEGORY_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(547,'TXT_STATICBLOCKS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(548,'TXT_SUBTRACT',1,'2010-09-23 11:07:22',NULL,NULL),(549,'TXT_PLUS',1,'2010-09-23 11:07:22',NULL,NULL),(550,'TXT_PERCENT',1,'2010-09-23 11:07:22',NULL,NULL),(551,'TXT_EQUAL',1,'2010-09-23 11:07:22',NULL,NULL),(552,'ERR_EMPTY_ADDRESSTYPE',1,'2010-09-23 11:07:22',NULL,NULL),(554,'ERR_EMPTY_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(555,'ERR_EMPTY_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(556,'ERR_EMPTY_LANGUAGE',1,'2010-09-23 11:07:22',NULL,NULL),(557,'ERR_EMPTY_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(558,'ERR_EMPTY_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(559,'TXT_CONFIRM_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(560,'ERR_EMPTY_CONFIRM_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(561,'TXT_ACCERT_TERMS_AND_POLICY_OF_PRIVATE',1,'2010-09-23 11:07:22',NULL,NULL),(562,'ERR_TERMS_NOT_AGREED',1,'2010-09-23 11:07:22',NULL,NULL),(563,'ERR_PASSWORDS_NOT_COMPATIBILE',1,'2010-09-23 11:07:22',NULL,NULL),(564,'ERR_EMPTY_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(566,'TXT_CLIENT_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(567,'TXT_CLIENT_ORDERS',1,'2010-09-23 11:07:22',NULL,NULL),(568,'TXT_CLIENT_ADDRESS_DEFAULT',1,'2010-09-23 11:07:22',NULL,NULL),(569,'TXT_USE_ADDRESS_DEFAULT',1,'2010-09-23 11:07:22',NULL,NULL),(570,'TXT_DEFAULT',1,'2010-09-23 11:07:22',NULL,NULL),(571,'TXT_USE_DEFAULT',1,'2010-09-23 11:07:22',NULL,NULL),(572,'TXT_ADD_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(573,'TXT_ADDRESS_BOOK',1,'2010-09-23 11:07:22',NULL,NULL),(576,'ERR_EMPTY  ',1,'2010-09-23 11:07:22',NULL,NULL),(577,'ERR_COMPARE',1,'2010-09-23 11:07:22',NULL,NULL),(578,'TXT_CHANGE_PASS',1,'2010-09-23 11:07:22',NULL,NULL),(579,'ERR_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(580,'TXT_PRODUCT_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(581,'TXT_PRODUCT_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(582,'TXT_PRODUCT_NEWS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(583,'TXT_PRODUCT_PROMOTION_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(584,'TXT_DONT_HAVE_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(585,'TXT_REGISTRATION_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(586,'TXT_REMIND',1,'2010-09-23 11:07:22',NULL,NULL),(587,'TXT_SEND_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(588,'ERR_EMAIL_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(589,'TXT_ADDRESSLABEL',1,'2010-09-23 11:07:22',NULL,NULL),(590,'ERR_WRONG_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(591,'TXT_DISPATCHMETHODPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(592,'ERR_EMPTY_DISPATCHMETHODCOST',1,'2010-09-23 11:07:22',NULL,NULL),(593,'TXT_PRODUCT_COMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(594,'TXT_FILEEXTENSION',1,'2010-09-23 11:07:22',NULL,NULL),(595,'TXT_FILETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(596,'TXT_FILES',1,'2010-09-23 11:07:22',NULL,NULL),(597,'TXT_FAX',1,'2010-09-23 11:07:22',NULL,NULL),(598,'TXT_CONTACT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(599,'TXT_CONTACT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(600,'TXT_DELETE_UNBIND_FILES',1,'2010-09-23 11:07:22',NULL,NULL),(601,'ERR_EMPTY_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(602,'ERR_EMPTY_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(603,'ERR_EMPTY_SHEATCH',1,'2010-09-23 11:07:22',NULL,NULL),(604,'ERR_EMPTY_MENUCATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(605,'ERR_EMPTY_PRODUCT_TOP',1,'2010-09-23 11:07:22',NULL,NULL),(606,'ERR_EMPTY_SUPPORT_MENU',1,'2010-09-23 11:07:22',NULL,NULL),(607,'ERR_EMPTY_PROMOTION ',1,'2010-09-23 11:07:22',NULL,NULL),(608,'ERR_EMPTY_PRODUCT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(609,'ERR_EMPTY_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(610,'TXT_ACCEPT_AN_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(611,'TXT_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(612,'ERR_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(613,'ERR_EMPTY_PRODUCT_SEARCH',1,'2010-09-23 11:07:22',NULL,NULL),(614,'ERR_SELECT',1,'2010-09-23 11:07:22',NULL,NULL),(615,'TXT_EXPAND_SUBCATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(616,'TXT_CANCEL',1,'2010-09-23 11:07:22',NULL,NULL),(617,'ERR_EMPTY_NIP',1,'2010-09-23 11:07:22',NULL,NULL),(618,'ERR_EMPTY_REGON',1,'2010-09-23 11:07:22',NULL,NULL),(619,'TXT_PRODUCT_STANDARD',1,'2010-09-23 11:07:22',NULL,NULL),(620,'TXT_DISPATCH',1,'2010-09-23 11:07:22',NULL,NULL),(621,'TXT_GLOBALPRICE',1,'2010-09-23 11:07:22',NULL,NULL),(622,'TXT_PRICE_BASE',1,'2010-09-23 11:07:22',NULL,NULL),(623,'TXT_ORDER_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(624,'TXT_GLOBALQTY',1,'2010-09-23 11:07:22',NULL,NULL),(625,'ERR_WRONG_POSTCODE',1,'2010-09-23 11:07:22',NULL,NULL),(626,'TXT_PLACENAME',1,'2010-09-23 11:07:22',NULL,NULL),(627,'TXT_ORDER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(628,'TXT_CHANGE_ADDR_TEMP',1,'2010-09-23 11:07:22',NULL,NULL),(629,'TXT_QTY_OPINIONS',1,'2010-09-23 11:07:22',NULL,NULL),(630,'TXT_SHOW_ALL_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(631,'TXT_AVERAGE_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(632,'TXT_WRITE_COMMENT',1,'2010-09-23 11:07:22',NULL,NULL),(633,'TXT_CLEAR',1,'2010-09-23 11:07:22',NULL,NULL),(634,'TXT_AN_ORDER_REALIZATION',1,'2010-09-23 11:07:22',NULL,NULL),(637,'TXT_DETAIL_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(639,'TXT_PRODUCT_ADDED_SUCCESSFULLY',1,'2010-09-23 11:07:22',NULL,NULL),(640,'TXT_ADD_ANOTHER_ONE',1,'2010-09-23 11:07:22',NULL,NULL),(641,'TXT_DISPLAY_ALL',1,'2010-09-23 11:07:22',NULL,NULL),(642,'TXT_EDIT_RECENTLY_ADDED',1,'2010-09-23 11:07:22',NULL,NULL),(643,'TXT_ADD_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(644,'TXT_DISPATCH_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(645,'ERR_EMPTY_UNDER_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(646,'TXT_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(647,'TXT_USER_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(648,'ERR_WRONG_FORMAT_POSTCODE',1,'2010-09-23 11:07:22',NULL,NULL),(649,'TXT_INTEGRATION_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(650,'TXT_ORDER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(656,'TXT_INSERT_TEMP_ADDR',1,'2010-09-23 11:07:22',NULL,NULL),(657,'ERR_WRONG_FORMAT_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(658,'TXT_EMAIL_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(659,'TXT_CART_IS_EMPTY',1,'2010-09-23 11:07:22',NULL,NULL),(660,'TXT_CHOSE_PRODUCT_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(662,'TXT_SORT_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(663,'TXT_ASC',1,'2010-09-23 11:07:22',NULL,NULL),(664,'TXT_DESC',1,'2010-09-23 11:07:22',NULL,NULL),(666,'TXT_RANGETYPE_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(667,'TXT_CHOOSE_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(668,'TXT_GOOD',1,'2010-09-23 11:07:22',NULL,NULL),(671,'TXT_REVIEWS_LOGIN_REQUIRED',1,'2010-09-23 11:07:22',NULL,NULL),(672,'TXT_EDIT_CLIENT_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(673,'TXT_FORM_REQUIRED',1,'2010-09-23 11:07:22',NULL,NULL),(674,'ERR_EMPTY_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(675,'TXT_SEARCH_RESULTS_FOR_QUERY',1,'2010-09-23 11:07:22',NULL,NULL),(677,'TXT_EMPTY_STOREHOUSE',1,'2010-09-23 11:07:22',NULL,NULL),(678,'TXT_ALL_PRODUCERS',1,'2010-09-23 11:07:22',NULL,NULL),(680,'ERR_CANT_PLACE_AN_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(681,'ERR_FILL_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(682,'TXT_MEDIA',1,'2010-09-23 11:07:22',NULL,NULL),(683,'TXT_EMPTY_CART',1,'2010-09-23 11:07:22',NULL,NULL),(684,'TXT_ATTRIBUTE_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(685,'TXT_PRODUCT_REVIEW',1,'2010-09-23 11:07:22',NULL,NULL),(686,'TXT_STANDARD_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(687,'TXT_FORM_TEMP_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(688,'ERR_EMPTY_DISTINCTION',1,'2010-09-23 11:07:22',NULL,NULL),(689,'TXT_PRODUCT_RELATED',1,'2010-09-23 11:07:22',NULL,NULL),(690,'TXT_UPSELL_PRODUCT',1,'2012-02-21 10:11:13',1,NULL),(691,'TXT_DELETE_RECENTLY_ADDED',1,'2010-09-23 11:07:22',NULL,NULL),(692,'TXT_CONFIRMATION',1,'2010-09-23 11:07:22',NULL,NULL),(693,'ERR_MAIN_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(694,'TXT_ADD_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(696,'TXT_SEE',1,'2010-09-23 11:07:22',NULL,NULL),(697,'TXT_ADDING_OPINIONS_STATUTE',1,'2010-09-23 11:07:22',NULL,NULL),(698,'TXT_WRITE_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(699,'TXT_SHOW_DETAILED_USERS_GRADES',1,'2010-09-23 11:07:22',NULL,NULL),(700,'ERR_EMPTY_PRODUCT_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(701,'TXT_PRODUCT_TAGS_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(702,'TXT_PRODUCTS_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(703,'ERR_TAGS_ADDING',1,'2010-09-23 11:07:22',NULL,NULL),(704,'TXT_TEXTCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(705,'TXT_STATSCLIENTS',1,'2010-09-23 11:07:22',NULL,NULL),(706,'TXT_STATSPRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(707,'TXT_STATSSALES',1,'2010-09-23 11:07:22',NULL,NULL),(708,'TXT_CLIENTORDER_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(709,'TXT_MOST_SEARCH',1,'2010-09-23 11:07:22',NULL,NULL),(711,'TXT_DO_REMOVE_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(712,'TXT_DO_REMOVE_DO_YOU_WONT_TO',1,'2010-09-23 11:07:22',NULL,NULL),(713,'TXT_WORDS',1,'2010-09-23 11:07:22',NULL,NULL),(714,'TXT_UNKNOWN_RANGE',1,'2010-09-23 11:07:22',NULL,NULL),(715,'ERR_BIND_UPSELL_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(716,'ERR_STOCK_LESS_THAN_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(717,'ERR_BIND_CROSSSELL_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(718,'ERR_BIND_SIMILARPRODUCT_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(719,'ERR_NONZERO_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(720,'ERR_SHORTAGE_OF_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(721,'ERR_CHOSE_PRODUCT_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(722,'ERR_MAX_STORAGE_STATE_ON_CART',1,'2010-09-23 11:07:22',NULL,NULL),(723,'ERR_ORDERED_QTY_EXCEEDS_STORAGE_STATE',1,'2010-09-23 11:07:22',NULL,NULL),(724,'ERR_MAX_STORAGE_STATE_ADD_TO_CART',1,'2010-09-23 11:07:22',NULL,NULL),(725,'ERR_COULDNT_INCREASE_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(726,'ERR_ADD_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(727,'ERR_FILL_AN_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(728,'ERR_FILL_A_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(729,'TXT_ADD_NEW_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(730,'TXT_TAG_ASCRIBED',1,'2010-09-23 11:07:22',NULL,NULL),(731,'ERR_DUPLICATED_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(732,'ERR_INSERT_PHRASE',1,'2010-09-23 11:07:22',NULL,NULL),(733,'ERR_PHRASE_SEARCHING',1,'2010-09-23 11:07:22',NULL,NULL),(734,'TXT_WANT_DELETE_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(737,'TXT_GLOBAL_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(738,'TXT_GLOBAL_SETTINGS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(743,'ERR_DUPLICATE_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(744,'TXT_LOGGOUT_CHANGED_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(745,'ERR_DELIVERER_NO_EXIST ',1,'2010-09-23 11:07:22',NULL,NULL),(746,'ERR_DELIVERER_PRODUCT_ADD ',1,'2010-09-23 11:07:22',NULL,NULL),(747,'ERR_DELIVERER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(748,'TXT_POLL',1,'2010-09-23 11:07:22',NULL,NULL),(749,'TXT_POLL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(750,'TXT_QUESTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(751,'TXT_ANSWERS',1,'2010-09-23 11:07:22',NULL,NULL),(752,'TXT_VOTES',1,'2010-09-23 11:07:22',NULL,NULL),(753,'ERR_POLL_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(754,'TXT_POLL_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(755,'ERR_EMPTY_QUESTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(756,'TXT_ANSWERS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(757,'ERR_ANSWERS_EMPTY',1,'2010-09-23 11:07:22',NULL,NULL),(758,'TXT_POLL_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(759,'ERR_EMPTY_ANSWERS',1,'2010-09-23 11:07:22',NULL,NULL),(760,'ERR_EMPTY_VOTES',1,'2010-09-23 11:07:22',NULL,NULL),(761,'ERR_POLL_ANSWERS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(762,'ERR_POLL_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(763,'ERR_POLL_EDIT ',1,'2010-09-23 11:07:22',NULL,NULL),(764,'ERR_ADD_DENGEROUS_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(765,'TXT_ANSWERS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(766,'ERR_POLL_ANSWERS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(767,'TXT_ADD_TO_WISH_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(768,'ERR_EMPTY_POLL',1,'2010-09-23 11:07:22',NULL,NULL),(769,'TXT_POLL_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(770,'TXT_WISHLIST',1,'2010-09-23 11:07:22',NULL,NULL),(771,'TXT_CLIENT_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(772,'ERR_EMPTY_CLIENT_TAGS',1,'2010-09-23 11:07:22',NULL,NULL),(773,'ERR_QUERY_WISHLIST',1,'2010-09-23 11:07:22',NULL,NULL),(774,'TXT_WISHLIST_PRODUCT_WAS_DELETED',1,'2010-09-23 11:07:22',NULL,NULL),(775,'ERR_WISHLIST_NO_PRODUCT_SELECTED',1,'2010-09-23 11:07:22',NULL,NULL),(776,'TXT_WISHLIST_PRODUCT_WAS_ADDED',1,'2010-09-23 11:07:22',NULL,NULL),(777,'ERR_WISHLIST_HAS_THIS_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(778,'ERR_CART_QUANTITY_IS_NOT_NUMERIC',1,'2010-09-23 11:07:22',NULL,NULL),(780,'ERR_EMPTY_WISHLIST',1,'2010-09-23 11:07:22',NULL,NULL),(781,'TXT_ORDER_NUMER',1,'2010-09-23 11:07:22',NULL,NULL),(782,'TXT_COST_OF_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(783,'TXT_PRODUCT_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(784,'TXT_HEADER_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(785,'TXT_HEADER_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(786,'TXT_FOOTER_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(787,'TXT_WELCOME',1,'2010-09-23 11:07:22',NULL,NULL),(788,'TXT_EMAIL_ORDER_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(789,'TXT_EMAIL_CHANGE_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(790,'TXT_OLD_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(791,'TXT_NEW_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(792,'TXT_PASSWORD_CHANGE_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(793,'TXT_NEW_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(794,'TXT_ADDRESS_CHANGE_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(795,'TXT_NEW_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(796,'TXT_ADDRESS_ADD_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(797,'TXT_CLIENT_REGISTRATION_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(799,'TXT_LOG',1,'2010-09-23 11:07:22',NULL,NULL),(800,'TXT_REORDER',1,'2010-09-23 11:07:22',1,NULL),(801,'TXT_ADDRESS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(802,'TXT_ADDRESS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(803,'TXT_PASSWORD_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(804,'TXT_EMAIL_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(805,'TXT_REGISTRATION_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(806,'TXT_PASSWORD_FORGOT',1,'2010-09-23 11:07:22',NULL,NULL),(807,'TXT_PASSWORD_FORGOT_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(808,'ERR_WHILE_VOTING',1,'2010-09-23 11:07:22',NULL,NULL),(809,'TXT_THANK_YOU',1,'2010-09-23 11:07:22',NULL,NULL),(810,'TXT_DELIVERER_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(811,'TXT_ERROR_FORBIDDEN_CODE',1,'2010-09-23 11:07:22',NULL,NULL),(812,'TXT_REGISTER_USER_OK',1,'2010-09-23 11:07:22',NULL,NULL),(813,'TXT_CHECK_PRIVATE_MAIL',1,'2010-09-23 11:07:22',NULL,NULL),(814,'TXT_NEED_HELP',1,'2010-09-23 11:07:22',NULL,NULL),(815,'TXT_DATA_CHANGED_MAIL_SEND',1,'2010-09-23 11:07:22',NULL,NULL),(816,'TXT_ERROR_OLD_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(817,'ERR_EMPTY_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(818,'ERR_EMPTY_TO',1,'2010-09-23 11:07:22',NULL,NULL),(820,'TXT_DISABLE_USER',1,'2010-09-23 11:07:22',NULL,NULL),(821,'TXT_ENABLE_USER',1,'2010-09-23 11:07:22',NULL,NULL),(822,'TXT_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(823,'ERR_CONTENTCATEGORY_BIND_TO_STATICCONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(824,'TXT_END_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(825,'TXT_START_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(826,'TXT_ORDER_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(827,'TXT_CLIENT_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(828,'TXT_DISABLE',1,'2010-09-23 11:07:22',NULL,NULL),(829,'TXT_DISABLE_CONFIRM',1,'2010-09-23 11:07:22',NULL,NULL),(830,'TXT_ENABLE',1,'2010-09-23 11:07:22',NULL,NULL),(831,'TXT_ENABLE_CONFIRM',1,'2010-09-23 11:07:22',NULL,NULL),(832,'ERR_UNABLE_TO_ENABLE_USER',1,'2010-09-23 11:07:22',NULL,NULL),(833,'ERR_UNABLE_TO_DISABLE_USER',1,'2010-09-23 11:07:22',NULL,NULL),(834,'ERR_CAN_NOT_DISABLE_YOURSELF',1,'2010-09-23 11:07:22',NULL,NULL),(835,'ERR_CAN_NOT_ENABLE_YOURSELF',1,'2010-09-23 11:07:22',NULL,NULL),(836,'TXT_NOT_PUBLISH',1,'2010-09-23 11:07:22',NULL,NULL),(837,'TXT_ENABLE_PUBLISH',1,'2010-09-23 11:07:22',NULL,NULL),(838,'TXT_DISABLE_PUBLISH',1,'2010-09-23 11:07:22',NULL,NULL),(839,'ERR_UNABLE_TO_ENABLE_STATICBLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(840,'ERR_UNABLE_TO_DISABLE_STATICBLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(841,'TXT_CLIENTS_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(842,'TXT_SUBJECT',1,'2010-09-23 11:07:22',NULL,NULL),(843,'TXT_NEWSLETTER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(844,'TXT_NEWSLETTER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(845,'ERR_NEWSLETTER_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(846,'ERR_NEWSLETTER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(848,'TXT_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(849,'TXT_SHORT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(850,'TXT_UNIT_MEASURE',1,'2011-09-12 20:54:00',1,NULL),(851,'TXT_UNIT_MEASURE_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(852,'TXT_UNIT_MEASURE_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(853,'ERR_EMPTY_SHORT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(854,'ERR_UNIT_MEASURE_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(855,'ERR_UNIT_MEASURE_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(856,'TXT_NEWSLETTER_INFO_FRONTEND',1,'2010-09-23 11:07:22',1,NULL),(857,'TXT_INSERT_CLIENT_TO_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(858,'ERR_INSERT_CLIENT_TO_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(859,'TXT_GLOBAL_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(860,'TXT_PRICE_WITH_DISPATCHMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(861,'TXT_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(862,'TXT_REGISTRATION_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(863,'TXT_CLIENT_REGISTRATION_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(864,'TXT_STATISTIC_POLL',1,'2010-09-23 11:07:22',NULL,NULL),(865,'ERR_PRODUCT_DUPLICATE ',1,'2010-09-23 11:07:22',NULL,NULL),(866,'TXT_PRODUCT_ID',1,'2010-09-23 11:07:22',NULL,NULL),(867,'TXT_ORDER_CLIENT_COPY',1,'2010-09-23 11:07:22',NULL,NULL),(868,'TXT_SAVE',1,'2010-09-23 11:07:22',NULL,NULL),(869,'TXT_DELETE',1,'2010-09-23 11:07:22',NULL,NULL),(871,'TXT_DELETE_CLIENT_FROM_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(872,'ERR_DELETE_CLIENT_FROM_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(873,'TXT_VALUE_IN_PERCENT',1,'2010-09-23 11:07:22',NULL,NULL),(874,'TXT_EXAMPLE_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(875,'TXT_POSTCODE_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(876,'TXT_EMAIL_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(877,'TXT_PARENT_CATEGORY_EXAMPLE',1,'2010-09-23 11:07:22',NULL,NULL),(878,'TXT_RECIPIENT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(879,'TXT_MAIN_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(880,'TXT_QUICK_ACCESS',1,'2010-09-23 11:07:22',NULL,NULL),(881,'TXT_RETURN',1,'2010-09-23 11:07:22',NULL,NULL),(882,'TXT_RESET',1,'2010-09-23 11:07:22',NULL,NULL),(884,'TXT_PERSONAL_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(885,'TXT_ADDITIONAL_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(886,'TXT_CHANGE_USERS_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(887,'TXT_PASSWORD_CHANGE_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(888,'TXT_PASSWORD_NEW_COMMENT',1,'2010-09-23 11:07:22',NULL,NULL),(889,'TXT_ANSWERS_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(890,'TXT_DISPATCHMETHOD_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(891,'TXT_DISPATCHMETHOD_PRICE_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(892,'ERR_EMPTY_DISPATCHMETHOD_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(893,'ERR_EMPTY_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(894,'TXT_NAVIGATION',1,'2010-09-23 11:07:22',NULL,NULL),(895,'ERR_WRONG_FORMAT_FAX',1,'2010-09-23 11:07:22',NULL,NULL),(896,'TXT_ADD_ORDERSTATUS',1,'2010-09-23 11:07:22',NULL,NULL),(897,'TXT_START_AGAIN',1,'2010-09-23 11:07:22',NULL,NULL),(898,'TXT_SAVE_AND_ADD_ANOTHER',1,'2010-09-23 11:07:22',NULL,NULL),(899,'TXT_SAVE_AND_FINISH',1,'2010-09-23 11:07:22',NULL,NULL),(900,'ERR_ORDERSTATUS_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(901,'ERR_ORDERSTATUS_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(902,'ERR_NAME_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(903,'TXT_EDIT_ORDERSTATUS',1,'2010-09-23 11:07:22',NULL,NULL),(904,'TXT_ORDERS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(905,'TXT_PRODUCTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(906,'TXT_ADD_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(907,'TXT_CATEGORY_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(908,'TXT_ADD_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(909,'TXT_PARENT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(910,'TXT_EDIT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(911,'TXT_ATTRIBUTE_PRODUCTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(912,'TXT_ADD_ATTRIBUTE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(913,'ERR_ATTRIBUTE_PRODUCT_GROUP_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(914,'TXT_ATTRIBUTES_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(915,'TXT_ATTRIBUTES_GROUP_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(916,'TXT_ATTRIBUTE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(917,'ERR_EMPTY_ATTRIBUTE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(918,'TXT_EDIT_ATTRIBUTE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(919,'TXT_PRODUCERS_LIST',1,'2011-09-12 15:44:35',1,NULL),(920,'TXT_ADD_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(921,'TXT_EDIT_PRODUCER',1,'2010-09-23 11:07:22',NULL,NULL),(922,'ERR_PRODUCER_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(924,'ERR_ATTRIBUTES_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(925,'TXT_ADD_DELIVERER',1,'2010-09-23 11:07:22',NULL,NULL),(926,'TXT_PRODUCTCOMBINACTIONS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(928,'TXT_UPSELL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(929,'TXT_ADD_UPSELL',1,'2010-09-23 11:07:22',NULL,NULL),(930,'TXT_UPSELL_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(931,'TXT_CROSS_SELLS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(932,'TXT_ADD_CROSS_SELL',1,'2010-09-23 11:07:22',NULL,NULL),(933,'TXT_SIMILAR_PRODUCTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(934,'TXT_ADD_SIMILAR_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(935,'TXT_PRODUCT_PROMOTIONS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(936,'TXT_CLIENTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(937,'TXT_ADD_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(938,'TXT_ADDRESS_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(939,'TXT_PHONE_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(940,'TXT_EDIT_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(941,'ERR_EMPTY_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(942,'TXT_CLIENT_GROUPS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(943,'TXT_ADD_CLIENT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(944,'TXT_EDIT_CLIENT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(945,'ERR_EMPTY_GROUP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(946,'TXT_DISPATCHMETHODS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(947,'TXT_ADD_DISPATCHMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(948,'TXT_EDIT_DISPATCHMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(949,'TXT_ADD_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(950,'TXT_EDIT_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(951,'ERR_VALUE_INVALID',1,'2010-09-23 11:07:22',NULL,NULL),(952,'ERR_VAT_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(953,'TXT_CLIENT_NEWSLETTERS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(954,'TXT_ADD_CLIENT_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(955,'TXT_NEWSLETTER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(956,'TXT_ADD_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(957,'TXT_TEXT',1,'2010-09-23 11:07:22',NULL,NULL),(958,'TXT_HTML',1,'2010-09-23 11:07:22',NULL,NULL),(959,'ERR_EMPTY_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(960,'ERR_EMPTY_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(961,'ERR_NEWSLETTER_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(962,'TXT_EDIT_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(963,'TXT_RANGES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(964,'TXT_RANGETYPES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(965,'TXT_ADD_RANGETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(966,'TXT_ADD_RANGE_TYPE_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(967,'TXT_EDIT_RANGETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(968,'TXT_PRODUCTRANGES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(969,'TXT_TAGS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(970,'TXT_MOST_SEARCH_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(971,'TXT_INTEGRATIONS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(972,'TXT_ADD_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(973,'TXT_ADD_NEWS',1,'2011-09-14 10:34:00',1,NULL),(974,'ERR_TOPIC_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(975,'TXT_EDIT_NEWS',1,'2011-09-14 10:31:32',1,NULL),(976,'TXT_ADD_STATICBLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(977,'ERR_EMPTY_CONTENT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(978,'TXT_EDIT_STATICBLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(979,'TXT_ADD_CONTENTCATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(980,'TXT_EDIT_CONTENT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(981,'TXT_POLLS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(982,'TXT_ADD_POLL',1,'2010-09-23 11:07:22',NULL,NULL),(983,'TXT_VOTES_POSITIONS',1,'2010-09-23 11:07:22',NULL,NULL),(984,'TXT_EDIT_POLL',1,'2010-09-23 11:07:22',NULL,NULL),(985,'ERR_NUMERIC_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(986,'ERR_QUESTIONS_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(987,'TXT_ADD_USER',1,'2010-09-23 11:07:22',NULL,NULL),(988,'TXT_EDIT_USER',1,'2010-09-23 11:07:22',NULL,NULL),(989,'TXT_ADD_PAYMENTMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(990,'TXT_EDIT_PAYMENTMETHOD',1,'2010-09-23 11:07:22',NULL,NULL),(993,'TXT_EDIT_BUG_REPORT',1,'2010-09-23 11:07:22',NULL,NULL),(994,'TXT_CONTACTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(995,'TXT_ADD_CONTACT',1,'2010-09-23 11:07:22',NULL,NULL),(996,'TXT_EDIT_CONTACT',1,'2010-09-23 11:07:22',NULL,NULL),(997,'TXT_LANGUAGES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(998,'TXT_ADD_LANGUAGE',1,'2010-09-23 11:07:22',NULL,NULL),(999,'ERR_TRANSLATION_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(1000,'TXT_EDIT_LANGUAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1001,'TXT_TRANSLATIONS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1002,'TXT_ADD_TRANSLATION',1,'2010-09-23 11:07:22',NULL,NULL),(1006,'TXT_EDIT_TRANSLATION',1,'2010-09-23 11:07:22',NULL,NULL),(1008,'TXT_FILES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1009,'TXT_UNIT_MEASURES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1010,'TXT_ADD_UNIT_MEASURE',1,'2010-09-23 11:07:22',NULL,NULL),(1011,'TXT_EDIT_UNIT_MEASURE',1,'2010-09-23 11:07:22',NULL,NULL),(1012,'ERR_EMPTY_SHOP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1013,'TXT_SHOP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1014,'TXT_DATE_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(1015,'TXT_EDIT_GLOBAL_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(1016,'ERR_GROUP_NAME_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(1017,'TXT_DISABLE_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(1018,'TXT_ENABLE_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(1023,'TXT_GROUPS_USERS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1024,'TXT_ADD_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1025,'TXT_RIGHTS',1,'2010-09-23 11:07:22',NULL,NULL),(1026,'TXT_GROUP_RIGHTS',1,'2010-09-23 11:07:22',NULL,NULL),(1027,'TXT_EDIT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1028,'TXT_BASIC_GROUP_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1029,'ERR_NUMERIC_INVALID',1,'2010-09-23 11:07:22',NULL,NULL),(1030,'TXT_CONSTANT_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1031,'ERR_LITERAL_INVALID',1,'2010-09-23 11:07:22',NULL,NULL),(1032,'TXT_EXAMPLE',1,'2010-09-23 11:07:22',NULL,NULL),(1033,'ERR_CONTENTCATEGORY_BIND_TO_CONTENTCATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1034,'TXT_DESKTOP',1,'2010-09-23 11:07:22',NULL,NULL),(1035,'TXT_SALE',1,'2010-09-23 11:07:22',NULL,NULL),(1036,'TXT_CRM',1,'2010-09-23 11:07:22',NULL,NULL),(1037,'TXT_EXTERNAL_SYSTEMS',1,'2010-09-23 11:07:22',NULL,NULL),(1038,'TXT_GEKOLAB',1,'2010-09-23 11:07:22',NULL,NULL),(1039,'TXT_RAPORTS',1,'2010-09-23 11:07:22',NULL,NULL),(1040,'TXT_UPSELL_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1041,'TXT_EDIT_UPSELL',1,'2010-09-23 11:07:22',NULL,NULL),(1042,'TXT_SIMILAR_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1043,'ERR_SIMILARPRODUCT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1044,'TXT_ADD_SIMILARPRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(1045,'TXT_ADD_CROSSSELL',1,'2010-09-23 11:07:22',NULL,NULL),(1046,'TXT_CROSSSELL_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1047,'TXT_EDIT_SIMILARPRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(1048,'ERR_SIMILAR_PRODUCT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1049,'TXT_EDIT_CROSSSELL',1,'2010-09-23 11:07:22',NULL,NULL),(1050,'TXT_EDIT_DELIVERER',1,'2010-09-23 11:07:22',NULL,NULL),(1051,'TXT_ADD_PRODUCTCOMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(1052,'ERR_PRODUCTCOMBINATION_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1054,'TXT_SHORT_DESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(1055,'TXT_EDIT_PRODUCTCOMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(1056,'TXT_FAX_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(1057,'TXT_BESTSELLERS',1,'2010-09-23 11:07:22',NULL,NULL),(1058,'TXT_SUM',1,'2010-09-23 11:07:22',NULL,NULL),(1059,'TXT_MOST_POPULAR',1,'2010-09-23 11:07:22',NULL,NULL),(1060,'TXT_MANY_TIMES',1,'2010-09-23 11:07:22',NULL,NULL),(1061,'TXT_SHOW_RAPORTS',1,'2010-09-23 11:07:22',NULL,NULL),(1062,'TXT_CONTACT_ADDRESS_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1063,'TXT_ACTIVE',1,'2010-09-23 11:07:22',NULL,NULL),(1064,'ERR_USER_ACTIVE_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1065,'ERR_CLIENT_ACTIVE_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1066,'TXT_PHOTOS',1,'2010-09-23 11:07:22',NULL,NULL),(1067,'TXT_ATTRIBUTES',1,'2010-09-23 11:07:22',NULL,NULL),(1068,'ERR_EMPTY_PRODUCT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1069,'TXT_MAX_LENGTH',1,'2010-09-23 11:07:22',NULL,NULL),(1070,'TXT_EMPTY_CATEGORY_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1071,'TXT_FILE_TYPES_IMAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1072,'TXT_SUFFIXTYPE_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1075,'TXT_BAR_CODE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1076,'TXT_EDIT_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(1077,'TXT_LOGIN_FORM_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1078,'TXT_LOGIN_FORM_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(1079,'ERR_EMPTY_LOGIN_FORM_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(1080,'ERR_EMPTY_LOGIN_FORM_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1081,'TXT_LOGIN_FORM_RESET_PASSWORD',1,'2010-09-23 11:07:22',NULL,NULL),(1082,'TXT_LOG_IN',1,'2010-09-23 11:07:22',NULL,NULL),(1085,'TXT_ERROR_OCCURE',1,'2010-09-23 11:07:22',NULL,NULL),(1086,'TXT_BIND_COUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1087,'TXT_ATTRIBUTE_CHANGE_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1088,'TXT_EDIT_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1089,'TXT_DISPATCHMETHOD_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1090,'TXT_ORDER_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1091,'TXT_PRODUCT_VARIANTS',1,'2010-09-23 11:07:22',NULL,NULL),(1092,'TXT_CATEGORY_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1093,'TXT_CATEGORY_ADD_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1094,'TXT_PRODUCT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1095,'TXT_MAIN_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1097,'TXT_NEW_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1098,'TXT_CATEGORY_ORDER_SAVED',1,'2010-09-23 11:07:22',NULL,NULL),(1099,'TXT_RETURN_TO_DESKTOP',1,'2010-09-23 11:07:22',NULL,NULL),(1101,'TXT_DUPLICATE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(1102,'TXT_ADD_STATUS_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1103,'TXT_PRODUCT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1104,'TXT_PROMOTION_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1105,'TXT_NEW_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1106,'ERROR_PASSWORD_GENERATE',1,'2010-09-23 11:07:22',NULL,NULL),(1107,'TXT_INTEGRATION_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1108,'TXT_INTEGRATION_SYMBOL',1,'2010-09-23 11:07:22',NULL,NULL),(1109,'TXT_ENABLE_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1110,'TXT_DISABLE_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1111,'TXT_PROMOTIONS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1112,'TXT_ADD_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1113,'TXT_DELIVERERS',1,'2010-09-23 11:07:22',NULL,NULL),(1114,'TXT_PRODUCERS',1,'2010-09-23 11:07:22',NULL,NULL),(1115,'TXT_PROMOTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1116,'TXT_GROUPS_OF_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1117,'TXT_RULE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1118,'TXT_EDIT_ATTRIBUTE_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1119,'TXT_ADD_ATTRIBUTE_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1120,'TXT_DELETE_ATTRIBUTE_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1121,'TXT_ATTRIBUTE_GROUP_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1122,'TXT_ATTRIBUTE_GROUP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1123,'TXT_ASSIGN_CATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(1124,'TXT_CHOOSE_ATTRIBUTE_GROUP_TO_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1125,'TXT_ATTRIBUTE_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1126,'TXT_ATTRIBUTEGROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1127,'TXT_THUMB',1,'2010-09-23 11:07:22',NULL,NULL),(1128,'TXT_PROMOTION_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(1129,'TXT_PROMOTION_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1130,'TXT_DATE_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(1131,'TXT_DATE_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1132,'TXT_NEW_ATTRIBUTE_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1133,'TXT_QUANTITY_UNIT',1,'2010-09-23 11:07:22',NULL,NULL),(1135,'TXT_SYMBOL',1,'2010-09-23 11:07:22',NULL,NULL),(1136,'ERR_GROUPS_OF_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1137,'TXT_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1138,'TXT_PROMOTION_RULE',1,'2010-09-23 11:07:22',NULL,NULL),(1139,'ERR_QUANTITY_UNIT',1,'2010-09-23 11:07:22',NULL,NULL),(1140,'ERR_SYMBOL',1,'2010-09-23 11:07:22',NULL,NULL),(1141,'TXT_GROUP_PROMOTION_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(1142,'ERR_GROUP_PROMOTION_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(1143,'ERR_EMPTY_RULE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1144,'TXT_PROMOTION_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(1145,'TXT_EDIT_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1146,'TXT_PROMOTION_QUANTITY_UNIT',1,'2010-09-23 11:07:22',NULL,NULL),(1147,'TXT_VIEW_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1148,'TXT_VIEW_ORDER_BASIC_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1149,'TXT_VIEW_ORDER_ORDER_NO',1,'2010-09-23 11:07:22',NULL,NULL),(1151,'TXT_VIEW_ORDER_CURRENT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1152,'TXT_VIEW_ORDER_IP_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1154,'TXT_VIEW_ORDER_CLIENT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1155,'TXT_VIEW_ORDER_CLIENT_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(1156,'TXT_VIEW_ORDER_CLIENT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1157,'TXT_VIEW_BILLING_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1158,'TXT_VIEW_DELIVERY_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1159,'TXT_VIEW_ORDER_BILLING_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1160,'TXT_VIEW_ORDER_DELIVERY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1161,'TXT_VIEW_ORDER_BILLING_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1162,'TXT_VIEW_ORDER_BILLING_CITY',1,'2010-09-23 11:07:22',NULL,NULL),(1163,'TXT_VIEW_ORDER_BILLING_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(1164,'TXT_VIEW_ORDER_BILLING_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(1165,'TXT_VIEW_ORDER_DELIVERY_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1166,'TXT_VIEW_ORDER_DELIVERY_CITY',1,'2010-09-23 11:07:22',NULL,NULL),(1167,'TXT_VIEW_ORDER_DELIVERY_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(1168,'TXT_VIEW_ORDER_DELIVERY_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(1170,'TXT_VIEW_ORDER_DELIVERY_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1171,'TXT_VIEW_ORDER_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1172,'TXT_VIEW_ORDER_STATUS_AND_COMMENTS',1,'2010-09-23 11:07:22',NULL,NULL),(1173,'TXT_VIEW_ORDER_SUMMARY',1,'2010-09-23 11:07:22',NULL,NULL),(1174,'TXT_VIEW_ORDER_NET_TOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(1175,'TXT_VIEW_ORDER_TOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(1176,'TXT_VIEW_ORDER_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(1177,'TXT_VIEW_ORDER_TAX',1,'2010-09-23 11:07:22',NULL,NULL),(1178,'TXT_VIEW_ORDER_CLIENT_NOT_INFORMED',1,'2010-09-23 11:07:22',NULL,NULL),(1179,'TXT_VIEW_ORDER_CLIENT_INFORMED',1,'2010-09-23 11:07:22',NULL,NULL),(1180,'TXT_VIEW_ORDER_CHANGE_AUTHOR',1,'2010-09-23 11:07:22',NULL,NULL),(1181,'TXT_VIEW_ORDER_PRODUCT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1182,'TXT_VIEW_ORDER_PRODUCT_CODE',1,'2010-09-23 11:07:22',NULL,NULL),(1183,'TXT_VIEW_ORDER_PRODUCT_NET_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1184,'TXT_VIEW_ORDER_PRODUCT_QUANTITY',1,'2010-09-23 11:07:22',NULL,NULL),(1185,'TXT_VIEW_ORDER_PRODUCT_NET_SUBTOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(1186,'TXT_VIEW_ORDER_PRODUCT_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(1187,'TXT_VIEW_ORDER_PRODUCT_VAT_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1188,'TXT_VIEW_ORDER_PRODUCT_SUBTOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(1192,'TXT_VIEW_ORDER_ORDER_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(1193,'TXT_VIEW_ORDER_CURRENT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1194,'TXT_VIEW_ORDER_IP_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1195,'TXT_VIEW_ORDER_CLIENT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1196,'TXT_VIEW_ORDER_CLIENT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1197,'TXT_VIEW_ORDER_CLIENT_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(1206,'TXT_VIEW_ORDER_DELIVERY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1207,'TXT_VIEW_ORDER_DELIVERY_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1208,'TXT_VIEW_ORDER_DELIVERY_CITY',1,'2010-09-23 11:07:22',NULL,NULL),(1209,'TXT_VIEW_ORDER_DELIVERY_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(1210,'TXT_VIEW_ORDER_DELIVERY_PHONE',1,'2010-09-23 11:07:22',NULL,NULL),(1211,'TXT_VIEW_ORDER_PAYMENT_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1213,'TXT_VIEW_ORDER_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1214,'TXT_VIEW_ORDER_CHANGE_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1215,'TXT_VIEW_ORDER_CHANGE_COMMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1216,'TXT_VIEW_ORDER_CHANGE_INFORM_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(1217,'TXT_VIEW_ORDER_CHANGE_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1218,'TXT_VIEW_ORDER_HISTORY',1,'2010-09-23 11:07:22',NULL,NULL),(1219,'TXT_VIEW_ORDER_SHIPPING_LISTS',1,'2010-09-23 11:07:22',NULL,NULL),(1220,'TXT_VIEW_ORDER_INVOICES',1,'2010-09-23 11:07:22',NULL,NULL),(1221,'TXT_VIEW_ORDER_NO_RECORDED_HISTORY',1,'2010-09-23 11:07:22',NULL,NULL),(1223,'TXT_CENEO_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1224,'TXT_NOKAUT_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1225,'TXT_SKAPIEC_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1226,'TXT_CHOOSE_CATEGORY_INTEGRATION',1,'2010-09-23 11:07:22',NULL,NULL),(1227,'TXT_CURRENT_ORDER_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1228,'TXT_DISPATCH_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1229,'TXT_PAYMENT_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1230,'TXT_TOTAL_ORDER_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1231,'TXT_ORDER_BASE_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1232,'TXT_EDIT_ORDER_ORDERED_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1233,'TXT_EDIT_ORDER_CLIENT',1,'2010-09-23 11:07:22',NULL,NULL),(1234,'TXT_EDIT_ORDER_BILLING_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1235,'TXT_EDIT_ORDER_SHIPPING_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1236,'TXT_EDIT_ORDER_PAYMENT_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1237,'TXT_EDIT_ORDER_DELIVERY_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(1238,'TXT_COMMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1239,'TXT_CHANGE_ORDER_STATUS_NR',1,'2010-09-23 11:07:22',NULL,NULL),(1240,'TXT_ANY_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(1241,'TXT_CHOOSE_VARIANT',1,'2010-09-23 11:07:22',NULL,NULL),(1242,'ERR_EMAIL_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(1243,'TXT_ERROR_OCCURED',1,'2010-09-23 11:07:22',NULL,NULL),(1244,'ERR_PERMISSION_BREAK_OCCURED',1,'2010-09-23 11:07:22',NULL,NULL),(1245,'TXT_BANK_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1246,'TXT_BANK_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1247,'TXT_BANK_NUMBER',1,'2010-09-23 11:07:22',NULL,NULL),(1248,'TXT_ENABLE_PRODUCT_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(1249,'TXT_DISABLE_PRODUCT_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(1250,'TXT_NEW_USER',1,'2010-09-23 11:07:22',NULL,NULL),(1251,'TXT_COMPARE',1,'2010-09-23 11:07:22',NULL,NULL),(1252,'TXT_STANDARD_PRODUCT_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(1253,'TXT_EDIT_PASSWORD_USER',1,'2010-09-23 11:07:22',NULL,NULL),(1254,'TXT_DO_YOU_REALLY_WANT_TO_DELETE_ATTRIBUTE_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1255,'TXT_DO_YOU_REALLY_WANT_TO_DELETE_ATTRIBUTE_GROUP_DESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(1256,'TXT_PRICE_NET',1,'2010-09-23 11:07:22',NULL,NULL),(1257,'TXT_PRICE_GROSS',1,'2010-09-23 11:07:22',NULL,NULL),(1258,'TXT_CLIENT_ACTIVITY',1,'2010-09-23 11:07:22',NULL,NULL),(1259,'TXT_CLIENT_ACTIVITY_ADD_TO_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1260,'TXT_CLIENT_ACTIVITY_IN_CART',1,'2010-09-23 11:07:22',NULL,NULL),(1261,'TXT_CLIENT_ACTIVITY_ON_WISHLIST',1,'2010-09-23 11:07:22',NULL,NULL),(1262,'TXT_INTEGRATION_TERMS',1,'2010-09-23 11:07:22',NULL,NULL),(1263,'TXT_ADD_ORDERS',1,'2010-09-23 11:07:22',NULL,NULL),(1264,'TXT_ALL_ORDER_COST',1,'2010-09-23 11:07:22',NULL,NULL),(1265,'TXT_PAY',1,'2010-09-23 11:07:22',NULL,NULL),(1266,'TXT_SINGLE_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(1267,'TXT_ADD_ORDER_ORDERED_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1268,'TXT_PAYMENT_CONTROLLER',1,'2010-09-23 11:07:22',NULL,NULL),(1269,'ERR_EMPTY_PAYMENT_CONTROLLER',1,'2010-09-23 11:07:22',NULL,NULL),(1270,'TXT_CONFIGURATION_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1271,'TXT_COMPANY_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1272,'TXT_COMPANY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1273,'TXT_SHORT_COMPANY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1274,'TXT_KRS',1,'2010-09-23 11:07:22',NULL,NULL),(1275,'ERR_PASSWORD_NEW_INVALID',1,'2010-09-23 11:07:22',NULL,NULL),(1276,'TXT_OPENING_HOURS',1,'2010-09-23 11:07:22',NULL,NULL),(1277,'TXT_CHOSE_DEPARTMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1278,'TXT_DEPARTMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1279,'TXT_OFFICE_HOURS_IS_AT_DISPOSAL',1,'2010-09-23 11:07:22',NULL,NULL),(1280,'TXT_RECIPIENT',1,'2010-09-23 11:07:22',NULL,NULL),(1281,'TXT_SEND_QUERY',1,'2010-09-23 11:07:22',NULL,NULL),(1282,'TXT_SEND_OK',1,'2010-09-23 11:07:22',NULL,NULL),(1283,'TXT_STANDARD_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(1284,'ERR_EMPTY_BASE_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(1285,'TXT_EMAIL_FORM_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1286,'ERR_EMPTY_EMAIL_FORM_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1287,'ERR_BAD_EMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(1288,'UPLOAD_ERR_OK',1,'2010-09-23 11:07:22',NULL,NULL),(1289,'UPLOAD_ERR_INI_SIZE',1,'2010-09-23 11:07:22',NULL,NULL),(1290,'UPLOAD_ERR_FORM_SIZE',1,'2010-09-23 11:07:22',NULL,NULL),(1291,'UPLOAD_ERR_PARTIAL',1,'2010-09-23 11:07:22',NULL,NULL),(1292,'UPLOAD_ERR_NO_FILE',1,'2010-09-23 11:07:22',NULL,NULL),(1293,'UPLOAD_ERR_NO_TMP_DIR',1,'2010-09-23 11:07:22',NULL,NULL),(1294,'UPLOAD_ERR_CANT_WRITE',1,'2010-09-23 11:07:22',NULL,NULL),(1295,'UPLOAD_ERR_EXTENSION',1,'2010-09-23 11:07:22',NULL,NULL),(1296,'TXT_ADDRESS_COMPANY_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1297,'MAX_UPLOAD_FILE_SIZE_IS',1,'2010-09-23 11:07:22',NULL,NULL),(1298,'TXT_PROVINCE',1,'2010-09-23 11:07:22',NULL,NULL),(1299,'TXT_SELLER',1,'2010-09-23 11:07:22',NULL,NULL),(1300,'TXT_COMBINATION_PRODUCT_STOCK',1,'2010-09-23 11:07:22',NULL,NULL),(1301,'TXT_NEWSLETTER_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(1302,'TXT_SEND_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(1303,'TXT_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1304,'TXT_LIST_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(1305,'TXT_STANDARD_ORDER_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1306,'TXT_LAST_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1307,'TXT_BANKTRANSFER',1,'2010-09-23 11:07:22',NULL,NULL),(1308,'TXT_BANKTRANSFER_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1309,'TXT_ACCEPT_AN_ORDER_BANKTRANSFER',1,'2010-09-23 11:07:22',NULL,NULL),(1310,'TXT_BANK_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1311,'TXT_BANK_TRANSFER_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1312,'TXT_SUM_PRODUCTS_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1313,'TXT_BANK_NUMBER_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(1314,'TXT_USE_VAT',1,'2010-09-23 11:07:22',NULL,NULL),(1316,'TXT_TABLE',1,'2010-09-23 11:07:22',NULL,NULL),(1317,'TXT_ENTER_NEW_CATEGORY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1318,'TXT_ENTER_NEW_ATTRIBUTE_GROUP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1319,'TXT_SELLPRICE_GROSS',1,'2010-09-23 11:07:22',NULL,NULL),(1320,'TXT_BUYPRICE_GROSS',1,'2010-09-23 11:07:22',NULL,NULL),(1321,'TXT_DISPATCHMETHOD_TABLE_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1322,'TXT_ONDELIVERY_PAYMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1323,'TXT_ONDELIVERY_INFO',1,'2010-09-23 11:07:22',1,NULL),(1324,'TXT_ONDELIVERY_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1325,'TXT_PICKUP_PAYMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1326,'TXT_PICKUP_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1327,'TXT_PICKUP_RECEIPT',1,'2010-09-23 11:07:22',NULL,NULL),(1328,'TXT_CLICK_LINK_TO_ACTIVE_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1329,'TXT_CONFIRMED_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1330,'TXT_VERSION',1,'2010-09-23 11:07:22',NULL,NULL),(1331,'TXT_CHANNEL',1,'2010-09-23 11:07:22',NULL,NULL),(1332,'TXT_INVALID_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(1333,'TXT_CONFIRMATION_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1334,'TXT_ERROR_CONFIRMATION_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1335,'TXT_ERROR_CONFIRMATION_ORDER_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1336,'TXT_SERVER_VERSION',1,'2010-09-23 11:07:22',NULL,NULL),(1337,'TXT_LOCAL_VERSION',1,'2010-09-23 11:07:22',NULL,NULL),(1338,'TXT_SKAPIEC',1,'2010-09-23 11:07:22',NULL,NULL),(1339,'TXT_VIEW_INVOICES_PDF',1,'2010-09-23 11:07:22',NULL,NULL),(1340,'TXT_CHECKPOINTS',1,'2010-09-23 11:07:22',NULL,NULL),(1341,'TXT_INSTALL',1,'2010-09-23 11:07:22',NULL,NULL),(1342,'TXT_UNINSTALL',1,'2010-09-23 11:07:22',NULL,NULL),(1343,'TXT_PDF_PAGE_NUMBER',1,'2010-09-23 11:07:22',NULL,NULL),(1344,'TXT_PDF_PAGE_OUT_OF',1,'2010-09-23 11:07:22',NULL,NULL),(1345,'TXT_INVOICE_FOOTER_MESSAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1346,'TXT_INVOICE_NUMBER',1,'2011-06-19 13:51:51',1,NULL),(1350,'TXT_WEB_API_KEY',1,'2010-09-23 11:07:22',NULL,NULL),(1353,'TXT_RECIPIENT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1354,'TXT_ADD_RECIPIENT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1355,'TXT_EDIT_RECIPIENT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1357,'TXT_WEB_API_KEY_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1358,'TXT_CLIENT_NEWSLETTER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1359,'TXT_CREATE_NEW_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1360,'TXT_CREATE_NEW_EMAIL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1361,'TXT_EMAIL_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1362,'TXT_CLIENT_HISTORY_LOGS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1363,'TXT_CLIENT_HISTORYLOGS',1,'2010-09-23 11:07:22',NULL,NULL),(1364,'TXT_CLIENTID',1,'2010-09-23 11:07:22',NULL,NULL),(1365,'TXT_CENT',1,'2010-09-23 11:07:22',NULL,NULL),(1366,'TXT_NEW_INVOICE_FOR_AN_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1368,'TXT_SALE_DATE',1,'2010-09-23 11:07:22',NULL,NULL),(1369,'TXT_MATURITY',1,'2011-06-19 13:24:35',1,NULL),(1371,'TXT_TRANSFEREE',1,'2010-09-23 11:07:22',NULL,NULL),(1372,'TXT_ORDINAL_NUMEREL_SHORT',1,'2010-09-23 11:07:22',NULL,NULL),(1373,'TXT_UNIT_OF_MEASUREMENT_SHORT',1,'2010-09-23 11:07:22',NULL,NULL),(1374,'TXT_UNIT_OF_MEASURE_PRODUCT_NET_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1375,'TXT_NETTO_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1376,'TXT_VAT_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1377,'TXT_GROSS_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1378,'TXT_TOTAL',1,'2010-09-23 11:07:22',NULL,NULL),(1379,'TXT_IN_WORDS',1,'2010-09-23 11:07:22',NULL,NULL),(1380,'TXT_CONTAIN',1,'2010-09-23 11:07:22',NULL,NULL),(1381,'TXT_PERSONS_NAME_AND_SURNAME_AUTORIZED_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1382,'TXT_TO_DRAW_INVOICES',1,'2010-09-23 11:07:22',NULL,NULL),(1383,'TXT_AND_ALSO',1,'2010-09-23 11:07:22',NULL,NULL),(1384,'TXT_SEAL',1,'2010-09-23 11:07:22',NULL,NULL),(1385,'TXT_TO_RECEIPT_INVOICES',1,'2010-09-23 11:07:22',NULL,NULL),(1386,'TXT_COPY',1,'2010-09-23 11:07:22',NULL,NULL),(1387,'TXT_ORIGINAL',1,'2010-09-23 11:07:22',NULL,NULL),(1388,'TXT_DUPLICATE',1,'2010-09-23 11:07:22',NULL,NULL),(1389,'INVOICE_SINGLE_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(1390,'TXT_INVOICE_SHOW_SHOP_NAME_AND_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(1391,'TXT_NAME_OF_INVOICE_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(1392,'TXT_INVOICE_SHOW_SHOP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1393,'TXT_INVOICE_LOGO_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1394,'TXT_INVOICE_LOGO',1,'2010-09-23 11:07:22',NULL,NULL),(1398,'TXT_MOST_VIEWED_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1399,'TXT_LAST_VIEWED',1,'2010-09-23 11:07:22',NULL,NULL),(1400,'TXT_MOST_VIEWED',1,'2010-09-23 11:07:22',NULL,NULL),(1401,'TXT_ADD_RANGE',1,'2010-09-23 11:07:22',NULL,NULL),(1402,'TXT_RANGE_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1403,'TXT_ADD_RANGE_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1404,'TXT_EDIT_RANGE',1,'2010-09-23 11:07:22',NULL,NULL),(1405,'ERR_RANGE_BIND_TO_RANGETYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1406,'ERR_STATICCONTENT_BIND_TO_STATICCONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1407,'ERR_CONTENTCATEGORY_BIND_TO_STATICBLOCKS',1,'2010-09-23 11:07:22',NULL,NULL),(1408,'ERR_EMPTY_HTML',1,'2010-09-23 11:07:22',NULL,NULL),(1409,'ERR_EMPTY_TEXT',1,'2010-09-23 11:07:22',NULL,NULL),(1410,'TXT_BUY_ALSO_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1411,'TXT_BUYALSO',1,'2010-09-23 11:07:22',NULL,NULL),(1412,'TXT_RULE',1,'2010-09-23 11:07:22',NULL,NULL),(1413,'TXT_MAIN_INFORMATION',1,'2010-09-23 11:07:22',NULL,NULL),(1414,'TXT_RULE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1415,'TXT_INACTIVE',1,'2010-09-23 11:07:22',NULL,NULL),(1416,'TXT_IN_FORCE_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(1417,'TXT_IN_FORCE_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1418,'TXT_ACTION',1,'2010-09-23 11:07:22',NULL,NULL),(1419,'TXT_CONDITIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1420,'TXT_CUSTOMER_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(1421,'TXT_CUSTOMER_OPINION_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(1422,'TXT_ADD_CUSTOMER_OPINION',1,'2010-09-23 11:07:22',NULL,NULL),(1423,'TXT_STOP_ANOTHER_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1424,'TXT_ACCESS_ANOTHER_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1425,'TXT_DIRECTION_FOR_USE',1,'2010-09-23 11:07:22',NULL,NULL),(1426,'TXT_CLIENT_ONLINE',1,'2010-09-23 11:07:22',NULL,NULL),(1427,'ERR_DIRECTION_FOR_USE',1,'2010-09-23 11:07:22',NULL,NULL),(1428,'TXT_INHERITANCE_PROMOTION',1,'2010-09-23 11:07:22',NULL,NULL),(1430,'TXT_INCREASE_BY_PERCENT',1,'2010-09-23 11:07:22',NULL,NULL),(1431,'TXT_DECREASE_BY_PERCENT',1,'2010-09-23 11:07:22',NULL,NULL),(1432,'TXT_INCREASE_BY_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1433,'TXT_DECREASE_BY_AMONUT',1,'2010-09-23 11:07:22',NULL,NULL),(1434,'TXT_REAL_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1435,'ERR_PRIORITY_INVALID',1,'2010-09-23 11:07:22',NULL,NULL),(1436,'TXT_DEFAULT_ADDRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1439,'TXT_AUTOUPDATE_UNINSTALL_ERROR',1,'2010-09-23 11:07:22',NULL,NULL),(1440,'TXT_AUTOUPDATE_INSTALL_ERROR',1,'2010-09-23 11:07:22',NULL,NULL),(1441,'TXT_AUTOUPDATE_PLEASE_WAIT',1,'2010-09-23 11:07:22',NULL,NULL),(1442,'TXT_AUTOUPDATE_INSTALLATION_IN_PROGRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1443,'TXT_ERROR_ENCOUNTERED',1,'2010-09-23 11:07:22',NULL,NULL),(1444,'TXT_AUTOUPDATE_INSTALLATION',1,'2010-09-23 11:07:22',NULL,NULL),(1445,'TXT_AUTOUPDATE_UPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1446,'TXT_AUTOUPDATE_UPDATE_IN_PROGRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1447,'TXT_AUTOUPDATE_UPDATE_IN_PROGRESS_FROM_VERSION',1,'2010-09-23 11:07:22',NULL,NULL),(1448,'TXT_AUTOUPDATE_UPDATE_IN_PROGRESS_TO_VERSION',1,'2010-09-23 11:07:22',NULL,NULL),(1449,'TXT_AUTOUPDATE_UNINSTALLATION_IN_PROGRESS',1,'2010-09-23 11:07:22',NULL,NULL),(1450,'TXT_AUTOUPDATE_UNINSTALLATION',1,'2010-09-23 11:07:22',NULL,NULL),(1451,'TXT_AUTOUPDATE_SUCCESSFUL',1,'2010-09-23 11:07:22',NULL,NULL),(1452,'TXT_AUTOUPDATE_SUCCESSFUL_DESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(1456,'TXT_USER_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1457,'TXT_CLIENT_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1458,'TXT_DESCRIPTION_COURIER',1,'2010-09-23 11:07:22',NULL,NULL),(1459,'TXT_LOGO',1,'2010-09-23 11:07:22',NULL,NULL),(1460,'TXT_WEIGHT',1,'2010-09-23 11:07:22',NULL,NULL),(1461,'ERR_PAYMENTMETHOD_BIND_TO_ORDERSTATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1467,'TXT_CONFIRMATIVE_EMIAL',1,'2010-09-23 11:07:22',NULL,NULL),(1468,'TXT_CONFIRMATIVE_FORM',1,'2010-09-23 11:07:22',NULL,NULL),(1473,'TXT_TITLE_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(1474,'TXT_TITLE_FORMAT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1475,'TXT_CONDITION',1,'2010-09-23 11:07:22',NULL,NULL),(1476,'TXT_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(1477,'TXT_SECOND_HAND',1,'2010-09-23 11:07:22',NULL,NULL),(1478,'TXT_INSERT_DESCRIPTION_FROM_SHOP',1,'2010-09-23 11:07:22',NULL,NULL),(1479,'TXT_INSERT_DESCRIPTION_HENDWRITING',1,'2010-09-23 11:07:22',NULL,NULL),(1481,'TXT_USE_MAINPHOTO_AS_THUMB',1,'2010-09-23 11:07:22',NULL,NULL),(1482,'TXT_CHOOSE_ANOTHER_PHOTO',1,'2010-09-23 11:07:22',NULL,NULL),(1484,'TXT_SALING_FORMAT',1,'2010-09-23 11:07:22',NULL,NULL),(1486,'TXT_ONLY_BUY_NOW',1,'2010-09-23 11:07:22',NULL,NULL),(1487,'TXT_ORDER_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1488,'TXT_ORDER_STATUS_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1489,'TXT_ADD_ORDER_STATUS_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1490,'TXT_EDIT_ORDER_STATUS_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1491,'TXT_VAT_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1492,'TXT_PROFORMA_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1493,'TXT_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1494,'ERR_EMPTY_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1495,'TXT_KIND_OF_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1496,'TXT_ADD_KIND_OF_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1497,'TXT_REMOVABLE',1,'2010-09-23 11:07:22',NULL,NULL),(1498,'TXT_EDIT_KIND_OF_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1499,'TXT_INVOICE_TYPE_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1500,'TXT_ADD_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1501,'ERR_EMPTY_ORDER_STATUS_GROUPS',1,'2010-09-23 11:07:22',NULL,NULL),(1502,'ERR_ADD_NEW_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1503,'ERR_CAN_NOT_REMOVABLE_INVOICE_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(1504,'TXT_DELETE_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1505,'TXT_INVOICE_TYPE_EXAMPLE',1,'2010-09-23 11:07:22',NULL,NULL),(1506,'TXT_DELETE_THIS_INVOICE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1507,'ERR_EMPTY_WEIGHT',1,'2010-09-23 11:07:22',NULL,NULL),(1508,'TXT_KG',1,'2010-09-23 11:07:22',NULL,NULL),(1509,'TXT_DISPATCHMETHOD_WEIGHT_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1510,'TXT_EMAIL_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(1511,'ERR_EMPTY_EMAIL_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(1512,'TXT_NAME_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(1513,'ERR_EMPTY_NAME_SENDER',1,'2010-09-23 11:07:22',NULL,NULL),(1514,'TXT_WELCOME_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1515,'ERR_WELCOME_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1516,'TXT_FOOTER_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1517,'ERR_FOOTER_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1519,'TXT_DEFAULT_SHOP_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1522,'TXT_STARTING_PRICE_OF_ITEM',1,'2010-09-23 11:07:22',NULL,NULL),(1523,'TXT_ITEM_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1524,'TXT_RESERVED_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1525,'TXT_BUY_NOW_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1526,'TXT_ITEM_QUANTITY',1,'2010-09-23 11:07:22',NULL,NULL),(1529,'TXT_HIGHEST_BIDDER_ID',1,'2010-09-23 11:07:22',NULL,NULL),(1535,'TXT_BOLD_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1540,'TXT_USER_PAYING_FOR_SHIPMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1564,'TXT_NUMBER_OF_NOT_SOLD_ITEMS',1,'2010-09-23 11:07:22',NULL,NULL),(1565,'TXT_NUMBER_OF_WON_ITEM',1,'2010-09-23 11:07:22',NULL,NULL),(1566,'TXT_NUMBER_OF_BID',1,'2010-09-23 11:07:22',NULL,NULL),(1569,'TXT_BIDDER_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1570,'TXT_BIDDER_RATING',1,'2010-09-23 11:07:22',NULL,NULL),(1571,'TXT_BIDDER_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(1575,'TXT_COUNT_OF_WATCHERS',1,'2010-09-23 11:07:22',NULL,NULL),(1576,'TXT_CHECK_BUY_NOW_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1579,'TXT_MINUTES_TO_RECEIVE_RAPORT',1,'2010-09-23 11:07:22',NULL,NULL),(1583,'TXT_BLOKED_USER',1,'2010-09-23 11:07:22',NULL,NULL),(1589,'TXT_COMMISSION',1,'2010-09-23 11:07:22',NULL,NULL),(1590,'TXT_PREVIOUS_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1591,'TXT_NEXT_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1592,'TXT_LESS',1,'2010-09-23 11:07:22',NULL,NULL),(1604,'TXT_SHOP_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(1606,'TXT_ACTIVE_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1609,'TXT_STORE_SELECTOR',1,'2010-09-23 11:07:22',NULL,NULL),(1611,'TXT_DEFAULT_SHOP_MAIL',1,'2010-09-23 11:07:22',NULL,NULL),(1612,'TXT_CHANGE_SENDER_SHORT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1613,'TXT_CHANGE_SENDER_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1616,'TXT_WELCOME_CONTENT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1617,'TXT_FOOTER_CONTENT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1618,'TXT_FORM_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1619,'ERR_EMPTY_FORM_TITLE_LOGIN',1,'2010-09-23 11:07:22',NULL,NULL),(1620,'TXT_ADDR_HELP_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(1621,'TXT_REGISTRATION_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1622,'ERR_REGISTRATION_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1623,'TXT_REGISTRATION_CONTENT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1624,'TXT_VIEW_PRODUCT_AFTER_CONFIRMATION_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1625,'TXT_PROMOTIONAL',1,'2010-09-23 11:07:22',NULL,NULL),(1628,'TXT_SHOP_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(1629,'TXT_REGISTRATION_ENCOURAGEMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1630,'TXT_CONTENT_IN_PRODUCTS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1631,'TXT_CONTENT_IN_PRODUCTS_BOX_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1632,'TXT_LEFT_BOX_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1633,'TXT_RIGHT_BOX_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1634,'TXT_LEFT_BOX_CONTENT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1635,'TXT_CONTENT_AFTER_CORRECT_CONFIRMATION',1,'2010-09-23 11:07:22',NULL,NULL),(1636,'TXT_CONTENT_AFTER_CORRECT_CONFIRMATION_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1637,'TXT_CONTENT_AFTER_WRONG_CONFIRMATION',1,'2010-09-23 11:07:22',NULL,NULL),(1638,'TXT_CONTENT_AFTER_WRONG_CONFIRMATION_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1639,'TXT_WIDTH',1,'2010-09-23 11:07:22',NULL,NULL),(1640,'TXT_HEIGHT',1,'2010-09-23 11:07:22',NULL,NULL),(1641,'TXT_KEEP_PROPORTION',1,'2011-06-26 10:38:52',1,NULL),(1642,'TXT_GALLERY_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(1643,'TXT_SMALL_SIZE',1,'2010-09-23 11:07:22',NULL,NULL),(1644,'TXT_NORMAL_SIZE',1,'2010-09-23 11:07:22',NULL,NULL),(1645,'TXT_STORES',1,'2010-09-23 11:07:22',NULL,NULL),(1646,'TXT_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1647,'TXT_USER_TEMPLATES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1648,'ERR_EMPTY_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1653,'TXT_FREE_DISPATCH',1,'2010-09-23 11:07:22',NULL,NULL),(1654,'TXT_CHOOSE_STORE',1,'2010-09-23 11:07:22',NULL,NULL),(1655,'TXT_MANAGEMENT_TOOLS',1,'2010-09-23 11:07:22',NULL,NULL),(1656,'TXT_GRAPH',1,'2010-09-23 11:07:22',NULL,NULL),(1657,'TXT_SALES',1,'2010-09-23 11:07:22',NULL,NULL),(1658,'TXT_TODAY',1,'2010-09-23 11:07:22',NULL,NULL),(1659,'TXT_CURRENT_MONTH',1,'2010-09-23 11:07:22',NULL,NULL),(1660,'TXT_PERIOD',1,'2010-09-23 11:07:22',NULL,NULL),(1661,'TXT_LAST_ORDERS',1,'2010-09-23 11:07:22',NULL,NULL),(1662,'TXT_INDIVIDUAL_RECEIPT',1,'2010-09-23 11:07:22',NULL,NULL),(1663,'TXT_EMAIL_RECEIPT',1,'2010-09-23 11:07:22',NULL,NULL),(1664,'TXT_NEW_CUSTOMERS',1,'2010-09-23 11:07:22',NULL,NULL),(1667,'TXT_PRIORITY_PACKAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1668,'TXT_PRIORITY_LETTER',1,'2010-09-23 11:07:22',NULL,NULL),(1669,'TXT_PAID_ON_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(1670,'TXT_SUMMARY_DAY',1,'2010-09-23 11:07:22',NULL,NULL),(1672,'TXT_TOGETHER',1,'2010-09-23 11:07:22',NULL,NULL),(1673,'TXT_IN',1,'2010-09-23 11:07:22',NULL,NULL),(1674,'TXT_PRIORITY_PAID_ON_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(1675,'TXT_PRIORITY_REGISTERED_LETTER',1,'2010-09-23 11:07:22',NULL,NULL),(1676,'TXT_COURIER_MAIL',1,'2010-09-23 11:07:22',NULL,NULL),(1677,'TXT_COURIER_MAIL_ON_DELIVERY',1,'2010-09-23 11:07:22',NULL,NULL),(1678,'TXT_TRANSPORT_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1687,'TXT_DESCRIPTION_AND_PHOTOS',1,'2010-09-23 11:07:22',NULL,NULL),(1689,'TXT_TITLE_FORMAT_LONG_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1690,'TXT_COUNTRY',1,'2010-09-23 11:07:22',NULL,NULL),(1691,'TXT_CITY',1,'2010-09-23 11:07:22',NULL,NULL),(1692,'TXT_CLIENT_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1693,'TXT_STATUS_AND_COMMENTS',1,'2010-09-23 11:07:22',NULL,NULL),(1695,'TXT_DESCRIPTION_SHORT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1696,'TXT_DESCRIPTION_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1697,'TXT_PRICE_AND_DURATION',1,'2010-09-23 11:07:22',NULL,NULL),(1698,'TXT_STARTING_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1699,'TXT_CALCULATE_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1700,'TXT_STARTING_PRICE_CALCULATE',1,'2010-09-23 11:07:22',NULL,NULL),(1701,'ERR_STARTING_PRICE_CALCULATE',1,'2010-09-23 11:07:22',NULL,NULL),(1702,'TXT_STARTING_PRICE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1703,'TXT_BUY_NOW_PRICE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1705,'TXT_CHOOSE_PIECES',1,'2010-09-23 11:07:22',NULL,NULL),(1706,'TXT_CHOOSE_SETS',1,'2010-09-23 11:07:22',NULL,NULL),(1707,'TXT_CHOOSE_PAIRS',1,'2010-09-23 11:07:22',NULL,NULL),(1708,'TXT_KIND_OF_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(1711,'TXT_DISPLAY_ONLY_PERCENTAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1712,'TXT_LEAVE_QTY',1,'2010-09-23 11:07:22',NULL,NULL),(1713,'TXT_LEAVE_PERCENTAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1715,'TXT_STARTING_TIME',1,'2010-09-23 11:07:22',NULL,NULL),(1717,'TXT_DISPLAY_ANOTHER_TIME',1,'2010-09-23 11:07:22',NULL,NULL),(1718,'TXT_LEAVE_STORAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1723,'TXT_STORAGE_LIMIT',1,'2010-09-23 11:07:22',NULL,NULL),(1724,'TXT_DURATION',1,'2010-09-23 11:07:22',NULL,NULL),(1725,'TXT_TRANSPORT_AND_PAYMENT',1,'2010-09-23 11:07:22',NULL,NULL),(1726,'TXT_SHIPMENT_COST_COVERED',1,'2010-09-23 11:07:22',NULL,NULL),(1727,'TXT_BUYER',1,'2010-09-23 11:07:22',NULL,NULL),(1729,'TXT_ADDITIONAL_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1730,'TXT_BOLD_TITLE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1731,'TXT_HIGHLIGHT',1,'2010-09-23 11:07:22',NULL,NULL),(1732,'TXT_HIGHLIGHT_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1735,'TXT_DIFFERENCE',1,'2010-09-23 11:07:22',NULL,NULL),(1736,'TXT_DIFFERENCE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1737,'TXT_CATEGORY_PAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1738,'TXT_CATEGORY_PAGE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1740,'TXT_MAIN_PAGE',1,'2010-09-23 11:07:22',NULL,NULL),(1741,'TXT_MAIN_PAGE_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1743,'ERR_EMPTY_ONLY_BUY_NOW',1,'2010-09-23 11:07:22',NULL,NULL),(1745,'TXT_PRICE_IS_SHOP_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1747,'TXT_INSERT_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1749,'TXT_STORE',1,'2010-09-23 11:07:22',NULL,NULL),(1750,'TXT_SHOP_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(1751,'TXT_PRODUCT_SELECTOR',1,'2010-09-23 11:07:22',NULL,NULL),(1754,'TXT_SHOP_VIEW_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1755,'TXT_NAMESPACE',1,'2010-09-23 11:07:22',NULL,NULL),(1756,'TXT_ADD_SHOP_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(1757,'TXT_STORE_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1758,'TXT_ADD_STORE',1,'2010-09-23 11:07:22',NULL,NULL),(1760,'TXT_EDIT_SHOP_VIEW',1,'2010-09-23 11:07:22',NULL,NULL),(1761,'TXT_EDIT_STORE',1,'2010-09-23 11:07:22',NULL,NULL),(1762,'TXT_MAIN_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1763,'TXT_ADDITIONAL_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1765,'TXT_MULTISTORE',1,'2010-09-23 11:07:22',NULL,NULL),(1766,'TXT_LAYER',1,'2010-09-23 11:07:22',NULL,NULL),(1767,'TXT_BORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1768,'TXT_COLOUR',1,'2010-09-23 11:07:22',NULL,NULL),(1769,'TXT_PAGE_SCHEME',1,'2010-09-23 11:07:22',NULL,NULL),(1770,'TXT_MAIN_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1771,'TXT_SHOP_BG_COLOUR',1,'2010-09-23 11:07:22',NULL,NULL),(1772,'TXT_SHOP_TYPEFACE',1,'2010-09-23 11:07:22',NULL,NULL),(1773,'TXT_FOOTER_BG_COLOUR',1,'2010-09-23 11:07:22',NULL,NULL),(1774,'TXT_FOOTER_TYPEFACE',1,'2010-09-23 11:07:22',NULL,NULL),(1775,'TXT_BORDER_RADIUS',1,'2010-09-23 11:07:22',NULL,NULL),(1776,'TXT_BORDER_RADIUS_INFO',1,'2010-09-23 11:07:22',NULL,NULL),(1777,'TXT_LINE_SPACING_PARAGRAPH',1,'2010-09-23 11:07:22',NULL,NULL),(1778,'TXT_EMPTY_NAME_OF_INVOICE_TAG',1,'2010-09-23 11:07:22',NULL,NULL),(1779,'TXT_INVOICE_EXPORT',1,'2010-09-23 11:07:22',NULL,NULL),(1780,'TXT_CHOOSE_SHOP',1,'2010-09-23 11:07:22',NULL,NULL),(1781,'ERR_EMPTY_KIND_OF_INVOICE',1,'2010-09-23 11:07:22',NULL,NULL),(1782,'TXT_AMOUNT_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(1783,'TXT_AMOUNT_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1784,'TXT_ORIGINAL_AND_COPY',1,'2010-09-23 11:07:22',NULL,NULL),(1785,'TXT_ORDER_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1786,'TXT_ADD_TO_WISHLIST',1,'2010-09-23 11:07:22',NULL,NULL),(1787,'TXT_LINE_HEIGHT',1,'2010-09-23 11:07:22',NULL,NULL),(1788,'TXT_DEFAULT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1789,'TXT_BG_HEARDER_COLOR',1,'2010-09-23 11:07:22',NULL,NULL),(1790,'TXT_EMPTY_SEARCH',1,'2010-09-23 11:07:22',NULL,NULL),(1791,'TXT_VIEW_CATEGORY_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1792,'TXT_FORMS',1,'2010-09-23 11:07:22',NULL,NULL),(1793,'TXT_PRODUCT_CARD',1,'2010-09-23 11:07:22',NULL,NULL),(1794,'TXT_PRODUCT_FOLDER',1,'2010-09-23 11:07:22',NULL,NULL),(1795,'TXT_TEMPLATE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1796,'ERR_EMPTY_TEMPLATE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1797,'TXT_PAGE_SCHEMES',1,'2010-09-23 11:07:22',NULL,NULL),(1798,'TXT_PAGE_SCHEME_TEMPLATES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1799,'TXT_PAGE_SCHEME_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1800,'TXT_PAGE_SCHEME_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1801,'TXT_LAYER_INSTRUCTION',1,'2010-09-23 11:07:22',NULL,NULL),(1802,'TXT_RIGHTS_FOR',1,'2010-09-23 11:07:22',NULL,NULL),(1803,'TXT_CLIENTS_SELECTION',1,'2010-09-23 11:07:22',NULL,NULL),(1804,'ERR_WRONG_NIP',1,'2010-09-23 11:07:22',NULL,NULL),(1805,'TXT_ADD_FILES',1,'2010-09-23 11:07:22',NULL,NULL),(1806,'ERR_WRONG_FORMAT_BANK_NUMBER',1,'2010-09-23 11:07:22',NULL,NULL),(1807,'TXT_MEASURE',1,'2010-09-23 11:07:22',NULL,NULL),(1808,'TXT_WEIGHT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1809,'TXT_SELECT_VIEW_FROM_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(1810,'TXT_LOOKS',1,'2010-09-23 11:07:22',NULL,NULL),(1811,'TXT_SELECT_VIEW_FROM_PRODUCTCOMBINATION',1,'2010-09-23 11:07:22',NULL,NULL),(1812,'TXT_LAYOUT_BOX_SCHEME',1,'2010-09-23 11:07:22',NULL,NULL),(1813,'TXT_LAYOUT_BOX_SCHEME_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1814,'TXT_LAYOUT_BOX_SCHEME_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1815,'TXT_LAYOUT_BOX_SCHEME_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1816,'TXT_LAYOUT_BOX_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1817,'TXT_LAYOUT_BOXES',1,'2010-09-23 11:07:22',NULL,NULL),(1818,'TXT_BOX_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(1819,'TXT_BOX_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1820,'TXT_BOX_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1821,'TXT_BOX_BEHAVOIUR',1,'2010-09-23 11:07:22',NULL,NULL),(1822,'ERR_EMPTY_BOX_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(1823,'TXT_GRAPHICS',1,'2010-09-23 11:07:22',NULL,NULL),(1824,'TXT_CHOOSE_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(1825,'TXT_LAYOUT_BOX_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1826,'TXT_GLOBAL_LAYER',1,'2010-09-23 11:07:22',NULL,NULL),(1827,'TXT_CLIENTS_IN_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1828,'TXT_CLIENT_IN_SHOP',1,'2010-09-23 11:07:22',NULL,NULL),(1829,'TXT_PAYMENTMETHOD_AND_DISPACHMETHOD_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1830,'TXT_AUTOMATICLY_ASSIGN_TO_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1831,'TXT_TAKE_THE_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1832,'TXT_SUBPAGE_LAYOUT',1,'2010-09-23 11:07:22',NULL,NULL),(1833,'TXT_SUBPAGE_LAYOUT_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(1834,'TXT_SUBPAGE_COLUMNS',1,'2010-09-23 11:07:22',NULL,NULL),(1835,'TXT_COLUMNS_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1836,'TXT_BOX_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1837,'TXT_COLUMN_NUMBER_FOR_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1838,'TXT_COLLAPSED_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1839,'TXT_SUBPAGE_LAYOUT_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(1840,'TXT_SUBPAGE_COLUMN_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1841,'ERR_EMPTY_SUBPAGE_COLUMN_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1842,'TXT_SUBPAGE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1844,'ERR_EMPTY_WIDTH',1,'2010-09-23 11:07:22',NULL,NULL),(1845,'TXT_NOTES_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1846,'TXT_DEFAULT_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(1847,'TXT_CHOOSE_SUBPAGE_LAYOUT',1,'2010-09-23 11:07:22',NULL,NULL),(1848,'TXT_PROMOTIONRULE_DISCOUNT_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1849,'TXT_SHOW_TAX_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1850,'TXT_INORMATION',1,'2010-09-23 11:07:22',NULL,NULL),(1851,'TXT_HEADER_BG_COLOUR',1,'2010-09-23 11:07:22',NULL,NULL),(1852,'TXT_HEADER_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1853,'TXT_FOOTER_OPTIONS',1,'2010-09-23 11:07:22',NULL,NULL),(1854,'TXT_PERIOD_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1855,'TXT_ADD_PERIOD',1,'2010-09-23 11:07:22',NULL,NULL),(1856,'TXT_EDIT_PERIOD',1,'2010-09-23 11:07:22',NULL,NULL),(1858,'TXT_IDENTIFICATION',1,'2010-09-23 11:07:22',NULL,NULL),(1859,'ERR_BAD_LOGIN_OR_PIN',1,'2010-09-23 11:07:22',NULL,NULL),(1860,'TXT_ACTIVATION_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1861,'TXT_ACCOUNT_CLIENT_ACTIVATION',1,'2010-09-23 11:07:22',NULL,NULL),(1862,'TXT_FOOTER',1,'2010-09-23 11:07:22',NULL,NULL),(1863,'TXT_DEFAULT_BOX_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1864,'TXT_DEFAULT_BOX_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(1865,'TXT_CLICK_LINK_TO_ACTIVE_ACCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(1866,'TXT_NEW_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1868,'TXT_LAYOUT_BOX_CONTENT_SETTINGS',1,'2010-09-23 11:07:22',NULL,NULL),(1869,'TXT_CATEGORY_MENU',1,'2010-09-23 11:07:22',NULL,NULL),(1879,'TXT_LAYOUT_BOX_SCHEMES',1,'2010-09-23 11:07:22',NULL,NULL),(1880,'TXT_LAYOUT_TEMPLATES',1,'2010-09-23 11:07:22',NULL,NULL),(1881,'TXT_SUBPAGE_LAYOUTS',1,'2010-09-23 11:07:22',NULL,NULL),(1882,'TXT_SET_DEFAULT',1,'2010-09-23 11:07:22',NULL,NULL),(1883,'TXT_NETTO',1,'2011-09-12 17:53:17',1,NULL),(1885,'TXT_EXCHANGE_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(1886,'TXT_EXCHANGE_TYPE_EXPORT',1,'2010-09-23 11:07:22',NULL,NULL),(1887,'TXT_EXCHANGE_TYPE_IMPORT',1,'2010-09-23 11:07:22',NULL,NULL),(1888,'TXT_EXCHANGE_ENTITY',1,'2010-09-23 11:07:22',NULL,NULL),(1889,'TXT_RULES_CATALOG',1,'2010-09-23 11:07:22',NULL,NULL),(1890,'TXT_RULES_CART',1,'2010-09-23 11:07:22',NULL,NULL),(1891,'TXT_CATEGORIES_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1892,'TXT_TEXT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1893,'TXT_PRODUCT_PROMOTIONS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1894,'TXT_PRODUCT_NEWS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1895,'TXT_GRAPHICS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1896,'TXT_POLL_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1897,'TXT_NEWS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1898,'TXT_PRODUCTS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1899,'TXT_RULES_CATALOG_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1900,'TXT_PRODUCTS_IN_CATEGORY_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1901,'TXT_PRODUCTS_CROSS_SELL_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1902,'TXT_PRODUCTS_SIMILAR_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1903,'TXT_PRODUCTS_UP_SELL_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1904,'TXT_CATEGORY_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1905,'TXT_PRODUCT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1906,'TXT_LAYERED_NAVIGATION_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1907,'TXT_NEWS_LIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1908,'TXT_CONTACT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1910,'TXT_EDIT_RULE_CATALOG',1,'2010-09-23 11:07:22',NULL,NULL),(1911,'TXT_PRICE_FROM',1,'2010-09-23 11:07:22',NULL,NULL),(1912,'TXT_PRICE_TO',1,'2010-09-23 11:07:22',NULL,NULL),(1913,'TXT_PRODUCT_IS_NEW',1,'2010-09-23 11:07:22',NULL,NULL),(1914,'TXT_ENTER_NEW_RULE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1915,'TXT_RULES_CATALOG_ORDER_SAVED',1,'2010-09-23 11:07:22',NULL,NULL),(1916,'TXT_FINAL_CART_PRICE',1,'2010-09-23 11:07:22',NULL,NULL),(1917,'TXT_PAYMENTMETHODS',1,'2010-09-23 11:07:22',NULL,NULL),(1918,'TXT_RULES_CART_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1919,'TXT_EDIT_RULE_CART',1,'2010-09-23 11:07:22',NULL,NULL),(1920,'TXT_RULE_CART_ORDER_SAVED',1,'2010-09-23 11:07:22',NULL,NULL),(1921,'TXT_ENTER_NEW_CART_RULE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1922,'TXT_SITEMAPS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(1923,'TXT_ADD_SITEMAPS',1,'2010-09-23 11:07:22',NULL,NULL),(1924,'TXT_REFRESH_SITEMAPS',1,'2010-09-23 11:07:22',NULL,NULL),(1925,'TXT_CHECKOUT',1,'2010-09-23 11:07:22',NULL,NULL),(1926,'TXT_DISCOUNT_FOR_ALL_GROUP',1,'2010-09-23 11:07:22',NULL,NULL),(1927,'ERR_EMPTY_PRODUCT_SEO',1,'2010-09-23 11:07:22',NULL,NULL),(1928,'TXT_PRODUCT_SEO',1,'2011-09-08 10:41:29',1,NULL),(1929,'TXT_EDIT_RULE_CATALOG_CONFIRMATION',1,'2010-09-23 11:07:22',NULL,NULL),(1930,'ERR_EMPTY_CATEGORY_SEO',1,'2010-09-23 11:07:22',NULL,NULL),(1931,'TXT_CATEGORY_SEO',1,'2010-09-23 11:07:22',NULL,NULL),(1932,'TXT_CSV',1,'2010-09-23 11:07:22',NULL,NULL),(1933,'TXT_XML',1,'2010-09-23 11:07:22',NULL,NULL),(1934,'TXT_EXCHANGE_FILES',1,'2010-09-23 11:07:22',NULL,NULL),(1935,'TXT_EXCHANGE',1,'2010-09-23 11:07:22',NULL,NULL),(1936,'TXT_SUBPAGE_LAYOUT_DESCRIPTION',1,'2010-09-23 11:07:22',NULL,NULL),(1937,'TXT_RULE_RESULTS',1,'2010-09-23 11:07:22',NULL,NULL),(1938,'TXT_SITEMAPS_LASTUPDATE',1,'2010-09-23 11:07:22',NULL,NULL),(1939,'TXT_SITEMAPS_PINGSERVER',1,'2010-09-23 11:07:22',NULL,NULL),(1940,'TXT_EXCHANGE_PARSE',1,'2010-09-23 11:07:22',NULL,NULL),(1941,'TXT_EXCHANGE_RUN',1,'2010-09-23 11:07:22',NULL,NULL),(1942,'TXT_SEO_URL',1,'2011-09-14 15:52:07',1,NULL),(1943,'TXT_EXCHANGE_PARSE_HELP',1,'2010-09-23 11:07:22',NULL,NULL),(1944,'TXT_SITEMAPS',1,'2010-09-23 11:07:22',NULL,NULL),(1945,'TXT_SITEMAPS_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(1946,'TXT_PUBLISH_FOR_CATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(1947,'TXT_PRIORITY_FOR_CATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(1948,'TXT_PUBLISH_FOR_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1949,'TXT_PRIORITY_FOR_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(1950,'TXT_PUBLISH_FOR_PRODUCERS',1,'2010-09-23 11:07:22',NULL,NULL),(1951,'TXT_PRIORITY_FOR_PRODUCERS',1,'2010-09-23 11:07:22',NULL,NULL),(1952,'TXT_PUBLISH_FOR_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(1953,'TXT_PRIORITY_FOR_NEWS',1,'2010-09-23 11:07:22',NULL,NULL),(1954,'TXT_PUBLISH_FOR_PAGES',1,'2010-09-23 11:07:22',NULL,NULL),(1955,'TXT_PRIORITY_FOR_PAGES',1,'2010-09-23 11:07:22',NULL,NULL),(1956,'TXT_EDIT_SITMAPS',1,'2010-09-23 11:07:22',NULL,NULL),(1962,'TXT_WISHLIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(1963,'TXT_TERMS_AND_CONDITIONS_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1964,'TXT_PRIVACY_POLICY_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(1965,'TXT_RECOMMEND_FRIEND',1,'2010-09-23 11:07:22',NULL,NULL),(1966,'TXT_RECOMMEND_THIS_SITE',1,'2010-09-23 11:07:22',NULL,NULL),(1986,'TXT_CONTROLLER_SEO_LIST',1,'2011-09-08 12:34:15',1,NULL),(1990,'TXT_CONTROLLER_SEO',1,'2010-09-23 11:07:22',NULL,NULL),(1991,'TXT_MINIMUM_ORDER_VALUE',1,'2010-09-23 11:07:22',NULL,NULL),(1995,'TXT_MINIMUM_ORDER_VALUE_HELP',1,'2010-09-23 11:07:22',NULL,NULL),(1998,'TXT_ORDER_STATUS_GROUPS_HELP',1,'2010-09-23 11:07:22',NULL,NULL),(2031,'TXT_PAYPAL_REFUND_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(2032,'TXT_REFUND_FULL',1,'2010-09-23 11:07:22',NULL,NULL),(2033,'TXT_REFUND_PARTIAL',1,'2010-09-23 11:07:22',NULL,NULL),(2034,'TXT_REFUND_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(2035,'TXT_REFUND_MEMO',1,'2010-09-23 11:07:22',NULL,NULL),(2042,'TXT_BASIC_INFORMATION',1,'2010-09-23 11:07:22',NULL,NULL),(2043,'TXT_KEYWORD_TITLE',1,'2011-09-14 15:51:41',1,NULL),(2044,'TXT_KEYWORDS',1,'2011-09-14 15:51:43',1,NULL),(2045,'TXT_KEYWORD_DESCRIPTION',1,'2011-09-14 15:51:46',1,NULL),(2046,'TXT_TEXT_FORM_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(2047,'TXT_HTML_FORM_CONTENT',1,'2010-09-23 11:07:22',NULL,NULL),(2048,'TXT_TRANSMAILS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2049,'TXT_ADD_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(2050,'TXT_EDIT_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(2051,'TXT_TRANSACTION_TEMPLATES',1,'2010-09-23 11:07:22',NULL,NULL),(2052,'TXT_RULES_CATALOG_PRODUCTS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2053,'TXT_CHOSEN_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(2054,'TXT_TRANSMAIL_HEADERS_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2055,'TXT_TRANSMAIL_FOOTER_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2056,'TXT_HEADERS_TEMPLATES',1,'2010-09-23 11:07:22',NULL,NULL),(2057,'TXT_FOOTERS_TEMPLATES',1,'2010-09-23 11:07:22',NULL,NULL),(2058,'TXT_TECHNICAL_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(2059,'TXT_NO_NEW_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(2060,'TXT_NO_PROMOTION_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(2061,'TXT_ORDER_SAMPLEREQUEST',1,'2010-09-23 11:07:22',NULL,NULL),(2062,'TXT_VIRTUAL_PRODUCT_IN_CART',1,'2010-09-23 11:07:22',NULL,NULL),(2064,'TXT_SAMPLE_REQUEST_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2065,'TXT_SAMPLE_REQUEST',1,'2010-09-23 11:07:22',NULL,NULL),(2066,'TXT_ADD_CONTROLLER_SEO',1,'2011-09-08 12:34:44',1,NULL),(2067,'TXT_EDIT_CONTROLLERSEO',1,'2011-09-08 12:34:51',1,NULL),(2068,'TXT_SIDE',1,'2010-09-23 11:07:22',NULL,NULL),(2071,'TXT_SUBSTITUTED_SERVICE',1,'2010-09-23 11:07:22',NULL,NULL),(2072,'TXT_LAST_DATE_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(2073,'TXT_SUM_ALL_ORDER',1,'2010-09-23 11:07:22',NULL,NULL),(2074,'TXT_NOTIFICATION_ADD',1,'2010-09-23 11:07:22',NULL,NULL),(2075,'TXT_SUBSTITUTED_SERVICE_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2076,'TXT_MAIN_SIDE',1,'2010-09-23 11:07:22',NULL,NULL),(2077,'TXT_ADMIN_PANEL',1,'2010-09-23 11:07:22',NULL,NULL),(2078,'TXT_EMPTY_CMS',1,'2010-09-23 11:07:22',NULL,NULL),(2079,'TXT_UNDER_CATEGORY',1,'2010-09-23 11:07:22',NULL,NULL),(2080,'ERR_NEWS_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(2081,'ERR_CMS_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(2082,'TXT_REFRESH',1,'2010-09-23 11:07:22',NULL,NULL),(2083,'TXT_ADMIN',1,'2010-09-23 11:07:22',NULL,NULL),(2084,'TXT_NOTIFICATION_EDIT',1,'2010-09-23 11:07:22',NULL,NULL),(2085,'TXT_FILE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(2086,'ERR_EMPTY_FILE_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(2087,'TXT_TAGS_FOR_ACTION',1,'2010-09-23 11:07:22',NULL,NULL),(2088,'ERR_FILE_NAME_ALREADY_EXISTS',1,'2010-09-23 11:07:22',NULL,NULL),(2089,'TXT_NO_PRODUCTS',1,'2010-09-23 11:07:22',NULL,NULL),(2090,'TXT_BANK_TRANSFER_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2091,'TXT_BUY_ALSO_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2092,'TXT_CART_PREVIEW_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2093,'TXT_CLIENT_ADDRESS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2094,'TXT_CLIENT_LOGIN_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2095,'TXT_CLIENT_ORDER_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2096,'TXT_CLIENT_SETTINGS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2097,'TXT_CLIENT_TAGS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2098,'TXT_CMS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2099,'TXT_CROSS_SELL_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2100,'TXT_DELIVERY_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2101,'TXT_FINALIZATION_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2102,'TXT_FORGOT_PASSWORD_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2103,'TXT_MOST_SEARCHED_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2104,'TXT_NEWSLETTER_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2105,'TXT_ON_DELIVERY_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2106,'TXT_PAYMENT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2107,'TXT_PICKUP_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2109,'TXT_PRIVACY_POLICY_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2110,'TXT_PRODUCT_NEWS_LIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2111,'TXT_PRODUCT_PROMOTION_LIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2112,'TXT_PRODUCT_SEARCH_LIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2113,'TXT_PRODUCT_TAGS_LIST_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2114,'TXT_RECOMMEND_FRIEND_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2115,'TXT_REGISTRATION_CART_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2116,'TXT_SEARCH_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2117,'TXT_SIMILAR_PRODUCT_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2118,'TXT_TAGS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2119,'TXT_TERMS_AND_CONDITIONS_BOX',1,'2010-09-23 11:07:22',NULL,NULL),(2120,'TXT_TRANSMAIL',1,'2010-09-23 11:07:22',NULL,NULL),(2121,'TXT_NOTE_FOR_PRODUCT',1,'2010-09-23 11:07:22',NULL,NULL),(2122,'TXT_CLIENT_SEND_CONFIRMATION',1,'2010-09-23 11:07:22',NULL,NULL),(2140,'ERR_EMPTY_URL',1,'2010-09-23 11:07:22',NULL,NULL),(2142,'TXT_DONE',1,'2010-09-23 11:07:22',NULL,NULL),(2143,'TXT_UPDATE_SERVICE',1,'2010-09-23 11:07:22',NULL,NULL),(2144,'ERR_BIND_VIEW_TO_ASSIGNTOGROUP',1,'2010-09-23 11:07:22',NULL,NULL),(2145,'TXT_SUBSTITUTED_SERVICE_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(2146,'TXT_SUBSTITUTED_SERVICES_TEMPLATES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2147,'TXT_LIST_OF_TAGS_FOR_USE',1,'2010-09-23 11:07:22',NULL,NULL),(2148,'TXT_EDIT_PRODUCT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(2149,'TXT_ADD_PRODUCT_STATUS',1,'2010-09-23 11:07:22',NULL,NULL),(2150,'TXT_RUN_BACKUP',1,'2010-09-23 11:07:22',NULL,NULL),(2151,'TXT_ADD_CHECKPOINTS',1,'2010-09-23 11:07:22',NULL,NULL),(2152,'TXT_MAIL_TITLE',1,'2010-09-23 11:07:22',NULL,NULL),(2153,'TXT_FILE_NAME_TEMPLATE',1,'2010-09-23 11:07:22',NULL,NULL),(2154,'TXT_NOTIFICATIONS_REPORT',1,'2010-09-23 11:07:22',NULL,NULL),(2155,'TXT_CHOOSE_NOTIFICATION_DATE_FOR_REPORT',1,'2010-09-23 11:07:22',NULL,NULL),(2156,'TXT_BACKUP_DATA',1,'2010-09-23 11:07:22',NULL,NULL),(2157,'TXT_BACKUP_TYPE',1,'2010-09-23 11:07:22',NULL,NULL),(2158,'TXT_BACKUP_TYPE_SQL',1,'2010-09-23 11:07:22',NULL,NULL),(2159,'TXT_BACKUP_TYPE_FILES',1,'2010-09-23 11:07:22',NULL,NULL),(2160,'TXT_SQL_PROGRESS',1,'2010-09-23 11:07:22',NULL,NULL),(2161,'TXT_SQL_RECORDS',1,'2010-09-23 11:07:22',NULL,NULL),(2162,'TXT_FILES_PROGRESS',1,'2010-09-23 11:07:22',NULL,NULL),(2163,'TXT_FILES_RECORDS',1,'2010-09-23 11:07:22',NULL,NULL),(2164,'TXT_CURRENCIES_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(2165,'TXT_CURRENCIES',1,'2010-09-23 11:07:22',NULL,NULL),(2166,'TXT_CURRENCY_NAME',1,'2010-09-23 11:07:22',NULL,NULL),(2167,'TXT_CURRENCY_SYMBOL',1,'2010-09-23 11:07:22',NULL,NULL),(2168,'TXT_EXCHANGE_RATE',1,'2010-09-23 11:07:22',NULL,NULL),(2169,'TXT_ADD_CURRENCIES',1,'2010-09-23 11:07:22',NULL,NULL),(2170,'TXT_REFRESH_CURRENCIES',1,'2010-09-23 11:07:22',NULL,NULL),(2171,'TXT_EDIT_CURRENCIES',1,'2010-09-23 11:07:22',NULL,NULL),(2172,'TXT_CURRENCY_EXCHANGE',1,'2010-09-23 11:07:22',NULL,NULL),(2173,'TXT_ACTIVE_NEWSLETTER_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(2174,'TXT_TRANSLATIONS',1,'2010-09-23 11:07:22',NULL,NULL),(2175,'TXT_TRANSLATION_SYNC',1,'2010-09-23 11:07:22',NULL,NULL),(4257,'ERR_BIND_ORDER_STATUS_PAYMENT_METHOD',1,'2010-09-23 11:07:22',NULL,NULL),(4258,'TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(4259,'TXT_LINK_FOR_NEWSLETTER_ACTIVATION',1,'2010-09-23 11:07:22',NULL,NULL),(4260,'TXT_LINK_FOR_UNWANTED_NEWSLETTER',1,'2010-09-23 11:07:22',NULL,NULL),(4261,'TXT_EMPTY_PRODUCT_WITH_ATTRIBUTES',1,'2010-09-23 11:07:22',NULL,NULL),(4262,'TXT_EMPTY_CATEGORIES',1,'2010-09-23 11:07:22',NULL,NULL),(4263,'TXT_POLL_NO_EXIST',1,'2010-09-23 11:07:22',NULL,NULL),(4264,'TXT_CART_VALUE_AMOUNT_EXCEED',1,'2010-09-23 11:07:22',NULL,NULL),(4265,'TXT_CART_VALUE_NOT_GREATER_THAN',1,'2010-09-23 11:07:22',NULL,NULL),(4266,'TXT_CART_DELIVERY_VALUE_AMOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(4267,'TXT_CART_DELIVERY_VALUE_NOT_GREATER_THAN',1,'2010-09-23 11:07:22',NULL,NULL),(4268,'TXT_MEET_CONDITION_FOR_DISCOUNT',1,'2010-09-23 11:07:22',NULL,NULL),(4269,'TXT_OR',1,'2010-09-23 11:07:22',NULL,NULL),(4270,'TXT_SUBPAGE_LAYOUT_LIST',1,'2010-09-23 11:07:22',NULL,NULL),(4271,'TXT_SUBPAGE_LAYOUT_DISBAND_VIEW_SPECIFIC',1,'2010-09-23 11:07:22',NULL,NULL),(4272,'TXT_RECEIVE_EMAIL_WITH_ACTIVE_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(4273,'TXT_RECEIVE_EMAIL_WITH_DEACTIVE_LINK',1,'2010-09-23 11:07:22',NULL,NULL),(4274,'TXT_CHECK_PRIVATE_MAIL_WITH_NEW_PASSWD',1,'2010-09-23 11:07:22',NULL,NULL),(4275,'TXT_CHANGE_CURRENCY',1,'2010-09-23 11:07:22',NULL,NULL),(4276,'TXT_SHOP',1,'2010-09-23 11:07:22',NULL,NULL),(4277,'TXT_ON_CHOSEN_CURRENCY',1,'2010-09-23 11:07:22',NULL,NULL),(4278,'TXT_ON_MONETARY_UNIT',1,'2010-09-23 11:07:22',NULL,NULL),(4279,'TXT_ZAGIEL_PROPOSAL_ACCEPT',1,'2010-09-23 11:07:22',NULL,NULL),(4280,'TXT_ZAGIEL_PROPOSAL_CANCEL',1,'2010-09-23 11:07:22',NULL,NULL),(4281,'EMPTY_BANKTRNASFER_INFO_ADMIN_CONTACT',1,'2010-09-23 11:07:22',NULL,NULL),(4282,'TXT_DELIVERY_ADDRESS_CHOOSE',1,'2010-11-03 21:54:34',NULL,NULL),(4285,'TXT_ADD_REVIEW',1,'2010-11-09 13:34:16',NULL,NULL),(4286,'TXT_CHOOSE_RANGE_VALUE',1,'2010-11-09 13:52:09',NULL,NULL),(4287,'TXT_ALL_ORDERS_PRICE_BEFORE_PROMOTION',1,'2010-11-10 09:52:38',NULL,NULL),(4288,'TXT_ALL_ORDERS_PRICE_AFTER_PROMOTION',1,'2010-11-10 09:52:59',NULL,NULL),(4289,'TXT_ALL_ORDERS_PRICE_NETTO',1,'2010-11-10 10:01:43',NULL,NULL),(4290,'TXT_ALL_ORDERS_PRICE_GROSS',1,'2010-11-10 10:02:05',NULL,NULL),(4291,'TXT_DISPATCHMETHOD_PANE',1,'2010-11-10 12:26:08',NULL,NULL),(4292,'TXT_PAYMENTMETHOD_PANE',1,'2010-11-10 12:26:31',NULL,NULL),(4293,'TXT_IMPORTANT_LINKS',1,'2010-11-10 14:32:14',NULL,NULL),(4294,'TXT_EMPTY_PRODUCTS_TAGS',1,'2010-11-10 14:54:30',NULL,NULL),(4295,'ERR_EMPTY_PROMOTION_LIST',1,'2010-11-10 15:41:12',NULL,NULL),(4296,'TXT_LOGIN_TO_ADD_REVIEW',1,'2010-11-11 17:17:11',NULL,NULL),(4297,'TXT_RECOMMEND_SHOP',1,'2010-11-13 10:34:55',1,NULL),(4298,'TXT_NO_PRODUCT_REVIEWS',1,'2010-11-13 12:05:04',NULL,NULL),(4299,'TXT_LOG_IN_TO_VOTE',1,'2010-11-13 17:57:32',NULL,NULL),(4300,'TXT_SITEMAP',1,'2010-11-13 18:19:29',NULL,NULL),(4301,'TXT_SELL_CURRENCY',1,'2010-11-13 21:48:35',NULL,NULL),(4302,'TXT_BUY_CURRENCY',1,'2010-11-13 21:48:55',NULL,NULL),(4303,'TXT_CURRENCY_DATA',1,'2010-11-13 21:51:53',NULL,NULL),(4304,'TXT_LANGUAGE_FLAG',1,'2010-11-13 21:52:08',NULL,NULL),(4305,'TXT_SPY',1,'2010-11-14 16:36:09',NULL,NULL),(4306,'TXT_POLL_DOES_NOT_EXIST',1,'2010-11-14 17:23:08',NULL,NULL),(4310,'ERR_BIND_SELF_PARENT_INVALID',1,'2010-11-21 16:40:48',NULL,NULL),(4311,'TXT_ORDER_TRANSACTION_SUMMARY',1,'2010-11-23 12:29:24',NULL,NULL),(4312,'TXT_DUPLICATE_CATEGORY',1,'2010-11-23 12:29:24',NULL,NULL),(4313,'TXT_ANALYTICS_DATA',1,'2011-09-21 21:54:12',1,NULL),(4314,'TXT_GA_CODE',1,'2010-11-23 20:36:06',NULL,NULL),(4315,'TXT_GA_TRANSACTIONS',1,'2010-11-23 20:36:06',NULL,NULL),(4316,'TXT_GA_PAGES',1,'2010-11-23 20:36:06',NULL,NULL),(4317,'ERR_BIND_STATUS_PRODUCT',1,'2010-11-24 09:02:26',NULL,NULL),(4318,'ERR_FILE_BIND_TO_PRODUCER',1,'2010-11-24 09:07:07',NULL,NULL),(4319,'TXT_DEFAULT_LANGUAGE_CURRENCY',1,'2010-11-24 09:23:02',NULL,NULL),(4320,'TXT_GUEST',1,'2010-11-24 09:26:54',NULL,NULL),(4321,'TXT_DEVELOPER_DATA',1,'2010-11-26 19:12:17',NULL,NULL),(4322,'TXT_SHOP_OFFLINE',1,'2010-11-26 19:12:17',NULL,NULL),(4323,'TXT_OFFLINE_MESSAGE',1,'2010-11-26 19:12:17',NULL,NULL),(4324,'TXT_SAVE_AND_CONTINUE',1,'2010-12-01 08:23:20',NULL,NULL),(4325,'TXT_ORDER_WITHOUT_REGISTRATION',1,'2010-12-01 08:32:41',NULL,NULL),(4326,'TXT_DELIVERY_GUEST',1,'2010-12-01 08:40:51',NULL,NULL),(4327,'TXT_ORDER_WITH_REGISTRATION',1,'2010-12-01 08:45:12',NULL,NULL),(4328,'TXT_ORDER_WITH_LOGIN',1,'2010-12-01 08:46:54',NULL,NULL),(4330,'TXT_ACCEPT_PRIVACY_DESCRIPTION',1,'2010-12-03 13:18:00',NULL,NULL),(4331,'TXT_CONTROLLER_BANKTRANSFER',1,'2010-12-03 18:35:40',NULL,NULL),(4332,'TXT_CONTROLLER_CART',1,'2010-12-03 18:35:40',NULL,NULL),(4333,'TXT_CONTROLLER_CATEGORYLIST',1,'2010-12-03 18:35:40',NULL,NULL),(4334,'TXT_CONTROLLER_CENEO',1,'2010-12-03 18:35:40',NULL,NULL),(4335,'TXT_CONTROLLER_CLIENT',1,'2010-12-03 18:35:40',NULL,NULL),(4336,'TXT_CONTROLLER_CLIENTADDRESS',1,'2010-12-03 18:35:40',NULL,NULL),(4337,'TXT_CONTROLLER_CLIENTLOGIN',1,'2010-12-03 18:35:40',NULL,NULL),(4338,'TXT_CONTROLLER_CLIENTORDER',1,'2010-12-03 18:35:40',NULL,NULL),(4339,'TXT_CONTROLLER_CLIENTSETTINGS',1,'2010-12-03 18:35:40',NULL,NULL),(4340,'TXT_CONTROLLER_CONFIRMATION',1,'2010-12-03 18:35:40',NULL,NULL),(4341,'TXT_CONTROLLER_CONTACT',1,'2010-12-03 18:35:40',NULL,NULL),(4342,'TXT_CONTROLLER_DELIVERY',1,'2010-12-03 18:35:40',NULL,NULL),(4343,'TXT_CONTROLLER_ERROR',1,'2010-12-03 18:35:40',NULL,NULL),(4344,'TXT_CONTROLLER_FEEDS',1,'2010-12-03 18:35:40',NULL,NULL),(4345,'TXT_CONTROLLER_FINALIZATION',1,'2010-12-03 18:35:40',NULL,NULL),(4346,'TXT_CONTROLLER_FORGOTLOGIN',1,'2010-12-03 18:35:40',NULL,NULL),(4347,'TXT_CONTROLLER_FORGOTPASSWORD',1,'2010-12-03 18:35:40',NULL,NULL),(4348,'TXT_CONTROLLER_GOOGLEANALITYCS',1,'2010-12-03 18:35:40',NULL,NULL),(4349,'TXT_CONTROLLER_GOOGLESITEMAPS',1,'2010-12-03 18:35:40',NULL,NULL),(4350,'TXT_CONTROLLER_INSTALL',1,'2010-12-03 18:35:40',NULL,NULL),(4351,'TXT_CONTROLLER_INTEGRATION',1,'2010-12-03 18:35:40',NULL,NULL),(4352,'TXT_CONTROLLER_INVOICE',1,'2010-12-03 18:35:40',NULL,NULL),(4353,'TXT_CONTROLLER_LAYERNAVIGATION',1,'2010-12-03 18:35:40',NULL,NULL),(4354,'TXT_CONTROLLER_LOGIN',1,'2010-12-03 18:35:40',NULL,NULL),(4355,'TXT_CONTROLLER_MAINSIDE',1,'2010-12-03 18:35:40',NULL,NULL),(4356,'TXT_CONTROLLER_MISSINGCART',1,'2010-12-03 18:35:40',NULL,NULL),(4357,'TXT_CONTROLLER_MOSTSEARCH',1,'2010-12-03 18:35:40',NULL,NULL),(4358,'TXT_CONTROLLER_NEWS',1,'2010-12-03 18:35:40',NULL,NULL),(4359,'TXT_CONTROLLER_NEWSLETTER',1,'2010-12-03 18:35:40',NULL,NULL),(4361,'TXT_CONTROLLER_ONDELIVERY',1,'2010-12-03 18:35:40',NULL,NULL),(4362,'TXT_CONTROLLER_ORDER',1,'2010-12-03 18:35:40',NULL,NULL),(4363,'TXT_CONTROLLER_PAYMENT',1,'2010-12-03 18:35:40',NULL,NULL),(4364,'TXT_CONTROLLER_PICKUP',1,'2010-12-03 18:35:40',NULL,NULL),(4365,'TXT_CONTROLLER_PINVALIDATION',1,'2010-12-03 18:35:40',NULL,NULL),(4366,'TXT_CONTROLLER_PLATNOSCI',1,'2010-12-03 18:35:40',NULL,NULL),(4367,'TXT_CONTROLLER_POLL',1,'2010-12-03 18:35:40',NULL,NULL),(4368,'TXT_CONTROLLER_PRODUCT',1,'2010-12-03 18:35:40',NULL,NULL),(4369,'TXT_CONTROLLER_PRODUCTCART',1,'2010-12-03 18:35:40',NULL,NULL),(4370,'TXT_CONTROLLER_PRODUCTCOMBINATION',1,'2010-12-03 18:35:40',NULL,NULL),(4371,'TXT_CONTROLLER_PRODUCTFILTRATION',1,'2010-12-03 18:35:40',NULL,NULL),(4372,'TXT_CONTROLLER_PRODUCTLIST',1,'2010-12-03 18:35:40',NULL,NULL),(4373,'TXT_CONTROLLER_PRODUCTNEWS',1,'2010-12-03 18:35:40',NULL,NULL),(4374,'TXT_CONTROLLER_PRODUCTPROMOTION',1,'2010-12-03 18:35:40',NULL,NULL),(4375,'TXT_CONTROLLER_PRODUCTREVIEW',1,'2010-12-03 18:35:40',NULL,NULL),(4376,'TXT_CONTROLLER_PRODUCTSEARCH',1,'2010-12-03 18:35:40',NULL,NULL),(4377,'TXT_CONTROLLER_PRODUCTTAGS',1,'2010-12-03 18:35:40',NULL,NULL),(4378,'TXT_CONTROLLER_REGISTRATION',1,'2010-12-03 18:35:40',NULL,NULL),(4379,'TXT_CONTROLLER_REGISTRATIONCART',1,'2010-12-03 18:35:40',NULL,NULL),(4380,'TXT_CONTROLLER_SEARCHRESULTS',1,'2010-12-03 18:35:40',NULL,NULL),(4381,'TXT_CONTROLLER_STATICCONTENT',1,'2010-12-03 18:35:40',NULL,NULL),(4382,'TXT_CONTROLLER_WISHLIST',1,'2010-12-03 18:35:40',NULL,NULL),(4383,'TXT_CONTROLLER_SITEMAP',1,'2010-12-03 18:35:40',NULL,NULL),(4385,'TXT_CONTROLLER_PAYPAL',1,'2010-12-03 18:35:40',NULL,NULL),(4387,'TXT_CONTROLLER_RSS',1,'2010-12-03 18:35:40',NULL,NULL),(4388,'TXT_CONTROLLER_PAYFLOW',1,'2010-12-03 18:35:40',NULL,NULL),(4389,'TXT_CONTROLLER_ERATY',1,'2010-12-03 18:35:40',NULL,NULL),(4390,'TXT_CART_SETTINGS',1,'2010-12-03 18:47:05',NULL,NULL),(4391,'TXT_CART_REDIRECT',1,'2011-10-09 12:48:10',1,NULL),(4392,'TXT_NO_CART_REDIRECT',1,'2010-12-03 18:47:05',NULL,NULL),(4393,'TXT_CART_TRACK_STOCK',1,'2010-12-03 18:47:05',NULL,NULL),(4394,'TXT_BUSINESS_HOURS',1,'2010-12-05 22:46:55',NULL,NULL),(4395,'TXT_SET_USER_LAYER_RIGHTS',1,'2010-12-05 22:46:55',NULL,NULL),(4396,'TXT_GLOBAL_USER',1,'2010-12-05 22:46:55',NULL,NULL),(4397,'ERR_BIND_CURRENCY_LANGUAGE',1,'2010-12-05 22:46:55',NULL,NULL),(4399,'ERR_EMPTY_NAME_OF_COUNTRY',1,'2010-12-08 12:16:46',NULL,NULL),(4400,'TXT_CONFIRM_LEAVING_ORDER',1,'2010-12-08 12:16:46',NULL,NULL),(4401,'TXT_MAXIMUM_WEIGHT',1,'2010-12-08 12:16:46',NULL,NULL),(4402,'TXT_MAXIMUM_WEIGHT_HELP',1,'2010-12-08 12:16:46',NULL,NULL),(4403,'TXT_FREE_DELIVERY',1,'2010-12-08 12:16:46',NULL,NULL),(4404,'TXT_FREE_DELIVERY_HELP',1,'2010-12-08 12:16:46',NULL,NULL),(4405,'TXT_ERATY_BOX',1,'2010-12-11 16:01:01',NULL,NULL),(4406,'TXT_PAYFLOW_BOX',1,'2010-12-11 16:01:01',NULL,NULL),(4408,'TXT_SHOWCASE_BOX',1,'2010-12-11 16:01:01',NULL,NULL),(4409,'TXT_SITEMAP_BOX',1,'2010-12-11 16:01:01',NULL,NULL),(4412,'TXT_CATEGORY_ORDER',1,'2010-12-22 16:43:17',NULL,NULL),(4413,'ERR_EMPTY_SHOP',1,'2011-01-11 20:16:54',NULL,NULL),(4414,'ERR_EMPTY_DATE_FROM',1,'2011-01-11 20:16:54',NULL,NULL),(4415,'ERR_EMPTY_DATE_TO',1,'2011-01-11 20:16:54',NULL,NULL),(4416,'ERR_EMPTY_NAMESPACE',1,'2011-01-11 20:16:54',NULL,NULL),(4417,'ERR_EMPTY_STORE',1,'2011-01-11 20:16:54',NULL,NULL),(4418,'ERR_EMPTY_PROVINCE',1,'2011-01-11 20:16:54',NULL,NULL),(4419,'ERR_EMPTY_KIND_OF_CURRENCY',1,'2011-01-11 20:16:54',NULL,NULL),(4420,'ERR_EMPTY_DATE_FORMAT',1,'2011-01-11 20:16:54',NULL,NULL),(4421,'ERR_EMPTY_SIDE',1,'2011-01-11 20:16:54',NULL,NULL),(4422,'ERR_EMPTY_CONTROLLER',1,'2011-01-11 20:16:54',NULL,NULL),(4423,'TXT_ADD_CONTROLLERSEO',1,'2011-01-11 20:16:54',NULL,NULL),(4424,'ERR_EMPTY_CURRENCY_SYMBOL',1,'2011-01-11 20:16:54',NULL,NULL),(4425,'TXT_UPDATE_EXCHANGE_RATES',1,'2011-01-11 20:16:54',NULL,NULL),(4426,'TXT_UPDATE_EXCHANGE_RATES_HELP',1,'2011-01-11 20:16:54',NULL,NULL),(4427,'TXT_COPY_FROM_LANGUAGE',1,'2011-01-11 20:16:54',NULL,NULL),(4428,'ERR_EMPTY_DELIVERY_COST_TYPE',1,'2011-01-11 20:16:54',NULL,NULL),(4432,'ERR_EMPTY_SITEMAPS_NAME',1,'2011-01-11 20:16:54',NULL,NULL),(4433,'ERR_EMPTY_SITEMAPS_PINGSERVER',1,'2011-01-11 20:16:54',NULL,NULL),(4434,'ERR_EMPTY_TRANSACTION_TEMPLATES',1,'2011-01-11 20:16:54',NULL,NULL),(4435,'TXT_REFRESH_SEO',1,'2011-02-04 17:36:52',NULL,NULL),(4436,'TXT_MEET_CONDITION_FOR_ADDITIONAL_PAYMENT',1,'2011-02-09 21:41:19',NULL,NULL),(4437,'TXT_MINIMUM_ORDER_VALUE_REQUIRED',1,'2011-02-11 16:26:41',NULL,NULL),(4438,'TXT_TRACKSTOCK',1,'2011-04-06 21:36:40',NULL,NULL),(4439,'TXT_PAYPAL_PAYMENT_CANCELLED',1,'2011-04-06 21:36:40',NULL,NULL),(4440,'TXT_PASSAGES',1,'2011-04-16 20:08:15',NULL,NULL),(4442,'TXT_STATISTICS',1,'2011-04-16 20:08:15',NULL,NULL),(4443,'TXT_MARKETING',1,'2011-04-16 20:08:15',NULL,NULL),(4444,'TXT_STATICBLOCKS',1,'2011-04-16 20:08:15',NULL,NULL),(4455,'TXT_FEATURED_ITEM',1,'2011-04-16 20:08:16',NULL,NULL),(4459,'TXT_SELLER_PAYING_FOR_SHIPMENT',1,'2011-04-16 20:08:16',NULL,NULL),(4483,'TXT_SELLER_LOGIN',1,'2011-04-16 20:08:16',NULL,NULL),(4484,'TXT_SELLER_RATING',1,'2011-04-16 20:08:16',NULL,NULL),(4485,'TXT_SELLER_COUNTRY',1,'2011-04-16 20:08:16',NULL,NULL),(4491,'TXT_SELLING_BLOKED_USER',1,'2011-04-16 20:08:16',NULL,NULL),(4496,'TXT_ABOUT_US',1,'2011-04-16 20:08:16',NULL,NULL),(4497,'TXT_TOP_10',1,'2011-04-16 20:08:16',NULL,NULL),(4498,'TXT_WHAT_NEXT',1,'2011-04-16 20:08:16',NULL,NULL),(4499,'TXT_DATEFORMAT',1,'2011-04-16 20:08:16',NULL,NULL),(4510,'TXT_TODAY_IS',1,'2011-04-16 20:08:16',NULL,NULL),(4511,'TXT_PERMISSION_ADD',1,'2011-04-16 20:08:16',NULL,NULL),(4520,'TXT_RAMDOM',1,'2011-04-16 20:08:16',NULL,NULL),(4521,'TXT_LINK_OR_ENCOURAGEMENT',1,'2011-04-16 20:08:16',NULL,NULL),(4522,'TXT_ADMIN_HISTORYLOGS_LIST',1,'2011-04-16 20:08:16',NULL,NULL),(4527,'TXT_ECONOMIC_PACKAGE',1,'2011-04-16 20:08:16',NULL,NULL),(4528,'TXT_ECONOMIC_LETTER',1,'2011-04-16 20:08:16',NULL,NULL),(4529,'TXT_ECONOMIC_REGISTERED_LETTER',1,'2011-04-16 20:08:16',NULL,NULL),(4530,'TXT_PERMISSION_INDEX',1,'2011-04-16 20:08:16',NULL,NULL),(4531,'TXT_PERMISSION_EDIT',1,'2011-04-16 20:08:16',NULL,NULL),(4532,'TXT_PERMISSION_DELETE',1,'2011-04-16 20:08:16',NULL,NULL),(4533,'TXT_PERMISSION_VIEW',1,'2011-04-16 20:08:16',NULL,NULL),(4534,'TXT_PERMISSION_ALL',1,'2011-04-16 20:08:16',NULL,NULL),(4535,'TXT_TASK',1,'2011-04-16 20:08:16',NULL,NULL),(4536,'TXT_DEFECT',1,'2011-04-16 20:08:16',NULL,NULL),(4537,'TXT_LOWEST',1,'2011-04-16 20:08:16',NULL,NULL),(4538,'TXT_LOW',1,'2011-04-16 20:08:16',NULL,NULL),(4539,'TXT_NORMAL',1,'2011-04-16 20:08:16',NULL,NULL),(4540,'TXT_HIGH',1,'2011-04-16 20:08:16',NULL,NULL),(4541,'TXT_HIGHEST',1,'2011-04-16 20:08:16',NULL,NULL),(4542,'TXT_FIXED',1,'2011-04-16 20:08:16',NULL,NULL),(4543,'TXT_INVALID',1,'2011-04-16 20:08:16',NULL,NULL),(4544,'TXT_OPENED',1,'2011-04-16 20:08:16',NULL,NULL),(4545,'TXT_REOPENED',1,'2011-04-16 20:08:16',NULL,NULL),(4546,'TXT_PRIORITY',1,'2011-04-16 20:08:16',NULL,NULL),(4547,'TXT_NR_ERROR',1,'2011-04-16 20:08:16',NULL,NULL),(4548,'TXT_BUG_REPORT',1,'2011-04-16 20:08:16',NULL,NULL),(4549,'TXT_BUG_REPORT_ADD',1,'2011-04-16 20:08:16',NULL,NULL),(4550,'TXT_MOVIE',1,'2011-04-16 20:08:16',NULL,NULL),(4551,'TXT_ENHANCEMENT',1,'2011-04-16 20:08:16',NULL,NULL),(4552,'TXT_TICKET_EDIT',1,'2011-04-16 20:08:16',NULL,NULL),(4554,'TXT_VIEW_FORM',1,'2011-04-16 20:08:16',NULL,NULL),(4555,'TXT_TICKET',1,'2011-04-16 20:08:16',NULL,NULL),(4556,'TXT_ADDITIONAL_INFORMATIONS',1,'2011-04-16 20:08:16',NULL,NULL),(4557,'TXT_WHAT_DO_YOU_WANT_TO_DO_NEXT',1,'2011-04-16 20:08:16',NULL,NULL),(4558,'TXT_BAD',1,'2011-04-16 20:08:16',NULL,NULL),(4559,'TXT_IDEAL',1,'2011-04-16 20:08:16',NULL,NULL),(4561,'TXT_GOOGLE_ANALITYCS',1,'2011-04-16 20:08:16',NULL,NULL),(4562,'TXT_ADD_PRODUCTCOMBINACTION',1,'2011-04-16 20:08:16',NULL,NULL),(4573,'TXT_WATERMARK_INFO',1,'2011-04-16 20:08:17',NULL,NULL),(4586,'TXT_WATERMARK',1,'2011-04-16 20:08:17',NULL,NULL),(4589,'TXT_MAINPHOTO',1,'2011-04-16 20:08:17',NULL,NULL),(4590,'TXT_ADD_BUG_REPORT',1,'2011-04-16 20:08:17',NULL,NULL),(4593,'TXT_ANOTHER_FORMS',1,'2011-04-16 20:08:17',NULL,NULL),(4594,'TXT_ANOTHER_FORMS_INFO',1,'2011-04-16 20:08:17',NULL,NULL),(4600,'TXT_EKO_OFFER_INFO',1,'2011-04-16 20:08:17',NULL,NULL),(4601,'TXT_EKO_OFFER',1,'2011-04-16 20:08:17',NULL,NULL),(4602,'TXT_DEMAGED',1,'2011-04-16 20:08:17',NULL,NULL),(4608,'TXT_PIN',1,'2011-04-16 20:08:17',NULL,NULL),(4610,'TXT_OSCOMMERCE_SETTINGS',1,'2011-04-16 20:08:17',NULL,NULL),(4611,'TXT_PRODUCT_BESTSELLERS_BOX',1,'2011-04-16 20:08:17',NULL,NULL),(4612,'TXT_ADD_SITMAPS',1,'2011-04-16 20:08:17',NULL,NULL),(4613,'TXT_EXCHANGE_TYPE_OSCOMMERCE_IMPORT',1,'2011-04-16 20:08:17',NULL,NULL),(4615,'TXT_OSCOMMERCE_USERNAME',1,'2011-04-16 20:08:17',NULL,NULL),(4616,'TXT_OSCOMMERCE_PASSWORD',1,'2011-04-16 20:08:17',NULL,NULL),(4674,'TXT_PIN_VALIDATION_BOX',1,'2011-04-16 20:08:17',NULL,NULL),(4676,'TXT_ADD_WORDPRESS',1,'2011-04-16 20:08:17',NULL,NULL),(4677,'TXT_EDIT_WORDPRESS',1,'2011-04-16 20:08:17',NULL,NULL),(4680,'ERR_UPDATE_WORDPRESS',1,'2011-04-16 20:08:17',NULL,NULL),(4683,'TXT_MONTHLY',1,'2011-04-18 18:39:27',NULL,NULL),(4684,'TXT_CALCULATE_ZAGIEL',1,'2011-04-18 18:40:12',NULL,NULL),(4687,'TXT_CONTROLLER_WORDPRESS',1,'2011-04-22 21:29:40',NULL,NULL),(4688,'TXT_ENABLE_OPINIONS',1,'2011-04-22 21:29:41',NULL,NULL),(4689,'TXT_ENABLE_TAGS',1,'2011-04-22 21:29:41',NULL,NULL),(4690,'TXT_HIERARCHY',1,'2011-04-22 21:29:41',NULL,NULL),(4691,'TXT_PHONE_SHOP',1,'2011-04-22 21:29:41',NULL,NULL),(4692,'TXT_PLN',1,'2011-04-22 21:29:41',NULL,NULL),(4696,'TXT_REGISTER_IN_SHOP',1,'2011-05-10 20:31:39',NULL,NULL),(4697,'TXT_REGISTER_INSTRUCTION',1,'2011-05-10 20:32:18',NULL,NULL),(4698,'ERR_DISPATCHMETHOD_USED_IN_ORDERS',1,'2011-05-25 17:48:40',NULL,NULL),(4699,'ERR_PAYMENTMETHOD_USED_IN_ORDERS',1,'2011-05-25 18:38:15',NULL,NULL),(4700,'TXT_COPY_DELIVERY_ADRESS',1,'2011-05-25 19:33:49',1,NULL),(4701,'TXT_ENABLE_CATALOG_MODE',1,'2011-05-25 19:34:38',NULL,NULL),(4702,'TXT_FORCE_CLIENT_LOGIN',1,'2011-10-09 12:45:44',1,NULL),(4703,'TXT_EDIT_NEW_TAB',1,'2011-05-25 19:35:01',NULL,NULL),(4704,'TXT_ENABLE_IN_FOOTER',1,'2011-05-25 19:35:13',NULL,NULL),(4705,'TXT_ENABLE_IN_HEADER',1,'2011-05-25 19:35:24',NULL,NULL),(4706,'TXT_WITHOUT_HTTP',1,'2011-05-25 22:27:45',NULL,NULL),(4708,'ERR_EMPTY_NICK',1,'2011-05-30 21:35:36',NULL,NULL),(4710,'ERR_EMPTY_SEX',1,'2011-05-30 21:35:36',NULL,NULL),(4711,'ERR_FIELD',1,'2011-05-30 21:35:36',NULL,NULL),(4712,'ERR_FIELD_ONLY_ALPHANUM',1,'2011-05-30 21:35:36',NULL,NULL),(4713,'ERR_FIELD_SIZE',1,'2011-05-30 21:35:36',NULL,NULL),(4714,'ERR_FIELD_SIZE3',1,'2011-05-30 21:35:36',NULL,NULL),(4715,'ERR_INHERITANCE_PROMOTION',1,'2011-05-30 21:35:36',NULL,NULL),(4716,'ERR_NICK',1,'2011-05-30 21:35:36',NULL,NULL),(4718,'ERR_WRONG_EMAIL_FORMAT',1,'2011-05-30 21:35:36',NULL,NULL),(4780,'TXT_BUG_REPORTS_LIST',1,'2011-05-30 21:35:36',NULL,NULL),(4781,'TXT_CHANGE_NICK',1,'2011-05-30 21:35:36',NULL,NULL),(4782,'TXT_CHANGE_TWITTER_DATA',1,'2011-05-30 21:35:36',NULL,NULL),(4815,'TXT_GOOGLEANALITYCSACCOUNT',1,'2011-05-30 21:35:37',NULL,NULL),(4816,'TXT_GOOGLE_ANALITYCS_ACCOUNT',1,'2011-05-30 21:35:37',NULL,NULL),(4817,'TXT_GOOGLE_ANALITYCS_EMAIL',1,'2011-05-30 21:35:37',NULL,NULL),(4818,'TXT_GOOGLE_ANALITYCS_PASSWORD',1,'2011-05-30 21:35:37',NULL,NULL),(4824,'TXT_MY_BILLING',1,'2011-05-30 21:35:37',NULL,NULL),(4825,'TXT_NICK',1,'2011-05-30 21:35:37',NULL,NULL),(4826,'TXT_NICK_NEW',1,'2011-05-30 21:35:37',NULL,NULL),(4830,'TXT_PARCEL_TIME_DELIVER_COURIER',1,'2011-05-30 21:35:37',NULL,NULL),(4882,'TXT_SEX',1,'2011-05-30 21:35:37',NULL,NULL),(4890,'TXT_WONTFIX',1,'2011-05-30 21:35:38',NULL,NULL),(4891,'TXT_WORDPRESS',1,'2011-05-30 21:35:38',NULL,NULL),(4892,'TXT_WORDPRESS_LIST',1,'2011-05-30 21:35:38',NULL,NULL),(4893,'TXT_WORKSFORME',1,'2011-05-30 21:35:38',NULL,NULL),(4894,'TXT_WRITE_MESSAGE_FOR_TWITTER',1,'2011-05-30 21:35:38',NULL,NULL),(4897,'TXT_WORDPRESS_BOX',1,'2011-05-30 21:35:38',NULL,NULL),(4898,'TXT_WORDPRESS_LIST_BOX',1,'2011-05-30 21:35:38',NULL,NULL),(4899,'ERR_EMPTY_OSCOMMERCE_HOST',1,'2011-05-30 21:35:38',NULL,NULL),(4900,'ERR_EMPTY_OSCOMMERCE_USERNAME',1,'2011-05-30 21:35:38',NULL,NULL),(4901,'ERR_EMPTY_OSCOMMERCE_PASSWORD',1,'2011-05-30 21:35:38',NULL,NULL),(4902,'TXT_SLIDESHOW_BOX',1,'2011-05-30 21:35:38',NULL,NULL),(4903,'TXT_PROMOTION_PRICE_WIHT_DISPATCH_METHOD',1,'2011-05-30 21:35:38',NULL,NULL),(4904,'TXT_PROMOTION_PRICE_WITH_DISPATCH_METHOD_NETTO',1,'2011-05-30 21:35:38',NULL,NULL),(4905,'TXT_ENABLE_RSS',1,'2011-06-06 20:20:57',NULL,NULL),(4906,'TXT_ORDER_UPLOADER_DATA',1,'2011-06-06 20:21:09',NULL,NULL),(4907,'TXT_ORDER_UPLOADER_ENABLED',1,'2011-06-06 20:21:20',NULL,NULL),(4908,'TXT_ORDER_UPLOADER_MAX_FILESIZE',1,'2011-06-06 20:21:41',NULL,NULL),(4909,'TXT_ORDER_UPLOADER_CHUNKSIZE',1,'2011-06-06 20:22:12',NULL,NULL),(4910,'TXT_ORDER_UPLOADER_ALLOWED_EXTENSIONS',1,'2011-06-06 20:22:33',NULL,NULL),(4911,'TXT_ORDER_FILES_UPLOAD',1,'2011-06-06 20:24:33',NULL,NULL),(4912,'TXT_ORDER_FILES_UPLOAD_HELP',1,'2011-06-06 20:25:10',NULL,NULL),(4913,'TXT_SELECT_FILES',1,'2011-06-06 20:25:26',NULL,NULL),(4914,'TXT_UPLOAD_FILES',1,'2011-06-06 20:25:47',NULL,NULL),(4915,'TXT_PLACE_ORDER',1,'2011-06-06 20:41:24',NULL,NULL),(4916,'TXT_INDIVIDUAL_CLIENT',1,'2011-06-17 23:22:07',NULL,NULL),(4917,'TXT_COMPANY_CLIENT',1,'2011-06-17 23:22:16',NULL,NULL),(4918,'TXT_JS_ADD_TO_ORDER',1,'2011-06-19 10:54:02',NULL,NULL),(4919,'TXT_JS_CLOSETEXT',1,'2011-06-19 10:54:02',NULL,NULL),(4920,'TXT_JS_PREVTEXT',1,'2011-06-19 10:54:02',NULL,NULL),(4921,'TXT_JS_NEXTTEXT',1,'2011-06-19 10:54:02',NULL,NULL),(4922,'TXT_JS_CURRENTTEXT',1,'2011-06-19 10:54:02',NULL,NULL),(4923,'TXT_JS_EXCEPTION_HAS_OCCURED',1,'2011-06-19 10:54:02',NULL,NULL),(4924,'TXT_JS_SELECT_STORE',1,'2011-06-19 10:54:02',NULL,NULL),(4925,'TXT_JS_ADD_REPETITION',1,'2011-06-19 10:54:02',NULL,NULL),(4926,'TXT_JS_DELETE_REPETITION',1,'2011-06-19 10:54:03',NULL,NULL),(4927,'TXT_JS_FORM_DATA_INVALID',1,'2011-06-19 10:54:03',NULL,NULL),(4928,'TXT_JS_SCROLL_TO_FIELD',1,'2011-06-19 10:54:03',NULL,NULL),(4929,'TXT_JS_CLOSE_ALERT',1,'2011-06-19 10:54:03',NULL,NULL),(4930,'TXT_JS_NEXT',1,'2011-06-19 10:54:03',NULL,NULL),(4931,'TXT_JS_PREVIOUS',1,'2011-06-19 10:54:03',NULL,NULL),(4932,'TXT_JS_SAVE',1,'2011-06-19 10:54:03',NULL,NULL),(4933,'TXT_JS_ADD_FIELD_REPETITION',1,'2011-06-19 10:54:03',NULL,NULL),(4934,'TXT_JS_REMOVE_FIELD_REPETITION',1,'2011-06-19 10:54:03',NULL,NULL),(4935,'TXT_JS_ALL_ACTIONS',1,'2011-06-19 10:54:03',NULL,NULL),(4936,'TXT_JS_ALL_CONTROLLERS',1,'2011-06-19 10:54:03',NULL,NULL),(4937,'TXT_JS_PRODUCT_SELECT_ID',1,'2011-06-19 10:54:03',NULL,NULL),(4938,'TXT_JS_PRODUCT_SELECT_NAME',1,'2011-06-19 10:54:03',NULL,NULL),(4939,'TXT_JS_PRODUCT_SELECT_PRICE',1,'2011-06-19 10:54:03',NULL,NULL),(4940,'TXT_JS_PRODUCT_SELECT_PRICE_GROSS',1,'2011-06-19 10:54:04',NULL,NULL),(4941,'TXT_JS_PRODUCT_SELECT_BUYPRICE',1,'2011-06-19 10:54:04',NULL,NULL),(4942,'TXT_JS_PRODUCT_SELECT_BUYPRICE_GROSS',1,'2011-06-19 10:54:04',NULL,NULL),(4943,'TXT_JS_PRODUCT_SELECT_BARCODE',1,'2011-06-19 10:54:04',NULL,NULL),(4944,'TXT_JS_PRODUCT_SELECT_PRODUCER',1,'2011-06-19 10:54:04',NULL,NULL),(4945,'TXT_JS_PRODUCT_SELECT_VAT',1,'2011-06-19 10:54:04',NULL,NULL),(4946,'TXT_JS_PRODUCT_SELECT_VAT_VALUE',1,'2011-06-19 10:54:04',NULL,NULL),(4947,'TXT_JS_PRODUCT_SELECT_NET_SUBSUM',1,'2011-06-19 10:54:04',NULL,NULL),(4948,'TXT_JS_PRODUCT_SELECT_SUBSUM',1,'2011-06-19 10:54:04',NULL,NULL),(4949,'TXT_JS_PRODUCT_SELECT_CATEGORIES',1,'2011-06-19 10:54:04',NULL,NULL),(4950,'TXT_JS_PRODUCT_SELECT_ADDDATE',1,'2011-06-19 10:54:04',NULL,NULL),(4951,'TXT_JS_PRODUCT_SELECT_ADDUSER',1,'2011-06-19 10:54:04',NULL,NULL),(4952,'TXT_JS_PRODUCT_SELECT_EDITDATE',1,'2011-06-19 10:54:04',NULL,NULL),(4953,'TXT_JS_PRODUCT_SELECT_EDITUSER',1,'2011-06-19 10:54:04',NULL,NULL),(4954,'TXT_JS_PRODUCT_SELECT_DESELECT',1,'2011-06-19 10:54:04',NULL,NULL),(4955,'TXT_JS_PRODUCT_SELECT_SELECTED',1,'2011-06-19 10:54:04',NULL,NULL),(4956,'TXT_JS_PRODUCT_SELECT_QUANTITY',1,'2011-06-19 10:54:04',NULL,NULL),(4957,'TXT_JS_PRODUCT_SELECT_VARIANT',1,'2011-06-19 10:54:04',NULL,NULL),(4958,'TXT_JS_PRODUCT_SELECT_ADD',1,'2011-06-19 10:54:04',NULL,NULL),(4959,'TXT_JS_PRODUCT_SELECT_CLOSE_ADD',1,'2011-06-19 10:54:04',NULL,NULL),(4960,'TXT_JS_PRODUCT_SELECT_SUM',1,'2011-06-19 10:54:04',NULL,NULL),(4961,'TXT_JS_DATAGRID_SELECT_SELECTED',1,'2011-06-19 10:54:05',NULL,NULL),(4962,'TXT_JS_DATAGRID_SELECT_DESELECT',1,'2011-06-19 10:54:05',NULL,NULL),(4963,'TXT_JS_DATAGRID_SELECT_DG_ERROR',1,'2011-06-19 10:54:05',NULL,NULL),(4964,'TXT_JS_FILE_SELECTOR_ID',1,'2011-06-19 10:54:05',NULL,NULL),(4965,'TXT_JS_FILE_SELECTOR_FILENAME',1,'2011-06-19 10:54:05',NULL,NULL),(4966,'TXT_JS_FILE_SELECTOR_FILETYPE',1,'2011-06-19 10:54:05',NULL,NULL),(4967,'TXT_JS_FILE_SELECTOR_EXTENSION',1,'2011-06-19 10:54:05',NULL,NULL),(4968,'TXT_JS_FILE_SELECTOR_DESELECT',1,'2011-06-19 10:54:05',NULL,NULL),(4969,'TXT_JS_FILE_SELECTOR_THUMB',1,'2011-06-19 10:54:05',NULL,NULL),(4970,'TXT_JS_FILE_SELECTOR_SHOW_THUMB',1,'2011-06-19 10:54:05',NULL,NULL),(4971,'TXT_JS_FILE_SELECTOR_PHOTO',1,'2011-06-19 10:54:05',NULL,NULL),(4972,'TXT_JS_FILE_SELECTOR_PHOTO_MAIN',1,'2011-06-19 10:54:05',NULL,NULL),(4973,'TXT_JS_FILE_SELECTOR_PHOTO_VISIBLE',1,'2011-06-19 10:54:05',NULL,NULL),(4974,'TXT_JS_FILE_SELECTOR_PHOTO_CANCEL',1,'2011-06-19 10:54:05',NULL,NULL),(4975,'TXT_JS_FILE_SELECTOR_UPLOAD_ERROR',1,'2011-06-19 10:54:05',NULL,NULL),(4976,'TXT_JS_FILE_SELECTOR_UPLOAD_SUCCESS',1,'2011-06-19 10:54:05',NULL,NULL),(4977,'TXT_JS_FILE_SELECTOR_PROCESSING_ERROR',1,'2011-06-19 10:54:05',NULL,NULL),(4978,'TXT_JS_FILE_SELECTOR_FORM_BLOCKED',1,'2011-06-19 10:54:05',NULL,NULL),(4979,'TXT_JS_FILE_SELECTOR_FORM_BLOCKED_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(4980,'TXT_JS_FILE_SELECTOR_SELECTED_IMAGE',1,'2011-06-19 10:54:05',NULL,NULL),(4981,'TXT_JS_PRODUCT_VARIANTS_EDITOR_SET_FOR_THIS_PRODUCT',1,'2011-06-19 10:54:05',NULL,NULL),(4982,'TXT_JS_PRODUCT_VARIANTS_EDITOR_SET_FOR_THIS_PRODUCT_SUFFIX',1,'2011-06-19 10:54:05',NULL,NULL),(4983,'TXT_JS_PRODUCT_VARIANTS_EDITOR_ADD_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(4984,'TXT_JS_PRODUCT_VARIANTS_EDITOR_SAVE_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(4985,'TXT_JS_PRODUCT_VARIANTS_EDITOR_ADD_VALUE',1,'2011-06-19 10:54:05',NULL,NULL),(4986,'TXT_JS_PRODUCT_VARIANTS_EDITOR_SAVE_VALUE',1,'2011-06-19 10:54:05',NULL,NULL),(4987,'TXT_JS_PRODUCT_VARIANTS_EDITOR_ID',1,'2011-06-19 10:54:05',NULL,NULL),(4988,'TXT_JS_PRODUCT_VARIANTS_EDITOR_STOCK',1,'2011-06-19 10:54:05',NULL,NULL),(4989,'TXT_JS_PRODUCT_VARIANTS_EDITOR_MODIFIER_TYPE',1,'2011-06-19 10:54:05',NULL,NULL),(4990,'TXT_JS_PRODUCT_VARIANTS_EDITOR_MODIFIER',1,'2011-06-19 10:54:05',NULL,NULL),(4991,'TXT_JS_PRODUCT_VARIANTS_EDITOR_PRICE',1,'2011-06-19 10:54:05',NULL,NULL),(4992,'TXT_JS_PRODUCT_VARIANTS_EDITOR_ADD_VARIANT',1,'2011-06-19 10:54:05',NULL,NULL),(4993,'TXT_JS_PRODUCT_VARIANTS_EDITOR_SAVE_VARIANT',1,'2011-06-19 10:54:05',NULL,NULL),(4994,'TXT_JS_PRODUCT_VARIANTS_EDITOR_AVAILBLE_ATTRIBUTES',1,'2011-06-19 10:54:05',NULL,NULL),(4995,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_BASE_NET_PRICE',1,'2011-06-19 10:54:05',NULL,NULL),(4996,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_BASE_GROSS_PRICE',1,'2011-06-19 10:54:05',NULL,NULL),(4997,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_MODIFIER_TYPE',1,'2011-06-19 10:54:05',NULL,NULL),(4998,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_MODIFIER_VALUE',1,'2011-06-19 10:54:05',NULL,NULL),(4999,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_NET_PRICE',1,'2011-06-19 10:54:05',NULL,NULL),(5000,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_GROSS_PRICE',1,'2011-06-19 10:54:05',NULL,NULL),(5001,'TXT_JS_PRODUCT_VARIANTS_EDITOR_VARIANT_EDITOR_STOCK',1,'2011-06-19 10:54:05',NULL,NULL),(5002,'TXT_JS_PRODUCT_VARIANTS_EDITOR_CHOOSE_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(5003,'TXT_JS_TREE_ADD_ITEM',1,'2011-06-19 10:54:05',NULL,NULL),(5004,'TXT_JS_TREE_DUPLICATE_ITEM',1,'2011-06-19 10:54:05',NULL,NULL),(5005,'TXT_JS_TREE_ADD_SUBITEM',1,'2011-06-19 10:54:05',NULL,NULL),(5006,'TXT_JS_TREE_DELETE_ITEM',1,'2011-06-19 10:54:05',NULL,NULL),(5007,'TXT_JS_TREE_SAVE_ORDER',1,'2011-06-19 10:54:05',NULL,NULL),(5008,'TXT_JS_TREE_RESTORE_ORDER',1,'2011-06-19 10:54:05',NULL,NULL),(5009,'TXT_JS_TREE_EXPAND_ALL',1,'2011-06-19 10:54:05',NULL,NULL),(5010,'TXT_JS_TREE_RETRACT_ALL',1,'2011-06-19 10:54:05',NULL,NULL),(5011,'TXT_JS_TREE_OK',1,'2011-06-19 10:54:05',NULL,NULL),(5012,'TXT_JS_TREE_CANCEL',1,'2011-06-19 10:54:05',NULL,NULL),(5013,'TXT_JS_TREE_FOUND_DUPLICATES',1,'2011-06-19 10:54:05',NULL,NULL),(5014,'TXT_JS_TREE_DUPLICATE_ENTRY_ALL_LEVELS',1,'2011-06-19 10:54:05',NULL,NULL),(5015,'TXT_JS_TREE_FOUND_DUPLICATES_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5016,'TXT_JS_TREE_DUPLICATE_ENTRY_ALL_LEVELS_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5017,'TXT_JS_TREE_DUPLICATE_ENTRY',1,'2011-06-19 10:54:05',NULL,NULL),(5018,'TXT_JS_TREE_DUPLICATE_ENTRY_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5019,'TXT_JS_TREE_DELETE_ITEM_WARNING',1,'2011-06-19 10:54:05',NULL,NULL),(5020,'TXT_JS_TREE_DELETE_ITEM_WARNING_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5021,'TXT_JS_TREE_DESELECT',1,'2011-06-19 10:54:05',NULL,NULL),(5022,'TXT_JS_ATTRIBUTE_EDITOR_CHOOSE_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(5023,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(5024,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_ATTRIBUTE_PROVIDE_NEW_NAME',1,'2011-06-19 10:54:05',NULL,NULL),(5025,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_ATTRIBUTE_ERROR',1,'2011-06-19 10:54:05',NULL,NULL),(5026,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_ATTRIBUTE_ERROR_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5027,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_VALUE',1,'2011-06-19 10:54:05',NULL,NULL),(5028,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_VALUE_PROVIDE_NEW_NAME',1,'2011-06-19 10:54:05',NULL,NULL),(5029,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_VALUE_ERROR',1,'2011-06-19 10:54:05',NULL,NULL),(5030,'TXT_JS_ATTRIBUTE_EDITOR_RENAME_VALUE_ERROR_DESCRIPTION',1,'2011-06-19 10:54:05',NULL,NULL),(5031,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE',1,'2011-06-19 10:54:05',NULL,NULL),(5032,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE',1,'2011-06-19 10:54:05',NULL,NULL),(5033,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_OK',1,'2011-06-19 10:54:05',NULL,NULL),(5034,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_CANCEL',1,'2011-06-19 10:54:06',NULL,NULL),(5035,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_CONFIRM',1,'2011-06-19 10:54:06',NULL,NULL),(5036,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_CONFIRM_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5037,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_ERROR',1,'2011-06-19 10:54:06',NULL,NULL),(5038,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_ATTRIBUTE_FROM_BASE_ERROR_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5039,'TXT_JS_ATTRIBUTE_EDITOR_EDIT_ATTRIBUTE_VALUES',1,'2011-06-19 10:54:06',NULL,NULL),(5040,'TXT_JS_ATTRIBUTE_EDITOR_REMOVE_VALUE',1,'2011-06-19 10:54:06',NULL,NULL),(5041,'TXT_JS_ATTRIBUTE_EDITOR_ADD_ATTRIBUTE',1,'2011-06-19 10:54:06',NULL,NULL),(5042,'TXT_JS_ATTRIBUTE_EDITOR_ADD_VALUE',1,'2011-06-19 10:54:06',NULL,NULL),(5043,'TXT_JS_ATTRIBUTE_EDITOR_ATTRIBUTES',1,'2011-06-19 10:54:06',NULL,NULL),(5044,'TXT_JS_ATTRIBUTE_EDITOR_VALUES',1,'2011-06-19 10:54:06',NULL,NULL),(5045,'TXT_JS_CLIENT_SELECT_ID',1,'2011-06-19 10:54:06',NULL,NULL),(5046,'TXT_JS_CLIENT_SELECT_FIRST_NAME',1,'2011-06-19 10:54:06',NULL,NULL),(5047,'TXT_JS_CLIENT_SELECT_SURNAME',1,'2011-06-19 10:54:06',NULL,NULL),(5048,'TXT_JS_CLIENT_SELECT_EMAIL',1,'2011-06-19 10:54:06',NULL,NULL),(5049,'TXT_JS_CLIENT_SELECT_PHONE',1,'2011-06-19 10:54:06',NULL,NULL),(5050,'TXT_JS_CLIENT_SELECT_SEX',1,'2011-06-19 10:54:06',NULL,NULL),(5051,'TXT_JS_CLIENT_SELECT_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5052,'TXT_JS_CLIENT_SELECT_ADDDATE',1,'2011-06-19 10:54:06',NULL,NULL),(5053,'TXT_JS_CLIENT_SELECT_EDITDATE',1,'2011-06-19 10:54:06',NULL,NULL),(5054,'TXT_JS_CLIENT_SELECT_SELECT_CLIENT',1,'2011-06-19 10:54:06',NULL,NULL),(5055,'TXT_JS_PRODUCT_SELECT_CLOSE_SELECTION',1,'2011-06-19 10:54:06',NULL,NULL),(5056,'TXT_JS_CLIENT_SELECT_CLIENT_NAME',1,'2011-06-19 10:54:06',NULL,NULL),(5057,'TXT_JS_CLIENT_SELECT_CLIENT_EMAIL',1,'2011-06-19 10:54:06',NULL,NULL),(5058,'TXT_JS_CLIENT_SELECT_CLIENT_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5059,'TXT_JS_ADDRESS_DIFFERENT',1,'2011-06-19 10:54:06',NULL,NULL),(5060,'TXT_JS_ADDRESS_COPY_FROM',1,'2011-06-19 10:54:06',NULL,NULL),(5061,'TXT_JS_ADDRESS_UPDATE_DATA',1,'2011-06-19 10:54:06',NULL,NULL),(5062,'TXT_JS_ADDRESS_UPDATE_DATA_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5063,'TXT_JS_PRODUCT_AGGREGATOR_FORM_BLOCKED',1,'2011-06-19 10:54:06',NULL,NULL),(5064,'TXT_JS_PRODUCT_AGGREGATOR_FORM_BLOCKED_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5065,'TXT_JS_PRODUCT_AGGREGATOR_COUNT',1,'2011-06-19 10:54:06',NULL,NULL),(5066,'TXT_JS_PRODUCT_AGGREGATOR_SUM',1,'2011-06-19 10:54:06',NULL,NULL),(5067,'TXT_JS_PRODUCT_AGGREGATOR_SETVALUE_PROHIBITED',1,'2011-06-19 10:54:06',NULL,NULL),(5068,'TXT_JS_PRICE_MODIFIER_VALUE',1,'2011-06-19 10:54:06',NULL,NULL),(5069,'TXT_JS_PRICE_MODIFIER_MODIFIER',1,'2011-06-19 10:54:06',NULL,NULL),(5070,'TXT_JS_RANGE_EDITOR_USE_VAT',1,'2011-06-19 10:54:06',NULL,NULL),(5071,'TXT_JS_RANGE_EDITOR_VAT',1,'2011-06-19 10:54:06',NULL,NULL),(5072,'TXT_JS_RANGE_EDITOR_FROM',1,'2011-06-19 10:54:06',NULL,NULL),(5073,'TXT_JS_RANGE_EDITOR_TO',1,'2011-06-19 10:54:06',NULL,NULL),(5074,'TXT_JS_RANGE_EDITOR_ADD_RANGE',1,'2011-06-19 10:54:06',NULL,NULL),(5075,'TXT_JS_RANGE_EDITOR_REMOVE_RANGE',1,'2011-06-19 10:54:06',NULL,NULL),(5076,'TXT_JS_DATETIME_HOUR',1,'2011-06-19 10:54:06',NULL,NULL),(5077,'TXT_JS_STATIC_LISTING_COLLAPSE',1,'2011-06-19 10:54:06',NULL,NULL),(5078,'TXT_JS_STATIC_LISTING_EXPAND',1,'2011-06-19 10:54:06',NULL,NULL),(5079,'TXT_JS_PREVIEW_TRIGGER_LABEL',1,'2011-06-19 10:54:06',NULL,NULL),(5080,'TXT_JS_COLOUR_SCHEME_PICKER_COLOUR',1,'2011-06-19 10:54:06',NULL,NULL),(5081,'TXT_JS_COLOUR_SCHEME_PICKER_GRADIENT',1,'2011-06-19 10:54:06',NULL,NULL),(5082,'TXT_JS_COLOUR_SCHEME_PICKER_IMAGE',1,'2011-06-19 10:54:06',NULL,NULL),(5083,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_POSITION',1,'2011-06-19 10:54:06',NULL,NULL),(5084,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_REPEAT',1,'2011-06-19 10:54:06',NULL,NULL),(5085,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_REPEAT_NO',1,'2011-06-19 10:54:06',NULL,NULL),(5086,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_REPEAT_X',1,'2011-06-19 10:54:06',NULL,NULL),(5087,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_REPEAT_Y',1,'2011-06-19 10:54:06',NULL,NULL),(5088,'TXT_JS_COLOUR_SCHEME_PICKER_BACKGROUND_REPEAT_XY',1,'2011-06-19 10:54:06',NULL,NULL),(5089,'TXT_JS_LOCALFILE_SELECT',1,'2011-06-19 10:54:06',NULL,NULL),(5090,'TXT_JS_LOCALFILE_PROCESSING_ERROR',1,'2011-06-19 10:54:06',NULL,NULL),(5091,'TXT_JS_LOCALFILE_NONE_SELECTED',1,'2011-06-19 10:54:06',NULL,NULL),(5092,'TXT_JS_LOCALFILE_FULLPATH',1,'2011-06-19 10:54:06',NULL,NULL),(5093,'TXT_JS_LOCALFILE_FILENAME',1,'2011-06-19 10:54:06',NULL,NULL),(5094,'TXT_JS_LOCALFILE_FILESIZE',1,'2011-06-19 10:54:06',NULL,NULL),(5095,'TXT_JS_LOCALFILE_FILEOWNER',1,'2011-06-19 10:54:06',NULL,NULL),(5096,'TXT_JS_LOCALFILE_FILEMTIME',1,'2011-06-19 10:54:06',NULL,NULL),(5097,'TXT_JS_LOCALFILE_DELETE',1,'2011-06-19 10:54:06',NULL,NULL),(5098,'TXT_JS_LOCALFILE_DELETE_WARNING',1,'2011-06-19 10:54:06',NULL,NULL),(5099,'TXT_JS_LOCALFILE_DELETE_WARNING_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5100,'TXT_JS_LOCALFILE_OK',1,'2011-06-19 10:54:06',NULL,NULL),(5101,'TXT_JS_LOCALFILE_CANCEL',1,'2011-06-19 10:54:06',NULL,NULL),(5102,'TXT_JS_BORDER_NONE',1,'2011-06-19 10:54:06',NULL,NULL),(5103,'TXT_JS_BORDER_SIDE_TOP',1,'2011-06-19 10:54:06',NULL,NULL),(5104,'TXT_JS_BORDER_SIDE_RIGHT',1,'2011-06-19 10:54:06',NULL,NULL),(5105,'TXT_JS_BORDER_SIDE_BOTTOM',1,'2011-06-19 10:54:06',NULL,NULL),(5106,'TXT_JS_BORDER_SIDE_LEFT',1,'2011-06-19 10:54:06',NULL,NULL),(5107,'TXT_JS_BORDER_SEPARATE',1,'2011-06-19 10:54:06',NULL,NULL),(5108,'TXT_JS_LAYOUT_BOXES_LIST_SPAN',1,'2011-06-19 10:54:06',NULL,NULL),(5109,'TXT_JS_LAYOUT_BOXES_LIST_COLLAPSED',1,'2011-06-19 10:54:06',NULL,NULL),(5110,'TXT_JS_LAYOUT_BOXES_LIST_ADD',1,'2011-06-19 10:54:06',NULL,NULL),(5111,'TXT_JS_LAYOUT_BOXES_LIST_REMOVE',1,'2011-06-19 10:54:06',NULL,NULL),(5112,'TXT_JS_TECHNICAL_DATA_CHOOSE_SET',1,'2011-06-19 10:54:06',NULL,NULL),(5113,'TXT_JS_TECHNICAL_DATA_SAVE_SET',1,'2011-06-19 10:54:06',NULL,NULL),(5114,'TXT_JS_TECHNICAL_DATA_SAVE_AS_NEW_SET',1,'2011-06-19 10:54:06',NULL,NULL),(5115,'TXT_JS_TECHNICAL_DATA_DELETE_SET',1,'2011-06-19 10:54:06',NULL,NULL),(5116,'TXT_JS_TECHNICAL_DATA_UNSAVED_CHANGES',1,'2011-06-19 10:54:06',NULL,NULL),(5117,'TXT_JS_TECHNICAL_DATA_UNSAVED_CHANGES_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5118,'TXT_JS_TECHNICAL_DATA_UNSAVED_CHANGES_SAVE',1,'2011-06-19 10:54:06',NULL,NULL),(5119,'TXT_JS_TECHNICAL_DATA_UNSAVED_CHANGES_DISCARD',1,'2011-06-19 10:54:06',NULL,NULL),(5120,'TXT_JS_TECHNICAL_DATA_UNSAVED_CHANGES_CANCEL',1,'2011-06-19 10:54:06',NULL,NULL),(5121,'TXT_JS_TECHNICAL_DATA_SAVE_SET_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5122,'TXT_JS_TECHNICAL_DATA_SAVE_SET_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5123,'TXT_JS_TECHNICAL_DATA_ADD_NEW_SET',1,'2011-06-19 10:54:06',NULL,NULL),(5124,'TXT_JS_TECHNICAL_DATA_DELETE_SET_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5125,'TXT_JS_TECHNICAL_DATA_DELETE_SET_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5126,'TXT_JS_TECHNICAL_DATA_DELETE_SET_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5127,'TXT_JS_TECHNICAL_DATA_ADD_NEW_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5128,'TXT_JS_TECHNICAL_DATA_DELETE_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5129,'TXT_JS_TECHNICAL_DATA_SAVE_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5130,'TXT_JS_TECHNICAL_DATA_DELETE_GROUP_PERMANENTLY',1,'2011-06-19 10:54:06',NULL,NULL),(5131,'TXT_JS_TECHNICAL_DATA_SAVE_GROUP_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5132,'TXT_JS_TECHNICAL_DATA_SAVE_GROUP_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5133,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5134,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_GROUP_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5135,'TXT_JS_TECHNICAL_DATA_DELETE_GROUP_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5136,'TXT_JS_TECHNICAL_DATA_DELETE_GROUP_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5137,'TXT_JS_TECHNICAL_DATA_SAVE_ATTRIBUTE',1,'2011-06-19 10:54:06',NULL,NULL),(5138,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_PERMANENTLY',1,'2011-06-19 10:54:06',NULL,NULL),(5139,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE',1,'2011-06-19 10:54:06',NULL,NULL),(5140,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5141,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5142,'TXT_JS_TECHNICAL_DATA_DELETE_ATTRIBUTE_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5143,'TXT_JS_TECHNICAL_DATA_SAVE_ATTRIBUTE_SUCCESS',1,'2011-06-19 10:54:06',NULL,NULL),(5144,'TXT_JS_TECHNICAL_DATA_SAVE_ATTRIBUTE_SUCCESS_DESCRIPTION',1,'2011-06-19 10:54:06',NULL,NULL),(5145,'TXT_JS_TECHNICAL_DATA_EDIT_ATTRIBUTE',1,'2011-06-19 10:54:06',NULL,NULL),(5146,'TXT_JS_TECHNICAL_DATA_EDIT_GROUP',1,'2011-06-19 10:54:06',NULL,NULL),(5147,'TXT_JS_TECHNICAL_DATA_ADD_NEW_ATTRIBUTE',1,'2011-06-19 10:54:07',NULL,NULL),(5148,'TXT_JS_TECHNICAL_DATA_EDIT_MULTILINGUAL_VALUE',1,'2011-06-19 10:54:07',NULL,NULL),(5149,'TXT_JS_TECHNICAL_DATA_VALUE_TYPE_STRING',1,'2011-06-19 10:54:07',NULL,NULL),(5150,'TXT_JS_TECHNICAL_DATA_VALUE_TYPE_MULTILINGUAL_STRING',1,'2011-06-19 10:54:07',NULL,NULL),(5151,'TXT_JS_TECHNICAL_DATA_VALUE_TYPE_TEXT',1,'2011-06-19 10:54:07',NULL,NULL),(5152,'TXT_JS_TECHNICAL_DATA_VALUE_TYPE_IMAGE',1,'2011-06-19 10:54:07',NULL,NULL),(5153,'TXT_JS_TECHNICAL_DATA_VALUE_TYPE_BOOLEAN',1,'2011-06-19 10:54:07',NULL,NULL),(5154,'TXT_JS_PROGRESS_INDICATOR_RUN_COMMENT',1,'2011-06-19 10:54:07',NULL,NULL),(5155,'TXT_JS_PROGRESS_INDICATOR_RUN',1,'2011-06-19 10:54:07',NULL,NULL),(5156,'TXT_JS_PROGRESS_INDICATOR_SUCCESS',1,'2011-06-19 10:54:07',NULL,NULL),(5157,'TXT_JS_HELP',1,'2011-06-19 10:54:07',NULL,NULL),(5158,'TXT_JS_ORDER_EDITOR_HISTORICAL_PRODUCT',1,'2011-06-19 10:54:07',NULL,NULL),(5159,'TXT_JS_CUSTOMIZE',1,'2011-06-19 10:54:07',NULL,NULL),(5160,'TXT_JS_CHOOSE_MODE',1,'2011-06-19 10:54:07',NULL,NULL),(5161,'TXT_JS_MODE_CLICK',1,'2011-06-19 10:54:07',NULL,NULL),(5162,'TXT_JS_MODE_HOVER',1,'2011-06-19 10:54:07',NULL,NULL),(5163,'TXT_JS_MODE_DELAY',1,'2011-06-19 10:54:07',NULL,NULL),(5164,'TXT_JS_MODE_DELAY_MS',1,'2011-06-19 10:54:07',NULL,NULL),(5165,'TXT_JS_SAVE_DESC',1,'2011-06-19 10:54:07',NULL,NULL),(5166,'TXT_JS_RESTORE_DEFAULT',1,'2011-06-19 10:54:07',NULL,NULL),(5167,'TXT_JS_RESTORE_DEFAULT_DESC',1,'2011-06-19 10:54:07',NULL,NULL),(5168,'TXT_JS_CANCEL',1,'2011-06-19 10:54:07',NULL,NULL),(5169,'TXT_JS_OK',1,'2011-06-19 10:54:07',NULL,NULL),(5170,'TXT_JS_ADD',1,'2011-06-19 10:54:07',NULL,NULL),(5171,'TXT_JS_YES',1,'2011-06-19 10:54:07',NULL,NULL),(5172,'TXT_JS_NO',1,'2011-06-19 10:54:07',NULL,NULL),(5173,'TXT_JS_SHOW_LIST',1,'2011-06-19 10:54:07',NULL,NULL),(5174,'TXT_JS_ACCESSKEY',1,'2011-06-19 10:54:07',NULL,NULL),(5175,'TXT_INVOICE_NUMERATION',1,'2011-06-19 12:18:32',NULL,NULL),(5176,'TXT_SALES_PERSON',1,'2011-06-19 13:27:04',NULL,NULL),(5177,'TXT_TOTAL_PAYED',1,'2011-06-19 13:27:20',NULL,NULL),(5178,'TXT_INVOICE_ADD',1,'2011-06-19 13:27:56',NULL,NULL),(5179,'TXT_DAYS',1,'2011-06-19 13:38:12',NULL,NULL),(5180,'TXT_INVOICE_DEFAULT_PAYMENT_DUE',1,'2011-06-19 13:38:33',NULL,NULL),(5181,'TXT_INVOICE_TYPE_PRO',1,'2011-06-19 13:52:54',1,NULL),(5182,'TXT_INVOICE_TYPE_VAT',1,'2011-06-19 13:52:15',NULL,NULL),(5183,'TXT_INVOICE_TYPE_COR',1,'2011-06-19 13:52:23',NULL,NULL),(5184,'TXT_INVOICE_DATE',1,'2011-06-19 13:57:49',NULL,NULL),(5185,'TXT_ADD_INVOICE_PRO',1,'2011-06-19 18:55:08',NULL,NULL),(5186,'TXT_ADD_INVOICE_VAT',1,'2011-06-19 18:55:25',NULL,NULL),(5187,'TXT_AMOUNT_PAYED',1,'2011-06-19 19:05:53',NULL,NULL),(5188,'TXT_AMOUNT_TO_PAY',1,'2011-06-19 19:06:03',NULL,NULL),(5189,'ERR_ALPHANUMERIC_INVALID',1,'2011-06-21 19:06:42',NULL,NULL),(5190,'TXT_PAGINATION',1,'2011-06-22 20:37:21',NULL,NULL),(5191,'TXT_DISABLE_CATEGORY',1,'2011-06-25 12:31:38',NULL,NULL),(5192,'TXT_DISABLE_PRODUCT',1,'2011-06-25 12:31:53',NULL,NULL),(5193,'TXT_ENABLE_PRODUCT',1,'2011-09-08 10:41:45',1,NULL),(5194,'TXT_ENABLE_CATEGORY',1,'2011-09-08 12:17:28',1,NULL),(5195,'TXT_ATTENTION_AFTER_CHANGING_ADMINLINK',1,'2011-06-26 14:02:53',1,NULL),(5196,'TXT_SYSTEM_CONFIGURATION',1,'2011-06-25 22:02:31',NULL,NULL),(5197,'TXT_ADMIN_PANEL_LINK',1,'2011-06-25 22:03:25',1,NULL),(5198,'TXT_FORCE_MOD_REWRITE',1,'2011-06-25 22:03:07',NULL,NULL),(5199,'TXT_FORCE_MOD_REWRITE_HELP',1,'2011-06-25 22:05:07',NULL,NULL),(5200,'TXT_MAIL_SETTINGS',1,'2011-06-25 22:12:01',NULL,NULL),(5201,'TXT_MAIL_TYPE',1,'2011-06-25 22:12:10',NULL,NULL),(5202,'TXT_MAIL_SERVER',1,'2011-06-25 22:12:19',NULL,NULL),(5203,'TXT_MAIL_SERVER_PORT',1,'2011-06-25 22:12:30',NULL,NULL),(5204,'TXT_MAIL_SMTP_SECURE',1,'2011-06-25 22:12:49',NULL,NULL),(5205,'TXT_MAIL_SMTP_AUTH',1,'2011-06-25 22:13:05',NULL,NULL),(5206,'TXT_MAIL_SMTP_USERNAME',1,'2011-06-25 22:13:16',NULL,NULL),(5207,'TXT_MAIL_SMTP_PASSWORD',1,'2011-06-25 22:13:27',NULL,NULL),(5208,'TXT_MAIL_FROMNAME',1,'2011-06-25 22:13:45',NULL,NULL),(5209,'TXT_MAIL_FROMEMAIL',1,'2011-06-25 22:13:54',NULL,NULL),(5210,'TXT_GALLERY_SMALL_IMAGE_SETTINGS',1,'2011-06-26 10:41:11',NULL,NULL),(5211,'TXT_GALLERY_MEDIUM_IMAGE_SETTINGS',1,'2011-06-26 10:41:33',NULL,NULL),(5212,'TXT_GALLERY_NORMAL_IMAGE_SETTINGS',1,'2011-06-26 10:41:58',NULL,NULL),(5213,'TXT_REFRESH_SEO_META',1,'2011-06-26 11:17:35',NULL,NULL),(5214,'TXT_GEKOLAB_CATEGORY_PAYMENT',1,'2011-06-26 13:25:40',NULL,NULL),(5215,'TXT_GEKOLAB_CATEGORY_COMPARE',1,'2011-06-27 16:38:57',NULL,NULL),(5216,'TXT_META_INFORMATION',1,'2011-07-01 13:36:17',NULL,NULL),(5217,'TXT_PRODUCER_BOX',1,'2011-07-11 16:25:04',NULL,NULL),(5218,'TXT_PRODUCER_LIST_BOX',1,'2011-07-11 16:25:27',NULL,NULL),(5219,'TXT_AVAILABLE_PRODUCERS',1,'2011-07-11 17:21:24',NULL,NULL),(5220,'TXT_CURRENCY_DECIMAL_SEPARATOR',1,'2011-07-12 15:26:37',NULL,NULL),(5221,'TXT_CURRENCY_DECIMAL_COUNT',1,'2011-07-12 15:26:54',NULL,NULL),(5222,'TXT_CURRENCY_THOUSAND_SEPARATOR',1,'2011-07-12 15:27:06',NULL,NULL),(5223,'TXT_CURRENCY_POSITIVE_PREFFIX',1,'2011-07-12 15:27:17',NULL,NULL),(5224,'TXT_CURRENCY_POSITIVE_SUFFIX',1,'2011-07-12 15:27:28',NULL,NULL),(5225,'TXT_CURRENCY_NEGATIVE_PREFFIX',1,'2011-07-12 15:27:38',NULL,NULL),(5226,'TXT_CURRENCY_NEGATIVE_SUFFIX',1,'2011-07-12 15:27:47',NULL,NULL),(5227,'TXT_FILTER',1,'2011-07-14 10:01:33',NULL,NULL),(5228,'TXT_FAVICON',1,'2011-07-20 11:03:56',NULL,NULL),(5229,'ERR_CHANNEL_CONNECT',1,'2011-07-24 11:40:33',NULL,NULL),(5230,'TXT_DISCOUNTPRICE',1,'2011-07-25 12:28:32',NULL,NULL),(5231,'TXT_ENABLE_CLIENTGROUP_PROMOTION',1,'2011-07-26 09:13:54',1,NULL),(5232,'TXT_ENABLE_GROUP_PRICE',1,'2011-07-25 13:32:41',NULL,NULL),(5233,'TXT_GROUP_PRICE_FOR',1,'2011-07-25 13:33:59',NULL,NULL),(5234,'TXT_ENABLE_PROMOTION',1,'2011-07-26 09:14:24',1,NULL),(5235,'TXT_STANDARD_SELLPRICE',1,'2011-07-26 09:12:52',NULL,NULL),(5236,'TXT_WISH_LIST',1,'2011-07-27 13:09:44',NULL,NULL),(5237,'TXT_SHOW_CART',1,'2011-07-27 14:52:45',NULL,NULL),(5238,'TXT_PROMOTION_ENDDATE',1,'2011-07-27 15:22:04',NULL,NULL),(5239,'TXT_SHOW_PRODUCER_PHOTO',1,'2011-07-28 09:52:18',NULL,NULL),(5240,'TXT_SHOW_PRODUCER_DESCRIPTION',1,'2011-07-28 09:52:31',NULL,NULL),(5241,'TXT_SPY_ISBOT',1,'2011-07-28 12:20:58',NULL,NULL),(5242,'TXT_SPY_ISMOBILE',1,'2011-07-28 12:21:07',NULL,NULL),(5243,'TXT_SPY_BROWSER',1,'2011-07-28 12:21:48',NULL,NULL),(5244,'TXT_SPY_PLATFORM',1,'2011-07-28 12:21:57',NULL,NULL),(5245,'TXT_SPY_LAST_ADDRESS',1,'2011-07-28 12:57:42',NULL,NULL),(5246,'TXT_API',1,'2011-07-31 22:46:51',NULL,NULL),(5247,'TXT_API_KEY',1,'2011-07-31 22:47:05',NULL,NULL),(5248,'TXT_YOUR_NAME',1,'2011-08-01 00:14:57',NULL,NULL),(5249,'TXT_YOUR_EMAIL',1,'2011-08-01 00:15:15',NULL,NULL),(5250,'TXT_FRIEND_FIRSTNAME',1,'2011-08-01 00:15:36',NULL,NULL),(5251,'TXT_FRIEND_EMAIL',1,'2011-08-01 00:15:47',NULL,NULL),(5252,'ERR_EMPTY_FRIEND_FIRSTNAME',1,'2011-08-01 00:16:38',NULL,NULL),(5253,'ERR_EMPTY_FRIEND_EMAIL',1,'2011-08-01 00:16:55',NULL,NULL),(5254,'TXT_DATAGRID_ROWS_PER_PAGE',1,'2011-08-01 11:57:28',NULL,NULL),(5255,'TXT_DATAGRID_CLICK_ROW_ACTION',1,'2011-08-01 11:57:48',NULL,NULL),(5256,'TXT_DATAGRID_EDIT_ROW',1,'2011-08-01 11:58:10',NULL,NULL),(5257,'TXT_SHOW_CONTEXT_MENU',1,'2011-08-01 11:58:34',NULL,NULL),(5258,'TXT_INTERFACE_SETTINGS',1,'2011-08-01 11:59:02',NULL,NULL),(5259,'TXT_PRICE_BEFORE_PROMOTION',1,'2011-08-01 13:05:01',NULL,NULL),(5260,'TXT_SELECT_CLIENTS',1,'2011-08-03 15:00:07',NULL,NULL),(5261,'TXT_SEND_RECOMMENDATION',1,'2011-08-04 12:41:10',NULL,NULL),(5262,'TXT_RECOMMEND_SHOP_NAME',1,'2011-08-04 12:46:10',NULL,NULL),(5263,'TXT_RECOMMEND_ADDRESS',1,'2011-08-04 12:47:09',NULL,NULL),(5264,'TXT_RECOMMEND_COMMENT',1,'2011-08-04 12:47:53',NULL,NULL),(5265,'TXT_RECOMMENDATION',1,'2011-08-04 12:54:25',NULL,NULL),(5266,'ERR_INVOICE_NIP',1,'2011-08-11 08:06:42',NULL,NULL),(5267,'TXT_PLATNOSCI_CANCELLED',1,'2011-08-11 14:10:31',NULL,NULL),(5268,'TXT_REFRESH_TRANSMAIL',1,'2011-08-11 15:05:00',NULL,NULL),(5269,'TXT_REFRESH_TRANSMAIL_HELP',1,'2011-08-11 15:07:33',NULL,NULL),(5270,'TXT_GEKOLAB_MANUAL_INSTALL',1,'2011-08-12 13:11:11',NULL,NULL),(5271,'TXT_EXCHANGE_TYPE_MIGRATION',1,'2011-08-12 16:47:05',NULL,NULL),(5272,'TXT_EXCHANGE_TYPE_MIGRATION_SETTINGS',1,'2011-08-12 16:58:35',NULL,NULL),(5273,'TXT_MIGRATION_API_URL',1,'2011-08-12 17:00:23',NULL,NULL),(5274,'TXT_MIGRATION_API_KEY',1,'2011-08-12 17:00:38',NULL,NULL),(5276,'ERR_ALLEGRO_POSTAGEMENT_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5277,'ERR_EMPTY_POINTS_AND_COUPON',1,'2011-08-13 09:45:12',NULL,NULL),(5278,'TXT_ABROAD_AGREE',1,'2011-08-13 09:45:12',NULL,NULL),(5279,'TXT_ACTIVE_AUCTIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5280,'TXT_ACTIVE_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5281,'TXT_ADDING_ALLEGRO_OPTIONS_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5282,'TXT_ADDING_ALLEGRO_USER_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5283,'TXT_ADD_ALLEGRO_OPTIONS_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5284,'TXT_ADD_ALLEGRO_POSTAGE_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5285,'TXT_ADD_ALLEGRO_USER_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5286,'TXT_ADD_COUPONS',1,'2011-08-13 09:45:12',NULL,NULL),(5287,'TXT_ADVICE_DISPLAY_ITEM',1,'2011-08-13 09:45:12',NULL,NULL),(5288,'TXT_ALLEGRO',1,'2011-08-13 09:45:12',NULL,NULL),(5289,'TXT_ALLEGRO_AUCTIONS_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5290,'TXT_ALLEGRO_BILLING_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5291,'TXT_ALLEGRO_CATEGORIES',1,'2011-08-13 09:45:12',NULL,NULL),(5292,'TXT_ALLEGRO_CATEGORY',1,'2011-08-13 09:45:12',NULL,NULL),(5293,'TXT_ALLEGRO_CONFIRMATIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5294,'TXT_ALLEGRO_DEFAULT_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5295,'TXT_ALLEGRO_DISPLAY_ITEM',1,'2011-08-13 09:45:12',NULL,NULL),(5296,'TXT_ALLEGRO_EMAIL_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5297,'TXT_ALLEGRO_FAVOURITE_CATEGORIES',1,'2011-08-13 09:45:12',NULL,NULL),(5298,'TXT_ALLEGRO_FAV_CATS_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5299,'TXT_ALLEGRO_INTEGRATION',1,'2011-08-13 09:45:12',NULL,NULL),(5300,'TXT_ALLEGRO_LOGGED',1,'2011-08-13 09:45:12',NULL,NULL),(5301,'TXT_ALLEGRO_LOGIN_PROCESS',1,'2011-08-13 09:45:12',NULL,NULL),(5302,'TXT_ALLEGRO_OPTIONS_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5303,'TXT_ALLEGRO_PAYMENTMETHOD_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5304,'TXT_ALLEGRO_POSTAGE_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5305,'TXT_ALLEGRO_POSTAGE_TEMPLATES_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5306,'TXT_ALLEGRO_POSTAGE_TEMPLATES_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5307,'TXT_ALLEGRO_RELATED_CATEGORIES',1,'2011-08-13 09:45:12',NULL,NULL),(5308,'TXT_ALLEGRO_SETTINGS',1,'2011-08-13 09:45:12',NULL,NULL),(5309,'TXT_ALLEGRO_SYNTAX_TEMPLATE_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5310,'TXT_ALLEGRO_SYNTAX_TEMPLATE_SHOTRT_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5311,'TXT_ALLEGRO_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5312,'TXT_ALLEGRO_USER_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5313,'TXT_ALLEGRO_USER_TEMPLATES',1,'2011-08-13 09:45:12',NULL,NULL),(5314,'TXT_ALLEGRO_USER_TEMPLATES_EDIT',1,'2011-08-13 09:45:12',NULL,NULL),(5315,'TXT_ALLEGRO_VERSION_KEY',1,'2011-08-13 09:45:12',NULL,NULL),(5316,'TXT_ALLEGRO_WEBAPI',1,'2011-08-13 09:45:12',NULL,NULL),(5317,'TXT_ALLEGRO_WWW_SERVICE',1,'2011-08-13 09:45:12',NULL,NULL),(5318,'TXT_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5319,'TXT_AUCTIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5320,'TXT_AUCTION_ABORDER_BEFORE_START',1,'2011-08-13 09:45:12',NULL,NULL),(5321,'TXT_AUCTION_BAD_CATEGORY',1,'2011-08-13 09:45:12',NULL,NULL),(5322,'TXT_AUCTION_CAN_BE_SEND_ABROAD',1,'2011-08-13 09:45:12',NULL,NULL),(5323,'TXT_AUCTION_CHECKED_COURIER_SHIPMENT',1,'2011-08-13 09:45:12',NULL,NULL),(5324,'TXT_AUCTION_CHECKED_PRIOR_SHIPMENT',1,'2011-08-13 09:45:12',NULL,NULL),(5325,'TXT_AUCTION_CHECKED_TRANSP_OPTS',1,'2011-08-13 09:45:12',NULL,NULL),(5326,'TXT_AUCTION_ID',1,'2011-08-13 09:45:12',NULL,NULL),(5327,'TXT_AUCTION_LISTED_IN_OTOMOTO',1,'2011-08-13 09:45:12',NULL,NULL),(5328,'TXT_AUCTION_LISTED_WEBAPI',1,'2011-08-13 09:45:12',NULL,NULL),(5329,'TXT_AUCTION_MAX_AMOUNT',1,'2011-08-13 09:45:12',NULL,NULL),(5330,'TXT_AUCTION_MAX_USER_AMOUNT',1,'2011-08-13 09:45:12',NULL,NULL),(5331,'TXT_AUCTION_NAME',1,'2011-08-13 09:45:12',NULL,NULL),(5332,'TXT_AUCTION_NEW_TRANSPOR_OPTION',1,'2011-08-13 09:45:12',NULL,NULL),(5333,'TXT_AUCTION_OPTION',1,'2011-08-13 09:45:12',NULL,NULL),(5334,'TXT_AUCTION_PERSONAL_ACCEPTANCE_OPTION',1,'2011-08-13 09:45:12',NULL,NULL),(5335,'TXT_AUCTION_SEE_DESC_ABOUT_TRANSP',1,'2011-08-13 09:45:12',NULL,NULL),(5336,'TXT_AUCTION_SELLER_ID',1,'2011-08-13 09:45:12',NULL,NULL),(5337,'TXT_AUCTION_START_FUTURE',1,'2011-08-13 09:45:12',NULL,NULL),(5338,'TXT_AUCTION_WAITING_TO_START',1,'2011-08-13 09:45:12',NULL,NULL),(5339,'TXT_BID_AUCTIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5340,'TXT_BRUTTO',1,'2011-08-13 09:45:12',NULL,NULL),(5341,'TXT_CATEGORY_PAGE_INFO_LINK',1,'2011-08-13 09:45:12',NULL,NULL),(5342,'TXT_CHOOSE_ALLEGRO_OPTIONS_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5343,'TXT_CLIENT_HISTORY_POINTS',1,'2011-08-13 09:45:12',NULL,NULL),(5344,'TXT_CLIENT_POINTS',1,'2011-08-13 09:45:12',NULL,NULL),(5345,'TXT_COLUMN_WIDTH_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5346,'TXT_COMMING_SOON',1,'2011-08-13 09:45:12',NULL,NULL),(5347,'TXT_CONTROLLER_CLIENTPOINTS',1,'2011-08-13 09:45:12',NULL,NULL),(5348,'TXT_CONTROLLER_NOKAUT',1,'2011-08-13 09:45:12',NULL,NULL),(5349,'TXT_COUNTRY_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5350,'TXT_COUNT_BID_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5351,'TXT_COUNT_OF_DISPLAY_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5352,'TXT_COUNT_OF_ITEM',1,'2011-08-13 09:45:12',NULL,NULL),(5353,'TXT_COUNT_OF_PHOTO_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5354,'TXT_COUPONS',1,'2011-08-13 09:45:12',NULL,NULL),(5355,'TXT_COUPONS_CLIENT_QTY',1,'2011-08-13 09:45:12',NULL,NULL),(5356,'TXT_COUPONS_GLOBAL_QTY',1,'2011-08-13 09:45:12',NULL,NULL),(5357,'TXT_COUPONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5358,'TXT_COUPONS_NAME',1,'2011-08-13 09:45:12',NULL,NULL),(5359,'TXT_COUPONS_REGISTRY',1,'2011-08-13 09:45:12',NULL,NULL),(5360,'TXT_COUPONS_SETTINGS',1,'2011-08-13 09:45:12',NULL,NULL),(5361,'TXT_COUPONS_USED_QTY',1,'2011-08-13 09:45:12',NULL,NULL),(5362,'TXT_COUPON_CODE',1,'2011-08-13 09:45:12',NULL,NULL),(5363,'TXT_DELIVELER_CODE_NAME',1,'2011-08-13 09:45:12',NULL,NULL),(5364,'TXT_DISABLE_POINTS_REWARDS_REGISTRY',1,'2011-08-13 09:45:12',NULL,NULL),(5365,'TXT_DISPALY_WITH_BUY_NOW',1,'2011-08-13 09:45:12',NULL,NULL),(5366,'TXT_DISPLAY',1,'2011-08-13 09:45:12',NULL,NULL),(5367,'TXT_DISPLAY_ALL_ITEMS',1,'2011-08-13 09:45:12',NULL,NULL),(5368,'TXT_DISPLAY_NOW',1,'2011-08-13 09:45:12',NULL,NULL),(5369,'TXT_DISPLAY_ONLY',1,'2011-08-13 09:45:12',NULL,NULL),(5370,'TXT_DO_REFUND',1,'2011-08-13 09:45:12',NULL,NULL),(5371,'TXT_DURATION_INFO',1,'2011-08-13 09:45:12',NULL,NULL),(5372,'TXT_EAN_CODE_NAME',1,'2011-08-13 09:45:12',NULL,NULL),(5373,'TXT_EDIT_ALLEGRO_OPTIONS_TEMPLATE',1,'2011-08-13 09:45:12',NULL,NULL),(5374,'TXT_EDIT_ALLEGRO_SETTINGS',1,'2011-08-13 09:45:12',NULL,NULL),(5375,'TXT_EDIT_COUPONS',1,'2011-08-13 09:45:12',NULL,NULL),(5376,'TXT_EDIT_VIEW_POINTSREWARDS',1,'2011-08-13 09:45:12',NULL,NULL),(5377,'TXT_EMPTY_ACTIVE_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5378,'TXT_EMPTY_FUTURE_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5379,'TXT_EMPTY_NOT_SOLD_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5380,'TXT_EMPTY_SOLD_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5381,'TXT_ENABLE_POINTS_REWARDS_REGISTRY',1,'2011-08-13 09:45:12',NULL,NULL),(5382,'TXT_ENDING_TIME_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5383,'TXT_FACEBOOK_DATA',1,'2011-09-09 11:49:31',1,NULL),(5384,'TXT_FEE_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5385,'TXT_FINISHED_AUCTIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5386,'TXT_FUTURE_AUCTIONS',1,'2011-08-13 09:45:12',NULL,NULL),(5387,'TXT_FUTURE_AUCTIONS_LIST',1,'2011-08-13 09:45:12',NULL,NULL),(5388,'TXT_GET_OR_UPDATE_ALLEGRO_CATEGORIES',1,'2011-08-13 09:45:12',NULL,NULL),(5389,'TXT_INVALID_AUCTION_PRICE',1,'2011-08-13 09:45:12',NULL,NULL),(5390,'TXT_ITEM_STATUS_IN_FUTURE_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5391,'TXT_KILLED_BY_ADMIN_AUCTION',1,'2011-08-13 09:45:12',NULL,NULL),(5392,'TXT_MAIN_PAGE_INFO_LINK',1,'2011-08-13 09:45:13',NULL,NULL),(5393,'TXT_MAXIMUM_POINTS_FOR_ORDER',1,'2011-08-13 09:45:13',NULL,NULL),(5394,'TXT_MAXIMUM_POINTS_FOR_ORDER_HELP',1,'2011-08-13 09:45:13',NULL,NULL),(5395,'TXT_NOT_SOLD_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5396,'TXT_NOT_SOLD_AUCTIONS_LIST',1,'2011-08-13 09:45:13',NULL,NULL),(5397,'TXT_NUMBER_OF_NOT_SOLD_ITEM_ENDED_AUCT',1,'2011-08-13 09:45:13',NULL,NULL),(5398,'TXT_NUMBER_OF_SOLD_ITEM_ENDED_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5399,'TXT_OSCOMMERCE_HOST',1,'2011-08-13 09:45:13',NULL,NULL),(5400,'TXT_PAY_AUCTION_COLLECT_ON_DELIVERY',1,'2011-08-13 09:45:13',NULL,NULL),(5401,'TXT_PAY_AUCTION_CREDIT_CARD',1,'2011-08-13 09:45:13',NULL,NULL),(5402,'TXT_PAY_AUCTION_ESCROW',1,'2011-08-13 09:45:13',NULL,NULL),(5403,'TXT_PAY_AUCTION_MONEY_TRANSFER',1,'2011-08-13 09:45:13',NULL,NULL),(5404,'TXT_PAY_AUCTION_PAYU',1,'2011-08-13 09:45:13',NULL,NULL),(5405,'TXT_PAY_AUCTION_SEE_DETAIL_DESC',1,'2011-08-13 09:45:13',NULL,NULL),(5406,'TXT_POINTS_ACCEPTED',1,'2011-08-13 09:45:13',NULL,NULL),(5407,'TXT_POINTS_ADDED',1,'2011-08-13 09:45:13',NULL,NULL),(5408,'TXT_POINTS_BALANCE',1,'2011-08-13 09:45:13',NULL,NULL),(5409,'TXT_POINTS_FOR_OPINION',1,'2011-08-13 09:45:13',NULL,NULL),(5410,'TXT_POINTS_FOR_ORDER',1,'2011-08-13 09:45:13',NULL,NULL),(5411,'TXT_POINTS_FOR_PRODUCT',1,'2011-08-13 09:45:13',NULL,NULL),(5412,'TXT_POINTS_FOR_REDEEM',1,'2011-08-13 09:45:13',NULL,NULL),(5413,'TXT_POINTS_FOR_REFERRAL',1,'2011-08-13 09:45:13',NULL,NULL),(5414,'TXT_POINTS_FOR_REGISTRATION',1,'2011-08-13 09:45:13',NULL,NULL),(5415,'TXT_POINTS_FOR_TAG',1,'2011-08-13 09:45:13',NULL,NULL),(5416,'TXT_POINTS_FOR_TAGS',1,'2011-08-13 09:45:13',NULL,NULL),(5417,'TXT_POINTS_PENDING',1,'2011-08-13 09:45:13',NULL,NULL),(5418,'TXT_POINTS_QTY',1,'2011-08-13 09:45:13',NULL,NULL),(5419,'TXT_POINTS_REWARDS',1,'2011-08-13 09:45:13',NULL,NULL),(5420,'TXT_POINTS_REWARDS_EXCLUDE',1,'2011-08-13 09:45:13',NULL,NULL),(5421,'TXT_POINTS_REWARDS_OPINION',1,'2011-08-13 09:45:13',NULL,NULL),(5422,'TXT_POINTS_REWARDS_OPINION_ENABLE',1,'2011-08-13 09:45:13',NULL,NULL),(5423,'TXT_POINTS_REWARDS_ORDERS',1,'2011-08-13 09:45:13',NULL,NULL),(5424,'TXT_POINTS_REWARDS_ORDERS_ENABLE',1,'2011-08-13 09:45:13',NULL,NULL),(5425,'TXT_POINTS_REWARDS_ORDERS_INCLUDE_SHIPPING',1,'2011-08-13 09:45:13',NULL,NULL),(5426,'TXT_POINTS_REWARDS_ORDERS_INCLUDE_SHIPPING_HELP',1,'2011-08-13 09:45:13',NULL,NULL),(5427,'TXT_POINTS_REWARDS_ORDERS_INCLUDE_TAX',1,'2011-08-13 09:45:13',NULL,NULL),(5428,'TXT_POINTS_REWARDS_ORDERS_INCLUDE_TAX_HELP',1,'2011-08-13 09:45:13',NULL,NULL),(5429,'TXT_POINTS_REWARDS_ORDER_POINTS_VALUE',1,'2011-08-13 09:45:13',NULL,NULL),(5430,'TXT_POINTS_REWARDS_POINTS_VALUE',1,'2011-08-13 09:45:13',NULL,NULL),(5431,'TXT_POINTS_REWARDS_REDEEM',1,'2011-08-13 09:45:13',NULL,NULL),(5432,'TXT_POINTS_REWARDS_REFERRAL',1,'2011-08-13 09:45:13',NULL,NULL),(5433,'TXT_POINTS_REWARDS_REFERRAL_ENABLE',1,'2011-08-13 09:45:13',NULL,NULL),(5434,'TXT_POINTS_REWARDS_REGISTRATION',1,'2011-08-13 09:45:13',NULL,NULL),(5435,'TXT_POINTS_REWARDS_REGISTRATION_ENABLE',1,'2011-08-13 09:45:13',NULL,NULL),(5436,'TXT_POINTS_REWARDS_REGISTRY',1,'2011-08-13 09:45:13',NULL,NULL),(5437,'TXT_POINTS_REWARDS_REGISTRY_BOX',1,'2011-08-13 09:45:13',NULL,NULL),(5438,'TXT_POINTS_REWARDS_SETTINGS',1,'2011-08-13 09:45:13',NULL,NULL),(5439,'TXT_POINTS_REWARDS_TAGS',1,'2011-08-13 09:45:13',NULL,NULL),(5440,'TXT_POINTS_REWARDS_TAGS_ENABLE',1,'2011-08-13 09:45:13',NULL,NULL),(5441,'TXT_POINTS_SPENT',1,'2011-08-13 09:45:13',NULL,NULL),(5442,'TXT_POINTS_STATUS',1,'2011-08-13 09:45:13',NULL,NULL),(5443,'TXT_POINTS_TO_REDEEM',1,'2011-08-13 09:45:13',NULL,NULL),(5444,'TXT_POINTS_TYPE',1,'2011-08-13 09:45:13',NULL,NULL),(5445,'TXT_PRIVATE_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5446,'TXT_PROMOTE_AUCTION_BY_HIGHLIGHT',1,'2011-08-13 09:45:13',NULL,NULL),(5447,'TXT_PROMOTE_AUCTION_BY_THUMBNAIL',1,'2011-08-13 09:45:13',NULL,NULL),(5448,'TXT_PROMOTE_AUCTION_MAIN_PAGe',1,'2011-08-13 09:45:13',NULL,NULL),(5449,'TXT_PROMOTE_AUCTION_ON_CATEGORY_PANE',1,'2011-08-13 09:45:13',NULL,NULL),(5450,'TXT_QTY_INFO',1,'2011-08-13 09:45:13',NULL,NULL),(5451,'TXT_RENEWABLE_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5452,'TXT_SHOP_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5453,'TXT_SOLD_AUCTIONS',1,'2011-08-13 09:45:13',NULL,NULL),(5454,'TXT_SOLD_AUCTIONS_LIST',1,'2011-08-13 09:45:13',NULL,NULL),(5455,'TXT_STARTING_TIME_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5456,'TXT_STARTING_TIME_INFO',1,'2011-08-13 09:45:13',NULL,NULL),(5457,'TXT_TICKET_ADD',1,'2011-08-13 09:45:13',NULL,NULL),(5458,'TXT_URL_WSDL',1,'2011-08-13 09:45:13',NULL,NULL),(5459,'TXT_USER_NOTE_AUCTION',1,'2011-08-13 09:45:13',NULL,NULL),(5460,'TXT_USE_COUPON_FOR_ORDER',1,'2011-08-13 09:45:13',NULL,NULL),(5461,'TXT_USE_POINTS_FOR_ORDER',1,'2011-08-13 09:45:13',NULL,NULL),(5462,'TXT_MIGRATION',1,'2011-08-15 20:27:40',1,NULL),(5463,'TXT_PACKAGE',1,'2011-08-18 18:30:53',NULL,NULL),(5464,'TXT_OFFLINE_MODE',1,'2011-08-19 10:09:53',NULL,NULL),(5465,'TXT_OFFLINE_INSTRUCTION',1,'2011-08-19 10:19:28',1,NULL),(5466,'TXT_PAYMENT_CONFIGURATION_VIEW',1,'2011-08-19 10:29:26',NULL,NULL),(5467,'TXT_PRODUCT_QUOTE',1,'2011-08-19 12:27:51',NULL,NULL),(5468,'TXT_REQUEST_QUOTE',1,'2011-08-19 12:36:53',NULL,NULL),(5469,'TXT_DEFAULT_ORDER_COMMENT',1,'2011-08-21 19:58:24',NULL,NULL),(5470,'TXT_LANGUAGE_DATA',1,'2011-08-23 14:02:17',NULL,NULL),(5471,'TXT_LAYOUT_BOX_LIST',1,'2011-08-23 14:02:28',NULL,NULL),(5472,'TXT_VIEW_ORDER_FILES',1,'2011-08-23 14:04:45',NULL,NULL),(5473,'TXT_VIEW_REPORT',1,'2011-08-23 14:04:57',NULL,NULL),(5474,'TXT_VIEW_THUMB',1,'2011-08-23 14:05:07',NULL,NULL),(5475,'TXT_WRONG_EMAIL',1,'2011-08-23 14:06:02',NULL,NULL),(5476,'ERR_PROBLEM_DURING_AJAX_EXECUTION',1,'2011-08-25 09:32:52',NULL,NULL),(5477,'TXT_DEEPTH',1,'2011-08-25 17:41:06',NULL,NULL),(5478,'TXT_GEKOLAB_SETTINGS',1,'2011-08-26 13:36:32',NULL,NULL),(5479,'TXT_GEKOLAB_KEY',1,'2011-08-26 13:36:46',NULL,NULL),(5480,'TXT_PREV_ORDER',1,'2011-08-31 21:03:33',NULL,NULL),(5481,'TXT_NEXT_ORDER',1,'2011-08-31 21:03:44',NULL,NULL),(5482,'TXT_FACEBOOK_APP_ID',1,'2011-09-09 11:49:52',NULL,NULL),(5483,'TXT_FACEBOOK_SECRET',1,'2011-09-09 11:50:08',NULL,NULL),(5484,'TXT_LOGIN_WITH_FACEBOOK',1,'2011-09-09 17:00:56',NULL,NULL),(5485,'TXT_REGISTER_WITH_FACEBOOK',1,'2011-09-10 09:46:08',NULL,NULL),(5486,'TXT_NET_PRICE',1,'2011-09-12 17:53:54',NULL,NULL),(5487,'TXT_MEASURE_QTY',1,'2011-09-12 20:54:20',NULL,NULL),(5488,'TXT_MEASURE_M2',1,'2011-09-12 20:54:30',NULL,NULL),(5489,'TXT_FACEBOOK_LIKE_BOX',1,'2011-09-14 09:42:47',NULL,NULL),(5490,'TXT_AVAILABLITY',1,'2011-09-18 21:26:16',NULL,NULL),(5491,'TXT_IN_STOCK',1,'2011-09-18 21:27:17',NULL,NULL),(5492,'TXT_OUT_OF_STOCK',1,'2011-09-18 21:27:36',NULL,NULL),(5493,'TXT_WRITE_REVIEW',1,'2011-09-18 22:50:09',NULL,NULL),(5494,'TXT_NEWS_SUMMARY',1,'2011-09-23 11:01:55',NULL,NULL),(5495,'TXT_READ_MORE',1,'2011-09-23 11:07:39',NULL,NULL),(5496,'TXT_REGISTRATION_DISABLED_HELP',1,'2011-10-08 22:37:42',NULL,NULL),(5497,'TXT_REGISTRATION_SETTINGS',1,'2011-10-08 22:43:40',NULL,NULL),(5498,'TXT_ENABLE_REGISTRATION',1,'2011-10-08 22:43:55',NULL,NULL),(5499,'TXT_ENABLE_REGISTRATION_HELP',1,'2011-10-08 22:44:19',NULL,NULL),(5500,'TXT_REGISTRATION_CONFIRM',1,'2011-10-08 22:44:33',NULL,NULL),(5501,'TXT_REGISTRATION_CONFIRM_HELP',1,'2011-10-08 22:45:24',NULL,NULL),(5502,'TXT_FORCE_CLIENT_LOGIN_HELP',1,'2011-10-09 12:46:14',NULL,NULL),(5503,'TXT_ADMIN_NEWS',1,'2011-10-09 15:44:50',NULL,NULL),(5504,'TXT_INVOICES',1,'2011-10-19 10:16:44',NULL,NULL),(5505,'TXT_EXPORT_SELECTED',1,'2011-10-19 10:19:49',NULL,NULL),(5506,'ERR_EMPTY_INVOICES_SELECTED_LIST',1,'2011-10-19 10:20:42',NULL,NULL),(5507,'TXT_PRODUCT_SHIPPING_DATA_HELP',1,'2011-10-21 10:04:48',NULL,NULL),(5508,'TXT_PRODUCT_SHIPPING_DATA',1,'2011-10-21 10:06:29',NULL,NULL),(5509,'TXT_PRODUCT_SHIPPING_COST',1,'2011-10-21 10:07:03',NULL,NULL),(5510,'TXT_SHIPPING_STOCK_SETTINGS',1,'2011-10-21 10:11:55',NULL,NULL),(5511,'TXT_CLEAR_PARENT_CATEGORY',1,'2011-10-24 21:53:35',NULL,NULL),(5512,'TXT_SSL_HELP',1,'2011-10-27 22:58:22',NULL,NULL),(5513,'TXT_ENABLE_SSL',1,'2011-10-27 22:58:37',NULL,NULL),(5514,'TRANS_OPINIONS_QTY',1,'2011-11-05 17:40:49',NULL,NULL),(5515,'TXT_VIEW_GRID',1,'2011-11-07 09:05:34',NULL,NULL),(5516,'TXT_VIEW_LIST',1,'2011-11-07 09:05:44',NULL,NULL),(5517,'TXT_STATIC_ATTRIBUTES',1,'2011-11-21 14:20:30',NULL,NULL),(5518,'ERR_BIND_PRODUCT_ORDER',1,'2011-11-28 13:09:16',NULL,NULL),(5519,'TXT_ACTIVATION_REQUIRED',1,'2011-12-19 11:33:09',NULL,NULL),(5520,'TXT_ACTIVATE_CLIENT_ACCOUNT',1,'2011-12-19 11:35:37',NULL,NULL),(5521,'TXT_NEWS_LIST',1,'2012-02-07 14:05:47',NULL,NULL),(5522,'TXT_SEND_NOTIFICATION',1,'2012-02-07 15:35:29',NULL,NULL),(5523,'TXT_SENDING',1,'2012-02-07 15:35:36',NULL,NULL),(5524,'TXT_ORDER_SETTINGS',1,'2012-02-07 17:16:34',NULL,NULL),(5525,'TXT_FORCE_ORDER_CONFIRM',1,'2012-02-07 17:16:50',NULL,NULL),(5526,'TXT_CONFIRM_ORDER_STATUS_ID',1,'2012-02-07 17:17:08',NULL,NULL),(5527,'TXT_PROGRESS',1,'2012-02-21 17:29:36',NULL,NULL),(5528,'TXT_EXPORT_ORDERS',1,'2012-03-06 21:09:29',NULL,NULL),(5529,'TXT_GUEST_CHECKOUT',1,'2012-04-25 17:40:56',NULL,NULL),(5530,'TXT_GUEST_CHECKOUT_DISABLED',1,'2012-04-25 17:50:25',NULL,NULL),(5531,'TXT_OLD_PRICE',1,'2012-04-25 22:15:00',NULL,NULL),(5532,'TXT_INTEGRATION_WHITELIST',1,'2012-04-26 08:55:48',NULL,NULL),(5533,'TXT_CUSTOM_PRODUCT_LIST_BOX',1,'2012-09-01 15:54:19',NULL,NULL),(5534,'TXT_MAIN_CATEGORIES_BOX',1,'2012-09-01 17:47:15',NULL,NULL),(5535,'TXT_SEND_COUPON',1,'2012-09-01 17:55:01',NULL,NULL);
DROP TABLE IF EXISTS `translationdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translationdata` (
  `idtranslationdata` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `translation` text NOT NULL,
  `translationid` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idtranslationdata`),
  UNIQUE KEY `UNIQUE_translation_translation_translationid_languageid` (`translationid`,`languageid`),
  KEY `FK_translationdata_languageid` (`languageid`),
  KEY `FK_translationdata_translationid` (`translationid`),
  CONSTRAINT `FK_translationdata_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_translationdata_translationid` FOREIGN KEY (`translationid`) REFERENCES `translation` (`idtranslation`)
) ENGINE=InnoDB AUTO_INCREMENT=92801 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `translationdata` (`idtranslationdata`, `translation`, `translationid`, `languageid`) VALUES (2,'Klienci',2,1),(3,'Katalog',3,1),(4,'Zamówienia',4,1),(5,'Użytkownicy',5,1),(6,'Konfiguracja',6,1),(7,'CMS',7,1),(12,'E-mail',12,1),(13,'Imię i nazwisko',13,1),(14,'Nazwisko',14,1),(15,'Imię',16,1),(17,'Treść',20,1),(18,'Tytuł',22,1),(19,'Autor',23,1),(20,'Data publikacji',24,1),(21,'Data zakończenia publikacji',25,1),(22,'Opublikowano',26,1),(23,'Nie opublikowano',28,1),(24,'Dodaj',29,1),(25,'Zaznacz wszystko',30,1),(26,'Usuń',32,1),(27,'Treść',34,1),(28,'Dodawanie nowego newsa',36,1),(29,'Edytuj',38,1),(30,'Dodawanie nowego użytkownika',39,1),(31,'Dane użytkownika',40,1),(32,'Edycja użytkownika',41,1),(33,'Edycja newsa',42,1),(35,'Nazwa grupy',46,1),(36,'Edycja grupy',47,1),(37,'Dodawanie nowej grupy',48,1),(38,'Opis',49,1),(39,'ID',50,1),(40,'Lista użytkowników',51,1),(41,'Grupa',52,1),(42,'Poniedziałek',53,1),(43,'Wtorek',54,1),(44,'Środa',55,1),(45,'Czwartek',56,1),(46,'Piątek',57,1),(47,'Sobota',58,1),(48,'Niedziela',59,1),(49,'Data',60,1),(50,'Godzina',61,1),(51,'Zalogowany użytkownik',62,1),(52,'Strona główna sklepu',63,1),(54,'Uprawnienia grupy',65,1),(60,'Kontroler',73,1),(62,'Opcje',78,1),(63,'Uprawnienia grupy',79,1),(65,'Edycja klienta',81,1),(66,'Nazwa',82,1),(67,'Lista klientów',83,1),(68,'Dodawanie nowego klienta',84,1),(69,'Ulica',85,1),(70,'nr',86,1),(71,'Kod pocztowy',87,1),(72,'Miejscowość',88,1),(73,'Nazwa firmy',89,1),(74,'Regon',90,1),(75,'NIP',91,1),(76,'Telefon',92,1),(78,'Newsletter',94,1),(79,'Podatek VAT',95,1),(80,'Zaloguj się',96,1),(81,'Hasło',97,1),(82,'Przypomnij hasło',98,1),(83,'Grupy klientów',99,1),(84,'Grupy użytkowników',100,1),(85,'Grupy',102,1),(86,'Lista klientów',103,1),(87,'Dane klienta',104,1),(89,'Rabat',106,1),(90,'Moduł wysyłki',107,1),(91,'Moduł płatności',108,1),(92,'Błąd podczas aktualizacji danych klienta',109,1),(93,'Brak danych',110,1),(94,'Nieprawidłowy email',111,1),(95,'Błąd podczas dodawania nowego klienta',112,1),(96,'Błąd podczas aktualizacji grupy klienta',113,1),(97,'Błąd podczas dodawania pliku',114,1),(98,'Błędny typ pliku',115,1),(99,'Plik nie istnieje',116,1),(100,'Błąd podczas dodawania grupy klienta',117,1),(101,'Błąd podczas edycji grupy klienta',118,1),(102,'Błąd podczas próby dodania nowej metody wysyłki',119,1),(103,'Błąd podczas aktualizacji uprawnień',120,1),(104,'Błąd podczas edycji grupy użytkownika',121,1),(105,'Błąd podczas dodawania grupy użytkownika',122,1),(106,'Błąd podczas próby dodania nowej metody płatności',123,1),(107,'Nie znaleziono użytkownika',124,1),(108,'Nie można zmienić loginu użytkownika',125,1),(109,'Nie można zmienić hasła użytkownika',126,1),(110,'Błąd podczas aktualizacji danych użytkownika',127,1),(111,'Błąd podczas dodawania nowego użytkownika',128,1),(112,'Błąd podczas dodawania użytkownika do grupy',129,1),(113,'Błąd aktualizacji daty logowania',130,1),(114,'Błąd w trakcie zapisywania pliku',131,1),(115,'Błąd w trakcie przesyłania pliku',132,1),(116,'Dostępne metody wysłki',136,1),(117,'Dostępne metody płatności',137,1),(118,'Dostępne moduły wysyłki',138,1),(119,'Dostępne moduły płatności',141,1),(120,'Podana grupa nie istnieje',142,1),(121,'Podany klient nie istnieje',143,1),(122,'Podany moduł wysyłki nie istnieje',145,1),(138,'Status',161,1),(139,'Typ',162,1),(142,'--- wybierz ---',165,1),(144,'Cena',170,1),(145,'Produkty',171,1),(146,'Kategorie',173,1),(147,'Dodawanie nowej kategorii',174,1),(148,'Produkt',175,1),(149,'Dodawanie nowego produktu',176,1),(150,'Cena sprzedaży',177,1),(151,'Cena zakupu',178,1),(153,'Krótki opis',181,1),(154,'Przyjazne linki',182,1),(155,'Kod kreskowy',183,1),(156,'Kod EAN',184,1),(157,'Kod dostawcy',185,1),(159,'Dostawa',188,1),(160,'Status',189,1),(161,'Nie można usunąć kategorii ponieważ jest ona powiązana z inna tabelą',190,1),(162,'Zależność',192,1),(163,'Nie można usunąć produktu ponieważ jest on powiązany z inna tabelą',193,1),(164,'Nie można usunąć grupy klientów ponieważ jest ona powiązany z inna tabelą',194,1),(165,'Nie można usunąć klienta ponieważ jest on powiązany z inna tabelą',195,1),(166,'Nie można usunąć grupy administracyjnej ponieważ jest ona powiązany z inna tabelą',196,1),(167,'Nie można usunąć metody płatności ponieważ jest ona powiązany z inna tabelą',197,1),(168,'Nie można usunąć metody wysyłki ponieważ jest ona powiązany z inna tabelą',198,1),(169,'Cechy produktów',199,1),(170,'Nr lokalu',200,1),(171,'Nr budynku',201,1),(172,'Producent',202,1),(173,'Dostawca',203,1),(174,'Lista dostawców',204,1),(175,'Lista producentów',205,1),(176,'Typ adresu',206,1),(177,'Adres strony WWW',207,1),(178,'Błędny login lub hasło',208,1),(179,'Statusy zamówień',209,1),(180,'Wartość',210,1),(181,'Czy skasować rekord',211,1),(182,'Nazwa grupy cech produktów',212,1),(183,'Cechy grupy produktu',214,1),(184,'Dodawanie grupy cech produktów',215,1),(185,'Plik',216,1),(186,'Grupy cech produktu',217,1),(187,'Dodaj nowa metodę płatności',218,1),(188,'Dodaj nowa metodę wysyłki',219,1),(189,'Edycja metody wysyłki',220,1),(190,'Edycja metody płatności',221,1),(191,'Usuń zaznaczone',222,1),(192,'Lista stawek VAT',223,1),(193,'Dodaj nową stawkę VAT',224,1),(194,'Producent',225,1),(195,'Cechy produktów',226,1),(196,'Statusy zamówień',227,1),(197,'Dodaj nowy status zamówień',228,1),(198,'Odznacz wszystko',229,1),(199,'Stan magazynowy',230,1),(200,'Zestawy produktów',231,1),(201,'Lista zestawów produktów',232,1),(202,'Dodaj nowy zestaw produktów',233,1),(203,'Edytuj zestaw produktów',234,1),(204,'Edytuj produkt',235,1),(205,'Edytuj producenta',236,1),(206,'Edytuj kategorie produktów',237,1),(207,'Edytuj dostawcę',238,1),(208,'Dodaj nowego dostawcę',239,1),(209,'Edytuj status zamówienia',240,1),(210,'Edytuj stawkę VAT',241,1),(211,'Edytuj grupę klienta',242,1),(212,'Dodaj nową grupę klienta',243,1),(213,'Edytuj grupę użytkownika',244,1),(214,'Dodaj nową grupę użytkownika',245,1),(217,'Indywidualny',248,1),(218,'Firmowy',249,1),(219,'Korespondencyjny',250,1),(220,'Ilość produktów',251,1),(222,'Nie odnaleziono kontrolera',258,1),(223,'Zdjęcie',259,1),(224,'Zależność',260,1),(225,'Kategoria główna',261,1),(226,'Wyświetl dane',262,1),(227,'Dodatkowe opcje',263,1),(228,'Pola pogrubione  ( * )  są polami wymaganymi',264,1),(229,'Pola pogrubione oznaczone  ( * )  muszą zostać wypełnione',265,1),(231,'Ostatnie logowanie ',268,1),(232,'Lista grup administracyjnych',269,1),(233,'Tabela uprawnień',270,1),(235,'Lista grup klientów',272,1),(236,'Dodawanie nowego producenta',273,1),(237,'Modyfikator cenowy',274,1),(238,'Wybierz produkty',275,1),(239,'Cechy produktów',276,1),(240,'Zduplikuj produkt',277,1),(241,'Zestaw cech',278,1),(242,'Cecha',279,1),(243,'Szczegóły',282,1),(244,'Do koszyka',284,1),(245,'Opinie',285,1),(246,'Język',286,1),(247,'Lista języków',287,1),(248,'Edycja języka',288,1),(249,'Dodaj nowy język',289,1),(250,'Tłumaczenie',290,1),(251,'Dodaj nowe tłumaczenie',291,1),(252,'Lista tłumaczeń',293,1),(253,'Edycja tłumaczenia',295,1),(254,'Adres WWW',296,1),(255,'Sesja',297,1),(256,'Historia logowań',298,1),(257,'Logi administratorów',299,1),(259,'Zdjęcie główne',301,1),(260,'Dane techniczne',302,1),(261,'Opinie',303,1),(262,'Powiązane',304,1),(263,'Zobacz więcej',306,1),(264,'Kategorie',307,1),(265,'Liczba wyników na stronie',308,1),(266,'Przejdź do strony',309,1),(267,'Następna',310,1),(268,'Poprzednia',311,1),(269,'Ostatnio zalogowani',313,1),(270,'Polski',314,1),(271,'Angielski',315,1),(273,'Ostatnio dodane produkty',317,1),(274,'Koszyk',318,1),(275,'Produkty w koszyku',319,1),(276,'Usuń z koszyka',320,1),(278,'Podgląd',322,1),(280,'We wszystkich kategoriach',324,1),(281,'W kategorii',325,1),(282,'Wyszukaj produkt',327,1),(283,'Tu jesteś',328,1),(284,'Najczęściej szukano',329,1),(285,'Tagi',330,1),(286,'Powiązane',332,1),(287,'Podobne',333,1),(288,'Lista produktów',334,1),(291,'Strona główna',337,1),(292,'Wyświetl produkty w kategorii',338,1),(293,'Liczba produktów',339,1),(294,'Data dodania',340,1),(295,'Autor dodania',342,1),(296,'Data modyfikacji',343,1),(297,'Autor modyfikacji',344,1),(298,'Zmień status',345,1),(299,'Liczba wartości cechy',346,1),(300,'Wyświetl produkty tego producenta',347,1),(301,'Strona www',348,1),(302,'Wyświetl produkty posiadające tę stawkę VAT',349,1),(303,'Liczba klientów',350,1),(304,'Wyświetl klientów należących do tej grupy',351,1),(305,'Liczba użytkowników',352,1),(306,'Wyświetl użytkowników należących do tej grupy',353,1),(307,'Adres',354,1),(308,'Kobieta',355,1),(309,'Mężczyzna',356,1),(310,'Kontakt',357,1),(311,'Menu pomocnicze',358,1),(312,'Dodatkowe informacje',359,1),(314,'Regulamin sklepu',361,1),(315,'Dostawca',362,1),(316,'Następny produkt',363,1),(317,'Poprzedni produkt',364,1),(318,'Inne interesujące produkty',365,1),(319,'Nie podano imienia',366,1),(320,'Nie podano nazwiska',367,1),(321,'Nie podano adresu Email',368,1),(322,'Błędny adres Email',369,1),(323,'Nie podano numeru telefonu',370,1),(325,'Nie wybrano grupy',372,1),(326,'Nie podano wysokości rabatu',373,1),(327,'Nie podano numeru budynku',374,1),(328,'Nie podano nazwy ulicy',375,1),(329,'Nie podano nr lokalu',376,1),(330,'Nie podano nazwy firmy',377,1),(331,'Nie podano miejscowości',378,1),(332,'Nie podano kodu pocztowego',379,1),(333,'Nie podano typu adresu',380,1),(334,'Nie podano nazwy grupy atrybutów',381,1),(335,'Nie podano nazwy',382,1),(336,'Nie podano tłumaczenia',383,1),(337,'Nie podano ceny sprzedaży',384,1),(338,'Nie podano wartości',386,1),(339,'Nie wybrano typu',387,1),(340,'Nie wybrano rodzaju priorytetu',388,1),(341,'Nie podano tytułu',389,1),(342,'Zmień hasło',390,1),(343,'Stare hasło',391,1),(344,'Nowe hasło',392,1),(345,'Powtórz hasło',393,1),(346,'Finalizacja',394,1),(347,'Płatność',395,1),(348,'Wstecz',396,1),(349,'Zamawiający',397,1),(350,'Dane do wysyłki',398,1),(351,'Jeśli chcesz zmienić swój domyślny adres wysyłki',399,1),(352,'zmień adres dostawy',400,1),(353,'Uwaga',401,1),(354,'Wybierz sposób dostawy',402,1),(355,'czas dostawy',403,1),(356,'Wartość wraz z dostawą',404,1),(357,'koszt',405,1),(358,'zł',406,1),(359,'Wróć do zakupów',407,1),(360,'Etap zamówienia',408,1),(362,'Cena szt',410,1),(363,'Ilość',411,1),(364,'Wartość',412,1),(365,'Sprawdź wszystkie dane',413,1),(366,'Wypełnij formularz',414,1),(367,'Sumaryczna wartość zakupów',415,1),(368,'Wybierz sposób płatności',416,1),(369,'Realizuj zamówienie',417,1),(370,'Rejestracja',418,1),(371,'Logowanie',419,1),(372,'Nr mieszkania',420,1),(373,'Nr domu',421,1),(374,'Akceptuję',422,1),(375,'Politykę prywatności',423,1),(376,'Regulamin',424,1),(377,'Zarejestruj się',425,1),(378,'Wyślij',426,1),(379,'Wymagane',427,1),(380,'są polami obowiązkowymi.',428,1),(381,'Pola oznaczone',429,1),(382,'Jeśli masz pytania skontaktuj się z nami',430,1),(383,'sklep@sklep.com',431,1),(386,'Dane do wysyłki',434,1),(387,'Nie można usunąć rekordu',435,1),(388,'Podczas próby usunięcia rekordu napotkano następujące konfliktujące zależności:',436,1),(389,'Do usuwanej grupy przypisani są użytkownicy',437,1),(390,'Inne interesujące produkty',438,1),(391,'Możesz zapłacić za paczkę przedpłatą na konto. Będziesz mógł zarejestrować zamówienie i wydrukować fakturę proforma. Poznasz numer naszego konta i numer przedpłaty, na który powołasz się, wysyłając pieniądze.',439,1),(392,'Możesz zapłacić za paczkę bezpośrednio z konta w mBanku, MultiBanku, Inteligo, iPKO, PEKAO SA, BPH, BZWBK, Nordea, ING lub Lukas Banku korzystając z bezpiecznych i szybkich transakcji.',440,1),(393,'Możesz zapłacić za paczkę kartą kredytową. Formularz zamówienia umożliwia podanie numeru karty w bezpieczny sposób za pośrednictwem serwisu platnosci.pl. Koszt takiego zamówienia w przypadku przesyłki zwykłą pocztą to wyłącznie wartość zamówienia.',441,1),(394,'Możesz zamówić paczkę, płacąc przy odbiorze.',442,1),(395,'Menu',443,1),(396,'Status produktu',444,1),(397,'Statusy produktów',445,1),(398,'Konto',446,1),(399,'Logowanie do konta',447,1),(400,'kliknij tutaj',448,1),(401,'Sprzedaż krzyżowa',449,1),(402,'Akcesoria',450,1),(403,'Podobne produkty',451,1),(404,'Lista produktów powiązanych',452,1),(405,'Akcesoria',453,1),(406,'Produkty podobne',454,1),(407,'Zapisz zmiany',455,1),(408,'Waluta',456,1),(409,'Państwo',457,1),(411,'Nazwa sklepu',459,1),(412,'Lista globalnych ustawień',460,1),(413,'Globalne ustawienia',461,1),(414,'Globalne ustawienia- edycja',462,1),(415,'Wybór produktu bazowego',463,1),(416,'Wybór produktów powiązanych',464,1),(417,'Produkt bazowy',465,1),(418,'Globalna konfiguracja',466,1),(419,'Zmiana hasła',467,1),(420,'Panel klienta',468,1),(421,'Ustawienia',469,1),(422,'Twoje konto',470,1),(423,'Poprawne',471,1),(424,'Błędnie wypełnione',472,1),(425,'Zmień',473,1),(426,'potwierdzenie nie jest identyczne jak nowe hasło',474,1),(427,'nowe hasło powinno składać się z co najmniej 6 znaków.',475,1),(428,'Zmień e-mail',476,1),(429,'Zmień dane',477,1),(430,'Potwierdzenie',478,1),(431,'Stary e-mail',479,1),(432,'Powtórz nowy e-mail',480,1),(433,'Zmiana danych adresowych',481,1),(434,'i',482,1),(435,'Prosimy o dwukrotne wprowadzenie nowego adresu, by zminimalizować ryzyko popełnienia błędu. Od momentu zapisania zmiany, wiadomości dotyczące realizowanego zamówienia będą wysyłane na nowy adres email. Nowe hasło powinno składać się z co najmniej 6 znaków',483,1),(437,'Oczekujące',485,1),(438,'Ilość',486,1),(439,'Drukuj',487,1),(440,'Zobacz również historię zrealizowanych zamówień',488,1),(441,'Zamówienie nr',489,1),(442,'zrealizowane dnia',490,1),(443,'złożone dnia',491,1),(444,'Wartość całego zamówienia',492,1),(445,'Wartość wraz z dostawą',493,1),(446,'Sposób płatności',494,1),(447,'Sposób dostawy',495,1),(448,'Wyślij zapytanie',496,1),(449,'Pomoc',497,1),(450,'Warunki dostawy',498,1),(451,'Odpowiedzi na najczęściej zadawane pytania umieściliśmy na stronie',499,1),(452,'Informacje na temat kosztów dostawy znajdują sie na stronie',500,1),(453,'Wyniki wyszukiwania',501,1),(454,'Znaleziono wyników',502,1),(455,'Pokaż',503,1),(456,'Wszystkie',504,1),(457,'od',505,1),(458,'do',506,1),(459,'Metoda wysyłki przypięta do metod płatności',507,1),(460,'Nowy numer',508,1),(461,'Nie podano ceny zakupu',509,1),(463,'Koszt wysyłki',511,1),(464,'Skala opinii',513,1),(465,'Typ opinii',515,1),(466,'Dodawanie nowego typu opinii',516,1),(467,'Do usuwanej grupy przypięci są klienci',517,1),(468,'Stawka VAT została ustalona dla wybranych adresów klientów',518,1),(469,'Stawka VAT została ustalona dla wybranych produktów',519,1),(470,'Nie można wywołać operacji',520,1),(471,'Nie wszystkie konflikty zostały rozstrzygnięte',521,1),(472,'Opinie do produktów',522,1),(473,'Opinie dla produktów',523,1),(474,'Mężczyzna',524,1),(475,'Kobieta',525,1),(477,'Dodatkowe zdjęcie',527,1),(478,'Rodzaj zdjęcia',528,1),(479,'Producent został dowiązany do produktów',529,1),(480,'szt',531,1),(481,'Atrybut',532,1),(482,'W kategorii znajdują się produkty',533,1),(483,'Kategoria zawiera podkategorie',534,1),(484,'Tak',535,1),(485,'Nie',536,1),(486,'Widoczny',537,1),(488,'Kategoria treści',539,1),(489,'Alias',540,1),(490,'Lista statycznych bloków',541,1),(491,'Bloki statyczne',542,1),(492,'Kategorie statyczne',543,1),(493,'Lista kategorii statycznych',544,1),(494,'Dodawanie kategorii statycznych',545,1),(495,'Edycja kategorii statycznych',546,1),(496,'Edycja bloków statycznych',547,1),(497,'Minus od ceny',548,1),(498,'Dodaj do ceny',549,1),(499,'Procent ceny',550,1),(500,'Równe cenie',551,1),(501,'Pole typu adresu jest puste',552,1),(503,'Uzupełnij zawartość szablonu',554,1),(504,'Wybierz kategorie',555,1),(505,'Wybierz język',556,1),(506,'Nie podano loginu',557,1),(507,'Nie podano hasła',558,1),(508,'Powtórz hasło',559,1),(509,'Nie podano potwierdzenia hasła',560,1),(510,'Akceptuję Regulamin i Politykę prywatności',561,1),(511,'Aby korzystać z serwisu należy zaakceptować regulamin oraz politykę prywatności',562,1),(512,'Hasła nie są zgodne',563,1),(513,'Pole VAT jest puste',564,1),(515,'Adresy klienta',566,1),(516,'Zamowienia klienta',567,1),(517,'Domyślny adres wysyłki',568,1),(518,'Adres ten będzie używany jako domyślny do wszelkiego rodzaju wysyłek.',569,1),(519,'Domyślny',570,1),(520,'Ustaw jako domyślny',571,1),(521,'Dodaj nowy',572,1),(522,'Książka adresowa',573,1),(525,'Brak',576,1),(526,'Pola różnią się',577,1),(527,'Zmień hasło',578,1),(529,'Nowości w Sklepie',580,1),(530,'Promocje w Sklepie',581,1),(531,'Lista nowości w sklepie',582,1),(532,'Lista promocji w sklepie',583,1),(533,'Nie masz jeszcze konta w naszym sklepie?',584,1),(534,'Zarejestruj się w systemie, by w pełni cieszyć się możliwościami naszego sklepu',585,1),(535,'Przypomnij',586,1),(536,'Hasło zostanie wysłane na Twój adres',587,1),(537,'Podany adres nie występuje w bazie',588,1),(538,'Adresy klienta',589,1),(539,'Niepoprawny format',590,1),(540,'Koszt wysyłki',591,1),(541,'Nie wpisano wartości',592,1),(542,'Zestawy produktów',593,1),(543,'Rozszerzenie pliku',594,1),(544,'Typ pliku',595,1),(545,'Pliki',596,1),(546,'Fax',597,1),(547,'Edycja kontaktów',598,1),(548,'Dodaj nowy kontakt',599,1),(549,'Skasuj pliki niedowiązane',600,1),(550,'Brak nowości',601,1),(551,'Brak tagów',602,1),(552,'Brak haseł',603,1),(553,'Brak kategorii',604,1),(554,'Brak produktów w Top 10',605,1),(555,'Brak kategorii CMS',606,1),(556,'Brak promocji',607,1),(557,'Brak produktów',608,1),(558,'Brak produktu',609,1),(559,'Zamówienie przyjęte do realizacji.',610,1),(560,'Zamówienie',611,1),(561,'Wystąpił błąd podczas wysyłania zamówienia.',612,1),(562,'Brak produktów o podanej frazie',613,1),(563,'Wybierz',614,1),(564,'rozwiń',615,1),(565,'Anuluj',616,1),(566,'Nie podano numeru NIP',617,1),(567,'Nie podano numeru REGON',618,1),(568,'Produkt standardowy',619,1),(569,'Wysyłka',620,1),(570,'Koszt całkowity',621,1),(571,'Cena podstawowa',622,1),(572,'Status zamówienia',623,1),(573,'Łączna liczba produktów',624,1),(574,'W kodzie pocztowym występują tylko cyfry',625,1),(575,'Miasto',626,1),(576,'Lista zamówień',627,1),(577,' Chcesz jednorazowo wysłać przesyłkę na inny adres? Wypełnij formularz',628,1),(578,'Liczba opinii',629,1),(579,'Pokaż wszystkie',630,1),(580,'Średnia ocena',631,1),(581,'Wpisz komentarz',632,1),(582,'Wyczyść',633,1),(583,'Zamówienia realizujemy w dni robocze.',634,1),(586,'Oceny szczegółowe',637,1),(588,'Dodawanie produktu się powiodło',639,1),(589,'Dodaj kolejny produkt',640,1),(590,'Przejdz do listy produktów',641,1),(591,'Przejdz do edycji nowo dodanego produktu',642,1),(592,'Dodaj opinie',643,1),(593,'Wysyka na adres',644,1),(594,'Brak podkategorii',645,1),(595,'Integracje',646,1),(596,'Status użytkownika...',647,1),(597,'Wpisz poprawny format: xx-xxx',648,1),(598,'Lista porównywarek',649,1),(599,'Edycja zamówienia',650,1),(604,'Formularz jednorazowego wysłania przesyłki na inny adres',656,1),(606,'Nowy e-mail',658,1),(607,'Koszyk jest pusty',659,1),(608,'Wybierz wariant produktu',660,1),(609,'Sortuj według',662,1),(610,'rosnąco',663,1),(611,'malejąco',664,1),(612,'Edycja typu opinii',666,1),(613,'Wybierz VAT',667,1),(614,'Dobrze',668,1),(617,'Aby dodać opinię, musisz się zalogować.',671,1),(618,'Edycja adresu klienta',672,1),(619,'Pola oznaczone ( * ) są wymagane',673,1),(620,'Nie wybrano producenta',674,1),(621,'Wynik wyszukiwania dla frazy',675,1),(623,'Brak produktu w magazynie.',677,1),(624,'Wszyscy producenci',678,1),(625,'Nie możesz złożyć zamówienia',680,1),(626,'Wypełnij dane',681,1),(627,'Załączniki',682,1),(628,'Twój koszyk jest pusty.',683,1),(629,'Kategoria atrybutu',684,1),(630,'Komentarze',685,1),(631,'Wariant standardowy produktu',686,1),(632,'Formularz adresu wysyłki',687,1),(633,'Brak wyróżnienia',688,1),(634,'Produkt powiązany',689,1),(636,'Usuń nowo dodany produkt',691,1),(637,'Potwierdzenie danych',692,1),(638,'Brak zdjęcia',693,1),(639,'Dodaj Tag',694,1),(641,'Zobacz',696,1),(642,'Regulamin dodawania opinii',697,1),(643,'Wpisz Tag',698,1),(644,'Zobacz szczegółowe opinie klienta',699,1),(645,'Brak wyników wyszukiwania dla podanego tagu',700,1),(646,'Lista produktów oznaczonych tagiem',701,1),(647,'Produkt został otagowany następującymi słowami',702,1),(648,'Błąd podczas dodawania tagu.',703,1),(649,'Ranga',704,1),(650,'Statystyki klientów',705,1),(651,'Statystyki produktów',706,1),(652,'Statystyki sprzedaży',707,1),(653,'Wartość zamówień',708,1),(654,'Szukane frazy',709,1),(655,'Usunięcie adresu',711,1),(656,'Czy chcesz usunąć adres',712,1),(657,'Fraza',713,1),(658,'Niezdefiniowana',714,1),(659,'Z produktem powiązane są akcesoria',715,1),(660,'Ilość przekracza stan magazynowy produktu',716,1),(661,'Produkt powiązany w sprzedaży krzyżowej.',717,1),(662,'Do produktu dowiązany jest produkt podobny.',718,1),(663,'Proszę wybrać ilość sztuk produktu.',719,1),(664,'Brak wybranego produktu w magazynie.',720,1),(665,'Proszę wybrać wariant produktu.',721,1),(666,'Koszyk zawiera maksymalny stan magazynowy produktu.',722,1),(667,'Zamówiona ilość przekracza stan magazynowy.',723,1),(668,'Do koszyka dodano maksymalną ilość dostępną w magazynie',724,1),(669,'Nie można zwiększyć ilości.',725,1),(670,'Nieudana próba dodania opinii.',726,1),(671,'Proszę wpisać treść opinii.',727,1),(672,'Proszę wpisać tag.',728,1),(673,'Dodano nowy tag.',729,1),(674,'Tag został przypisany do produktu.',730,1),(675,'Nie możesz przypisać wcześniej dodanego przez Ciebie tagu.',731,1),(676,'Proszę wpisać frazę.',732,1),(677,'Wystąpił problem podczas wyszukiwania frazy.',733,1),(678,'Czy na pewno chcesz usunąć adres?',734,1),(681,'Globalne ustawienia',737,1),(682,'Lista globalnych ustawień',738,1),(687,'Podany e-mail istnieje już w bazie. Wprowadź inny adres. ',743,1),(688,'Adres e-mail został zmieniony. Nastąpi wylogowanie. Proszę zalogować się ponownie na nowy adres e-mail.',744,1),(689,'Dostawca nie istnieje',745,1),(690,'Błąd podczas dodawania produktów do dostawcy',746,1),(691,'Błąd podczas edycji dostawcy',747,1),(692,'Ankieta',748,1),(693,'Lista ankiet',749,1),(694,'Pytanie',750,1),(695,'Odpowiedz',751,1),(696,'Priorytet w ankiecie',752,1),(697,'Ankieta nie istnieje',753,1),(698,'Dodawanie nowej ankiety',754,1),(699,'Brak pytania',755,1),(700,'Dodaj odpowiedzi',756,1),(701,'Brak odpowiedzi',757,1),(702,'Edycja ankiety',758,1),(703,'Brak odpowiedzi',759,1),(704,'Brak priorytetu',760,1),(705,'Błąd podczad dodawania odpowiedzi',761,1),(706,'Błąd podczas dodawania ankiety',762,1),(707,'Bląd podczas edycji ankiety',763,1),(708,'Błąd. Opinia nie może zostać zapisana, ponieważ zawiera niedozwoloną treść.',764,1),(709,'Edycja odpowiedzi',765,1),(710,'Błąd podczas edycji odpowiedzi',766,1),(711,'Dodaj do listy życzeń',767,1),(712,'Ankieta jest pusta',768,1),(713,'Wynik ankiety',769,1),(714,'Lista życzeń',770,1),(715,'Tagi klienta',771,1),(716,'Brak tagów klienta',772,1),(717,'Błąd podczas pobierania listy życzeń z bazy danych',773,1),(718,'Produkt został usunięty z listy życzeń.',774,1),(719,'Który produkt chcesz dodać do listy życzeń?',775,1),(720,'Produkt został dodany do listy życzeń.',776,1),(721,'Twoja lista życzeń zawiera już ten produkt.',777,1),(722,'Błąd! Ilość musi być wartością liczbową!',778,1),(724,'Twoja lista życzeń jest pusta.',780,1),(725,'Numer zamówienia',781,1),(726,'Koszt dostawy',782,1),(727,'Ilość produktów',783,1),(728,'Wszelkie informacje dotyczące zamówienia i pracy sklepu internetowego można uzyskać w godzinach 10.00-18.00 od poniedziałku do piątku oraz w soboty w godzinach 10.00-12.00',784,1),(729,'Gekosale.pl Wita  ',785,1),(730,'Dziękujemy za zakupy w naszym sklepie',786,1),(731,'Witaj!',787,1),(732,'Ostatnie zrealizowane zamówienie',788,1),(733,'Nastąpiła zmiana adresu E-mail',789,1),(734,'Stary Email',790,1),(735,'Nowy Email',791,1),(736,'Nastąpiła zmiana Hasła',792,1),(737,'Nowe Hasło',793,1),(738,'Nastąpiła zmiana adresu',794,1),(739,'Nowy adres',795,1),(740,'Dodano nowy adres',796,1),(741,'Zarejestrowano nowego klienta',797,1),(743,'Login',799,1),(745,'Edycja adresu klienta',801,1),(746,'Dodano nowy adres klienta',802,1),(747,'Edycja hasła klienta',803,1),(748,'Edycja konta e-mail klienta',804,1),(749,'Rejestracja nowego klienta',805,1),(750,'Przypomnienie hasła klienta',806,1),(751,'Nowo wygenerowane hasło',807,1),(752,'Wystąpił błąd podczas głosowania',808,1),(753,'Dziękujemy!',809,1),(754,'Adres dostawy',810,1),(755,'Próba wpisania niedozwolonego kodu w pole formularza.',811,1),(756,'Rejestracja przebiegła pomyślnie. ',812,1),(757,'Na wpisany podczas rejestracji E-mail, została wysłana wiadomość.',813,1),(758,'Potrzebna pomoc ?',814,1),(759,'Dane zostały zmienione. Wiadomość potwierdzająca edycję wysłana została na Twój adres E-mail.',815,1),(760,'Błędnie wpisane stare hasło.',816,1),(761,'Wpisz wartość od',817,1),(762,'Wpisz wartość do',818,1),(763,'Wyłącz użytkownika',820,1),(764,'Włącz użytkownika',821,1),(765,'Faktura',822,1),(766,'Do kategorii przypisane są bloki statyczne',823,1),(767,'Data zakończenia',824,1),(768,'Data rozpoczęcia',825,1),(769,'Nowe zamówienie',826,1),(770,'Lista klientów zapisanych do Newsletter',827,1),(771,'Wyłącz',828,1),(772,'Potwierdzenie wyłączenia',829,1),(773,'Włącz',830,1),(774,'Potwierdzenie włączenia',831,1),(775,'Błąd podczas przejścia z offline na online',832,1),(776,'Błąd podczas przejścia z online na offline ',833,1),(777,'Nie można przejśc w stan offline',834,1),(778,'Nie można przejść w stan online',835,1),(779,'Nie publikuj',836,1),(780,'Publikuj blok',837,1),(781,'Wyłącz publikacje bloku',838,1),(782,'Błąd podczas przejścia ze statusu niewidocznego na widoczny ',839,1),(783,'Błąd podczas przejścia ze statusu widocznego na niewidoczny ',840,1),(784,'Zapisani do Newsletter',841,1),(785,'Temat',842,1),(786,'Dodaj nowy szablon',843,1),(787,'Edycja szablonu',844,1),(788,'Podany Newsletter nie istnieje',845,1),(789,'Błąd podczas edycji Newsletter',846,1),(791,'Nadawca',848,1),(792,'Forma skrócona',849,1),(794,'Dodaj nową jednostkę miar',851,1),(795,'Edytuj jednostkę miar',852,1),(796,'Wpisz skróconą formę',853,1),(797,'Błąd podczas dodawania jednostki miar',854,1),(798,'Podana jednostka nie istnieje',855,1),(800,'Zostałeś zapisany do newslettera, na podany mail został wysłany link aktywacyjny',857,1),(801,'Proszę wpisać adres email na który ma zostać wysłany newsletter',858,1),(802,'Koszt zamówienia',859,1),(803,'Koszt zamówienia wraz z dostawą',860,1),(804,'Ilość produktów',861,1),(805,'Rejestracja do Newsletter',862,1),(806,'Właśnie zostałeś zapisany do Newsletter',863,1),(807,'Statystyki ankiety',864,1),(808,'Błąd podczas duplikacji produktów',865,1),(809,'ID produktu',866,1),(810,'Kopia zamówienia klienta',867,1),(811,'Zapisz',868,1),(812,'Usuń',869,1),(814,'Zostałeś wypisany z Newsletter',871,1),(815,'Błąd podczas wypisania z Newsletter',872,1),(816,'Wartość jest podawana w \" % \"',873,1),(817,'Wymagany format TXT_EXAMPLE',874,1),(818,'XX-XXX',875,1),(819,'xxx@xx.xx',876,1),(820,'Wybierz kategorię nadrzędną',877,1),(821,'Odbiorca',878,1),(822,'Pola obowiązkowe',879,1),(823,'Szybki dostęp',880,1),(824,'Powrót',881,1),(825,'Zacznij od nowa',882,1),(827,'Dane osobowe',884,1),(828,'Opcje dodatkowe',885,1),(829,'Zmiana hasła',886,1),(831,'Hasło powinno składać się z min 5 znaków alfanumerycznych',888,1),(832,'Odpowiedzi',889,1),(833,'Koszt wysyłki - cenowy',890,1),(834,'Aby ustawić stały koszt wysyłki wypełnij jedynie pole \"Koszt wysyłki\"',891,1),(835,'Wpisz koszt wysyłki',892,1),(836,'Nie wybrano statusu',893,1),(837,'Opcje sortowania',894,1),(838,'Błędny format Fax',895,1),(839,'Dodaj status zamówienia',896,1),(840,'Zacznij od nowa',897,1),(841,'Zapisz i dodaj następny',898,1),(842,'Zapisz i zakończ',899,1),(843,'Błąd podczas dodawania statusu zamówienia',900,1),(844,'Błąd podczas edycji statusu zamówienia',901,1),(845,'Podana nazwa już występuje w bazie wpisz inną',902,1),(846,'Edycja statusu zamówienia',903,1),(847,'Lista zamówień',904,1),(848,'Lista produktów',905,1),(849,'Dodaj produkt',906,1),(850,'Lista kategorii',907,1),(851,'Dodaj kategorię',908,1),(852,'Kategoria nadrzędna',909,1),(853,'Edycja kategorii',910,1),(854,'Lista cech produktów',911,1),(855,'Dodaj cechę produktu',912,1),(856,'Podana grupa cech produktów juz istnieje wpisz inną',913,1),(857,'Cechy',914,1),(858,'Grupa cech',915,1),(859,'Cecha',916,1),(860,'Wpisz cechę produktu',917,1),(861,'Edycja cech produktu',918,1),(863,'Dodaj producenta',920,1),(864,'Edycja producenta',921,1),(865,'Błąd podczas edycji producenta',922,1),(867,'Błąd podczas edycji cech produktów',924,1),(868,'Dodaj dostawcę',925,1),(869,'Lista zestawów produktów',926,1),(871,'Lista akcesoriów',928,1),(872,'Dodaj akcesoria',929,1),(873,'Liczba akcesoriów',930,1),(874,'Lista produktów w sprzedaży krzyżowej',931,1),(875,'Dodaj sprzedaż krzyżową',932,1),(876,'Lista produktów podobnych',933,1),(877,'Dodaj produkt podobny',934,1),(878,'Lista produktów promocyjnych',935,1),(879,'Lista klientów',936,1),(880,'Dodaj klienta',937,1),(881,'Adres klienta',938,1),(882,'000-000-000',939,1),(883,'Edycja klienta',940,1),(884,'Wybierz grupę klienta',941,1),(885,'Lista grup klientów',942,1),(886,'Dodaj grupę klienta',943,1),(887,'Edytuj grupę klienta',944,1),(888,'Wpisz nazwę grupy',945,1),(889,'Lista metod wysyłki',946,1),(890,'Dodaj metodę wysyłki',947,1),(891,'Edytuj metodę wysyłki',948,1),(892,'Dodaj stawkę VAT',949,1),(893,'Edytuj stawkę VAT',950,1),(894,'Wpisz poprawną wartość',951,1),(895,'Podana stawka VAT już istnieje',952,1),(896,'Lista klientów zapisanych do Newsletter',953,1),(897,'Dodaj klienta do Newsletter',954,1),(898,'Lista szablonów Newsletter',955,1),(899,'Dodaj szablon Newsletter',956,1),(900,'Tekst',957,1),(901,'Kod HTML',958,1),(902,'Wpisz nadawcę',959,1),(903,'Uzupełnij zawartość szablonu',960,1),(904,'Błąd podczas dodawania szablonu Newsletter',961,1),(905,'Edycja szablonu Newsletter',962,1),(906,'Lista skali opinii',963,1),(907,'Lista typów opinii',964,1),(908,'Dodaj typ opinii',965,1),(909,'Wybierz kategorie, w których ma się pojawić rodzaj opinii',966,1),(910,'Edycja rodzaju opinii',967,1),(911,'Lista opinii dla produktów',968,1),(912,'Lista tagów',969,1),(913,'Lista najczęstszych wyszukiwań',970,1),(914,'Lista modułów integracji',971,1),(915,'Dodaj moduł integracji',972,1),(917,'Podany temat juz występuje w bazie wpisz inny',974,1),(919,'Dodaj blok statyczny',976,1),(920,'Wybierz kategorię',977,1),(921,'Edytuj blok statyczny',978,1),(922,'Dodaj kategorię statyczną',979,1),(923,'Edytuj kategorię statyczną',980,1),(924,'Lista ankiet',981,1),(925,'Dodaj ankietę',982,1),(926,'Podaj wartość liczbową. 1 - pierwsza',983,1),(927,'Edycja ankiety',984,1),(928,'Błędny format mogą występować tylko cyfry',985,1),(929,'Podane pytanie juz występuje w bazie wpisz inne',986,1),(930,'Dodaj użytkownika',987,1),(931,'Edycja użytkownika',988,1),(932,'Dodaj metodę płatności',989,1),(933,'Edytuj metodę płatności',990,1),(936,'Edytuj błąd',993,1),(937,'Lista kontaktów',994,1),(938,'Dodaj kontakt',995,1),(939,'Edycja kontaktu',996,1),(940,'Lista języków',997,1),(941,'Dodaj język',998,1),(942,'Podane tłumaczenie juz występuje w bazie wpisz inne',999,1),(943,'Edycja języka',1000,1),(944,'Lista tłumaczeń',1001,1),(945,'Dodaj tłumaczenie',1002,1),(946,'Edycja tłumaczenia',1006,1),(948,'Lista plików',1008,1),(949,'Lista jednostek miar',1009,1),(950,'Dodaj jednostkę miary',1010,1),(951,'Edycja jednostki miary',1011,1),(952,'Wpisz nazwę sklepu',1012,1),(953,'Nazwa sklepu',1013,1),(954,'Format daty',1014,1),(955,'Edycja globalnych ustawień',1015,1),(956,'Podana nazwa grupy już istnieje wpisz inną',1016,1),(957,'Zablokuj klienta',1017,1),(958,'Aktywuj klienta',1018,1),(959,'Lista grup użytkowników',1023,1),(960,'Dodaj grupę użytkowników',1024,1),(961,'Uprawnienia',1025,1),(962,'Uprawnienia grupy',1026,1),(963,'Edycja grupy użytkowników',1027,1),(964,'Podstawowe dane grupy',1028,1),(965,'Wpisz poprawną wartość liczbową',1029,1),(966,'Stały koszt wysyłki',1030,1),(967,'Wpisz poprawną wartość',1031,1),(968,'Przykład',1032,1),(969,'Kategoria połączona jest z inną kategorią',1033,1),(970,'Pulpit',1034,1),(971,'Sprzedaż',1035,1),(972,'CRM',1036,1),(973,'Integracja',1037,1),(974,'Gekolab',1038,1),(975,'Raporty',1039,1),(977,'Edytuj akcesoria',1041,1),(978,'Wybierz produkty podobne',1042,1),(979,'Błąd podczas dodawania produktów podobnych',1043,1),(980,'Dodaj produkty podobne',1044,1),(981,'Dodaj sprzedaż krzyżową',1045,1),(982,'Produkty sprzedaży krzyżowej',1046,1),(983,'Edytuj produkty podobne',1047,1),(984,'Błąd podczas edycji produktów podobnych',1048,1),(985,'Edycja sprzedaży krzyżowej',1049,1),(986,'Edycja dostawcy',1050,1),(987,'Dodaj zestaw produktów',1051,1),(988,'Błąd podczas dodawania nowego zestawu produktów',1052,1),(990,'Krótki opis',1054,1),(991,'Edycja zestawów produktów',1055,1),(992,'000-000-00-00',1056,1),(993,'Bestsellery',1057,1),(994,'Suma',1058,1),(995,'Najczęściej oglądane',1059,1),(996,'Ilość',1060,1),(997,'Zobacz raport',1061,1),(998,'Adres kontaktowy',1062,1),(1000,'Błąd podczas zmiany aktywności użytkownika',1064,1),(1001,'Błąd podczas zmiany aktywności klienta',1065,1),(1002,'Zdjęcia',1066,1),(1003,'Cechy produktów',1067,1),(1004,'Wpisz nazwę produktu',1068,1),(1005,'Maksymalna długość treści',1069,1),(1006,'Aby dodać produkt ale nie wyświetlać go po stronie sklepu pozostaw pole puste',1070,1),(1007,'Dozwolone typy plików',1071,1),(1008,'Modyfikator rabatowy',1072,1),(1011,'Kod Kreskowy',1075,1),(1012,'Edycja produktu',1076,1),(1013,'Login',1077,1),(1014,'Hasło',1078,1),(1015,'Wpisz hasło',1079,1),(1016,'Wpisz login',1080,1),(1017,'Przypomnij hasło',1081,1),(1018,'Zaloguj',1082,1),(1020,'Błęd logowania',1085,1),(1021,'Liczba dowiązań',1086,1),(1022,'Podczas edycji uzupełnij tylko pole z \"cechą\" ID pozostaw bez zmian',1087,1),(1023,'Edycja zamówienia',1088,1),(1024,'Wysyłka',1089,1),(1025,'Zamówienie',1090,1),(1026,'Warianty produktu',1091,1),(1027,'Kategorie',1092,1),(1028,'Wybierz kategorie, do których mają zostać dołączone cechy',1093,1),(1029,'Produkty',1094,1),(1030,'(kategoria główna)',1095,1),(1032,'Nowa kategoria',1097,1),(1033,'Kolejność kategorii została pomyślnie zmieniona.',1098,1),(1034,'Wróć do Pulpitu',1099,1),(1035,'Duplikacja produktu',1101,1),(1036,'Dostępne statusy produktów',1102,1),(1037,'Statusy',1103,1),(1038,'Informacje o promocji',1104,1),(1039,'Informacje o nowości',1105,1),(1040,'Wystąpił błąd podczas generowania hasła.',1106,1),(1041,'Nazwa porównywarki',1107,1),(1042,'Symbol porównywarki',1108,1),(1043,'Włącz porównywarkę',1109,1),(1044,'Wyłącz porównywarkę',1110,1),(1045,'Lista promocji',1111,1),(1046,'Dodaj promocje',1112,1),(1047,'Dostawcy',1113,1),(1048,'Producenci',1114,1),(1049,'Promocje',1115,1),(1050,'Grupa promocyjna',1116,1),(1051,'Nazwa reguły',1117,1),(1052,'Edycja grupy cech',1118,1),(1053,'Dodaj grupę cech',1119,1),(1054,'Usuń grupę cech',1120,1),(1055,'Grupa cech',1121,1),(1056,'Nazwa grupy cech',1122,1),(1057,'Przypisane do kategorii',1123,1),(1058,'Wybierz grupę cech do edycji',1124,1),(1059,'Grupy cech',1125,1),(1060,'Grupy cech',1126,1),(1061,'Miniaturka',1127,1),(1062,'Ilość sztuk promocyjnych',1128,1),(1063,'Wartość promocji',1129,1),(1064,'Data od',1130,1),(1065,'Data do',1131,1),(1066,'Nowa grupa',1132,1),(1067,'Ilość sztuk',1133,1),(1069,'Modyfikator cenowy',1135,1),(1070,'Proszę wybrać grupę promocyjną',1136,1),(1071,'Promocja',1137,1),(1072,'Reguły promocji',1138,1),(1073,'Błędna ilość sztuk',1139,1),(1074,'Wybierz modyfikator cenowy',1140,1),(1075,'Warianty grupy promocyjnej',1141,1),(1076,'Wybierz warianty grupy promocyjnej',1142,1),(1077,'Wpisz nazwę reguły',1143,1),(1078,'Dla stałej promocji zostaw puste pole',1144,1),(1079,'Edycja promocji',1145,1),(1080,'Dla nieograniczonej ilość sztuk zostaw puste pole',1146,1),(1081,'Podgląd zamówienia',1147,1),(1082,'Ogólne',1148,1),(1083,'Zamówienie nr',1149,1),(1085,'Status zamówienia',1151,1),(1086,'Numer IP',1152,1),(1088,'Nazwa klienta',1154,1),(1089,'Email klienta',1155,1),(1090,'Grupa klienta',1156,1),(1091,'Dane płatnika',1157,1),(1092,'Dane wysyłki',1158,1),(1093,'Imię i nazwisko',1159,1),(1094,'Dane osobowe',1160,1),(1095,'Adres',1161,1),(1096,'Miasto',1162,1),(1097,'Państwo',1163,1),(1098,'Nr telefonu',1164,1),(1099,'Ulica / nr',1165,1),(1100,'Kod pocztowy miasto ',1166,1),(1101,'Państwo',1167,1),(1102,'Telefon',1168,1),(1104,'Metoda wysyłki',1170,1),(1105,'Zamówione produkty',1171,1),(1106,'Status i komentarze',1172,1),(1107,'Podsumowanie',1173,1),(1108,'Suma netto',1174,1),(1109,'Suma',1175,1),(1110,'Wysyłka',1176,1),(1111,'Podatek',1177,1),(1112,'Niepowiadomiony',1178,1),(1113,'Powiadomiony',1179,1),(1114,'Autor',1180,1),(1115,'Produkt',1181,1),(1116,'SKU',1182,1),(1117,'Cena netto',1183,1),(1118,'Ilość',1184,1),(1119,'Podsuma netto',1185,1),(1120,'Stawka VAT',1186,1),(1121,'Podatek',1187,1),(1122,'Podsuma brutto',1188,1),(1125,'Data zamówienia',1192,1),(1126,'Status zamówienia',1193,1),(1127,'Numer IP',1194,1),(1128,'Informacje o Kliencie',1195,1),(1129,'Nazwa klienta',1196,1),(1130,'Email klienta',1197,1),(1137,'Dane osobowe',1206,1),(1138,'Ulica / nr',1207,1),(1139,'Kod pocztowy miasto ',1208,1),(1140,'Państwo',1209,1),(1141,'Telefon',1210,1),(1142,'Metoda płatności',1211,1),(1144,'Zamówione produkty',1213,1),(1145,'Status',1214,1),(1146,'Komentarz',1215,1),(1147,'Poinformuj Klienta',1216,1),(1148,'Aktualizuj status',1217,1),(1149,'Przebieg zamówienia',1218,1),(1150,'Dokumenty przewozowe',1219,1),(1151,'Faktury',1220,1),(1152,'Brak zmian do wyświetlenia',1221,1),(1154,'Integracja z Ceneo.pl',1223,1),(1155,'Integracja z Nokaut.pl',1224,1),(1156,'Integracja z Skapiec.pl',1225,1),(1157,'Wybierz odpowiadającą kategorię w porównywarce',1226,1),(1158,'Status',1227,1),(1159,'Sposób dostawy',1228,1),(1160,'Sposób płatności',1229,1),(1161,'Wartość całkowita',1230,1),(1162,'Wartość podstawowa',1231,1),(1163,'Zamówione produkty',1232,1),(1164,'Dane Klienta',1233,1),(1165,'Dane płatnika',1234,1),(1166,'Dane do wysyłki',1235,1),(1167,'Sposób płatności',1236,1),(1168,'Sposób dostawy',1237,1),(1169,'Komentarz',1238,1),(1170,'Zamiana statusu zamówienia nr: ',1239,1),(1171,'(dowolny wariant)',1240,1),(1172,'(wybierz wariant)',1241,1),(1173,'Podany Email juz istnieje',1242,1),(1174,'Wystąpił błąd',1243,1),(1175,'Nie masz uprawnień do tego kontrolera',1244,1),(1176,'Konto bankowe',1245,1),(1177,'Nazwa banku',1246,1),(1178,'Numer konta',1247,1),(1179,'Aktywuj nowość',1248,1),(1180,'Dezaktywuj nowość',1249,1),(1181,'Nowy użytkownik',1250,1),(1182,'Porównywarki',1251,1),(1183,'Stan magazynowy bez wariantów produktu',1252,1),(1184,'Edycja hasła użytkownika',1253,1),(1185,'Czy na pewno chcesz usunąć grupę cech?',1254,1),(1186,'Czy chcesz usunąć wszystko ?',1255,1),(1187,'netto',1256,1),(1188,'brutto',1257,1),(1189,'Aktywność Klienta',1258,1),(1190,'Dodaj do zamówienia',1259,1),(1191,'Aktualnie w koszyku',1260,1),(1192,'Na liście życzeń',1261,1),(1193,'Informacje o porównywarce',1262,1),(1194,'Dodaj zamówienie',1263,1),(1195,'Całkowoity koszt zamówienia',1264,1),(1196,'Zapłać',1265,1),(1197,'Zdjęcie',1266,1),(1198,'Dodaj produkty',1267,1),(1199,'Nazwa modelu płatności',1268,1),(1200,'Nie podano nazwy modelu płatności',1269,1),(1201,'Konfiguracja modułu',1270,1),(1202,'Dane firmowe',1271,1),(1203,'Nazwa firmy',1272,1),(1204,'Skrócona nazwa firmy',1273,1),(1205,'KRS',1274,1),(1206,'Hasło powinno składać się z minimum 6 znaków',1275,1),(1207,'Godz. otwarcia',1276,1),(1208,'Wybierz dział',1277,1),(1209,'Dział',1278,1),(1210,'jest do Państwa dyspozycji w godzinach',1279,1),(1211,'Odbiorca',1280,1),(1212,'Twoje zapytanie zostało wysłane',1281,1),(1213,'Wysłano',1282,1),(1214,'bez wariantów produktu',1283,1),(1215,'Wybierz produkt bazowy',1284,1),(1216,'E-mail',1285,1),(1217,'Wpisz e-mail',1286,1),(1218,'Podany adres e-mail nie istnieje',1287,1),(1219,'Plik przesłano',1288,1),(1220,'Za duży plik',1289,1),(1221,'Przekroczono dopuszczalny limit wielkości pliku ustalony w formularzu',1290,1),(1222,'Plik nie został przesłany poprawnie (przerwane połączenie)',1291,1),(1223,'Plik nie został przesłany',1292,1),(1224,'Nie znaleziono katalogu tymczasowego',1293,1),(1225,'Nie można zapisać pliku na serwerze (brak uprawnień)',1294,1),(1226,'Przesyłanie pliku przerwane przez rozszerzenie',1295,1),(1227,'Dane teleadresowe',1296,1),(1228,'Maksymalny rozmiar wynosi',1297,1),(1229,'Województwo',1298,1),(1230,'Sprzedawca',1299,1),(1231,'Stan magazynowy liczony jest na podstawie stanów magazynowych produktów w zestawie. Jeśli jeden zostanie wyczerpany zestaw zostanie wyłączony.',1300,1),(1232,'Szablony wiadomości',1301,1),(1233,'Wyślij newsletter',1302,1),(1234,'Aktualizuj',1303,1),(1235,'Listy odbiorców',1304,1),(1236,'Brak potwierdzenia zamówienia',1305,1),(1237,'Ostatnio zamówione',1306,1),(1238,'Przelew bankowy',1307,1),(1239,'Dane do przelewu bankowego',1308,1),(1240,'Zamówienie czeka na płatność przelewem bankowym. ',1309,1),(1241,'Nazwa banku',1310,1),(1242,'Tytuł przelewu bankowego (imię, nazwisko, nr zamówienia)',1311,1),(1243,'Łączna cena produktów',1312,1),(1244,'Dopuszczalne znaki to cyfry i znaki spacji',1313,1),(1245,'Uwzględnij VAT',1314,1),(1246,'Tabela',1316,1),(1247,'Wprowadź nazwę dla nowej kategorii',1317,1),(1248,'Wprowadź nazwę dla nowej grupy cech',1318,1),(1249,'Cena sprzedaży brutto',1319,1),(1250,'Cena zakupu brutto',1320,1),(1251,'Przedziały cenowe',1321,1),(1252,'Płatność za pobraniem',1322,1),(1254,'Przesyłka pobraniowa zostanie wysłana na adres',1324,1),(1255,'Płatność przy odbiorze',1325,1),(1256,'Wybrano płatność przy odbiorze',1326,1),(1257,'Adres odbioru zamówienia',1327,1),(1258,'Kliknij w link, aby potwierdzić zamówienie.',1328,1),(1259,'Zamówienie zostało potwierdzone.',1329,1),(1260,'Wersja',1330,1),(1261,'Kanał',1331,1),(1262,'Nieprawidłowy link',1332,1),(1263,'Potwierdzenie zamówienia',1333,1),(1264,'Wystąpił problem podczas potwierdzania zamówienia.',1334,1),(1265,'Proszę odświeżyć stronę lub skontaktować się z administratorem sklepu',1335,1),(1266,'Dostępna wersja',1336,1),(1267,'Zainstalowana wersja',1337,1),(1268,'Skąpiec',1338,1),(1269,'Wygenerowany PDF',1339,1),(1270,'Punkty kontrolne',1340,1),(1271,'Instaluj',1341,1),(1272,'Odinstaluj',1342,1),(1273,'Strona',1343,1),(1274,'z',1344,1),(1275,'Faktura wygenerowana przez Gekosale.pl. Jedyne bezpłatne oparte na licencji Open Source polskie oprogramowanie sklepu.',1345,1),(1280,'Klucz Allegro WebAPI',1350,1),(1283,'Listy odbiorców',1353,1),(1284,'Dodaj listę odbiorców',1354,1),(1285,'Edytuj listę odbiorców',1355,1),(1287,'Klucz wersji Allegro WebAPI jest zmienny.',1357,1),(1288,'Klienci zapisani do newslettera',1358,1),(1289,'Stwórz nową listę',1359,1),(1290,'Wprowadz adresy Email. Oddziel je od siebie za pomocą \" ; \"',1360,1),(1291,'Lista adresów Email',1361,1),(1292,'Historia logowań klientów',1362,1),(1293,'Logi klientów',1363,1),(1294,'ID klienta',1364,1),(1295,'gr',1365,1),(1296,' dla zamówienia nr ',1366,1),(1298,'Data sprzedaży',1368,1),(1301,'Nabywca',1371,1),(1302,'Lp.',1372,1),(1303,'J.m.',1373,1),(1304,'Cena jedn. netto',1374,1),(1305,'Kwota netto',1375,1),(1306,'Kwota VAT',1376,1),(1307,'Wartość brutto',1377,1),(1309,'Słownie',1379,1),(1310,'W tym',1380,1),(1311,'Imię i nazwisko osoby uprawnionej',1381,1),(1312,'do wystawiania faktur',1382,1),(1313,'oraz',1383,1),(1314,'pieczęć',1384,1),(1315,'do odbioru faktur',1385,1),(1316,'KOPIA',1386,1),(1317,'ORYGINAŁ',1387,1),(1318,'DUPLIKAT',1388,1),(1319,'Logo do faktury',1389,1),(1320,'Wyświetl nazwę sklepu i tag',1390,1),(1321,'Nazwa tagu do faktury',1391,1),(1322,'Wyświetl nazwę sklepu',1392,1),(1323,'Aby wydrukować fakturę VAT z firmowym logo, należy wybrać obraz.',1393,1),(1324,'Logo do faktury',1394,1),(1328,'Najczęściej oglądano',1398,1),(1329,'Ostatnio oglądane',1399,1),(1330,'Najczęściej oglądano',1400,1),(1331,'Dodaj skalę opinii',1401,1),(1332,'Skala opinii',1402,1),(1333,'UWAGA ! Kolejność musi zostać zachowana - rosnąco. Np: źle, normalnie, dobrze',1403,1),(1334,'Edytuj skalę opinii',1404,1),(1335,'Skala opinii jest dowiązana do następujących typów opinii',1405,1),(1336,'Blok statyczny jest powiązany z następującymi kategoriami',1406,1),(1337,'Kategoria jest powiązana z następującymi blokami statycznymi',1407,1),(1338,'Wpisz treść w formie HTML',1408,1),(1339,'Wpisz treść w formie Tekstowej',1409,1),(1340,'Z tym produktem kupiono również',1410,1),(1341,'Kupiono również',1411,1),(1342,'Reguła',1412,1),(1343,'Informacje podstawowe',1413,1),(1344,'Nazwa reguły',1414,1),(1345,'Nieaktywna',1415,1),(1346,'Obowiązuje od',1416,1),(1347,'Obowiązuje do',1417,1),(1348,'Akcja',1418,1),(1349,'Warunki',1419,1),(1350,'Komentarz do zamówienia',1420,1),(1351,'Klient jeszcze nie skomentował tego zamówienia',1421,1),(1352,'Dodaj komentarz',1422,1),(1353,'Wstrzymaj dalsze promocje',1423,1),(1354,'Naliczaj dalsze promocje',1424,1),(1355,'Sposób użycia',1425,1),(1356,'Klienci on-line',1426,1),(1357,'Wybierz sposób użycia.',1427,1),(1358,'Dziedziczenie promocji',1428,1),(1360,'Zmniejsz o procent',1430,1),(1361,'Zwiększ o procent',1431,1),(1362,'Zwiększ o konkretną kwotę',1432,1),(1363,'Zmniejsz o konkretną kwotę',1433,1),(1364,'Podaj konkretną wartość',1434,1),(1365,'Niepoprawna wartość w polu priorytet.',1435,1),(1366,'Domyślny adres',1436,1),(1369,'Deinstalacja paczki nie powiodła się.',1439,1),(1370,'Instalacja paczki nie powiodła się.',1440,1),(1371,'Proszę czekać...',1441,1),(1372,'Trwa instalacja paczki',1442,1),(1373,'Wystąpił błąd',1443,1),(1374,'Instalacja',1444,1),(1375,'Aktualizacja',1445,1),(1376,'Trwa aktualizacja paczki',1446,1),(1377,'z wersji',1447,1),(1378,'do wersji',1448,1),(1379,'Trwa deinstalacja paczki',1449,1),(1380,'Deinstalacja',1450,1),(1381,'Akcja zakończona pomyślnie',1451,1),(1382,'Jeśli strona nie przeładuje się automatycznie, powinieneś zrobić to ręcznie przed kontynuacją pracy.',1452,1),(1386,'Role użytkowników',1456,1),(1387,'Grupy klientów',1457,1),(1388,'Opis kuriera',1458,1),(1389,'Logo',1459,1),(1391,'Moduł płatności powiązany jest ze statusem zamówienia',1461,1),(1397,'Email potwierdzający',1467,1),(1398,'Formularz potwierdzający',1468,1),(1403,'Format tytułu',1473,1),(1404,'Tytuł jest ograniczony do 50 znaków. Niektóre litery/symbole są liczone jako więcej niż 1 znak.',1474,1),(1405,'Stan',1475,1),(1406,'Nowy',1476,1),(1407,'Używany',1477,1),(1408,'Wstaw opis produktu ze sklepu',1478,1),(1409,'Wstaw opis produktu ręcznie',1479,1),(1411,'Użyj zdjęcia głównego, jako miniaturki',1481,1),(1412,'Wybierz inne zdjęcie',1482,1),(1414,'Format sprzedaży',1484,1),(1416,'Kup Teraz!',1486,1),(1417,'Grupy statusów zamówień',1487,1),(1419,'Dodaj grupę statusów',1489,1),(1420,'Edytuj grupę statusów',1490,1),(1421,'Faktura VAT',1491,1),(1422,'Faktura PROFORMA',1492,1),(1423,'Typ faktury',1493,1),(1424,'Wybierz rodzaj faktury',1494,1),(1425,'Rodzaje faktur',1495,1),(1426,'Dodaj rodzaj faktury',1496,1),(1427,'Usuwalny',1497,1),(1428,'Edytuj rodzaj faktury',1498,1),(1429,'Lista rodzajów faktur',1499,1),(1430,'Dodaj rodzaj faktury',1500,1),(1431,'Wybierz grupę statusów zamówień',1501,1),(1432,'Błąd podczas dodawania nowego typu faktury.',1502,1),(1433,'Ten rodzaj faktury nie może zostać usunięty.',1503,1),(1434,'Usuń rodzaj faktury',1504,1),(1435,'Przykładowy rodzaj faktury',1505,1),(1436,'Usunąć ten rodzaj faktury?',1506,1),(1437,'Wpisz wagę towaru',1507,1),(1438,'Kg',1508,1),(1439,'Koszt wysyłki - wagowy',1509,1),(1440,'E-mail nadawcy',1510,1),(1441,'Wpisz e-mail nadawcy',1511,1),(1443,'Wpisz nazwę nadawcy',1513,1),(1445,'Wpisz treść powitania',1515,1),(1447,'Wpisz treść stopki',1517,1),(1449,'Wybierz akcesoria',1040,1),(1482,'Domyślna nazwa sklepu',1519,1),(1501,'Treść powitania',1514,1),(1505,'Treść stopki',1516,1),(1507,'Nazwa nadawcy',1512,1),(1511,'Cena wywoławcza aukcji',1522,1),(1513,'Cena za sztukę',1523,1),(1515,'Cena wywoławcza',1524,1),(1517,'Cena \'Kup teraz\'',1525,1),(1519,'Dostępna liczba sztuk',1526,1),(1525,'Identyfikator użytkownika oferującego najwyższą cenę',1529,1),(1537,'Pogrubiony tytuł',1535,1),(1547,'Kupujacy płaci za przesyłkę',1540,1),(1595,'Liczba przedmiotów, które nie zostały sprzedane, ale mogłyby być (w otwartej aukcji)',1564,1),(1597,'Liczba przedmiotów, które zostały kupione',1565,1),(1599,'Liczba ofert na aukcji',1566,1),(1605,'Login użytkownika z najwyższą ofertą',1569,1),(1607,'Liczba punktów użytkownika z najwyższą ofertą',1570,1),(1609,'Kraj użytkownika z najwyższą ofertą',1571,1),(1617,'Ilość osób obserwujących aukcję',1575,1),(1619,'Spawdź, czy aukcja posiada cenę \'Kup Teraz\'',1576,1),(1625,'Ile minu przed zakończeniem aukcji chcesz dostać raport',1579,1),(1657,'Prowizja ',1589,1),(1722,'Poprzednia kategoria',1590,1),(1724,'Następna kategoria',1591,1),(1742,'Minus',1592,1),(1940,'Kategoria sklepu',1604,1),(1945,'aktywna do',1606,1),(1954,'Wybierz sklep',1609,1),(1959,'Domyśny e-mail sklepu',1611,1),(1961,'Zmień e-mail lub nazwę nadawcy',1612,1),(1963,'Aby zmienić e-mail lub nazwę nadawcy, należy edytować globalne ustawienia Allegro sklepu.',1613,1),(1969,'Tekst wyświetlający się na górze wiadomości e-mail, wysyłanej podczas potwierdzenia zakupów na Allegro.',1616,1),(1971,'Tekst wyświetlający się na dole wiadomości e-mail, wysyłanej podczas potwierdzenia zakupów na Allegro.',1617,1),(1973,'Tytuł formularza',1618,1),(1975,'Wpisz tytuł formularza',1619,1),(1977,'Adres linku pomocy',1620,1),(1979,'Treść boksu rejestracji',1621,1),(1981,'Wpisz treść boksu rejestracji',1622,1),(1983,'Treść boksu pojawiająca się podczas wyświetlania formularza rejestracji klienta',1623,1),(1985,'Po potwierdzeniu zamówienia wyświetl produkty',1624,1),(1987,'Promocyjne',1625,1),(1993,'Linki do sklepu',1628,1),(1995,'Zachęta do rejestracji',1629,1),(1997,'Tekst w boksie produktów',1630,1),(1999,'Tekst ten pojawi się w boksie, którego rodzaj sam wybierzesz. Sam boks pojawi się dopiero po potwierdzeniu.',1631,1),(2001,'Treść lewego boksu',1632,1),(2003,'Treść prawego boksu',1633,1),(2005,'Tekst wyświetlający się na formularzu. W tym miejscu możesz wstawiać najważniejsze informacje dotyczące np. zamówień Allegro',1634,1),(2010,'Tekst po prawidłowym potwierdzeniu zamówienia',1635,1),(2018,'Tekst, który pojawi się zaraz po potwierdzeniu zamówienia',1636,1),(2021,'Tekst błędnego potwierdzenia zamówienia',1637,1),(2043,'Tekst ten pojawi się wyłącznie wtedy, gdy system napotka problem z automatycznym potwierdzeniem zamówienia w sklepie',1638,1),(2110,'Szerokość',1639,1),(2112,'Wysokość',1640,1),(2116,'Ustawienia galerii',1642,1),(2120,'Rozmiar zdjęcia normalnego',1644,1),(2125,'Miniaturka',1643,1),(2126,'Sklepy',1645,1),(2128,'Tytuł',1646,1),(2130,'Lista szablonów użytkownika Allegro',1647,1),(2132,'Wpisz tytuł',1648,1),(2153,'Darmowe opcje przesyłki',1653,1),(2190,'Wybierz sklep',1654,1),(2192,'Szybkie menu',1655,1),(2195,'Wykres',1656,1),(2200,'Sprzedaż',1657,1),(2202,'Dzisiaj',1658,1),(2204,'Bieżący miesiąc',1659,1),(2208,'Ostatnie zamówienia',1661,1),(2213,'Odbiór osobisty',1662,1),(2230,'Przesyłka elektroniczna (e-mail)',1663,1),(2232,'Nowi klienci',1664,1),(2244,'Paczka pocztowa priorytetowa',1667,1),(2246,'List priorytetowy',1668,1),(2248,'Przesyłka pobraniowa',1669,1),(2250,'Średnia dzienna',1670,1),(2252,'Łącznie',1672,1),(2256,'w',1673,1),(2258,'Przesyłka pobraniowa priorytetowa',1674,1),(2260,'List polecony priorytetowy',1675,1),(2262,'Przesyłka kurierska',1676,1),(2381,'Przesyłka kurierska pobraniowa',1677,1),(2383,'Opcje dotyczące transportu',1678,1),(2943,'Szukaj',323,1),(2945,'Opis i zdjęcia',1687,1),(2963,'Tytuł jest ograniczony do 50 znaków. Tagi HTML nie są widoczne. Niektóre symbole liczone są jako więcej  niż 1 znak: polskie znaki (np. ą, ę, ź, ć)- 2 znaki, \" - 6 znaków, < lub > - 4 znaki, &- 6 znaków. Możesz korzystać z dynamicznych tagów. Najpopul',1689,1),(2969,'Państwo',1690,1),(2971,'Miasto',1691,1),(2973,'Imię i nazwisko',1692,1),(2978,'Razem',1378,1),(2980,'Status i komentarze',1693,1),(2998,'Szczegółowo i rzetelnie opisz stan przedmiotu',1695,1),(3000,'Szczegółowo i rzetelnie opisz stan przedmiotu. W edytorze wizualnym łatwo przygotujesz opis bez znajomości HTML. Możesz używać znaczników HTML np <b>Kupuj u mnie</b>',1696,1),(3002,'Cena i czas trwania',1697,1),(3004,'Cena wywoławcza',1698,1),(3006,'Licz cenę wg wzoru',1699,1),(3008,'Cena wywoławcza = % ceny brutto w sklepie',1700,1),(3010,'Wypełnij pole licz cenę wg wzoru',1701,1),(3012,'Cena od której rozpocznie się licytacja. Najniższa cena wywoławcza to 1 zł. Przy zastosowaniu wzoru, cena wywoławcza to iloczyn podanej wartości procentowej z ceną produktu w sklepie.',1702,1),(3014,'Opcja Kup Teraz! umożliwia sprzedaż przedmiotu bez licytacji, za stałą cenę. Najniższa cena kup teraz to 1 zł. Opcja ta znika, gdy ktoś zalicytuje, a w aukcji z ceną minimalną, gdy cena ta zostanie osiągnięta. W przypadku formatu Aukcja (z licytacją) opcj',1703,1),(3018,'Sztuk',1705,1),(3020,'Zestaw',1706,1),(3022,'Pary',1707,1),(3024,'Rodzaj ilości',1708,1),(3030,'% stanu magazynowego, zaokrąglając do pełnej liczby',1711,1),(3032,'wybraną ilość sztuk',1712,1),(3034,'% stanu magazynowego, zaokrąglając do pełnej liczby',1713,1),(3038,'Data rozpoczęcia',1715,1),(3042,'Rozpocznij w innym terminie',1717,1),(3044,'Zostaw w magazynie',1718,1),(3054,'Weź pod uwagę stan magazynowy',1723,1),(3056,'Czas trwania',1724,1),(3058,'Transport i płatność',1725,1),(3060,'Koszt przesyłki pokrywa',1726,1),(3062,'Kupujący',1727,1),(3066,'Opcje dodatkowe',1729,1),(3068,'Twój przedmiot będzie promowany pogrubioną czcionką. Opłata 2,00 zł nie podlega zwrotowi.',1730,1),(3070,'Podświetlenie',1731,1),(3078,'Wyróżnienie',1735,1),(3080,'Twój przedmiot będzie zawsze widoczny na początku listy przedmiotów w wybranej kategorii i w wynikach wyszukiwania. Opłata 12,00 zł- nie podlega zwrotowi.',1736,1),(3082,'Strona kategorii',1737,1),(3084,'Twój przedmiot będzie promowany na liście kategorii. Wymagana ocena: jedna gwiazdka. Opłata 29,00 zł nie podlega zwrotowi. ',1738,1),(3088,'Twój przedmiot będzie promowany na stronie głównej Allegro. Wymagana ocena dwie gwiazdki. Opłata 99 zł - nie podlega zwrotowi.',1741,1),(3090,'Strona główna Allegro',1740,1),(3094,'Wypełnij pole ceny \"Kup Teraz!\"',1743,1),(3098,'Cena \"Kup Teraz!\" = Cena sprzedaży produktu w sklepie',1745,1),(3102,'Wpisz wartość',1747,1),(3111,'Selektor produktów',1751,1),(3116,'Lista sklepów',1754,1),(3118,'Namespace',1755,1),(3120,'Dodaj sklep',1756,1),(3128,'Edytuj sklep',1760,1),(3132,'Główne informacje',1762,1),(3134,'Dodatkowe informacje',1763,1),(3138,'MultiStore',1765,1),(3140,'Widok',1766,1),(3142,'W celu wygenerowania nowego hasła zaznacz checkbox',887,1),(3144,'Obramowanie',1767,1),(3146,'Kolor',1768,1),(3148,'Szablon stylów sklepu',1769,1),(3150,'Opcje główne',1770,1),(3152,'Tło sklepu',1771,1),(3154,'Krój czcionki sklepu',1772,1),(3156,'Tło stopki ',1773,1),(3158,'Krój czcionki stopki',1774,1),(3160,'Zaokrąglenie rogów obramowania',1775,1),(3164,'Odstęp za akapitem',1777,1),(3166,'Wpisz nazwę tagu do faktury',1778,1),(3170,'Wybierz sklep',1780,1),(3172,'Wybierz rodzaj faktury',1781,1),(3174,'Wartość od',1782,1),(3176,'Wartość do',1783,1),(3178,'Oryginał i kopia',1784,1),(3180,'Wartość zamówienia',1785,1),(3182,'Dodaj do listy życzeń',1786,1),(3184,'Interlinia',1787,1),(3186,'Domyślny boks',1788,1),(3188,'Kolor tła nagłówka',1789,1),(3190,'Brak szukanych fraz',1790,1),(3192,'Wybierz kategorie sklepu jakie mają być wyświetlane w danym widoku',1791,1),(3194,'Formularze',1792,1),(3196,'Karta produktu',1793,1),(3198,'Katalog produktów',1794,1),(3200,'Nazwa szablonu',1795,1),(3202,'Wpisz nazwę szablonu',1796,1),(3204,'Szablony stylów sklepu',1797,1),(3206,'Lista szablonów CSS sklepu',1798,1),(3208,'Nowy szablon stylów sklepu',1799,1),(3210,'Edycja szablonu stylów sklepu',1800,1),(3212,'Brak treści',579,1),(3218,'Zaznacz sklepy, w których wyświetlić rekord. Zostaną zastosowane ustawienia globalne lub dla danego sklepu.',1801,1),(3220,'Eksport faktur',1779,1),(3222,'Grupy statusów zam.',1488,1),(3224,'Uprawnienia dla',1802,1),(3225,'Wybierz klienta',1803,1),(3227,'Błędny format NIPu',1804,1),(3229,'Dodaj pliki',1805,1),(3231,'Błędny format numeru konta bankowego',1806,1),(3235,'Miara',1807,1),(3237,'Waga',1460,1),(3239,'Miara produktu',1808,1),(3241,'Wybierz sklep aby zrealizować zamówienie',1809,1),(3243,'Wygląd',1810,1),(3245,'Wybierz sklep w celu dodania zestawu produktu',1811,1),(3247,'Szablony boksów',1812,1),(3249,'Nowy szablon boksu',1813,1),(3251,'Edycja szalbonu boksu',1814,1),(3253,'Lista szablonów boxów',1815,1),(3255,'Nowy boks',1816,1),(3259,'Ustawienia boksu',1818,1),(3261,'Tytuł boksu',1819,1),(3263,'Zawartość boksu',1820,1),(3265,'Zachowanie boksu',1821,1),(3267,'Wpisz nazwę szablonu',1822,1),(3269,'Grafika',1823,1),(3271,'Wybierz szablon',1824,1),(3273,'Edycja boksu',1825,1),(3275,'Firmy',1749,1),(3276,'Firmy',1757,1),(3277,'Dodaj firmę',1758,1),(3278,'Edytuj firmę',1761,1),(3279,'Sklepy',1750,1),(3280,'Globalny',1826,1),(3282,'Klienci w tej grupie',1827,1),(3284,'Klienci w sklepie',1828,1),(3286,'Metody płatności i wysyłki',1829,1),(3288,'Automatyczny awans',1830,1),(3290,'Uwzględniaj wartość',1831,1),(3292,'Układ podstron',1832,1),(3294,'Edycja układu podstron',1833,1),(3296,'Kolumny podstrony',1834,1),(3298,'Określenie kolumn',1835,1),(3300,'Określenie boksów',1836,1),(3302,'Ilość kolumn na rozciągnięcie boksu',1837,1),(3304,'Zwinąć box?',1838,1),(3306,'Dodanie układu podstrony',1839,1),(3308,'Nazwa kolumny  podstrony',1840,1),(3310,'Wpisz nazwę kolumny podstrony',1841,1),(3316,'Wpisz szerokość kolumny',1844,1),(3318,'Rodzaj notatki',1845,1),(3320,'Szablon ma być domyślny?',1846,1),(3322,'Wybierz podstronę',1847,1),(3324,'Modyfikatory cenowe',1848,1),(3326,'Sposób wyświetlania cen',1849,1),(3328,'Informacja',1850,1),(3330,'Okres czasu',1660,1),(3331,'Kolor nagłówka',1851,1),(3333,'Opcje nagłówka',1852,1),(3335,'Opcje stopki',1853,1),(3337,'Zakres czasu',1854,1),(3339,'Dodaj nowy zakres czasu',1855,1),(3341,'Edytuj zakres czasu',1856,1),(3345,'Weryfikuj konto',1858,1),(3347,'Błędny login lub PIN',1859,1),(3349,'Aktywacja konta',1860,1),(3351,'Gratuluje. Konto zostało aktywowane. Możesz zalogować się na konto podając swój login oraz hasło',1861,1),(3353,'Stopka',1862,1),(3355,'Nazwa przykładowego boksu',1863,1),(3357,'Tekst zawartości przykładowego boksu',1864,1),(3359,'Kliknij w link, aby aktywować konto',1865,1),(3361,'Nowości',1866,1),(3367,'Ustawienia zawartości',1868,1),(3369,'Menu kategorii',1869,1),(3389,'Poza Internet Explorerem',1776,1),(3391,'Szablony boksów',1879,1),(3393,'Szablony stylów',1880,1),(3397,'Ustaw jako domyślny',1882,1),(3403,'Typ operacji',1885,1),(3409,'Rodzaj danych',1888,1),(3413,'Reguły katalogu',1889,1),(3415,'Reguły koszyka',1890,1),(3417,'Lista kategorii w sklepie',1891,1),(3419,'Tekst',1892,1),(3421,'Lista produktów promocyjnych',1893,1),(3423,'Lista nowych produktów',1894,1),(3425,'Grafika',1895,1),(3427,'Ankieta',1896,1),(3429,'Wiadomość',1897,1),(3431,'Lista produktów',1898,1),(3433,'Lista produktów w kategorii',1900,1),(3437,'Lista reguł katalogu',1899,1),(3439,'Lista produktów podobnych',1902,1),(3441,'Lista produktów up-sell',1903,1),(3443,'Kategoria',1904,1),(3445,'Produkt',1905,1),(3447,'Nawigacja warstwowa',1906,1),(3449,'Wiadomości',1907,1),(3451,'Dane kontaktowe',1908,1),(3453,'Lista produktów cross-sell',1901,1),(3457,'Edycja reguły katalogu',1910,1),(3459,'Cena od',1911,1),(3461,'Cena do',1912,1),(3463,'Produkt jest nowością',1913,1),(3465,'Wprowadź nazwę dla nowej reguły katalogu',1914,1),(3467,'Kolejność wykonywania reguł katalogu została zapisana',1915,1),(3469,'Finalna cena koszyka',1916,1),(3471,'Metody płatności',1917,1),(3473,'Lista reguł koszyka',1918,1),(3475,'Edycja reguły koszyka',1919,1),(3477,'Kolejność wykonywania reguł koszyka została zapisana',1920,1),(3479,'Wprowadź nazwę dla nowej reguły koszyka',1921,1),(3481,'Lista map strony',1922,1),(3483,'Dodaj mapę strony',1923,1),(3485,'Odśwież mapy strony',1924,1),(3487,'Boksy',1817,1),(3489,'Do kasy',1925,1),(3491,'Jednakowy modyfikator dla wszystkich grup',1926,1),(3493,'Nie określono SEO dla produktu',1927,1),(3497,'Potwierdzenie wybranych w regule produktów',1929,1),(3499,'Nie określono SEO dla kategorii',1930,1),(3501,'SEO dla kategorii',1931,1),(3503,'Plik CSV',1932,1),(3505,'Plik XML',1933,1),(3507,'Ustawienia wymiany danych',1934,1),(3509,'Wymiana danych',1935,1),(3511,'Opis',1936,1),(3513,'Rezultat działania reguły',1937,1),(3515,'Ostatni ping',1938,1),(3517,'Pingserver',1939,1),(3519,'Mapowanie danych',1940,1),(3521,'Uruchom import',1941,1),(3525,'Za pomocą poniższych list ustal odpowiedniki pól w pliku.',1943,1),(3527,'Mapy stron',1944,1),(3529,'Nazwa serwisu',1945,1),(3531,'Publikuj kategorie',1946,1),(3533,'Priorytet dla linków kategorii',1947,1),(3535,'Publikuj produkty',1948,1),(3537,'Priorytet dla linków produktów',1949,1),(3539,'Publikuj producentów',1950,1),(3541,'Priorytet dla linków producentów',1951,1),(3543,'Publikuj newsy',1952,1),(3545,'Priorytet dla linków newsów',1953,1),(3547,'Publikuj strony informacyjne',1954,1),(3549,'Priorytet dla linków stron statycznych',1955,1),(3551,'Edytuj mapę strony',1956,1),(3557,'Import danych z pliku',1887,1),(3558,'Export danych do pliku',1886,1),(3565,'Lista życzeń',1962,1),(3567,'Regulamin sklepu',1963,1),(3569,'Polityka prywatności',1964,1),(3571,'Poleć znajomemu',1965,1),(3573,'Polecił tą stronę',1966,1),(3619,'SEO kontrolerów',1990,1),(3621,'Minimalna wartość zamówienia',1991,1),(3629,'Minimalna wartość zamówienia do wykorzystania punktów',1995,1),(3635,'Automatycznie dodaj punkty za zamówienie, gdy osiągnie ono status z grupy',1998,1),(3711,'Typ zwrotu',2031,1),(3713,'Zwrot pełny',2032,1),(3715,'Zwrot częściowy',2033,1),(3717,'Kwota zwrotu',2034,1),(3719,'Notka dotycząca zwrotu',2035,1),(3733,'Informacje podstawowe',2042,1),(3741,'Treść w formie tekstowej',2046,1),(3742,'Treść w formie HTML',2047,1),(3743,'Lista szablonów transakcyjnych',2048,1),(3745,'Dodaj szablon',2049,1),(3747,'Edycja szablonu',2050,1),(3749,'Szablony transakcyjne',2051,1),(3751,'Lista produktów reguły',2052,1),(3753,'Wybrane produkty',2053,1),(3755,'Lista szablonów nagłówków',2054,1),(3757,'Lista szablonów stopek',2055,1),(3759,'Szablony nagłówków',2056,1),(3761,'Szablony stopek',2057,1),(3763,'Dane techniczne',2058,1),(3766,'Brak nowości',2059,1),(3768,'Brak promocji',2060,1),(3770,'Zamów próbki',2061,1),(3772,'W koszyku znajdują się produkty wirtualne. Sposób dostawy został wybrany automatycznie',2062,1),(3776,'Lista zamówionych próbek',2064,1),(3778,'Zamówione próbki',2065,1),(3784,'Strona',2068,1),(3790,'Powiadomienia względem aktywności',2071,1),(3792,'Data ostatniego zamówienia',2072,1),(3793,'Łączna suma wszystkich zamówień',2073,1),(11799,'Dodaj powiadomienie',2074,1),(11800,'Lista powiadomień względem aktywności',2075,1),(11801,'Strona sklepu',2076,1),(11802,'Strona panelu',2077,1),(11803,'Pusty CMS',2078,1),(11805,'Podkategoria',2079,1),(11807,'Wiadmość nie istnieje',2080,1),(11809,'CMS nie istnieje',2081,1),(11811,'Odśwież',2082,1),(11813,'Administrator sklepu',2083,1),(11814,'Edycja powiadomienia',2084,1),(11815,'Nazwa pliku',2085,1),(11816,'Wpisz nazwę pliku',2086,1),(11820,'Tagi dla akcji',2087,1),(11821,'Wpisana nazwa pliku istnieje już w bazie. Wpisz inną',2088,1),(11822,'Brak produktów',2089,1),(11824,'Przelew bankowy',2090,1),(11826,'Kupiono również',2091,1),(11828,'Podgląd koszyka',2092,1),(11830,'Adresy klienta',2093,1),(11832,'Formularz logowania',2094,1),(11834,'Zamówienia klienta',2095,1),(11836,'Ustawienia klienta',2096,1),(11838,'Tagi klienta',2097,1),(11840,'CMS',2098,1),(11842,'Sprzedaż krzyżowa',2099,1),(11844,'Dostawa',2100,1),(11846,'Finalizacja zamówienia',2101,1),(11848,'Przypomnij hasło',2102,1),(11850,'Najczęściej szukano',2103,1),(11852,'Newsletter',2104,1),(11854,'Płatność za pobraniem',2105,1),(11856,'Sposób płatności',2106,1),(11858,'Płatność przy odbiorze',2107,1),(11862,'Polityka prywatności',2109,1),(11864,'Lista nowości',2110,1),(11866,'Lista promocji',2111,1),(11868,'Lista wyszukiwanych produktów',2112,1),(11870,'Lista otagowanych produktów',2113,1),(11872,'Poleć znajomemu',2114,1),(11874,'Rejestracja klienta',2115,1),(11876,'Wyszukiwarka',2116,1),(11878,'Produkty podobne',2117,1),(11880,'Tagi',2118,1),(11882,'Regulamin sklepu',2119,1),(11884,'Szablon transakcyjny',2120,1),(11886,'Produkt',2121,1),(11888,'Potwierdzenie wyselekcjonowanych klientów',2122,1),(11914,'Nie podano adresu URL',2140,1),(11918,'Wykonano',2142,1),(11920,'Aktualizuj',2143,1),(11922,'Grupa jest wykorzystywana w ustawieniach automatycznego awansu',2144,1),(11924,'Szablony powiadomień',2145,1),(11926,'Lista szablonów powiadomień',2146,1),(11928,'Lista tagów możliwych do użycia',2147,1),(11930,'Edycja statusu produktu',2148,1),(11932,'Dodaj nowy status produktu',2149,1),(11934,'Uruchom backup',2150,1),(11936,'Dodaj nowy punkt przywracania',2151,1),(11938,'Tytuł wiadomości',2152,1),(11940,'Nazwa pliku szablonu',2153,1),(11942,'Powiadomienia wzgl. aktywności- raport',2154,1),(11944,'Wybierz datę wysyłki powiadomienia, by zobaczyć raport',2155,1),(11946,'Ustawienia punktu przywracania',2156,1),(11948,'Typ danych',2157,1),(11950,'Baza danych SQL',2158,1),(11952,'Struktura plików',2159,1),(11954,'Tworzenie kopii zapasowej bazy SQL',2160,1),(11956,'tabel',2161,1),(11958,'Tworzenie kopii zapasowej struktury plików',2162,1),(11960,'plików',2163,1),(11962,'Waluty',2164,1),(11964,'Waluty',2165,1),(11966,'Nazwa waluty',2166,1),(11968,'Symbol waluty',2167,1),(11970,'Kursy wymiany',2168,1),(11972,'Dodaj walutę',2169,1),(11974,'Pobierz kursy',2170,1),(11976,'Edycja waluty',2171,1),(11978,'Kursy wymiany',2172,1),(11980,'Kliknij link, aby aktywować newsletter.',2173,1),(11982,'Tłumaczenia',2174,1),(11984,'Synchronizacja tłumaczeń',2175,1),(11988,'Status jest wykorzystywany w metodach płatności',4257,1),(11990,'Jeśli nie zapisywałeś się do newsletter lub nie chcesz otrzymywać nowych wiadomości, kliknij w link.',4258,1),(11992,'Link do aktywacji newslettera',4259,1),(11994,'Link do dezaktywacji newslettera',4260,1),(12000,'Brak produktów z atrybutami',4261,1),(12002,'Brak kategorii',4262,1),(12006,'Brak ankiety',4263,1),(12008,'Wartość koszyka musi przekraczać kwotę',4264,1),(12010,'Wartość koszyka nie może być większa niż',4265,1),(12012,'Wartość koszyka wraz z dostawą musi przekroczyć kwotę',4266,1),(12014,'Wartość koszyka wraz z dostawą nie może przekroczyć kwoty',4267,1),(12016,'Spełnij poniższe warunki, a uzyskasz rabat',4268,1),(12018,'lub',4269,1),(12020,'Podstrony',1881,1),(12022,'Nazwa podstrony',1842,1),(12024,'Użyj globalnej',4270,1),(12026,'Użyj globalnej',4271,1),(12028,'Odbierz maila z linkiem aktywującym newsletter',4272,1),(12030,'Odbierz maila z linkiem dezaktywującym newsletter',4273,1),(12032,'Na podany podczas rejestracji e-mail wysłano wiadomość z nowym hasłem',4274,1),(12035,'Niepoprawny format nr telefonu',657,1),(12037,'Zmień walutę',4275,1),(12039,'Aktywna',1063,1),(12040,'Sklep',4276,1),(12042,'w wybranej walucie',4277,1),(12044,'jednostka w wybranej walucie',4278,1),(12046,'Potwierdzenie złożenia wniosku o raty za zamówienie',4279,1),(12048,'Anulowanie złożenia wniosku o raty za zamówienie',4280,1),(12050,'Proszę skontaktować się z administratorem sklepu w celu uzyskania numeru konta bankowego.',4281,1),(12052,'Wybierz ten adres',4282,1),(12054,'Dziękujemy za złożenie zamówienia.',1323,1),(12060,'Dodaj swoją opinię',4285,1),(12062,'Wybierz ocenę',4286,1),(12064,'Wartość zamówienia przed promocją',4287,1),(12066,'Wartość zamówienia po promocji',4288,1),(12068,'Wartość zamówienia netto',4289,1),(12070,'Wartość zamówienia brutto',4290,1),(12072,'Zamów ponownie',800,1),(12074,'Dostępne formy wysyłki',4291,1),(12076,'Dostępne formy płatności',4292,1),(12078,'Przydatne linki',4293,1),(12080,'Dla tego produktu nie dodano jeszcze tagów',4294,1),(12082,'Brak promocji w sklepie',4295,1),(12084,'Aby dodać opinię musisz być zalogowany',4296,1),(12086,'Zapisz się do naszego newslettera, aby mieć zawsze świeże informację na temat naszych produktów',856,1),(12090,'Poleć nas znajomym',4297,1),(12092,'Napisz opinię jako pierwszy',4298,1),(12094,'Zaloguj się, aby głosować',4299,1),(12096,'Mapa strony',4300,1),(12098,'Waluta sprzedaży',4301,1),(12100,'Waluta zakupu',4302,1),(12102,'Waluta domyślna',4303,1),(12104,'Flaga języka',4304,1),(12106,'Klienci online',4305,1),(12108,'Brak ankiet',4306,1),(26690,'Nie możesz ustawić kategorii nadrzędnej takiej samej jak edytowana kategoria.',4310,1),(32938,'Podsumowanie płatności',4311,1),(32939,'Duplikacja kategorii',4312,1),(35020,'Kod Google Analytics',4314,1),(35021,'Śledź transakcje',4315,1),(35022,'Śledź odwiedziny',4316,1),(37106,'Ten status jest wykorzystywany w produktach',4317,1),(39191,'Ten plik jest wykorzystywany w ustawieniach producenta',4318,1),(41277,'Domyślna waluta języka',4319,1),(41278,'Gość',4320,1),(43367,'Ustawienia developera',4321,1),(43368,'Sklep jest wyłączony',4322,1),(43369,'Kod HTML komunikatu offline',4323,1),(43370,'Zapisz i kontynuuj',4324,1),(43376,'Czy posiadasz już konto w naszym sklepie ?',4326,1),(43378,'Nie posiadam konta i chcę złożyć zamówienie bez rejestracji w sklepie',4325,1),(43379,'Nie posiadam konta ale chcę się zarejestrować',4327,1),(43381,'Posiadam konto i chcę się zalogować',4328,1),(49668,'Zapoznałem się z regulaminem sklepu i akceptuję jego wszystkie postanowienia. Wyrażam zgodę na przetwarzanie moich danych osobowych (zgodnie z ustawą z dnia 29.08.1997r. o Ochronie Danych Osobowych). Dysponuję prawem wglądu do swoich danych, poprawiania ich lub usunięcia. Udostępniam je dobrowolnie.',4330,1),(53861,'Przelew bankowy',4331,1),(53862,'Koszyk',4332,1),(53863,'Lista kategorii',4333,1),(53864,'Ceneo',4334,1),(53865,'Klient',4335,1),(53866,'Książka adresowa',4336,1),(53867,'Logowanie',4337,1),(53868,'Zamówienia',4338,1),(53869,'Ustawienia konta',4339,1),(53870,'Potwierdzenie zamówienia',4340,1),(53871,'Formularz kontaktowy',4341,1),(53872,'Wybór formy dostawy',4342,1),(53873,'Błąd',4343,1),(53874,'Kanał RSS',4344,1),(53875,'Potwierdzenie zamówienia',4345,1),(53876,'Zapomniany login',4346,1),(53877,'Zapomniane hasło',4347,1),(53878,'Google Analytics',4348,1),(53879,'Google Sitemap',4349,1),(53880,'Instalator',4350,1),(53881,'Integracja',4351,1),(53882,'Faktura',4352,1),(53883,'Nawigacja warstwowa',4353,1),(53884,'Logowanie',4354,1),(53885,'Strona główna',4355,1),(53886,'Porzucony koszyk',4356,1),(53887,'Najczęściej szukane',4357,1),(53888,'Newsy',4358,1),(53889,'Newsletter',4359,1),(53891,'Płatność za pobraniem',4361,1),(53892,'Zamówienie',4362,1),(53893,'Płatność',4363,1),(53894,'Płatność przy odbiorze',4364,1),(53895,'Potwierdzenie konta',4365,1),(53896,'Platnosci.pl',4366,1),(53897,'Ankiety',4367,1),(53898,'Produkt',4368,1),(53899,'Produkt',4369,1),(53900,'Zestawy produktów',4370,1),(53901,'Filtrowanie produktów',4371,1),(53902,'Lista produktów',4372,1),(53903,'Nowości w sklepie',4373,1),(53904,'Promocje w sklepie',4374,1),(53905,'Opinie dla produktów',4375,1),(53906,'Wyszukiwarka',4376,1),(53907,'Tagi',4377,1),(53908,'Rejestracja',4378,1),(53909,'Rejestracja',4379,1),(53910,'Wyniki wyszukiwania',4380,1),(53911,'Strona informacyjna',4381,1),(53912,'Lista życzeń',4382,1),(53913,'Mapa strony',4383,1),(53915,'PayPal',4385,1),(53917,'RSS',4387,1),(53918,'PayFlow',4388,1),(53919,'eRaty Żagiel',4389,1),(56075,'Ustawienia koszyka',4390,1),(56077,'Bez przekierowania',4392,1),(56078,'Śledź stan magazynowy produktów',4393,1),(58238,'Godziny pracy działu',4394,1),(58239,'Ustaw uprawnienia użytkownika',4395,1),(58240,'Użytkownik ma uprawnienia globalne',4396,1),(58241,'Waluta jest wykorzystywana jako domyślna dla języka',4397,1),(60407,'Nie wybrałeś państwa',4399,1),(60408,'Potwierdź opuszczenie zamówienia bez zapisania w nim zmian',4400,1),(60409,'Maksymalna waga zamówienia',4401,1),(60410,'Podaj maksymalną wagę dla jakiej ta metoda ma być aktywna',4402,1),(60411,'Bezpłatna dostawa',4403,1),(60412,'Podaj wartość zamówienia dla której niezależnie od wagi wysyłka ma być bezpłatna',4404,1),(60413,'Konto jest zablokowane',1583,1),(62583,'eRaty Żagiel',4405,1),(62584,'Płatność kartą kredytową',4406,1),(62586,'Prezentacja produktów',4408,1),(62587,'Mapa strony',4409,1),(66942,'Kolejność wyświetlania',4412,1),(69120,'Nie wybrano sklepu',4413,1),(69121,'Nie wybrano daty od',4414,1),(69122,'Nie wybrano daty do',4415,1),(69123,'Nie podano namespace',4416,1),(69124,'Nie wybrano firmy',4417,1),(69125,'Nie podano województwa',4418,1),(69127,'Nie wybrano waluty',4419,1),(69128,'Nie wybrano formatu daty',4420,1),(69129,'Nie wybrano strony',4421,1),(69130,'Nie podano nazwy kontrolera',4422,1),(69131,'Dodaj kontroler',4423,1),(69132,'Nie podano symbolu waluty',4424,1),(69133,'Aktualizacja kursów walutowych',4425,1),(69134,'Czy chcesz zaktualizować kursy dla waluty',4426,1),(69135,'Skopiuj tłumaczenia z innego języka',4427,1),(69136,'Nie wybrano sposobu obliczania kosztów',4428,1),(69140,'Nie podano nazwy serwisu',4432,1),(69141,'Nie podano adresu serwera ping',4433,1),(69142,'Nie wybrano szablonu',4434,1),(69143,'Koszty wysłki',510,1),(71345,'Odśwież strukturę SEO',4435,1),(77949,'Wymagana dopłata do zamówienia w wysokości',4436,1),(80152,'Aby zrealizować zamówienie musisz dodać do koszyka produkty o wartości',4437,1),(82356,'Śledzenie stanu magazynowego',4438,1),(82357,'Płatność za zamówienie została anulowana.',4439,1),(84099,'miesięcznie',4683,1),(84101,'Oblicz ratę',4684,1),(84102,'Załóż konto w',4696,1),(84103,'Zarejestruj się, żebyśmy mogli w przyszłości poprawnie Cię zidentyfikować.Założenie Konta w żaden sposób nie zobowiązuje Cię do zakupów u nas.',4697,1),(84104,'Metoda wysyłki jest wykorzystywana w zamówieniach',4698,1),(84105,'Metoda płatności jest wykorzystywana w zamówieniach',4699,1),(84108,'Tryb katalogu',4701,1),(84110,'Edytuj w nowej karcie',4703,1),(84111,'Pokaż w stopce sklepu',4704,1),(84112,'Pokaż w menu sklepu',4705,1),(84113,'podaj bez http:// na początku',4706,1),(84210,'Wpisz Nick',4708,1),(84238,'Nie wybrano płci',4710,1),(84264,'Błąd. Min. 3 lub maks. 20 znaków.',4711,1),(84265,'Błąd. Wymagane liczby i/lub cyfry.',4712,1),(84266,'Błąd. Min. 3 lub maks. 30 znaków.',4713,1),(84267,'Błąd. Min. 9 lub maks. 15 znaków.',4714,1),(84284,'Wybierz rodzaj dziedziczenia promocji.',4715,1),(84300,'Błędny Nick',4716,1),(84355,'Wystąpił błąd. Strona pod podanym adresem URL nie istnieje lub nie jest aplikacją WordPress',4680,1),(84370,'Błędny adres E-mail',4718,1),(84382,'O Firmie',4496,1),(84406,'Zgadzam się na wysłanie przedmiotu za granicę',4556,1),(84426,'Dodaj błąd',4590,1),(84463,'Dodaj zestaw produktów',4562,1),(84477,'Dodaj mapę strony',4612,1),(84491,'Dodaj link do WordPress',4676,1),(84493,'Historia logowań administratorów',4522,1),(84539,'Pozostałe formularze Allegro',4593,1),(84540,'Linki do pozostałych ustawień i akcji Allegro',4594,1),(84612,'Źle',4558,1),(84646,'Zgłoszone błędy',4548,1),(84647,'Lista błędów',4780,1),(84648,'Zgłoś błąd',4549,1),(84693,'Zmień Nick',4781,1),(84700,'Zmien dane do Twittera',4782,1),(84881,'Format daty',4499,1),(84895,'Wady',4536,1),(84925,'Uszkodzony',4602,1),(84993,'List ekonomiczny',4528,1),(84994,'Paczka pocztowa ekonomiczna',4527,1),(84995,'List polecony ekonomiczny',4529,1),(85062,'Edytuj link do WordPress',4677,1),(85063,'Oferta Eko- Uzytkownika',4601,1),(85064,'Twój przedmiot będzie promowany na liści Eko- Użytkowników, dostępnej ze strony głównej. Warunkiem promowania oferty, jako Eko - Użytkownika, jest wpłata na konto Fundacji Ekologicznej ALL FOR PLANET, nie mniejsza niż 5,00 zł. Opłata nie podlega zwrotowi.',4600,1),(85102,'Akcesoria',4551,1),(85130,'Import z osCommerce',4613,1),(85136,'Wyróżnienie aukcji',4455,1),(85156,'naprawa',4542,1),(85192,'Konto Google Analitycs',4815,1),(85193,'Google Analitycs',4561,1),(85194,'Konto Google Analitycs',4816,1),(85195,'Email Google Analitycs ',4817,1),(85196,'Hasło Google Analitycs ',4818,1),(85225,'wysoki',4540,1),(85226,'najwyższy',4541,1),(85229,'Twój przedmiot będzie promowany poprzez zaznaczenie żółtym kolorem na liście przedmiotów w swojej kategorii i w wynikach wyszukiwania. Opłata dodatkowa 6 zł nie podlega zwrotowi.',1732,1),(85236,'Idealnie',4559,1),(85259,'unieważnienie',4543,1),(85325,'Czy wyświetlane produkty mają być linkami do sklepu, czy tylko zachętą do rejestracji',4521,1),(85343,'niski',4538,1),(85344,'najniższy',4537,1),(85347,'Zdjęcie główne',4589,1),(85361,'Marketing',4443,1),(85385,'Film',4550,1),(85387,'Opłaty Allegro',4824,1),(85424,'Nick',4825,1),(85425,'Nowy Nick',4826,1),(85429,'normalny',4539,1),(85446,'Numer zgłoszenia',4547,1),(85461,'nowy',4544,1),(85496,'Hasło administratora',4616,1),(85497,'Ustawienia migracji z osCommerce',4610,1),(85498,'Login administratora osCommerce',4615,1),(85505,'Czas dostarczenia paczki przez kuriera GLS do 48 h.',4830,1),(85510,'Pasaże',4440,1),(85549,'Add',4511,1),(85550,'Select All',4534,1),(85551,'Delete',4532,1),(85552,'Edit',4531,1),(85553,'Index',4530,1),(85554,'View',4533,1),(85559,'(022) 444 44 44',4691,1),(85567,'PIN',4608,1),(85568,'Weryfikacja konta',4674,1),(85573,'PLN',4692,1),(85639,'Priorytet',4546,1),(85682,'Bestsellers',4611,1),(85750,'Losowe',4520,1),(85791,'ponowne otwarcie',4545,1),(85838,'Kraj sprzedawcy',4485,1),(85839,'Login sprzedawcy',4483,1),(85840,'Sprzedający płaci za przesyłkę',4459,1),(85841,'Liczba punktów sprzedawcy',4484,1),(85842,'Użytkownik z zablokowaną sprzedażą',4491,1),(85860,'Płeć',4882,1),(85919,'Bloki statyczne',4444,1),(85924,'Statystyki',4442,1),(85976,'Zadanie',4535,1),(85989,'Lista błędów',4555,1),(85991,'Edytuj listę błędów',4552,1),(85999,'Dzisiaj jest',4510,1),(86002,'Top 10',4497,1),(86081,'Aby wyświetlić .... (zmienic TXT_VIEW_FORM w translation na wlasciwy)',4554,1),(86137,'Znak wodny',4586,1),(86138,'Zaznacz opcję, jeśli chcesz, by Twoje zdjęcia oznaczone były loginem',4573,1),(86148,'Co chcesz teraz zrobić',4557,1),(86149,'Co chcesz dalej zrobić?',4498,1),(86156,'pozostawienie bez zmian',4890,1),(86157,'WordPress',4891,1),(86158,'Lista artykułów WordPress',4892,1),(86160,'odmowa zmian',4893,1),(86162,'Wpisz wiadomość dla Twitter',4894,1),(86259,'WordPress',4687,1),(86282,'Artykuł WordPress',4897,1),(86283,'Lista artykułów WordPress',4898,1),(86302,'Nie podano adresu wtyczki dla Gekosale',4899,1),(86303,'Nie podano loginu administratora w osCommerce',4900,1),(86304,'Nie podano hasła administratora w osCommerce',4901,1),(86313,'Włącz opinie w sklepie',4688,1),(86314,'Włącz tagi w sklepie',4689,1),(86315,'Kolejność',4690,1),(86316,'Slideshow',4902,1),(86317,'Cena promocyjna brutto',4903,1),(86318,'Cena promocyjna netto',4904,1),(88563,'Włącz RSS',4905,1),(88564,'Upload plików do zamówień',4906,1),(88565,'Uploader włączony',4907,1),(88566,'Maksymalny rozmiar pliku',4908,1),(88567,'Rozmiar pojedynczego fragmentu (chunk)',4909,1),(88568,'Dozwolone rozszerzenia',4910,1),(88569,'Dołącz pliki do zamówienia',4911,1),(88570,'Jeżeli chcesz dodać pliki do zamówienia wybierz je z dysku a następnie naciśnij przycisk \"Wgraj\"',4912,1),(88571,'Wybierz pliki',4913,1),(88572,'Wgraj pliki',4914,1),(88573,'Złóż zamówienie',4915,1),(88574,'Klient indywidualny',4916,1),(88575,'Firma',4917,1),(88576,'Dodaj do zamówienia',4918,1),(88577,'Zamknij',4919,1),(88578,'&#x3c;Poprzedni',4920,1),(88579,'Następny&#x3e;',4921,1),(88580,'Dziś',4922,1),(88581,'Wystąpił błąd!',4923,1),(88582,'Wybierz sklep',4924,1),(88583,'Dodaj nowy',4925,1),(88584,'Usuń',4926,1),(88585,'Nie można wysłać formularza, ponieważ zawiera on niepoprawne informacje. Przed zapisaniem zmian należy je poprawić.',4927,1),(88586,'Przejdź do pola',4928,1),(88587,'OK',4929,1),(88588,'Przewiń w prawo',4930,1),(88589,'Przewiń w lewo',4931,1),(88590,'Zapisz',4932,1),(88591,'Dodaj nową wartość',4933,1),(88592,'Usuń wartość',4934,1),(88593,'wszystkie',4935,1),(88594,'wszystkie',4936,1),(88595,'Id',4937,1),(88596,'Nazwa',4938,1),(88597,'Cena netto',4939,1),(88598,'Cena brutto',4940,1),(88599,'Cena zakupu netto',4941,1),(88600,'Cena zakupu brutto',4942,1),(88601,'Kod',4943,1),(88602,'Producent',4944,1),(88603,'VAT',4945,1),(88604,'Podatek',4946,1),(88605,'Netto',4947,1),(88606,'Brutto',4948,1),(88607,'Kategoria',4949,1),(88608,'Data dodania',4950,1),(88609,'Autor',4951,1),(88610,'Data modyfikacji',4952,1),(88611,'Autor modyfikacji',4953,1),(88612,'Anuluj wybór',4954,1),(88613,'Wybrane produkty',4955,1),(88614,'Ilość',4956,1),(88615,'Wariant produktu',4957,1),(88616,'Dodaj',4958,1),(88617,'Ukryj listę produktów',4959,1),(88618,'Suma',4960,1),(88619,'Wybrane rekordy',4961,1),(88620,'Anuluj wybór',4962,1),(88621,'Błąd podczas inicjalizacji Datagrida',4963,1),(88622,'Id pliku',4964,1),(88623,'Nazwa pliku',4965,1),(88624,'Typ pliku',4966,1),(88625,'Rozszerzenie',4967,1),(88626,'Anuluj wybór zdjęcia',4968,1),(88627,'Miniatura',4969,1),(88628,'Pokaż miniaturę',4970,1),(88629,'Zdjęcie',4971,1),(88630,'Główne',4972,1),(88631,'Widoczne',4973,1),(88632,'Anuluj wybór',4974,1),(88633,'Błąd podczas wysyłania pliku',4975,1),(88634,'Wysłano pomyślnie',4976,1),(88635,'Przetworzanie pliku na serwerze nie powiodło się',4977,1),(88636,'Nie wszystkie transfery plików zostały ukończone!',4978,1),(88637,'Nie można wysłać formularza, dopóki wszystkie transfery nie zostały zakończone. Poczekaj chwilę i spróbuj jeszcze raz.',4979,1),(88638,'Wybrany plik',4980,1),(88639,'Zestaw cech dla tego produktu',4981,1),(88640,'sugerowane zestawy są pogrubione',4982,1),(88641,'Dodaj nową cechę',4983,1),(88642,'Zapisz nową cechę',4984,1),(88643,'Dodaj nową wartość cechy',4985,1),(88644,'Zapisz nową wartość cechy',4986,1),(88645,'Id wariantu',4987,1),(88646,'Magazyn',4988,1),(88647,'Typ modyfikatora',4989,1),(88648,'Modyfikator',4990,1),(88649,'Cena',4991,1),(88650,'Dodaj nowy wariant',4992,1),(88651,'Zapisz zmiany',4993,1),(88652,'Cechy dostępne w wybranym zestawie',4994,1),(88653,'Cena podst. netto',4995,1),(88654,'Cena podst. brutto',4996,1),(88655,'Rodzaj modyfikatora ceny',4997,1),(88656,'Wartość modyfikatora',4998,1),(88657,'Cena netto wariantu',4999,1),(88658,'Cena brutto wariantu',5000,1),(88659,'Stan magazynowy',5001,1),(88660,'(wybierz cechę)',5002,1),(88661,'Nowa',5003,1),(88662,'Duplikuj',5004,1),(88663,'Nowa podrzędna',5005,1),(88664,'Usuń zaznaczoną',5006,1),(88665,'Zapisz kolejność',5007,1),(88666,'Przywróć kolejność',5008,1),(88667,'Rozwiń wszystkie',5009,1),(88668,'Zwiń wszystkie',5010,1),(88669,'OK',5011,1),(88670,'Anuluj',5012,1),(88671,'Znaleziono dwa elementy o tej samej nazwie na jednym poziomie',5013,1),(88672,'Znaleziono dwa elementy o tej samej nazwie',5014,1),(88673,'Proponowanej struktury nie można zapisać, ponieważ zawiera zduplikowane elementy na tym samym poziomie w tym samym poddrzewie.',5015,1),(88674,'Proponowanej struktury nie można zapisać, ponieważ zawiera zduplikowane elementy.',5016,1),(88675,'Element o tej nazwie już istnieje',5017,1),(88676,'Na jednym poziomie poddrzewa nie mogą się znaleźć dwa elementy o tej samej nazwie.',5018,1),(88677,'Czy na pewno chcesz usunąć tę kategorię?',5019,1),(88678,'Usunięcie będzie nieodwracalne. Przed usunięciem kategorii należy upewnić się, że nie zawiera ona podkategorii.',5020,1),(88679,'Anuluj wybór',5021,1),(88680,'(wybierz lub wpisz cechę)',5022,1),(88681,'Zmień nazwę cechy',5023,1),(88682,'Podaj nową nazwę (pamiętaj, że zmieni się ona we wszystkich grupach i produktach!)',5024,1),(88683,'Zmiana nazwy cechy nie powiodła się',5025,1),(88684,'Wystąpił błąd podczas dokonywania zmiany w bazie danych.',5026,1),(88685,'Zmień nazwę wartości cechy',5027,1),(88686,'Podaj nową nazwę (pamiętaj, że zmieni się ona we wszystkich grupach i produktach!)',5028,1),(88687,'Zmiana nazwy wartości cechy nie powiodła się',5029,1),(88688,'Wystąpił błąd podczas dokonywania zmiany w bazie danych.',5030,1),(88689,'Usuń cechę z grupy',5031,1),(88690,'Usuń cechę z bazy danych',5032,1),(88691,'Usuń',5033,1),(88692,'Anuluj',5034,1),(88693,'Czy na pewno chcesz usunąć tę cechę z bazy danych?',5035,1),(88694,'Spowoduje to utratę wszystkich należących do tej cechy wartości. Ponowne użycie tej cechy będzie wymagało jej dodania do bazy.',5036,1),(88695,'Usunięcie cechy z bazy nie powiodło się.',5037,1),(88696,'Sprawdź, czy usuwana cecha nie jest przypisana któremuś z produktów bądź nie przynależy do innego zestawu cech.',5038,1),(88697,'Edytuj wartości cechy',5039,1),(88698,'Usuń wartość cechy',5040,1),(88699,'Dodaj cechę',5041,1),(88700,'Dodaj wartość cechy',5042,1),(88701,'Cechy',5043,1),(88702,'Wartości',5044,1),(88703,'Id',5045,1),(88704,'Imię',5046,1),(88705,'Nazwisko',5047,1),(88706,'E-mail',5048,1),(88707,'Telefon',5049,1),(88708,'Płeć',5050,1),(88709,'Grupa',5051,1),(88710,'Data utworzenia konta',5052,1),(88711,'Data modyfikacji',5053,1),(88712,'Wybierz klienta',5054,1),(88713,'Ukryj listę klientów',5055,1),(88714,'Dane osobowe',5056,1),(88715,'Adres e-mail',5057,1),(88716,'Przynależy do grupy',5058,1),(88717,'(inny adres)',5059,1),(88718,'Skopiuj adres z',5060,1),(88719,'Czy zaktualizować dane adresowe?',5061,1),(88720,'Zmieniono klienta dla zamówienia. Czy automatycznie ustawić dane płatnika i dane wysyłki na podstawie danych adresowych klienta?',5062,1),(88721,'Trwa przeliczanie ceny zestawu!',5063,1),(88722,'Nie można zapisać zmian, dopóki przeliczanie nie zostanie ukończone.',5064,1),(88723,'Liczba produktów',5065,1),(88724,'Suma',5066,1),(88725,'Wywołanie metody SetValue dla pola typu FE_ProductAggregator jest niedozwolone.',5067,1),(88726,'Wartość modyfikatora',5068,1),(88727,'Rodzaj modyfikatora',5069,1),(88728,'Uwzględnij podatek VAT',5070,1),(88729,'Stawka VAT',5071,1),(88730,'od',5072,1),(88731,'do',5073,1),(88732,'Dodaj nowy zakres poniżej tego',5074,1),(88733,'Usuń zakres',5075,1),(88734,'godzina',5076,1),(88735,'Zwiń',5077,1),(88736,'Rozwiń',5078,1),(88737,'(Podgląd)',5079,1),(88738,'Kolor',5080,1),(88739,'Gradient',5081,1),(88740,'Grafika',5082,1),(88741,'Wyrównanie tła',5083,1),(88742,'Powtarzanie tła',5084,1),(88743,'bez powtarzania',5085,1),(88744,'w poziomie',5086,1),(88745,'w pionie',5087,1),(88746,'w poziomie i w pionie',5088,1),(88747,'Wybierz plik',5089,1),(88748,'Wysłanie pliku nie powiodło się',5090,1),(88749,'(nie wybrano pliku)',5091,1),(88750,'Pełna ścieżka',5092,1),(88751,'Nazwa pliku',5093,1),(88752,'Rozmiar pliku',5094,1),(88753,'Właściciel',5095,1),(88754,'Ost. modyfikacja',5096,1),(88755,'Usuń plik',5097,1),(88756,'Czy na pewno chcesz usunąć ten plik?',5098,1),(88757,'Jeśli jest on używany w innym miejscu, jego usunięcie może doprowadzić do niepoprawnego wyświetlania sklepu u klientów.',5099,1),(88758,'OK',5100,1),(88759,'Anuluj',5101,1),(88760,'brak',5102,1),(88761,'Górne obramowanie',5103,1),(88762,'Prawe obramowanie',5104,1),(88763,'Dolne obramowanie',5105,1),(88764,'Lewe obramowanie',5106,1),(88765,'Rozdziel strony obramowania',5107,1),(88766,'Rozciągnij',5108,1),(88767,'Zwinięty',5109,1),(88768,'Dodaj boks',5110,1),(88769,'Usuń boks',5111,1),(88770,'Zestaw atrybutów',5112,1),(88771,'Zapisz jako wybrany zestaw',5113,1),(88772,'Zapisz zestaw pod nową nazwą',5114,1),(88773,'Usuń wybrany zestaw',5115,1),(88774,'Wprowadzono niezapisane zmiany',5116,1),(88775,'Do wybranego zestawu danych technicznych wprowadzono niezapisane zmiany. Jeśli zmienisz zestaw bez ich zapisania, zmiany zostaną utracone. Czy zapisać zmiany?',5117,1),(88776,'Zapisz',5118,1),(88777,'Nie zapisuj',5119,1),(88778,'Anuluj zmianę zestawu',5120,1),(88779,'Zapisano zestaw',5121,1),(88780,'Zapisanie zestawu powiodło się.',5122,1),(88781,'Podaj nazwę nowego zestawu atrybutów',5123,1),(88782,'Po usunięciu zestawu, ten produkt nadal będzie posiadał obecnie wybrane dane techniczne, jednak nie będzie możliwe przypisanie ich zestawu innemu produktowi dopóki nie zostanie utworzony nowy zestaw. Czy na pewno chcesz usunąć ten zestaw?',5124,1),(88783,'Usunięto zestaw',5125,1),(88784,'Usunięcie zestawu powiodło się.',5126,1),(88785,'Dodaj nową grupę danych',5127,1),(88786,'Usuń tę grupę danych',5128,1),(88787,'Zapisz',5129,1),(88788,'Usuń',5130,1),(88789,'Zapisano zmiany w grupie',5131,1),(88790,'Zapisanie zmian w grupie danych powiodło się.',5132,1),(88791,'Usuń wybraną grupę danych technicznych',5133,1),(88792,'Trwałe usunięcie spowoduje zniknięcie tej grupy także w przypadku innych produktów. Czy chcesz kontynuować?',5134,1),(88793,'Usunięto grupę danych',5135,1),(88794,'Usunięcie grupy danych powiodło się.',5136,1),(88795,'Zapisz',5137,1),(88796,'Usuń',5138,1),(88797,'Usuń wybrany atrybut',5139,1),(88798,'Trwałe usunięcie spowoduje zniknięcie tego atrybutu także w przypadku innych produktów. Czy chcesz kontynuować?',5140,1),(88799,'Usunięto atrybut',5141,1),(88800,'Usunięcie atrybutu powiodło się.',5142,1),(88801,'Zapisano zmiany w atrybucie',5143,1),(88802,'Zapisanie zmian w atrybucie powiodło się.',5144,1),(88803,'Edytuj wybrany atrybut',5145,1),(88804,'Edytuj wybraną grupę',5146,1),(88805,'Dodaj nowy atrybut do tej grupy',5147,1),(88806,'Edytuj tę wartość w innych językach',5148,1),(88807,'Prosta wartość',5149,1),(88808,'Wartość wielojęzyczna',5150,1),(88809,'Dłuższy tekst',5151,1),(88810,'Obraz',5152,1),(88811,'Tak/Nie',5153,1),(88812,'Kliknij przycisk Uruchom',5154,1),(88813,'Uruchom',5155,1),(88814,'Operacja zakończona powodzeniem',5156,1),(88815,'Pomoc',5157,1),(88816,'Produkt został usunięty z bazy!',5158,1),(88817,'Dostosuj szybki dostęp',5159,1),(88818,'Kiedy podmenu ma zostać rozwinięte?',5160,1),(88819,'Po kliknięciu',5161,1),(88820,'Po najechaniu kursorem',5162,1),(88821,'Po najechaniu kursorem i opóźnieniu',5163,1),(88822,'ms',5164,1),(88824,'Zapamiętaj zawartość i kolejność menu szybkiego dostępu',5165,1),(88825,'Przywróć domyślne',5166,1),(88826,'Przywróć domyślną zawartość i kolejność menu szybkiego dostępu',5167,1),(88828,'Anuluj',5168,1),(88829,'OK',5169,1),(88830,'OK',5170,1),(88831,'Tak',5171,1),(88832,'Nie',5172,1),(88833,'Pokaż menu szybkiego dostępu',5173,1),(88839,'Klawisz szybkiego dostępu',5174,1),(88842,'Typ numeracji faktur',5175,1),(88843,'Termin płatności',1369,1),(88844,'Osoba wystawiająca fakturę',5176,1),(88845,'Zapłacono',5177,1),(88846,'Dodaj fakturę',5178,1),(88847,'dni',5179,1),(88848,'Domyślny termin płatności',5180,1),(88849,'Numer faktury',1346,1),(88851,'VAT',5182,1),(88852,'Korygująca',5183,1),(88853,'PROFORMA',5181,1),(88854,'Data wystawienia',5184,1),(88855,'Wystaw fakturę PROFORMA',5185,1),(88856,'Wystaw fakturę VAT',5186,1),(88857,'Zapłacono',5187,1),(88858,'Pozostało do zapłaty',5188,1),(88859,'Pole może zawierać tylko cyfry, małe i duże litery',5189,1),(88860,'Stronicowanie wyników',5190,1),(88861,'Wyłącz wyświetlanie kategorii',5191,1),(88862,'Wyłącz wyświetlanie produktu',5192,1),(88866,'Konfiguracja oprogramowania',5196,1),(88868,'Wymuś usunięcie index.php z linków',5198,1),(88870,'Zaznacz jeżeli chcesz wymusić usunięcie index.php z linków bez włączonego mod-rewrite. Opcja zależna od konfiguracji serwera.',5199,1),(88871,'Ustawienia e-mail',5200,1),(88872,'Rodzaj mailera',5201,1),(88873,'Serwer poczty',5202,1),(88874,'Port serwera poczty',5203,1),(88875,'Bezpieczne połączenie',5204,1),(88876,'Autentyfikacja SMTP',5205,1),(88877,'Login do serwera SMTP',5206,1),(88878,'Hasło do serwera SMTP',5207,1),(88879,'Nadawca e-mail',5208,1),(88880,'E-mail nadawcy',5209,1),(88881,'Zachowaj proporcje',1641,1),(88882,'Ustaw wymiary małych zdjęć w sklepie',5210,1),(88883,'Ustaw wymiary średnich zdjęć w sklepie',5211,1),(88884,'Ustaw wymiary dużych zdjęć w sklepie',5212,1),(88885,'Odśwież dane SEO',5213,1),(88886,'Moduły płatności',5214,1),(88887,'Adres panelu',5197,1),(88888,'Jeżeli zmienisz adres panelu po zapisaniu ustawień musisz ponownie się zalogować.',5195,1),(88889,'Porównywarki i pasaże',5215,1),(88890,'Informacje META',5216,1),(88891,'Producenci',5217,1),(88892,'Lista produktów producenta',5218,1),(88893,'Dostępni producenci',5219,1),(88894,'Separator dziesiętny',5220,1),(88895,'Ilość miejsc dziesiętnych',5221,1),(88896,'Separator tysięcy',5222,1),(88897,'Preffix dodatni',5223,1),(88898,'Suffix dodatni',5224,1),(88899,'Preffix ujemny',5225,1),(88900,'Suffix ujemny',5226,1),(88901,'Filtruj',5227,1),(88902,'Favicon',5228,1),(88903,'Nie udało się połączyć z kanałem aktualizacji',5229,1),(88904,'Cena promocyjna',5230,1),(88906,'Ustaw cenę sprzedaży dla grupy',5232,1),(88907,'Cena dla grupy',5233,1),(88909,'Cena standardowa',5235,1),(88910,'Ustaw cenę promocyjną dla tej grupy',5231,1),(88911,'Ustaw cenę promocyjną',5234,1),(88912,'Schowek',5236,1),(88913,'Zobacz koszyk',5237,1),(88914,'Promocja do',5238,1),(88915,'Pokaż zdjęcie producenta',5239,1),(88916,'Pokaż opis producenta',5240,1),(88917,'Bot',5241,1),(88918,'Mobile',5242,1),(88919,'Przeglądarka',5243,1),(88920,'System',5244,1),(88921,'Ostatni adres',5245,1),(88922,'Wyloguj',1,1),(88923,'Ustawienia API',5246,1),(88924,'Klucz API',5247,1),(88925,'Twoje imię',5248,1),(88926,'Twój e-mail',5249,1),(88927,'Imię znajomego',5250,1),(88928,'E-mail znajomego',5251,1),(88929,'Podaj imię znajomego',5252,1),(88930,'Podaj e-mail znajomego',5253,1),(88931,'Ilość rekordów na stronie',5254,1),(88932,'Akcja po kliknięciu w rekord w liście',5255,1),(88933,'Wejdź w edycję',5256,1),(88934,'Pokaż menu kontektstowe',5257,1),(88935,'Ustawienia interfejsu panelu',5258,1),(88936,'Cena przed promocją',5259,1),(88937,'Wybierz klientów',5260,1),(88938,'Wyślij link znajomemu',5261,1),(88939,'Wiadomość przekazana z serwisu',5262,1),(88940,'poleca stronę, która znajduje się pod adresem',5263,1),(88941,'z komentarzem:',5264,1),(88942,'Polecam Ci',5265,1),(88943,'Wystawianie faktur nie jest możliwe dla klientów nieposiadających numeru NIP.',5266,1),(88944,'Płatność poprzez system platnosci.pl została anulowana. Skontaktuj się z obsługą sklepu aby ustalić szczegóły zapłaty za zamówienie.',5267,1),(88945,'Odśwież szablonami standardowymi',5268,1),(88946,'Wszystkie maile zostaną odświeżone za pomocą plików standardowych dostępnych w design/_tpl/mailerTemplates. Wprowadzone zmiany w szablonach zostaną utracone. Czy kontynuować ?',5269,1),(88947,'Instalacja modułu z Gekolab',5270,1),(88948,'Migracja danych z innego sklepu',5271,1),(88949,'Ustawienia połączenia ze sklepem',5272,1),(88950,'Adres modułu migracji',5273,1),(88951,'Klucz zabezpieczający',5274,1),(90833,'Migracja danych',5462,1),(90834,'Moduł',5463,1),(90835,'Tryb offline',5464,1),(90838,'Sklep będzie widoczny tylko dla zalogowanych administratorów',5465,1),(90839,'Ten moduł musisz podpiąć pod wybrane sklepy i skonfigurować osobno dla każdego z nich po przełączeniu.',5466,1),(90840,'Zapytanie o produkt',5467,1),(90841,'Zapytaj o cenę',5468,1),(90842,'Domyślny komentarz dla zamówienia',5469,1),(90843,'Tłumaczenia',5470,1),(90844,'Lista boksów',5471,1),(90845,'Pliki do zamówienia',5472,1),(90846,'Zobacz raport',5473,1),(90847,'Miniatura',5474,1),(90848,'Niepoprawny adres e-mail',5475,1),(90849,'Wystąpił błąd',5476,1),(90850,'Głębokość',5477,1),(90851,'Ustawienia GekoLab',5478,1),(90852,'Klucz autoryzacyjny GekoLab',5479,1),(90853,'Poprzednie zamówienie',5480,1),(90854,'Następne zamówienie',5481,1),(90855,'Nazwa produktu',409,1),(90856,'URL produktu',1928,1),(90857,'Wyświetl produkt w sklepie',5193,1),(90858,'Wyświetlaj kategorię w sklepie',5194,1),(90860,'Dodaj nowy adres',2066,1),(90861,'Edytuj ustawienia adresu',2067,1),(90862,'Ustawienia adresów URL',1986,1),(90863,'Integracja z Facebook',5383,1),(90864,'ID aplikacji Facebook',5482,1),(90865,'Klucz (secret) aplikacji Facebook',5483,1),(90866,'Zaloguj się korzystając ze swojego konta na Facebook',5484,1),(90867,'Zarejestruj się korzystając z konta Facebook',5485,1),(90869,'Adres dostawy jest taki sam jak płatnika',4700,1),(90870,'Producenci',919,1),(90871,'netto',1883,1),(90872,'Cena netto',5486,1),(90873,'Jednostka miary',850,1),(90874,'sztuka',5487,1),(90875,'metr kwadratowy',5488,1),(90876,'Facebook \"Lubię to\"',5489,1),(90879,'Edycja aktualności',975,1),(90880,'Aktualności',18,1),(90881,'Dodaj',973,1),(90882,'Tytuł',2043,1),(90883,'Słowa kluczowe',2044,1),(90885,'Opis',2045,1),(90886,'Adres URL',1942,1),(90887,'Dostępność',5490,1),(90888,'W magazynie',5491,1),(90889,'Brak',5492,1),(90890,'Dodaj swoją opinię',5493,1),(90892,'Skrót aktualności',5494,1),(90893,'Czytaj dalej',5495,1),(90894,'Google Analytics',4313,1),(90895,'Rejestracja w sklepie nie jest możliwa w tym momencie',5496,1),(90896,'Ustawienia rejestracji i logowania',5497,1),(90897,'Rejestracja włączona',5498,1),(90898,'Jeżeli wyłączona to zakładanie kont możliwe jest tylko w panelu administracyjnym',5499,1),(90899,'Potwierdzanie rejestracji linkiem',5500,1),(90900,'Konta nieaktywne do czasu kliknięcia w link potwierdzający rejestrację',5501,1),(90901,'Wymuś zalogowanie w sklepie',4702,1),(90902,'Oferta sklepu będzie niewidoczna do czasu zalogowania',5502,1),(90903,'Przekierowanie po dodaniu produktu',4391,1),(90904,'Wiadomości',5503,1),(90905,'Faktury',5504,1),(90906,'Eksport zaznaczonych',5505,1),(90907,'Nie wybrano żadnych faktur do eksportu',5506,1),(90908,'Podaj indywidualny koszt wysyłki jednej sztuki produktu. Koszty wysyłki kalkulowane wagowo lub cenowo zostaną pominięte dla tego produktu i zostanie zastosowany koszt indywidualny. Koszt brutto zostanie obliczony w oparciu o stawkę VAT dla wybranej przez klienta formy wysyłki.',5507,1),(90909,'Ustawienia dostawy',5508,1),(90910,'Koszt dostawy',5509,1),(90911,'Dostawa i magazyn',5510,1),(90912,'Odznacz wybraną kategorię',5511,1),(90913,'Włącz tą opcję jeżeli chcesz korzystać z szyfrowania stron oraz posiadasz działający certyfikat SSL',5512,1),(90914,'Włącz SSL',5513,1),(90915,'Ilość opinii',5514,1),(90916,'Siatka',5515,1),(90917,'Lista',5516,1),(90918,'Cechy statyczne',5517,1),(90919,'Produkt występuje w zamówieniach. Możliwe jest tylko jego wyłączenie.',5518,1),(90920,'Wymagana aktywacja konta',5519,1),(90921,'Aktywuj swoje konto w sklepie',5520,1),(92786,'Wysyłanie powiadomień',5522,1),(92787,'Postęp wysyłania',5523,1),(92788,'Ustawienia zamówień',5524,1),(92789,'Wymagaj potwierdzania zamówień',5525,1),(92790,'Status zamówienia potwierdzonego',5526,1),(92791,'Akcesoria do produktu',690,1),(92792,'Postęp',5527,1),(92793,'Eksportuj zamówienia',5528,1),(92794,'Włącz zamówienia bez rejestracji',5529,1),(92795,'Aby kontynuować proces zamawiania musisz być zalogowany.',5530,1),(92796,'Poprzednia cena',5531,1),(92797,'Biała lista adresów IP',5532,1),(92798,'Wybrane produkty',5533,1),(92799,'Lista kategorii głównych',5534,1),(92800,'Użyj kuponu',5535,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `transmail` (`idtransmail`, `name`, `transmailactionid`, `contenttxt`, `contenthtml`, `addid`, `adddate`, `editid`, `editdate`, `parentid`, `viewid`, `active`, `transmailheaderid`, `transmailfooterid`, `filename`, `title`) VALUES (1,'Aktywacja klienta przez panel administracyjny',1,'','<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ACCOUNT_CLIENT_ACTIVATION{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>',1,'2011-11-28 21:25:25',1,'2011-08-12 11:56:40',NULL,NULL,1,1,1,NULL,NULL),(2,'Dodanie adresu do książki adresowej klienta',2,' ','<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ADDRESS_ADD_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-16 20:57:12',1,'2011-03-16 20:57:12',NULL,NULL,1,1,1,NULL,NULL),(3,'Rejestracja klienta w sklepie',3,'','<tr>\r\n	<td style=\"text-align: justify\" align=\"left\" valign=\"top\"><font\r\n		size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n		<p>{trans}TXT_CLIENT_REGISTRATION_CONTENT{/trans}</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	{if isset($activelink) && $activelink != \'\'}\r\n	<h3><a href=\"{$URL}{seo controller=registrationcart}/{$activelink}\">{trans}TXT_ACTIVATE_CLIENT_ACCOUNT{/trans}</a></h3>\r\n	{/if}\r\n	<p><font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />\r\n	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n	{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n	{trans}TXT_LOG{/trans} : {$address.email}<br>\r\n	{trans}TXT_PHONE{/trans} : {$address.phone}<br>\r\n	{trans}TXT_PASSWORD{/trans} : {if isset	($address.password)}{$address.password}{else}{$password}{/if}<br>\r\n	</p>\r\n	\r\n	</td>\r\n</tr>\r\n',1,'2011-12-19 22:11:56',1,'2011-05-10 21:28:20',NULL,NULL,1,1,1,NULL,NULL),(4,'Dodanie klienta przez panel administracyjny',4,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font></td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$personal_data.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$personal_data.surname} <br>\r\n			{trans}TXT_LOG{/trans} : {$personal_data.email}<br>\r\n			{trans}TXT_PASSWORD{/trans} : {$personal_data.password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	{trans}TXT_PHONE{/trans} : {$personal_data.phone}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:08',1,'2011-03-09 22:59:08',NULL,NULL,1,1,1,NULL,NULL),(5,'Dodanie klienta do newsletter',5,NULL,'{if isset($newsletterlink)}\r\n	      <tr>\r\n	        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        	<p>\r\n					{trans}TXT_CLIENT_REGISTRATION_NEWSLETTER{/trans}<br/>\r\n					\r\n					<font color=\"red\"><strong><a href=\"{$URL}newsletter/index/{$newsletterlink}\">{trans}TXT_ACTIVE_NEWSLETTER_LINK{/trans}</a></strong></font><br/></br>\r\n					<a href=\"{$URL}newsletter/index/{$unwantednewsletterlink}\">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>\r\n				</p>\r\n	        </td>\r\n	      </tr>\r\n	   {else}\r\n		   <tr>\r\n	       <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>Wypisujesz się z newsletter</b></font>\r\n	       	<p>					\r\n				<a href=\"{$URL}newsletter/index/{$unwantednewsletterlink}\">{trans}TXT_UNWANTED_ACTIVE_NEWSLETTER_LINK{/trans}</a>\r\n			</p>\r\n	       </td>\r\n	     </tr>\r\n	   {/if}',1,'2011-03-09 22:59:15',1,'2011-03-09 22:59:15',NULL,NULL,1,1,1,NULL,NULL),(6,'Kontakt- kopia treści wiadomości formularza kontaktowego',6,'{$CONTACT_CONTENT}\r\n\r\n{$firstname} {$surname} \r\n{$email}\r\n{$phone}','<tr>\r\n    <td>{$CONTACT_CONTENT}</td>\r\n  </tr>\r\n <tr>\r\n    	<td>\r\n	{$firstname} {$surname} <br />\r\n	{$email}<br />\r\n	{$phone}<br />\r\n	</td>\r\n  </tr>',1,'2011-04-29 16:56:18',1,'2011-04-29 16:56:18',NULL,NULL,1,1,1,NULL,NULL),(7,'Edycja adresu przez klienta',7,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_ADDRESS_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n	      	</p>\r\n      	</td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_ADDRESS{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$address.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$address.surname} <br>\r\n			{trans}TXT_PLACENAME{/trans} : {$address.placename}<br>\r\n			{trans}TXT_POSTCODE{/trans} : {$address.postcode}<br>\r\n	      	{trans}TXT_STREET{/trans} : {$address.street} <br>\r\n	      	{trans}TXT_STREETNO{/trans} : {$address.streetno}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:27',1,'2011-03-09 22:59:27',NULL,NULL,1,1,1,NULL,NULL),(8,'Edycja adresu E-mail (loginu) przez klienta',8,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_EMAIL_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n			{trans}TXT_OLD_EMAIL{/trans} : {$clientdata.email} <br>\r\n			{trans}TXT_NEW_EMAIL{/trans} : {$EMAIL_NEW}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:33',1,'2011-03-09 22:59:33',NULL,NULL,1,1,1,NULL,NULL),(9,'Edycja hasła przez klienta',9,NULL,'<tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_CHANGE_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$clientdata.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$clientdata.surname} <br> \r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$PASS_NEW}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:38',1,'2011-03-09 22:59:38',NULL,NULL,1,1,1,NULL,NULL),(10,'Edycja hasła użytkownika panelu administracyjnego',10,NULL,'\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_EDIT_USER{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_EDIT_PASSWORD_USER{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:44',1,'2011-03-09 22:59:44',NULL,NULL,1,1,1,NULL,NULL),(11,'Wygenerowanie nowego hasła dla klienta sklepu (zapomniane hasło)',11,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_FORGOT_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password} <br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 22:59:53',1,'2011-03-09 22:59:53',NULL,NULL,1,1,1,NULL,NULL),(12,'Przypomnienie loginu użytkownikowi panelu administracyjnego',12,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_PASSWORD_FORGOT_CONTENT{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_USERS{/trans}: </b></font><br/>\r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 23:00:03',1,'2011-03-09 23:00:03',NULL,NULL,1,1,1,NULL,NULL),(13,'Wygenerowanie nowego hasła dla użytkownika panelu',13,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_NEW_USER{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n	        <font color=\"#f6b900\"><b>{trans}TXT_NEW_USER{/trans}: </b></font><br/>\r\n	     	{trans}TXT_FIRSTNAME{/trans} : {$users.firstname} <br>\r\n			{trans}TXT_SURNAME{/trans} : {$users.surname} <br> \r\n			{trans}TXT_EMAIL{/trans} : {$users.email} <br> \r\n			{trans}TXT_NEW_PASSWORD{/trans} : {$password}<br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-03-09 23:00:12',1,'2011-03-09 23:00:12',NULL,NULL,1,1,1,NULL,NULL),(14,'Wysłanie newslettera do klientów sklepu ze strony panelu administracyj',14,NULL,'{$newsletter.htmlform}',1,'2011-03-09 23:00:20',1,'2011-03-09 23:00:20',NULL,NULL,1,1,1,NULL,NULL),(15,'Potwierdzenie złożenia zamówienia przez klienta w sklepie',15,'','<tr>\r\n	<td><font size=\"+1\"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br />\r\n	<table>\r\n		<thead>\r\n			<tr>\r\n				<th class=\"name\">{trans}TXT_PRODUCT_NAME{/trans}:</th>\r\n				<th class=\"price\">{trans}TXT_PRODUCT_PRICE{/trans}:</th>\r\n				<th class=\"quantity\">{trans}TXT_QUANTITY{/trans}:</th>\r\n				<th class=\"subtotal\">{trans}TXT_VALUE{/trans}:</th>\r\n			</tr>\r\n		</thead>\r\n		<tbody>\r\n		{foreach name=outer item=product from=$order.cart} \r\n			{if isset($product.standard)}\r\n			<tr>\r\n				<th>{$product.name}</th>\r\n				<td>{price}{$product.newprice}{/price}</td>\r\n				<td>{$product.qty} {trans}TXT_QTY{/trans}</td>\r\n				<td>{price}{$product.qtyprice}{/price}</td>\r\n			</tr>\r\n			{/if}\r\n			{foreach name=inner item=attributes from=$product.attributes}\r\n				<tr>\r\n					<th>{$attributes.name}<br />\r\n					{foreach name=f item=features from=$attributes.features} <small>\r\n					{$features.attributename}&nbsp;&nbsp;</small> {/foreach}</th>\r\n					<td>{price}{$attributes.newprice}{/price}</td>\r\n					<td>{$attributes.qty} {trans}TXT_QTY{/trans}</td>\r\n					<td>{price}{$attributes.qtyprice}{/price}</td>\r\n				</tr>\r\n			{/foreach} \r\n		{/foreach}\r\n		</tbody>\r\n	</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}: </b></font><br />\r\n	{if isset($order.rulescart)}\r\n		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>\r\n		<p>{$order.rulescart}: <strong>{$order.rulescartmessage}</strong></p>\r\n		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>\r\n		<p>{trans}TXT_VIEW_ORDER_TOTAL{/trans}: <strong>{price}{$order.priceWithDispatchMethodPromo}{/price}</strong></p>\r\n	{else}\r\n		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>\r\n		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>\r\n		<p>{trans}TXT_ALL_ORDERS_PRICE_GROSS{/trans}: <strong>{price}{$order.priceWithDispatchMethod}{/price}</strong></p>\r\n	{/if}\r\n	<p>{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}</p>\r\n	<p>{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}</p>\r\n	</td>\r\n</tr>\r\n{if $confirmorder == 1}\r\n<tr>\r\n	<td>\r\n	<p>{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />\r\n	<a href=\"{$URL}confirmation/index/{$orderlink}\">{$URL}confirmation/index/{$orderlink}</a>\r\n	</p>\r\n	</td>\r\n</tr>\r\n{/if}\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />\r\n	{if $order.clientaddress.companyname !=\r\n	\'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname}\r\n	<br>{/if} {if $order.clientaddress.nip != \'\'}{trans}TXT_NIP{/trans} :\r\n	{$order.clientaddress.nip} \r\n	\r\n	\r\n	<br>{/if} {trans}TXT_FIRSTNAME{/trans} :\r\n	{$order.clientaddress.firstname} \r\n	\r\n	\r\n	<br> {trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} \r\n	\r\n	\r\n	<br> {trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}\r\n	\r\n	\r\n	<br> {trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}\r\n	\r\n	\r\n	<br> {trans}TXT_STREET{/trans} : {$order.clientaddress.street} \r\n	\r\n	\r\n	<br> {trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}\r\n	\r\n	\r\n	<br> {trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}\r\n	\r\n	\r\n	<br> {trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}\r\n	\r\n	\r\n	<br> {trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}\r\n	\r\n	\r\n	<br>\r\n	</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />\r\n	{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>\r\n	{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} \r\n	\r\n	\r\n	<br> {trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}\r\n	\r\n	\r\n	<br> {trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}\r\n	\r\n	\r\n	<br> {trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} \r\n	\r\n	\r\n	<br> {trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}\r\n	\r\n	\r\n	<br> {trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}\r\n	\r\n	\r\n	<br> {trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}\r\n	\r\n	\r\n	<br> {trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}\r\n	\r\n	\r\n	<br>\r\n	</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td><font size=\"+1\"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br />\r\n	<p>{$order.customeropinion}</p>\r\n	</td>\r\n</tr>\r\n{if isset($orderfiles)}\r\n{foreach from=$orderfiles item=file key=key}\r\n<tr>\r\n	<td><a href=\"{$URL}upload/order/{$key}\">{$URL}upload/order/{$key}</a></td>\r\n</tr>\r\n{/foreach}\r\n{/if}\r\n',1,'2012-02-07 17:40:00',1,'2011-08-19 09:14:42',NULL,NULL,1,1,1,NULL,NULL),(16,'Potwierdzenie zmiany statusu zamówienia klienta',16,'','      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				{trans}TXT_VIEW_ORDER_HISTORY{/trans}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      	<td>\r\n	      	<p>\r\n		        <font color=\"#f6b900\"><b>{trans}TXT_CLIENT{/trans}: {$orderhistory.firstname} {$orderhistory.surname}</b></font><br/>\r\n				{trans}TXT_COMMENT{/trans} : {$orderhistory.content} <br> \r\n				{trans}TXT_STATUS{/trans} : {$orderhistory.orderstatusname} <br>\r\n	      	</p>\r\n      	</td>\r\n      </tr>',1,'2011-08-12 12:33:03',1,'2011-08-12 12:33:03',NULL,NULL,1,1,1,NULL,NULL),(17,'Kopia złożenia zamówienia klienta wysłana do administratora sklepu',17,'','<tr>\r\n	<td><font size=\"+1\"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br />\r\n	<table>\r\n		<thead>\r\n			<tr>\r\n				<th class=\"name\">{trans}TXT_PRODUCT_NAME{/trans}:</th>\r\n				<th class=\"price\">{trans}TXT_PRODUCT_PRICE{/trans}:</th>\r\n				<th class=\"quantity\">{trans}TXT_QUANTITY{/trans}:</th>\r\n				<th class=\"subtotal\">{trans}TXT_VALUE{/trans}:</th>\r\n			</tr>\r\n		</thead>\r\n		<tbody>\r\n		{foreach name=outer item=product from=$order.cart} \r\n			{if isset($product.standard)}\r\n			<tr>\r\n				<th>{$product.name}</th>\r\n				<td>{price}{$product.newprice}{/price}</td>\r\n				<td>{$product.qty} {trans}TXT_QTY{/trans}</td>\r\n				<td>{price}{$product.qtyprice}{/price}</td>\r\n			</tr>\r\n			{/if}\r\n			{foreach name=inner item=attributes from=$product.attributes}\r\n				<tr>\r\n					<th>{$attributes.name}<br />\r\n					{foreach name=f item=features from=$attributes.features} <small>\r\n					{$features.attributename}&nbsp;&nbsp;</small> {/foreach}</th>\r\n					<td>{price}{$attributes.newprice}{/price}</td>\r\n					<td>{$attributes.qty} {trans}TXT_QTY{/trans}</td>\r\n					<td>{price}{$attributes.qtyprice}{/price}</td>\r\n				</tr>\r\n			{/foreach} \r\n		{/foreach}\r\n		</tbody>\r\n	</table>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}: </b></font><br />\r\n	{if isset($order.rulescart)}\r\n		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>\r\n		<p>{$order.rulescart}: <strong>{$order.rulescartmessage}</strong></p>\r\n		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>\r\n		<p>{trans}TXT_VIEW_ORDER_TOTAL{/trans}: <strong>{price}{$order.priceWithDispatchMethodPromo}{/price}</strong></p>\r\n	{else}\r\n		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>\r\n		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>\r\n		<p>{trans}TXT_ALL_ORDERS_PRICE_GROSS{/trans}: <strong>{price}{$order.priceWithDispatchMethod}{/price}</strong></p>\r\n	{/if}\r\n	<p>{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}</p>\r\n	<p>{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}</p>\r\n	</td>\r\n</tr>\r\n<!--<tr>-->\r\n<!--	<td>-->\r\n<!--	<p>{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />-->\r\n<!--	<a href=\"{$URL}confirmation/index/{$orderlink}\">{$URL}confirmation/index/{$orderlink}</a>-->\r\n<!--	</p>-->\r\n<!--	</td>-->\r\n<!--</tr>-->\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />\r\n	{if $order.clientaddress.companyname !=\r\n	\'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname}\r\n	<br>{/if} {if $order.clientaddress.nip != \'\'}{trans}TXT_NIP{/trans} :\r\n	{$order.clientaddress.nip} \r\n	\r\n	\r\n	<br>{/if} {trans}TXT_FIRSTNAME{/trans} :\r\n	{$order.clientaddress.firstname} \r\n	\r\n	\r\n	<br> {trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} \r\n	\r\n	\r\n	<br> {trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}\r\n	\r\n	\r\n	<br> {trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}\r\n	\r\n	\r\n	<br> {trans}TXT_STREET{/trans} : {$order.clientaddress.street} \r\n	\r\n	\r\n	<br> {trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}\r\n	\r\n	\r\n	<br> {trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}\r\n	\r\n	\r\n	<br> {trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}\r\n	\r\n	\r\n	<br> {trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}\r\n	\r\n	\r\n	<br>\r\n	</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td>\r\n	<p><font size=\"+1\"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />\r\n	{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>\r\n	{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} \r\n	\r\n	\r\n	<br> {trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}\r\n	\r\n	\r\n	<br> {trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}\r\n	\r\n	\r\n	<br> {trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} \r\n	\r\n	\r\n	<br> {trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}\r\n	\r\n	\r\n	<br> {trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}\r\n	\r\n	\r\n	<br> {trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}\r\n	\r\n	\r\n	<br> {trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}\r\n	\r\n	\r\n	<br>\r\n	</p>\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td><font size=\"+1\"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br />\r\n	<p>{$order.customeropinion}</p>\r\n	</td>\r\n</tr>\r\n',1,'2011-12-19 22:12:47',1,'2011-08-11 15:01:53',NULL,NULL,1,1,1,NULL,NULL),(18,'Poleć znajomemu',18,'','<tr>\r\n	<td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n    	<p>{trans}TXT_RECOMMEND_SHOP_NAME{/trans} {$SHOP_NAME}</p>\r\n		<p>{$fromname} ({$fromemail}) {trans}TXT_RECOMMEND_ADDRESS{/trans} <a href=\"{$recommendurl}\">{$recommendurl}</a> {trans}TXT_RECOMMEND_COMMENT{/trans} {$comment}.</p>\r\n	</td>\r\n</tr>',1,'2011-08-04 12:57:50',1,'2011-08-04 12:57:50',NULL,NULL,1,1,1,NULL,NULL),(19,'Żagiel- potwierdzenie złożenia wniosku o kredyt',20,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        <p>\r\n	    		<font color=\"#f6b900\"><b>{trans}TXT_CUSTOMER{/trans}: {$clientOrder.firstname} {$clientOrder.surname} </b></font>\r\n	    		<br/><br/>\r\n	        	Zarejestrowany został wniosek o kredyt ratalny w systemie Żagiel. <br/><br/>\r\n	        	Administrator sklepu potwierdzi rezerwację towaru w możliwie najszybszym czasie.\r\n	        	Proszę czekać na kontakt z konsultantem systemu ratalnego Żagiel.\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n      <td height=\"20\" align=\"left\" valign=\"top\" style=\"text-align:justify\">Pamiętaj numer zamówienia: <strong> {$idorder} </strong></td>\r\n      </tr>',1,'2011-03-09 23:01:18',1,'2011-03-09 23:01:18',NULL,NULL,1,1,1,NULL,NULL),(20,'Żagiel- rezygnacja ze złożenia wniosku o kredyt',21,NULL,'      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n	        <p>\r\n	        	Zrezygnowałeś ze złożenia wniosku w systemie ratalnym Żagiel. Aby dokończyć zamówienie,\r\n	        	skontaktuj się z administratorem sklepu w celu wybrania innej metody płatności. \r\n	        	Twój numer zamówienia: <strong> {$idorder} </strong>\r\n			</p>\r\n        </td>\r\n      </tr>',1,'2011-03-09 23:01:28',1,'2011-03-09 23:01:28',NULL,NULL,1,1,1,NULL,NULL),(21,'test',19,NULL,'{literal}\r\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>{trans}MAIL_TITLE{/trans}</title>\r\n	<style type=\"text/css\">\r\n		body,td,th {\r\n			font-size: 11px;\r\n			color: #575656;\r\n			font-family: Arial;\r\n		}\r\n		body {\r\n			margin-left: 0px;\r\n			margin-top: 0px;\r\n			margin-right: 0px;\r\n			margin-bottom: 0px;\r\n		}\r\n		a:link {\r\n			color: #969696;\r\n			text-decoration: none;\r\n		}\r\n		a:visited {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n		a:hover {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n		a:active {\r\n			text-decoration: none;\r\n			color: #969696;\r\n		}\r\n	</style>\r\n</head>\r\n{/literal} <body>\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td>&nbsp;</td>\r\n    <td width=\"500\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n      <tr>\r\n        <td height=\"96\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n          <tr>\r\n            <td height=\"96\" align=\"left\" valign=\"middle\"><img src=\'cid:logo\' alt=\"Logo Sklep Internetowy\"/></td>\r\n          </tr>\r\n        </table></td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"23\" align=\"left\" valign=\"top\"><hr noshade=\"noshade\" size=\"1\" color=\"#e8e8e8\"/></td>\r\n      </tr>\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+2\"><b>{trans}TXT_HEADER_NAME{/trans}</b></font>\r\n   		<br/>{trans}TXT_HEADER_INFO{/trans}\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"38\" align=\"left\" valign=\"top\">&nbsp;</td>\r\n      </tr>\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+1\"><b>{trans}TXT_WELCOME{/trans}</b></font>\r\n        	<p>\r\n				<strong>{$active.firstname} {$active.surname} </strong>\r\n			</p>\r\n        </td>\r\n       </tr>\r\n       <tr>  \r\n        <td>\r\n        	<p>\r\n				{$addressURL}\r\n			</p>\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"20\" align=\"left\" valign=\"top\" style=\"text-align:justify\">&nbsp;</td>\r\n      </tr>\r\n    </table>\r\n   </td>\r\n    <td>&nbsp;</td>\r\n  </tr>   <tr>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td width=\"500\" height=\"10\" align=\"left\" valign=\"top\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n    <td width=\"500\" height=\"70\" align=\"center\" valign=\"middle\" bgcolor=\"#2c2c2c\">\r\n    <font color=\"#b1b1b1\">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>',1,'2012-04-25 19:30:20',NULL,NULL,NULL,NULL,0,1,1,'test','test');
DROP TABLE IF EXISTS `transmailaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmailaction` (
  `idtransmailaction` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `side` varchar(1) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `filetpl` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  `isnotification` int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtransmailaction`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `transmailaction` (`idtransmailaction`, `name`, `side`, `description`, `filetpl`, `controller`, `action`, `isnotification`) VALUES (1,'Aktywacja klienta przez panel administracyjny','1','Dodanie klienta przez panel administracyjny','activationAccount','client','edit',0),(2,'Dodanie adresu do książki adresowej klienta','0','Dodanie adresu do książki adresowej klienta po stronie sklepu','addAddress','clientaddressbox','add',0),(3,'Rejestracja klienta w sklepie','0','Rejestracja klienta w sklepie','addClient','registrationcartbox','index',0),(4,'Dodanie klienta przez panel administracyjny','1','Dodanie nowego klienta po stronie panelu administracyjnego','addClientFromAdmin','client','add',0),(5,'Dodanie klienta do newsletter','0','Dodanie klienta do newslettera po stronie sklepu','addClientNewsletter','newsletter',NULL,0),(6,'Kontakt- kopia treści wiadomości formularza kontaktowego','0','Wysłanie kopii treści wiadomości przesłanej z formularza kontaktowego po stronie sklepu','contact','contactbox','index',0),(7,'Edycja adresu przez klienta','0','Wiadomość potwierdzająca edycję adresu przez klienta po stronie sklepu','editAddress','clientaddressbox','index|edit',0),(8,'Edycja adresu E-mail (loginu) przez klienta','0','Wiadomość potwierdzająca edycję adresu E-mail przez klienta po stronie sklepu','editMail','clientsettingsbox','index',0),(9,'Edycja hasła przez klienta','0','Wiadomość powierdzająca edycję hasła przez klienta po stronie sklepu','editPassword','clientsettingsbox','index',0),(10,'Edycja hasła użytkownika panelu administracyjnego','1','Edycja hasła użytkownika panelu administracyjnego','editPasswordForUser','user','edit',0),(11,'Wygenerowanie nowego hasła dla klienta sklepu (zapomniane hasło)','0','Wiadomość z nowym, wygenerowanym hasłem klienta sklepu (zapomniane hasło)','forgotPassword','forgotpasswordbox','index',0),(12,'Przypomnienie loginu użytkownikowi panelu administracyjnego','0','Wiadomość z przypomnieniem loginu do panelu administracyjnego dla uzytkownika','forgotUsers','forgotlogin','index',0),(13,'Wygenerowanie nowego hasła dla użytkownika panelu','1','Wygenerowanie nowego hasła dla użytkownika panelu','newPasswordForUser','users','add',0),(14,'Wysłanie newslettera do klientów sklepu ze strony panelu administracyj','1','Wysłanie newslettera do klientów sklepu ze strony panelu','newsletter','newsletter','add|edit',0),(15,'Potwierdzenie złożenia zamówienia przez klienta w sklepie','0','Wiadomość potwierdzająca złożenie zamówienia przez klienta w sklepie','orderClient','finalizationbox','index',0),(16,'Potwierdzenie zmiany statusu zamówienia klienta','1','Wiadomość potwierdzająca zmianę statusu zamówienia klienta','orderhistory','order','view|add|edit',0),(17,'Kopia złożenia zamówienia klienta wysłana do administratora sklepu','0','Kopia złożenia zamówienia klienta wysłana do administratora sklepu','orderUser','finalization','index',0),(18,'Poleć znajomemu','0','Wysłanie wiadomości z adresem URL','recommendfriend','recommendfriendbox','index',0),(19,'Powiadomienie o aktywności klienta','1','Wysyłanie wiadomości do klietna dot. o powiadomieniu aktywności','substituteservice','substituteservice','index|add|edit',1),(20,'Żagiel- potwierdzenie złożenia wniosku o kredyt','0','Wiadomość potwierdzająca poprawne złożenie wniosku o kredyt w systemie ratalnym Żagiel','eratyAccept','eraty','accept',0),(21,'Żagiel- rezygnacja ze złożenia wniosku o kredyt','0','Wiadomość informująca o rezygnacji złożenia wniosku o kredyt w systemie ratalnym Żagiel','eratyCancel','eraty','cancel',0);
DROP TABLE IF EXISTS `transmailactiontag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmailactiontag` (
  `idtransmailactiontag` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transmailactionid` int(10) unsigned NOT NULL,
  `transmailtagsid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idtransmailactiontag`),
  KEY `FK_transmailactiontag_transmailactionid` (`transmailactionid`),
  KEY `FK_transmailactiontag_transmailtagsid` (`transmailtagsid`),
  CONSTRAINT `FK_transmailactiontag_transmailactionid` FOREIGN KEY (`transmailactionid`) REFERENCES `transmailaction` (`idtransmailaction`),
  CONSTRAINT `FK_transmailactiontag_transmailtagsid` FOREIGN KEY (`transmailtagsid`) REFERENCES `transmailtags` (`idtransmailtags`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `transmailactiontag` (`idtransmailactiontag`, `transmailactionid`, `transmailtagsid`) VALUES (1,2,1),(2,2,2),(3,2,10),(4,2,11),(5,2,19),(6,2,17),(7,2,14),(8,2,15),(9,3,10),(10,3,11),(11,3,12),(12,3,13),(13,3,19),(14,3,17),(15,3,14),(16,3,15),(17,3,20),(18,4,75),(19,4,76),(20,4,77),(21,4,78),(22,4,19),(23,4,17),(24,4,14),(25,4,15),(26,4,79),(27,6,85),(28,6,80),(29,6,81),(30,6,82),(31,7,1),(32,7,2),(33,7,10),(34,7,11),(35,7,19),(36,7,17),(37,7,14),(38,7,15),(39,8,1),(40,8,2),(41,8,4),(42,8,86),(43,9,1),(44,9,2),(45,9,87),(46,10,83),(47,11,83),(48,12,83),(49,13,35),(50,13,36),(51,13,34),(52,13,83),(53,14,38),(54,15,39),(55,15,40),(56,15,57),(57,15,58),(58,15,59),(59,15,88),(60,15,63),(61,15,64),(62,15,65),(63,15,66),(64,15,67),(65,15,68),(66,15,69),(67,15,70),(68,15,60),(69,15,61),(70,15,62),(71,15,39),(72,15,40),(73,15,41),(74,15,42),(75,15,43),(76,15,44),(77,15,45),(78,15,46),(79,15,47),(80,15,48),(81,15,49),(82,15,50),(83,15,51),(84,15,52),(85,15,53),(86,15,54),(87,15,55),(88,15,56),(89,16,71),(90,16,72),(91,16,73),(92,16,74),(93,17,39),(94,17,40),(95,17,57),(96,17,58),(97,17,59),(98,17,63),(99,17,64),(100,17,65),(101,17,66),(102,17,67),(103,17,68),(104,17,69),(105,17,70),(106,17,60),(107,17,61),(108,17,62),(109,17,39),(110,17,40),(111,17,41),(112,17,42),(113,17,43),(114,17,44),(115,17,45),(116,17,46),(117,17,47),(118,17,48),(119,17,49),(120,17,50),(121,17,51),(122,17,52),(123,17,53),(124,17,54),(125,17,55),(126,17,56),(127,18,8),(128,18,9),(129,18,84),(130,19,89),(131,19,90),(132,19,91),(133,19,92),(134,19,93),(135,19,94),(136,19,95),(137,19,96),(138,19,97),(139,5,98),(140,5,99),(143,15,102),(146,15,105),(147,20,106),(148,20,107),(149,20,108),(150,20,109),(151,20,110),(152,20,111),(153,20,112),(154,20,113),(155,20,114),(156,21,106),(157,21,107),(158,21,108),(159,21,109),(160,21,110),(161,21,111),(162,21,112),(163,21,113),(164,21,114);
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

INSERT INTO `transmailfooter` (`idtransmailfooter`, `name`, `contenthtml`, `contenttxt`, `addid`, `adddate`, `editid`, `editdate`, `viewid`) VALUES (1,'Domyślny szablon stopki',' <tr>\r\n        <td height=\"20\" align=\"left\" valign=\"top\" style=\"text-align:justify\">&nbsp;</td>\r\n      </tr>\r\n    </table>\r\n   </td>\r\n    <td>&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td width=\"500\" height=\"10\" align=\"left\" valign=\"top\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n    <td height=\"10\" bgcolor=\"#3d3d3d\">&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n    <td width=\"500\" height=\"70\" align=\"center\" valign=\"middle\" bgcolor=\"#2c2c2c\">\r\n    <font color=\"#b1b1b1\">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>\r\n    <td height=\"70\" bgcolor=\"#2c2c2c\">&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>','{$SHOPNAME}',1,'2011-01-13 19:31:44',NULL,NULL,NULL);
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

INSERT INTO `transmailheader` (`idtransmailheader`, `name`, `contenthtml`, `contenttxt`, `addid`, `adddate`, `editid`, `editdate`, `viewid`) VALUES (1,'Domyślny szablon nagłówka','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>{trans}MAIL_TITLE{/trans}</title>\r\n	<style type=\"text/css\">\r\n	{literal}\r\n		body,td,th {\r\n			font-size: 11px;\r\n			color: #575656;\r\n			font-family: Arial;\r\n		}\r\n		body {\r\n			margin-left: 0px;\r\n			margin-top: 0px;\r\n			margin-right: 0px;\r\n			margin-bottom: 0px;\r\n		}\r\n		a:link {\r\n			color: #BF3131;\r\n			text-decoration: none;\r\n		}\r\n		a:visited {\r\n			text-decoration: none;\r\n			color: #BF3131;\r\n		}\r\n		a:hover {\r\n			text-decoration: none;\r\n			color: #BF3131;\r\n		}\r\n		a:active {\r\n			text-decoration: none;\r\n			color: #BF3131;\r\n		}\r\n	{/literal}\r\n	</style>\r\n</head>\r\n\r\n<body>\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td>&nbsp;</td>\r\n    <td width=\"500\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n      <tr>\r\n        <td height=\"96\" align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n          <tr>\r\n          	{if isset($FRONTEND_URL)}\r\n            <td height=\"96\" align=\"left\" valign=\"middle\"><img src=\'cid:logo\' alt=\"Logo Sklep Internetowy\"/></td>\r\n            <td width=\"60\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$FRONTEND_URL}{seo controller=mainside}\" target=\"_blank\">{trans}TXT_MAINSIDE{/trans}</a></font></td>\r\n            <td width=\"79\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$FRONTEND_URL}{seo controller=clientsettings}\" target=\"_blank\">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>\r\n            <td width=\"75\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$FRONTEND_URL}{seo controller=contact}\" target=\"_blank\">{trans}TXT_CONTACT{/trans}</a></font></td>\r\n            {else}\r\n           	<td height=\"96\" align=\"left\" valign=\"middle\"><img src=\'cid:logo\' alt=\"Logo Sklep Internetowy\"/></td>\r\n            <td width=\"60\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=mainside}\" target=\"_blank\">{trans}TXT_MAINSIDE{/trans}</a></font></td>\r\n            <td width=\"79\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=clientsettings}\" target=\"_blank\">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>\r\n            <td width=\"75\" align=\"left\" valign=\"middle\"><font color=\"#969696\"><a href=\"{$URL}{seo controller=contact}\" target=\"_blank\">{trans}TXT_CONTACT{/trans}</a></font></td>\r\n            {/if}\r\n          </tr>\r\n        </table></td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"23\" align=\"left\" valign=\"top\"><hr noshade=\"noshade\" size=\"1\" color=\"#e8e8e8\"/></td>\r\n      </tr>\r\n      <tr>\r\n        <td style=\"text-align:justify\" align=\"left\" valign=\"top\"><font size=\"+2\"><b>{$SHOP_NAME}</b></font>\r\n   		<br/>{trans}TXT_HEADER_INFO{/trans}\r\n        </td>\r\n      </tr>\r\n      <tr>\r\n        <td height=\"38\" align=\"left\" valign=\"top\">&nbsp;</td>\r\n      </tr>','{trans}TXT_WELCOME{/trans}',1,'2012-02-07 19:40:13',1,NULL,NULL);
DROP TABLE IF EXISTS `transmailtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmailtags` (
  `idtransmailtags` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(200) NOT NULL,
  `name` varchar(60) NOT NULL,
  `inarray` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`idtransmailtags`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `transmailtags` (`idtransmailtags`, `tag`, `name`, `inarray`) VALUES (1,'{$clientdata.firstname}','TXT_FIRSTNAME',NULL),(2,'{$clientdata.surname}','TXT_SURNAME',NULL),(3,'{$clientdata.login}','TXT_LOGIN',NULL),(4,'{$clientdata.emial}','TXT_EMAIL',NULL),(5,'{$clientdata.password}','TXT_PASSWORD',NULL),(6,'{$clientdata.newpassword}','TXT_PASSWORD_NEW',NULL),(7,'{$clientdata.newmail}','TXT_EMAIL_NEW',NULL),(8,'{$client.firstname}','TXT_RECOMMEND_THIS_SITE',NULL),(9,'{$client.surname}','TXT_RECOMMEND_THIS_SITE',NULL),(10,'{$address.firstname}','TXT_FIRSTNAME',NULL),(11,'{$address.surname}','TXT_SURNAME',NULL),(12,'{$address.email}','TXT_EMAIL',NULL),(13,'{$address.password}','TXT_PASSWORD',NULL),(14,'{$address.street}','TXT_STREET',NULL),(15,'{$address.streetno}','TXT_NR',NULL),(16,'{$address.placeno}','TXT_PLACENO',NULL),(17,'{$address.postcode}','TXT_POSTCODE',NULL),(18,'{$address.place}','TXT_PLACE',NULL),(19,'{$address.placename}','TXT_PLACE',NULL),(20,'{$address.phone}','TXT_PHONE',NULL),(21,'{$address.companyname}','TXT_COMPANYNAME',NULL),(22,'{$address.NIP}','TXT_NIP',NULL),(23,'{$address.REGON}','TXT_REGON',NULL),(24,'{$address.VAT}','TXT_VAT',NULL),(25,'{$address.addresstype}','TXT_CLIENTADRESSTYPE',NULL),(26,'{$contact.firstname}','TXT_FIRSTNAME',NULL),(27,'{$contact.surname}','TXT_SURNAME',NULL),(28,'{$contact.email}','TXT_EMAIL',NULL),(29,'{$user.firstname}','TXT_FIRSTNAME',NULL),(30,'{$user.surname}','TXT_SURNAME',NULL),(31,'{$user.login}','TXT_LOGIN',NULL),(32,'{$user.email}','TXT_EMAIL',NULL),(33,'{$user.password}','TXT_PASSWORD',NULL),(34,'{$users.email}','TXT_EMAIL',NULL),(35,'{$users.firstname}','TXT_FIRSTNAME',NULL),(36,'{$users.surname}','TXT_SURNAME',NULL),(37,'{$newsletter.textform}','TXT_TEXT_FORM_CONTENT',NULL),(38,'{$newsletter.htmlform}','TXT_HTML_FORM_CONTENT',NULL),(39,'{$order.clientdata.firstname}','TXT_FIRSTNAME',NULL),(40,'{$order.clientdata.surname}','TXT_SURNAME',NULL),(41,'{$order.clientdata.placename}','TXT_PLACE',NULL),(42,'{$order.clientdata.postcode}','TXT_POSTCODE',NULL),(43,'{$order.clientdata.street}','TXT_STREET',NULL),(44,'{$order.clientdata.streetno}','TXT_NR',NULL),(45,'{$order.clientdata.placeno}','TXT_PLACENO',NULL),(46,'{$order.clientdata.phone}','TXT_PHONE',NULL),(47,'{$order.clientdata.email}','TXT_EMAIL',NULL),(48,'{$order.deliveryAddress.firstname}','TXT_FIRSTNAME',NULL),(49,'{$order.deliveryAddress.surname}','TXT_SURNAME',NULL),(50,'{$order.deliveryAddress.placename}','TXT_PLACE',NULL),(51,'{$order.deliveryAddress.postcode}','TXT_POSTCODE',NULL),(52,'{$order.deliveryAddress.street}','TXT_STREET',''),(53,'{$order.deliveryAddress.streetno}','TXT_NR',''),(54,'{$order.deliveryAddress.placeno}','TXT_PLACENO',''),(55,'{$order.deliveryAddress.phone}','TXT_PHONE',''),(56,'{$order.deliveryAddress.email}','TXT_EMAIL',''),(57,'{$order.dispatchmethod.dispatchmethodcost}','TXT_COST_OF_DELIVERY',''),(58,'{$order.dispatchmethod.dispatchmethodname}','TXT_METHOD_OF_DELIVERY',''),(59,'{$order.payment.paymentmethodname}','TXT_METHOD_OF_PEYMENT',''),(60,'{$order.globalPrice}','TXT_SUM_PRICE',NULL),(61,'{$order.priceWithDispatchMethod}','TXT_PRICE_WITH_DISPATCHMETHOD',NULL),(62,'{$order.count}','TXT_GLOBALQTY',NULL),(63,'{$product.name}','TXT_PRODUCT_NAME','$order.cart'),(64,'{$product.newprice}','TXT_PRICE','$order.cart'),(65,'{$product.qty}','TXT_NUMBEROFITEM','$order.cart'),(66,'{$product.qtyprice}','TXT_ITEM_PRICE','$order.cart'),(67,'{$attributes.name}','TXT_PRODUCT_ATTRIBUTES','$product.attributes'),(68,'{$attributes.newprice}','TXT_PRICE',NULL),(69,'{$attributes.qty}','TXT_QUANTITY_UNIT',NULL),(70,'{$attributes.qtyprice}','TXT_ITEM_PRICE',NULL),(71,'{$orderhistory.firstname}','TXT_FIRSTNAME',NULL),(72,'{$orderhistory.surname}','TXT_SURNAME',NULL),(73,'{$orderhistory.content}','TXT_CONTENT',NULL),(74,'{$orderhistory.orderstatusname}','TXT_STATUS',NULL),(75,'{$personal_data.firstname}','TXT_FIRSTNAME',NULL),(76,'{$personal_data.surname}','TXT_SURNAME',NULL),(77,'{$personal_data.email}','TXT_EMAIL',NULL),(78,'{$personal_data.password}','TXT_PASSWORD',NULL),(79,'{$personal_data.phone}','TXT_PHONE',NULL),(80,'{$firstname}','TXT_FIRSTNAME',NULL),(81,'{$surname}','TXT_SURNAME',NULL),(82,'{$email}','TXT_EMAIL',NULL),(83,'{$password}','TXT_PASSWORD_NEW',NULL),(84,'{$addressURL}','TXT_WEBSITE',NULL),(85,'{$CONTACT_CONTENT}','TXT_STATIC_CONTENT',NULL),(86,'{$EMAIL_NEW}','TXT_EMAIL_NEW',NULL),(87,'{$PASS_NEW}','TXT_PASSWORD_NEW',NULL),(88,'{$orderlink}','TXT_CLICK_LINK_TO_ACTIVE_ORDER',NULL),(89,'{$active.firstname}','TXT_FIRSTNAME',NULL),(90,'{$active.surname}','TXT_SURNAME',NULL),(91,'{$active.lastDateOrder}','TXT_LAST_DATE_ORDER',NULL),(92,'{$active.lastLogged}','TXT_LASTLOGGED',NULL),(93,'{$active.dateOfMaturity}','TXT_MATURITY',NULL),(94,'{$active.termsOfPayment}','TXT_VIEW_ORDER_PAYMENT_METHOD',NULL),(95,'{$active.orderDate}','TXT_VIEW_ORDER_ORDER_DATE',NULL),(96,'{$active.orderNo}','TXT_ORDERS_NR',NULL),(97,'{$active.orderPrice}','TXT_ALL_ORDERS_PRICE',NULL),(98,'{$newsletterlink}','TXT_LINK_FOR_NEWSLETTER_ACTIVATION',NULL),(99,'{$unwantednewsletterlink}','TXT_LINK_FOR_UNWANTED_NEWSLETTER',NULL),(102,'{$order.priceWithDispatchMethodPromo}','TXT_PROMOTION_PRICE_WIHT_DISPATCH_METHOD',NULL),(105,'{$order.priceWithDispatchMethodNettoPromo}','TXT_PROMOTION_PRICE_WITH_DISPATCH_METHOD_NETTO',NULL),(106,'{$clientOrder.firstname}','TXT_FIRSTNAME',NULL),(107,'{$clientOrder.surname}','TXT_SURNAME',NULL),(108,'{$clientOrder.email}','TXT_EMAIL',NULL),(109,'{$clientOrder.idorder}','TXT_ORDER_NUMER',NULL),(110,'{$clientOrder.orderdate}','TXT_VIEW_ORDER_ORDER_DATE',NULL),(111,'{$clientOrder.dispatchmethodname}','TXT_METHOD_OF_DELIVERY',NULL),(112,'{$clientOrder.paymentmethodname}','TXT_METHOD_OF_PEYMENT',NULL),(113,'{$clientOrder.dispatchmethodprice}','TXT_COST_OF_DELIVERY',NULL),(114,'{$clientOrder.globalprice}','TXT_SUM_PRICE',NULL);
DROP TABLE IF EXISTS `updatehistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `updatehistory` (
  `idupdatehistory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packagename` varchar(64) NOT NULL,
  `version` varchar(45) NOT NULL,
  `channel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idupdatehistory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `updatehistory` (`idupdatehistory`, `packagename`, `version`, `channel`) VALUES (1,'Gekosale','1.4.1','update.gekosale.pl');
DROP TABLE IF EXISTS `upsell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `upsell` (
  `idupsell` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `relatedproductid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idupsell`),
  UNIQUE KEY `UNIQUE_upsell_productid_relatedproductid` (`productid`,`relatedproductid`) USING BTREE,
  KEY `FK_upsell_addid` (`addid`),
  KEY `FK_upsell_editid` (`editid`),
  KEY `FK_upsell_relatedproductid` (`relatedproductid`),
  CONSTRAINT `FK_upsell_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_upsell_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_upsell_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_upsell_relatedproductid` FOREIGN KEY (`relatedproductid`) REFERENCES `product` (`idproduct`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `urlmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `urlmap` (
  `idurlmap` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL,
  `pkid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idurlmap`),
  UNIQUE KEY `UNIQUE_urlmap_url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `active` int(10) unsigned NOT NULL DEFAULT '0',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `globaluser` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `UNIQUE_user_login` (`login`),
  KEY `FK_user_addid` (`addid`),
  KEY `FK_user_editid` (`editid`),
  CONSTRAINT `FK_user_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_user_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `user` (`iduser`, `login`, `password`, `active`, `addid`, `adddate`, `editid`, `editdate`, `globaluser`) VALUES (1,'109daf41611032ee887357c184884b65abe3f85b','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,'2012-01-20 20:56:45',1,NULL,1);
DROP TABLE IF EXISTS `userdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userdata` (
  `userdataid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(128) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `userid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned DEFAULT NULL,
  `lastlogged` datetime DEFAULT NULL,
  PRIMARY KEY (`userdataid`),
  UNIQUE KEY `UNIQUE_userdata_userid` (`userid`),
  UNIQUE KEY `UNIQUE_userdata_email` (`email`),
  KEY `FK_userdata_addid` (`addid`),
  KEY `FK_userdata_editid` (`editid`),
  KEY `FK_userdata_photoid` (`photoid`),
  CONSTRAINT `FK_userdata_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_userdata_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_userdata_photoid` FOREIGN KEY (`photoid`) REFERENCES `file` (`idfile`),
  CONSTRAINT `FK_userdata_userid` FOREIGN KEY (`userid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `userdata` (`userdataid`, `firstname`, `surname`, `email`, `description`, `addid`, `adddate`, `editid`, `editdate`, `userid`, `photoid`, `lastlogged`) VALUES (1,'Jan','Kowalski','admin@gekosale.pl','KONTO STARTOWE',1,'2012-09-06 11:37:45',1,NULL,1,1,'2012-09-06 11:37:45');
DROP TABLE IF EXISTS `usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroup` (
  `idusergroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idusergroup`),
  UNIQUE KEY `UNIQUE_usergroup_userid_groupid` (`userid`,`groupid`),
  KEY `FK_usergroup_groupid` (`groupid`),
  KEY `FK_usergroup_addid` (`addid`),
  KEY `FK_usergroup_editid` (`editid`),
  CONSTRAINT `FK_usergroup_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_usergroup_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_usergroup_groupid` FOREIGN KEY (`groupid`) REFERENCES `group` (`idgroup`),
  CONSTRAINT `FK_usergroup_userid` FOREIGN KEY (`userid`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `usergroup` (`idusergroup`, `userid`, `groupid`, `addid`, `adddate`, `editid`, `editdate`) VALUES (12,1,1,1,'2012-01-20 20:57:04',NULL,NULL);
DROP TABLE IF EXISTS `usergroupview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroupview` (
  `idusergroupview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idusergroupview`),
  KEY `FK_usergroupview_viewid` (`viewid`),
  KEY `FK_usergroupview_groupid` (`groupid`),
  KEY `FK_usergroupview_addid` (`addid`),
  KEY `FK_usergroupview_userid` (`userid`),
  CONSTRAINT `FK_usergroupview_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_usergroupview_groupid` FOREIGN KEY (`groupid`) REFERENCES `group` (`idgroup`),
  CONSTRAINT `FK_usergroupview_userid` FOREIGN KEY (`userid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_usergroupview_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `userhistorylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userhistorylog` (
  `iduserhistorylog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `URL` varchar(255) NOT NULL,
  `sessionid` varchar(255) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`iduserhistorylog`),
  KEY `FK_userhistorylog_userid` (`userid`),
  KEY `FK_userhistorylog_viewid` (`viewid`),
  CONSTRAINT `FK_userhistorylog_userid` FOREIGN KEY (`userid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_userhistorylog_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `vat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vat` (
  `idvat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` decimal(5,2) NOT NULL DEFAULT '0.00',
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idvat`),
  UNIQUE KEY `UNIQUE_vat_value` (`value`),
  KEY `FK_vat_addid` (`addid`),
  KEY `FK_vat_editid` (`editid`),
  CONSTRAINT `FK_vat_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_vat_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `vat` (`idvat`, `value`, `addid`, `adddate`, `editid`, `editdate`) VALUES (2,23.00,1,'2011-01-14 21:03:32',NULL,NULL),(3,0.00,1,'2011-06-21 17:07:46',NULL,NULL),(4,7.00,1,'2011-08-15 13:36:04',NULL,NULL),(5,3.00,1,'2011-08-17 21:23:42',NULL,NULL);
DROP TABLE IF EXISTS `vattranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vattranslation` (
  `idvattranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `vatid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idvattranslation`),
  KEY `FK_vattranslation_languageid` (`languageid`),
  KEY `FK_vattranslation_vatid` (`vatid`),
  CONSTRAINT `FK_vattranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_vattranslation_vatid` FOREIGN KEY (`vatid`) REFERENCES `vat` (`idvat`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `vattranslation` (`idvattranslation`, `name`, `languageid`, `vatid`) VALUES (45,'VAT 23',1,2),(49,'VAT 0',1,3),(50,'VAT 7',1,4),(51,'VAT 3',1,5);
DROP TABLE IF EXISTS `view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view` (
  `idview` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `namespace` varchar(64) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `periodid` int(10) unsigned DEFAULT NULL,
  `taxes` int(10) unsigned DEFAULT NULL,
  `gacode` varchar(45) NOT NULL,
  `gapages` int(10) unsigned NOT NULL,
  `gatransactions` int(10) unsigned NOT NULL,
  `photoid` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `offline` int(10) unsigned NOT NULL DEFAULT '0',
  `offlinetext` text,
  `cartredirect` varchar(45) DEFAULT NULL,
  `minimumordervalue` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `showtax` int(10) unsigned DEFAULT '1',
  `enableopinions` int(10) unsigned DEFAULT '1',
  `enabletags` int(10) unsigned DEFAULT '1',
  `catalogmode` int(10) unsigned DEFAULT '0',
  `forcelogin` int(10) unsigned DEFAULT '0',
  `enablerss` int(10) DEFAULT '1',
  `invoicenumerationkind` varchar(4) NOT NULL DEFAULT 'ntmr',
  `invoicedefaultpaymentdue` int(11) NOT NULL DEFAULT '7',
  `uploaderenabled` int(10) unsigned DEFAULT '0',
  `uploadmaxfilesize` decimal(15,0) DEFAULT '10',
  `uploadchunksize` decimal(15,0) DEFAULT '100',
  `uploadextensions` text,
  `apikey` varchar(45) DEFAULT NULL,
  `faceboookappid` varchar(255) DEFAULT NULL,
  `faceboooksecret` varchar(255) DEFAULT NULL,
  `watermark` varchar(255) DEFAULT NULL,
  `confirmregistration` tinyint(1) NOT NULL,
  `enableregistration` tinyint(1) NOT NULL DEFAULT '1',
  `confirmorder` tinyint(4) DEFAULT '0',
  `confirmorderstatusid` int(11) DEFAULT NULL,
  `guestcheckout` int(11) DEFAULT '1',
  `ordernotifyaddresses` text NOT NULL,
  PRIMARY KEY (`idview`),
  KEY `UNIQUE_view_name` (`name`),
  KEY `FK_view_storeid` (`storeid`),
  KEY `FK_view_addid` (`addid`),
  KEY `FK_view_editid` (`editid`),
  CONSTRAINT `FK_view_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_view_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_view_storeid` FOREIGN KEY (`storeid`) REFERENCES `store` (`idstore`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `view` (`idview`, `name`, `namespace`, `storeid`, `addid`, `adddate`, `editid`, `editdate`, `periodid`, `taxes`, `gacode`, `gapages`, `gatransactions`, `photoid`, `favicon`, `offline`, `offlinetext`, `cartredirect`, `minimumordervalue`, `showtax`, `enableopinions`, `enabletags`, `catalogmode`, `forcelogin`, `enablerss`, `invoicenumerationkind`, `invoicedefaultpaymentdue`, `uploaderenabled`, `uploadmaxfilesize`, `uploadchunksize`, `uploadextensions`, `apikey`, `faceboookappid`, `faceboooksecret`, `watermark`, `confirmregistration`, `enableregistration`, `confirmorder`, `confirmorderstatusid`, `guestcheckout`, `ordernotifyaddresses`) VALUES (3,'Geko','core',1,1,'2012-09-06 23:16:10',1,NULL,1,0,'',0,0,'logo.png','favicon.ico',0,'','',0.0000,1,1,1,0,0,0,'ntmr',7,0,0,0,'','','112406142196542','79de4075692f132339225dc81b730782','',0,1,0,NULL,1,'');
DROP TABLE IF EXISTS `viewcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewcategory` (
  `idviewcategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `viewid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idviewcategory`),
  KEY `FK_viewcategory_categoryid` (`categoryid`),
  KEY `FK_viewcategory_addid` (`addid`),
  KEY `FK_viewcategory_viewid` (`viewid`),
  KEY `IDX_viewcategory_viewid_categoryid` (`categoryid`,`viewid`),
  CONSTRAINT `FK_viewcategory_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_viewcategory_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `category` (`idcategory`),
  CONSTRAINT `FK_viewcategory_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `viewtranslation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewtranslation` (
  `idviewtranslation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editid` int(10) unsigned DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `keyword_title` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `keyword_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`idviewtranslation`),
  KEY `FK_viewtranslation_addid` (`addid`),
  KEY `FK_viewtranslation_editid` (`editid`),
  KEY `FK_viewtranslation_viewid` (`viewid`),
  KEY `FK_viewtranslation_languageid` (`languageid`),
  CONSTRAINT `FK_viewtranslation_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_viewtranslation_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_viewtranslation_languageid` FOREIGN KEY (`languageid`) REFERENCES `language` (`idlanguage`),
  CONSTRAINT `FK_viewtranslation_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=345 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `viewtranslation` (`idviewtranslation`, `languageid`, `addid`, `adddate`, `editid`, `editdate`, `viewid`, `keyword_title`, `keyword`, `keyword_description`) VALUES (344,1,1,'2012-09-06 23:16:11',NULL,NULL,3,'','','');
DROP TABLE IF EXISTS `viewurl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewurl` (
  `idviewurl` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(45) NOT NULL,
  `viewid` int(10) unsigned NOT NULL,
  `addid` int(10) unsigned NOT NULL,
  `editid` int(10) unsigned DEFAULT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editdate` datetime DEFAULT NULL,
  PRIMARY KEY (`idviewurl`),
  KEY `FK_viewurl_addid` (`addid`),
  KEY `FK_viewurl_editid` (`editid`),
  KEY `FK_viewurl_viewid` (`viewid`),
  CONSTRAINT `FK_viewurl_addid` FOREIGN KEY (`addid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_viewurl_editid` FOREIGN KEY (`editid`) REFERENCES `user` (`iduser`),
  CONSTRAINT `FK_viewurl_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `viewurl` (`idviewurl`, `url`, `viewid`, `addid`, `editid`, `adddate`, `editdate`) VALUES (274,'geko.pl',3,1,NULL,'2012-09-06 23:16:11',NULL);
DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist` (
  `idwishlist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productid` int(10) unsigned NOT NULL,
  `productattributesetid` int(11) unsigned DEFAULT '0',
  `clientid` int(10) unsigned NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wishprice` decimal(16,2) unsigned DEFAULT '0.00',
  `viewid` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idwishlist`),
  KEY `FK_wishlist_clientid` (`clientid`),
  KEY `FK_wishlist_productid` (`productid`),
  KEY `FK_wishlist_viewid` (`viewid`),
  CONSTRAINT `FK_wishlist_clientid` FOREIGN KEY (`clientid`) REFERENCES `client` (`idclient`),
  CONSTRAINT `FK_wishlist_productid` FOREIGN KEY (`productid`) REFERENCES `product` (`idproduct`),
  CONSTRAINT `FK_wishlist_viewid` FOREIGN KEY (`viewid`) REFERENCES `view` (`idview`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='InnoDB free: 0 kB; (`clientid`) REFER `mvc/client`(`idclient';
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

