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
 * $Id: conflictresolver.php 655 2012-04-24 08:51:44Z gekosale $ 
 */

class ConflictResolverModel extends Model
{

	public function __construct ($registry)
	{
		parent::__construct($registry);
	}

	public function retry ($request)
	{
		$objResponse = new xajaxResponse();
		try{
			if (! (App::getModel($request['model'])->{$request['method']}(implode('', $request['input'])))){
				$objResponse->script("try { GF_Alert('{$this->registry->core->getMessage('TXT_UNABLE_TO_RETRY_OPERATION')}', '{$this->registry->core->getMessage('TXT_NOT_ALL_CONFLICTS_HAS_BEEN_RESOLVED')}'); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
			}
			else{
				$objResponse->script("try { GF_Datagrid.RefreshAll(); GF_Alert('{$this->registry->core->getMessage('TXT_OPERATION_RETRY_SUCCESSFUL')}', ''); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
			}
		}
		catch (Exception $e){
			$objResponse->script("try { GF_Alert('{$this->registry->core->getMessage('TXT_EXCEPTION_ENCOUNTERED')}', '{$e->message}'); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
		}
		return $objResponse;
	}

	public function cancel ($request)
	{
		$objResponse = new xajaxResponse();
		try{
			foreach ($request['input'] as $column => $value){
				DBTracker::flushErrorRecords(Array(
					$column => $value
				));
				break;
			}
			$objResponse->script("try { GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
		}
		catch (Exception $e){
			$objResponse->script("try { GF_Alert('{$this->registry->core->getMessage('TXT_EXCEPTION_ENCOUNTERED')}', '{$e->message}'); GF_ConflictResolver.GetMain().Update(); } catch (x) { GF_Debug.HandleException(x); }");
		}
		return $objResponse;
	}

	public function update ($request, $processFunction)
	{
		$c = DBTracker::getDeleteRecords();
		//print_r($c);
		$conflictGroups = Array();
		if (is_array($c) and count($c)){
			foreach ($c as $g){
				$conflictTables = Array();
				foreach ($g as $table){
					$columns = Array();
					foreach ($table['columns'] as $col){
						$columns[] = '' . '{' . 'name: \'' . $col['name'] . '\',' . 'type: \'' . $col['type'] . '\',' . 'values: [\'' . implode('\',\'', $col['values']) . '\']' . '}' . '';
					}
					$input = Array();
					foreach ($table['input'] as $name => $value){
						$input[] = '' . $name . ': \'' . $value . '\'' . '';
					}
					$conflictTables[] = '' . '{' . 'message: \'' . $this->registry->core->getMessage(isset($table['errorMsg']) ? $table['errorMsg'] : 'TXT_SOMETHING_WENT_WRONG_BUT_THERES_NO_ERROR_MESSAGE_SPECIFIED') . '\',' . 'conflicts: \'' . $table['recordCount'] . '\',' . 'controller: \'' . $table['controller'] . '\',' . 'columns: [' . implode(',', $columns) . ']' . '}' . '';
				}
				if (is_array($g) and count($g)){
					$conflictGroups[] = '' . '{' . 'model: \'' . $table['model'] . '\',' . 'method: \'' . $table['method'] . '\',' . 'input: {' . implode(',', $input) . '' . '},' . 'tables: [' . implode(',', $conflictTables) . ']' . '}' . '';
				}
			}
		}
		$objResponse = new xajaxResponse();
		$objResponse->script('' . '' . $processFunction . '({' . 'id: "' . (isset($request['id']) ? $request['id'] : '') . '",' . 'conflict_groups: [' . implode(',', $conflictGroups) . ']' . '});' . '');
		return $objResponse;
	}

}