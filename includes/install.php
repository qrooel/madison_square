<?php
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
 */

class Install
{
	
	protected $tplFile = 'design/frontend/core/installer/index/installer.tpl';

	public function __construct ()
	{
		$this->template = new Template(Array());
	}

	protected function check_dir ($path)
	{
		if (! is_dir($path)){
			mkdir($path, 0770);
		}
		return true;
	}

	protected function panelURL ($panel)
	{
		$file = 'index.php/'.$panel;
		if (LOCAL_CATALOG != ''){
			return App::getHost(1) . '/' . LOCAL_CATALOG . '/' . $file;
		}
		return App::getHost(1) . '/' . $file;
	}

	protected function configWriter ($Config)
	{
		$filename = ROOTPATH . 'config' . DS . 'settings.php';
		$out = fopen($filename, "w");
		fwrite($out, "<?php defined('ROOTPATH') OR die('No direct access allowed.');\r\n");
		fwrite($out, '/**
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
 */' . "\r\n");
		
		fwrite($out, '
		$Config = Array(' . "
			'database'=> Array(
				'phptype'=> 'mysqli',
				'hostspec'=> '{$Config['hostspec']}',
				'port'=> " . (int) $Config['mysqlport'] . ",
				'username'=> '{$Config['username']}',
				'password'=> '{$Config['password']}',
				'database'=> '{$Config['database']}',
				'encoding'=> 'utf8'
			),
			'phpmailer'=> Array(
				'Mailer'=> '{$Config['mailer']}',
				'CharSet'=> 'UTF-8',
				'FromName'=> '{$Config['fromname']}',
				'FromEmail'=> '{$Config['fromemail']}',
				'server'=> '{$Config['server']}',
				'port'=> " . (int) $Config['port'] . ",
				'SMTPSecure'=> '{$Config['smtpsecure']}',
				'SMTPAuth'=> '{$Config['smtpauth']}',
				'SMTPUsername'=> '{$Config['smtpusername']}',
				'SMTPPassword'=> '{$Config['smtppassword']}',
			),
			'admin_panel_link'=> '{$Config['admin_panel_link']}',
			'client_data_encription'=> 1,
			'client_data_encription_string'=> '{$Config['client_data_encription_string']}',
		);");
		fclose($out);
	}

	protected function checkExtension ($ext)
	{
		if (extension_loaded($ext)){
			return true;
		}
		if (ini_get('enable_dl') !== 1 || ini_get('safe_mode') === 1){
			return false;
		}
		$file = (PHP_SHLIB_SUFFIX === 'dll' ? 'php_' : '') . $ext . '.' . PHP_SHLIB_SUFFIX;
		return true;
	}

	protected function checkPhpVersion ($version)
	{
		if (version_compare(phpversion(), $version, '<=') === true){
			return false;
		}
		else{
			return true;
		}
	}

	public function testDBConnection ($data)
	{
		$db = @(new mysqli($data['hostspec'], $data['username'], $data['password'], $data['database'], $data['mysqlport']));
		if (mysqli_connect_error()){
			return mysqli_connect_error();
		}
		return true;
	}

	public function importDatabase ($data)
	{
		$data['namespace'] = 'core';
		
		@set_time_limit(0);
		$db = @(new mysqli($data['hostspec'], $data['username'], $data['password'], $data['database'], $data['mysqlport']));
		try{
			$db->query('SET names utf8');
			$db->query('SET FOREIGN_KEY_CHECKS = 0');
			$db->autocommit(false);
			$dir = opendir(ROOTPATH . 'sql/');
			while ($fh = readdir($dir)){
				if (strpos($fh, '.sql') !== FALSE){
					$file = file_get_contents(ROOTPATH . 'sql/' . $fh);
					$sql = explode(";\n", $file);
					foreach ($sql as $query){
						if (strlen($query) > 5){
							if (! @$db->query($query)){
								throw new Exception($db->error . "\r\nquery:" . $query);
							}
						}
					}
				}
			}
			$sql = "UPDATE user SET login=SHA1(?),password=SHA1(?), active=1 WHERE iduser = 1";
			$stmt = $db->prepare($sql);
			$stmt->bind_param('ss', $data['user_email'], $data['user_password']);
			$stmt->execute();
			
			$sql2 = "UPDATE userdata SET email = ? WHERE userid = 1";
			$stmt2 = $db->prepare($sql2);
			$stmt2->bind_param('s', $data['user_email']);
			$stmt2->execute();
			
			$sql3 = "UPDATE store SET name= ? WHERE idstore = 1";
			$stmt3 = $db->prepare($sql3);
			$stmt3->bind_param('s', $data['store_store']);
			$stmt3->execute();
			
			$sql4 = 'UPDATE view SET name= ?, namespace= ? WHERE idview = 3';
			$stmt4 = $db->prepare($sql4);
			$stmt4->bind_param('ss', $data['store_view'], $data['namespace']);
			$stmt4->execute();
			
			$sql5 = 'UPDATE viewurl SET url = ? WHERE viewid = 3';
			$stmt5 = $db->prepare($sql5);
			$stmt5->bind_param('s', $_SERVER['HTTP_HOST']);
			$stmt5->execute();
			
			$db->commit();
			$db->autocommit(true);
			$db->query('SET FOREIGN_KEY_CHECKS = 1');
			$db->close();
		}
		catch (Exception $e){
			$db->rollback();
			throw $e;
		}
	}

	public function Render ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'install',
			'action' => '',
			'method' => 'post'
		));
		
		$checkDirLogs = $this->check_dir('logs');
		$checkDirCache = $this->check_dir('cache');
		$checkDirSerialization = $this->check_dir('serialization');
		
		$minPHP = '5.2';
		
		$checkPHPVersion = $this->checkPhpVersion($minPHP);
		$checkExtensionZlib = $this->checkExtension('zlib');
		$checkExtensionGd = $this->checkExtension('gd');
		$checkExtensionMysqli = $this->checkExtension('mysqli');
		$checkExtensionCurl = $this->checkExtension('curl');
		
		if ($checkPHPVersion == TRUE && $checkExtensionZlib == TRUE && $checkExtensionMysqli == TRUE && $checkExtensionCurl == TRUE && $checkExtensionGd = TRUE){
			
			$license = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'license',
				'label' => 'Licencja'
			)));
			
			$licenceFile = file_get_contents(ROOTPATH . 'LICENCE');
			$gpl = '<div style="height:20em; border:1px solid #ccc; margin-bottom:8px; padding:5px; background:#fff; overflow: auto; overflow-x:hidden; overflow-y:scroll;">
				' . $licenceFile . '
				</div>';
			
			$license->AddChild(new FE_StaticText(Array(
				'text' => $gpl
			)));
			
			$license->AddChild(new FE_Checkbox(Array(
				'name' => 'accept_license',
				'label' => 'Akceptuję licencję',
				'rules' => Array(
					new FE_RuleRequired('Musisz zaakceptować licencję.')
				),
				'default' => 0
			)));
			
			$store = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'store',
				'label' => 'Konfiguracja sklepu'
			)));
			
			$store->AddChild(new FE_TextField(Array(
				'name' => 'store_store',
				'label' => 'Nazwa firmy',
				'rules' => Array(
					new FE_RuleRequired('Musisz podać nazwę firmy.')
				)
			)));
			
			$store->AddChild(new FE_TextField(Array(
				'name' => 'store_view',
				'label' => 'Nazwa sklepu',
				'rules' => Array(
					new FE_RuleRequired('Musisz podać nazwę sklepu.')
				)
			)));
			
			$store->AddChild(new FE_TextField(Array(
				'name' => 'admin_panel_link',
				'label' => 'Adres panelu administracyjnego',
				'rules' => Array(
					new FE_RuleRequired('Musisz podać adres panelu administracyjnego.')
				),
				'default' => 'admin'
			)));
			
			$store->AddChild(new FE_TextField(Array(
				'name' => 'user_email',
				'comment' => 'Używany jako login',
				'label' => 'E-mail',
				'rules' => Array(
					new FE_RuleRequired('Podaj login.')
				)
			)));
			
			$store->AddChild(new FE_Password(Array(
				'name' => 'user_password',
				'label' => 'Hasło',
				'rules' => Array(
					new FE_RuleRequired('Podaj hasło.')
				)
			)));
			
			$mailerdata = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'mailer_data',
				'label' => 'Ustawienia wysyłki email'
			)));
			
			$mailerType = $mailerdata->AddChild(new FE_Select(Array(
				'name' => 'mailer',
				'label' => 'Sposób wysyłania e-maili ze sklepu',
				'options' => FE_Option::Make(Array(
					'mail' => 'mail',
					'sendmail' => 'sendmail',
					'smtp' => 'smtp'
				))
			)));
			
			$mailerdata->AddChild(new FE_TextField(Array(
				'name' => 'server',
				'label' => 'Serwer',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				)
			)));
			
			$mailerdata->AddChild(new FE_TextField(Array(
				'name' => 'port',
				'label' => 'Port',
				'default' => 587,
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				)
			)));
			
			$mailerdata->AddChild(new FE_Select(Array(
				'name' => 'smtpsecure',
				'label' => 'Połączenie bezpieczne',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				),
				'options' => FE_Option::Make(Array(
					'' => 'none',
					'ssl' => 'ssl',
					'tls' => 'tls'
				)),
				'value' => ''
			)));
			
			$mailerdata->AddChild(new FE_Checkbox(Array(
				'name' => 'smtpauth',
				'label' => 'Autoryzacja',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				)
			)));
			
			$mailerdata->AddChild(new FE_TextField(Array(
				'name' => 'smtpusername',
				'label' => 'Użytkownik SMTP',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				)
			)));
			
			$mailerdata->AddChild(new FE_Password(Array(
				'name' => 'smtppassword',
				'label' => 'Hasło SMTP',
				'dependencies' => Array(
					new FE_Dependency(FE_Dependency::HIDE, $mailerType, new FE_ConditionNot(new FE_ConditionEquals('smtp')))
				)
			)));
			
			$mailerdata->AddChild(new FE_TextField(Array(
				'name' => 'fromname',
				'label' => 'Nadawca e-mail',
				'rules' => Array(
					new FE_RuleRequired('Podaj nadawcę email')
				)
			)));
			
			$mailerdata->AddChild(new FE_TextField(Array(
				'name' => 'fromemail',
				'label' => 'Adres e-mail',
				'rules' => Array(
					new FE_RuleRequired('Poda adres email.')
				)
			)));
			
			$database = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'database_settings',
				'label' => 'Ustawienia bazy SQL'
			)));
			
			$database->AddChild(new FE_TextField(Array(
				'name' => 'hostspec',
				'label' => 'Host',
				'default' => 'localhost',
				'rules' => Array(
					new FE_RuleRequired('Podaj host bazy SQL.')
				)
			)));
			
			$database->AddChild(new FE_TextField(Array(
				'name' => 'mysqlport',
				'label' => 'Port',
				'default' => 3306,
				'rules' => Array(
					new FE_RuleRequired('Podaj port bazy SQL.')
				)
			)));
			
			$database->AddChild(new FE_TextField(Array(
				'name' => 'username',
				'label' => 'Użytkownik',
				'rules' => Array(
					new FE_RuleRequired('Podaj użytkownika bazy SQL.')
				)
			)));
			
			$database->AddChild(new FE_Password(Array(
				'name' => 'password',
				'label' => 'Hasło',
				'rules' => Array(
					new FE_RuleRequired('Podaj hasło do bazy SQL.')
				)
			)));
			
			$database->AddChild(new FE_TextField(Array(
				'name' => 'database',
				'label' => 'Nazwa bazy',
				'rules' => Array(
					new FE_RuleRequired('Podaj nazwę bazy SQL.')
				)
			)));
			
			$database->AddChild(new FE_TextField(Array(
				'name' => 'client_data_encription_string',
				'label' => 'Klucz szyfrujący dane',
				'default' => substr(md5(uniqid(rand(), true)), 0, 32),
				'rules' => Array(
					new FE_RuleRequired('Musisz podać klucz szyfrujący.')
				)
			)));
			
			$installation = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'installation',
				'label' => 'Instalacja'
			)));
			
			$installation->AddChild(new FE_StaticText(Array(
				'text' => '<a href="#" class="installButton">Zainstaluj Gekosale</a>'
			)));
			
			$form->AddFilter(new FE_FilterTrim());
			
			if ($form->Validate(FE::SubmittedData())){
				$Data = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
				$conn = $this->testDBConnection($Data);
				if ($conn === TRUE){
					$this->importDatabase($Data);
					$this->configWriter($Data);
					header('Location: ' . $this->panelURL($Data['admin_panel_link']));
				}
				else{
					$this->template->assign('error', addslashes($conn));
				}
			}
		}
		else{
			$requirements = $form->AddChild(new FE_Fieldset(Array(
				'name' => 'requirements',
				'label' => 'Requirements'
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkPHPVersion == TRUE) ? '<p style="color: green;"><strong>PHP version</strong> - OK</p>' : '<p style="color: red"><strong>PHP version</strong> - ' . $minPHP . ' required, ' . phpversion() . ' available</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkExtensionZlib == TRUE) ? '<p style="color: green;"><strong>Zlib extension</strong> - OK</p>' : '<p style="color: red"><strong>Zlib extension</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkExtensionMysqli == TRUE) ? '<p style="color: green;"><strong>Mysqli extension</strong> - OK</p>' : '<p style="color: red"><strong>Mysqli extension</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkExtensionCurl == TRUE) ? '<p style="color: green;"><strong>cURL extension</strong> - OK</p>' : '<p style="color: red"><strong>cURL extension</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkExtensionGd == TRUE) ? '<p style="color: green;"><strong>GD extension</strong> - OK</p>' : '<p style="color: red"><strong>GD extension</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkDirLogs == TRUE) ? '<p style="color: green;"><strong>"logs" dir writeable</strong> - OK</p>' : '<p style="color: red"><strong>"logs" dir writeable</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkDirCache == TRUE) ? '<p style="color: green;"><strong>"cache" dir writeable</strong> - OK</p>' : '<p style="color: red"><strong>"cache" dir writeable</strong> - No</p>')
			)));
			
			$requirements->AddChild(new FE_StaticText(Array(
				'text' => (($checkDirSerialization == TRUE) ? '<p style="color: green;"><strong>"serialization" dir writeable</strong> - OK</p>' : '<p style="color: red"><strong>"serialization" dir writeable</strong> - No</p>')
			)));
		}
		$this->template->assign('DESIGNPATH', App::getURLForDesignDirectory());
		$this->template->assign('form', $form);
		$this->template->display(ROOTPATH . $this->tplFile);
	}
}

$Installer = new Install();
$Installer->Render();