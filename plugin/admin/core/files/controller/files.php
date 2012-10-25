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
 * $Revision: 655 $
 * $Author: gekosale $
 * $Date: 2012-04-24 10:51:44 +0200 (Wt, 24 kwi 2012) $
 * $Id: files.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class FilesController extends Controller
{

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'LoadAllFiles',
			App::getModel('files'),
			'getFilesForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'GetFilenameSuggestions',
			App::getModel('files'),
			'getFilenameForAjax'
		));
		$this->registry->xajax->registerFunction(array(
			'deleteUnbindPhotos',
			App::getModel('files'),
			'deleteAJAXUnbindPhotos'
		));
		$this->registry->xajax->registerFunction(array(
			'doDeleteFiles',
			App::getModel('files'),
			'doAJAXDeleteFiles'
		));
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		$this->registry->template->assign('datagrid_filter', App::getModel('files')->getDatagridFilterData());
		$this->registry->template->display($this->loadTemplate('index.tpl'));
	}

	public function add ()
	{
		
		$allowedExtensions = array(
			'csv',
			'xml',
			'png',
			'gif',
			'jpg',
			'jpeg',
			'pdf',
			'txt',
			'doc',
			'xls',
			'mpp',
			'pdf',
			'vsd',
			'ppt',
			'docx',
			'xlsx',
			'pptx',
			'tif',
			'zip',
			'tgz',
			'ico',
			'avi',
			'mov',
			'wmf',
			'mp4',
			'flv'
		);
		$allowedFolders = array(
			'upload/',
			'upload/keys/',
			'upload/competitors/',
			'design/_images_common/icons/languages/',
			'design/_images_frontend/upload/',
			'design/_images_frontend/core/logos/',
			'design/_images_frontend/staticlogos/'
		);
		
		try{
			ob_start();
			$this->disableLayout();
			if (! isset($_FILES['Filedata'])){
				echo '';
			}
			else{
				$_FILES['Filedata']['name'] = strtolower($_FILES['Filedata']['name']);
				if (isset($_POST['path'])){
					$ext = substr(strrchr($_FILES['Filedata']['name'], '.'), 1);
					if (! in_array($ext, $allowedExtensions)){
						throw new Exception('Wrong extension given.');
					}
					else{
						if (in_array($_POST['path'], $allowedFolders)){
							$this->AddToFilesystem($_FILES['Filedata'], $_POST['path']);
						}
						else{
							throw new Exception('Wrong path given.');
						}
					}
				}
				else{
					$this->AddToGallery($_FILES['Filedata']);
				}
			}
		}
		catch (Exception $e){
			echo $e->getMessage();
		}
	}

	public function edit ()
	{
		// dodawanie zdjec
		$form = new FE_Form(Array(
			'name' => 'edit_file',
			'action' => '',
			'method' => 'post'
		));
		
		$photosPane = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'photos_pane',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS')
		)));
		
		$photosPane->AddChild(new FE_Image(Array(
			'name' => 'photo',
			'label' => $this->registry->core->getMessage('TXT_PHOTOS'),
			'repeat_min' => 0,
			'repeat_max' => FE::INFINITE,
			'upload_url' => App::getURLAdressWithAdminPane() . 'files/add'
		)));
		
		$form->AddFilter(new FE_FilterTrim());
		
		if ($form->Validate(FE::SubmittedData())){
			App::redirect(__ADMINPANE__ . '/files');
		}
		
		$this->registry->template->assign('form', $form);
		$this->registry->xajax->processRequest();
		$this->registry->template->assign('xajax', $this->registry->xajax->getJavascript());
		
		$this->registry->template->display($this->loadTemplate('edit.tpl'));
	}

	protected function AddToFilesystem ($file, $path)
	{
		// FIXME: Brakuje jakiegos zabezpieczenia, ktore sprawiloby, ze plikow nie mozna byloby zapisac gdziekolwiek.
		$filepath = $path . $file['name'];
		if (file_exists($filepath)){
			throw new Exception('File "' . $file['name'] . '" already exists.');
		}
		if (! move_uploaded_file($file['tmp_name'], $filepath)){
			throw new Exception('File upload unsuccessful.');
		}
		echo "response = {sFilename: '{$file['name']}'}";
	}

	protected function AddToGallery ($file)
	{
		$id = App::getModel('gallery/gallery')->process($file, 1);
		if (! preg_match('/\.swf$/', $file['name'])){
			$image = App::getModel('gallery/gallery')->getSmallImageById($id);
		}
		else{
			$image = Array(
				'path' => '',
				'filename' => $file['name'],
				'filextensioname' => 'swf',
				'filetypename' => 'application/x-shockwave-flash'
			);
		}
		echo "response = {sId: '{$id}', sThumb: '{$image['path']}', sFilename: '{$image['filename']}', sExtension: '{$image['filextensioname']}', sFileType: '{$image['filetypename']}'}";
		die();
	}
}
