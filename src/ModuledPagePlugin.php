<?php

namespace ModuledPage;

use Api\IPlugin;

class ModuledPagePlugin implements IPlugin {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "moduledpageplugin";
	}

	// Implementation of IPlugin

	public function init() {
		$this->servicelocator
			->set($this->getName(), $this, true)
			->set('view', function() { return new \Base3\MvcView; })
			;
	}

}
