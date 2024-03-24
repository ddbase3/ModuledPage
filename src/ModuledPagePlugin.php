<?php

namespace ModuledPage;

use Api\IPlugin;

class ModuledPagePlugin implements IPlugin {

	// Implementation of IBase

	public function getName() {
		return "moduledpageplugin";
	}

	// Implementation of IPlugin

	public function init() {
		$servicelocator = \Base3\ServiceLocator::getInstance()
			->set($this->getName(), $this, true)
			->set('view', function() { return new \Base3\MvcView; })
			;
	}

}
