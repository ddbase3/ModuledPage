<?php

namespace ModuledPage\Page;

use Page\Api\IPageModuleDependent;

abstract class AbstractModule implements IPageModuleDependent {

	protected $data = array();

	// Implementation of IPageModule

	public function setData($data) {
		$this->data = $data;
	}

	// Implementation of IPageModuleDependent

	public function getRequiredModules() {
		return array();
	}

	// Implementation of IBase

	public function getName() {
		$ps = explode("\\", get_class($this));
		return strtolower(array_pop($ps));
	}

}
