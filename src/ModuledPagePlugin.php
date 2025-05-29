<?php declare(strict_types=1);

namespace ModuledPage;

use Base3\Api\IContainer;
use Base3\Api\IMvcView;
use Base3\Api\IPlugin;
use Base3\Core\Check;
use Base3\Core\MvcView;

class ModuledPagePlugin implements IPlugin {

	public function __construct(private readonly IContainer $container) {}

	// Implementation of IBase

	public static function getName(): string {
		return "moduledpageplugin";
	}

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(self::getName(), $this, IContainer::SHARED)

			->set('view', fn() => new MvcView)
			->set(IMvcView::class, 'view', IContainer::ALIAS)

                        ->set(
                                'moduledpagechecks',
                                array(
                                        fn() => new Check($this->container)
                                ));
	}
}
