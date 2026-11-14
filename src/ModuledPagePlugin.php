<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of ModuledPage for BASE3 Framework.
 *
 * ModuledPage composes BASE3 web pages from reusable page modules.
 * It combines module content using framework display and view contracts.
 * Page composition remains independent of individual host applications.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 **********************************************************************/

namespace Base3\ModuledPage;

use Base3\CoreFoundation\Api\IContainer;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\IPlugin;
use Base3\Core\Check;
use Base3\Core\MvcView;
use Base3\CoreFoundation\Language\Api\ILanguage;

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
