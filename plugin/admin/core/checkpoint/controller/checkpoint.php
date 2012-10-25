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
 * $Id: checkpoint.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class CheckpointController extends Admin
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
		$this->model = App::getModel('backup');
	}

	public function index ()
	{
		$this->registry->xajax->registerFunction(array(
			'deleteCheckpoint',
			$this->model,
			'deleteCheckpoint'
		));
		$this->registry->template->assign('chkpoints', $this->model->checkpointsFilesForJS());
		$this->Render();
	}

	public function add ()
	{
		
		$form = new FE_Form(Array(
			'name' => 'add_checkpoint',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BACKUP_DATA')
		)));
		
		$requiredData->AddChild(new FE_Select(Array(
			'name' => 'type',
			'label' => $this->registry->core->getMessage('TXT_BACKUP_TYPE'),
			'options' => Array(
				new FE_Option(1, $this->registry->core->getMessage('TXT_BACKUP_TYPE_SQL')),
				new FE_Option(2, $this->registry->core->getMessage('TXT_BACKUP_TYPE_FILES'))
			),
			'default' => 1
		)));
		
		if ($form->Validate(FE::SubmittedData())){
			$type = $form->getSubmitValues(FE_Form::FORMAT_FLAT);
			App::redirect(__ADMINPANE__ . '/checkpoint/confirm/' . $type['type']);
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}

	public function confirm ()
	{
		
		$type = $this->registry->core->getParam();
		
		$form = new FE_Form(Array(
			'name' => 'confirm_checkpoint',
			'action' => '',
			'method' => 'post'
		));
		
		$requiredData = $form->AddChild(new FE_Fieldset(Array(
			'name' => 'required_data',
			'label' => $this->registry->core->getMessage('TXT_BACKUP_DATA')
		)));
		
		$type = $this->registry->core->getParam();
		
		switch ($type) {
			case 1:
				
				$requiredData->AddChild(new FE_ProgressIndicator(Array(
					'name' => 'sql_progress',
					'label' => $this->registry->core->getMessage('TXT_SQL_PROGRESS'),
					'comment' => $this->registry->core->getMessage('TXT_SQL_RECORDS'),
					'chunks' => 1,
					'load' => Array(
						App::getModel('checkpoint'),
						'doLoadQuequeSQL'
					),
					'process' => Array(
						App::getModel('checkpoint'),
						'doProcessQuequeSQL'
					),
					'success' => Array(
						App::getModel('checkpoint'),
						'doSuccessQuequeSQL'
					),
					'preventSubmit' => true
				)));
				
				break;
			case 2:
				
				$requiredData->AddChild(new FE_ProgressIndicator(Array(
					'name' => 'files_progress',
					'label' => $this->registry->core->getMessage('TXT_FILES_PROGRESS'),
					'comment' => $this->registry->core->getMessage('TXT_FILES_RECORDS'),
					'chunks' => 100,
					'load' => Array(
						App::getModel('checkpoint'),
						'doLoadQuequeFiles'
					),
					'process' => Array(
						App::getModel('checkpoint'),
						'doProcessQuequeFiles'
					),
					'success' => Array(
						App::getModel('checkpoint'),
						'doSuccessQuequeFiles'
					),
					'preventSubmit' => true
				)));
				
				break;
		}
		
		$this->registry->template->assign('form', $form);
		$this->Render();
	}
}