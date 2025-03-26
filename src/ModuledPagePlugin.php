<?php declare(strict_types=1);

namespace ModuledPage;

use Base3\Api\IPlugin;
use Base3\Core\ServiceLocator;

class ModuledPagePlugin implements IPlugin {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "moduledpageplugin";
	}

	// Implementation of IPlugin

	public function init() {

		$this->servicelocator

			->set(
				$this->getName(),
				$this,
				ServiceLocator::SHARED)

			->set(
				'view',
				function() {
					return new \Base3\Core\MvcView;
				});
	}

}
