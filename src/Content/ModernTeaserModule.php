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

namespace Base3\ModuledPage\Content;

use Base3\CoreFoundation\Api\IMvcView;
use Base3\ModuledPage\Page\AbstractModuleContent;

class ModernTeaserModule extends AbstractModuleContent {

	public function __construct(private readonly IMvcView $view) {}

	public function requiresModule() {
		return array();
	}

	public static function getName(): string {
		return "modernteasermodule";
	}

	public function getHtml() {

		// http://labs.zeroseven.de/architektur/html-kann-ganz-schoen-schraeg-sein/

		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/ModernTeaserModule.php');
		foreach ($this->data as $tag => $content) $this->view->assign($tag, $content);
		return $this->view->loadTemplate();
	}

}
