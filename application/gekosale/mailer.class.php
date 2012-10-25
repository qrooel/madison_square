<?php
defined('ROOTPATH') or die('No direct access allowed.');
/**
 * Gekosale, Open Source E-Commerce Solution
 * http://www.gekosale.pl
 *
 * Copyright (c) 2012 WellCommerce sp. z o.o. Zabronione jest usuwanie informacji o licencji i autorach.
 *
 * This library is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version. 
 * 
 * 
 * $Revision: 438 $
 * $Author: gekosale $
 * $Date: 2011-08-27 11:29:36 +0200 (So, 27 sie 2011) $
 * $Id: mailer.class.php 438 2011-08-27 09:29:36Z gekosale $ 
 */

include_once (__PHPMAILER_CLASS__ . 'class.phpmailer.php');
class Mailer extends PHPMailer
{
	
	protected $registry;
	protected $__DEBUG_ELEMENTS = Array(
		'mail' => Array(
			'FromName',
			'FromEmail'
		),
		'sendmail' => Array(
			'FromName',
			'FromEmail'
		),
		'smtp' => Array(
			'server',
			'port',
			'SMTPSecure',
			'SMTPAuth',
			'SMTPUsername',
			'SMTPPassword',
			'FromName'
		)
	);

	public function __construct (&$registry)
	{
		$this->registry = $registry;
		$this->setConfig();
	}

	public function setConfig ()
	{
		$Config = $this->registry->config;
		$Array = $Config['phpmailer'];
		try{
			$this->debugConfig($Array);
		}
		catch (Exception $e){
			throw new Exception($e->getMessage());
		}
		switch ($Array['Mailer']) {
			case 'mail':
				$this->IsMail();
				$this->From = $Array['FromEmail'];
				break;
			case 'sendmail':
				$this->IsSendmail();
				$this->From = $Array['FromEmail'];
				break;
			case 'smtp':
				$this->IsSMTP();
				$this->Host = $Array['server'];
				$this->Port = $Array['port'];
				$this->SMTPSecure = $Array['SMTPSecure'];
				$this->SMTPAuth = $Array['SMTPAuth'];
				$this->From = $Array['FromEmail'];
				$this->Username = $Array['SMTPUsername'];
				$this->Password = $Array['SMTPPassword'];
				break;
			default:
				throw new Exception('Wrong e-mail sending method');
		}
		$this->CharSet = $Array['CharSet'];
		$this->FromName = $Array['FromName'];
		$this->IsHTML(true);
	}

	protected function debugConfig ($Config)
	{
		$Config = (array) $Config;
		if (! isset($Config['Mailer']))
			throw new Exception('Mailer sending method not set in settings file.');
		if (! isset($this->__DEBUG_ELEMENTS[$Config['Mailer']]))
			throw new Exception('Wrong type of sending method for Mailer set in settings file.');
		if (count($this->__DEBUG_ELEMENTS[$Config['Mailer']]) > 0){
			$diff = array_keys((array) $Config);
			if (count($tmp = array_diff(array_values($this->__DEBUG_ELEMENTS[$Config['Mailer']]), $diff)) > 0){
				throw new Exception('Mailer configuration not ready, please set elements: ' . implode(',', $tmp));
			}
		}
	}

	public function loadTemplateToBody ($templateFile)
	{
		$content = '';
		$fileHandler = '';
		if (! is_file($fileHandler = ROOTPATH . 'design/_tpl/mailerTemplates/' . $this->registry->session->getActiveLanguage() . '/' . $templateFile)){
			if (! is_file($fileHandler = ROOTPATH . 'design/_tpl/mailerTemplates/' . $templateFile)){
				throw new Exception('Mailer template file not found: ' . $templateFile);
			}
		}
		$content = $this->registry->template->fetch($fileHandler);
		$this->Body = $content;
	}

	public function setSubject ($subject)
	{
		$this->Subject = $subject;
	}

	public function loadContentToBody ($templateFile)
	{
		$contentTxt = '';
		$contentHtml = '';
		$sql = 'SELECT 
					CONCAT(TMH.contenthtml, TM.contenthtml, TMF.contenthtml) AS contenthtml,
					CONCAT(TMH.contenttxt, TM.contenttxt, TMF.contenttxt) AS contenttxt
					FROM transmail TM
					LEFT JOIN transmailaction TMA ON TM.transmailactionid = TMA.idtransmailaction
					LEFT JOIN transmailheader TMH ON TM.transmailheaderid = TMH.idtransmailheader
					LEFT JOIN transmailfooter TMF ON TM.transmailfooterid = TMF.idtransmailfooter
					WHERE TMA.filetpl = :tpl AND TM.viewid = :viewid AND active = 1';
		$stmt = $this->registry->db->prepareStatement($sql);
		$stmt->setString('tpl', $templateFile);
		$stmt->setInt('viewid', Helper::getViewId());
		$rs = $stmt->executeQuery();
		
		if ($rs->first()){
			$tplHtml = $rs->getString('contenthtml');
			$tplTxt = $rs->getString('contenthtml');
			$contentHtml = $this->registry->template->fetch('text:' . $tplHtml);
			$contentTxt = $this->registry->template->fetch('text:' . $tplTxt);
		}
		else{
			$sql = '	SELECT 
            				CONCAT(TMH.contenthtml, TM.contenthtml, TMF.contenthtml) AS contenthtml,
            				CONCAT(TMH.contenttxt, TM.contenttxt, TMF.contenttxt) AS contenttxt
            				FROM transmail TM
						  	LEFT JOIN transmailaction TMA ON TM.transmailactionid = TMA.idtransmailaction
						  	LEFT JOIN transmailheader TMH ON TM.transmailheaderid = TMH.idtransmailheader
						  	LEFT JOIN transmailfooter TMF ON TM.transmailfooterid = TMF.idtransmailfooter
						  	WHERE TMA.filetpl = :tpl AND TM.viewid IS NULL AND active = 1';
			$stmt = $this->registry->db->prepareStatement($sql);
			
			$stmt->setString('tpl', $templateFile);
			$stmt->setInt('viewid', Helper::getViewId());
			$rs = $stmt->executeQuery();
			
			if ($rs->first()){
				$tplHtml = $rs->getString('contenthtml');
				$tplTxt = $rs->getString('contenthtml');
				$contentHtml = $this->registry->template->fetch('text:' . $tplHtml);
				$contentTxt = $this->registry->template->fetch('text:' . $tplTxt);
			}
			else{
				$content = '';
				$fileHandler = '';
				$viewid = Helper::getViewId();
				$file = $templateFile.'.tpl';
				if ($viewid > 0){
					if (! is_file($fileHandler = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $file . $viewid . '.tpl')){
						$fileHandler = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $file;
					}
				}
				else{
					$fileHandler = ROOTPATH . 'design' . DS . '_tpl' . DS . 'mailerTemplates' . DS . $file;
				}
				if (! is_file($fileHandler)){
					throw new Exception('Mailer template file not found: ' . $file);
				}
				$contentHtml = $this->registry->template->fetch($fileHandler);
			}
		}
		$this->Body = $contentHtml;
		$mail->AltBody = $contentTxt;
	}

	public function changeHTMLimageSource ()
	{
		if (! empty($this->Body)){
			$content = $this->Body;
			preg_match_all("/(src|background)=\"(.*)\"/Ui", $content, $images);
			if (isset($images[2])){
				foreach ($images[2] as $i => $url){
					$filename = basename($url);
					$directory = dirname($url);
					$cid = 'cid:' . md5($filename);
					$fileParts = split("\.", $filename);
					$ext = $fileParts[1];
					$mimeType = $this->_mime_types($ext);
					$content = preg_replace("/" . $images[1][$i] . "=\"" . preg_quote($url, '/') . "\"/Ui", $images[1][$i] . "=\"" . $cid . "\"", $content);
					$this->AddEmbeddedImage($url, md5($filename), $filename, 'base64', $mimeType);
				}
			}
			$this->IsHTML(true);
			$this->Body = $content;
		}
	}
}