<?php declare(strict_types=1);

namespace ModuledPage;

use Base3\Api\IContainer;
use Base3\Api\IMvcView;
use Base3\Api\IPlugin;
use Base3\Core\Check;
use Base3\Core\MvcView;
use Base3\Language\Api\ILanguage;

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

			->set(IMvcView::class, fn($c) => new MvcView($c->get(ILanguage::class)))
			->set('view', IMvcView::class, IContainer::ALIAS)

                        ->set(
                                'moduledpagechecks',
                                array(
                                        fn() => new Check($this->container)
                                ));
	}
}
