<?php declare(strict_types=1);

namespace ModuledPage;

use Base3\Api\IPlugin;
use Base3\Api\IContainer;
use Base3\Core\MvcView;
use Base3\Api\IMvcView;
use Base3\Core\Check;

class ModuledPagePlugin implements IPlugin {

	private $container;

	public function __construct(IContainer $container) {
		$this->container = $container;
	}

	// Implementation of IBase

	public function getName() {
		return "moduledpageplugin";
	}

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(
				$this->getName(),
				$this,
				IContainer::SHARED)

			->set(
				'view',
				function() {
					return new MvcView;
				})

			->set(
				IMvcView::class,
				'view',
				IContainer::ALIAS)

                        ->set(
                                'moduledpagechecks',
                                array(
                                        function() { return new Check($this->container->get(IContainer::class)); }
                                ));
	}
}
