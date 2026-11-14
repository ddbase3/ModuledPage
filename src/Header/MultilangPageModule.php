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

namespace Base3\ModuledPage\Header;

use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\ModuledPage\Page\AbstractModuleHeader;

class MultilangPageModule extends AbstractModuleHeader {

	public function __construct(
		private readonly IConfiguration $configuration,
		private readonly ILanguage $language
	) {}

	public static function getName(): string {
		return "multilangpagemodule";
	}

	public function getHtml() {
		$cnf = $this->configuration->get('base');

		$out = isset($_REQUEST['out']) && strlen($_REQUEST['out']) ? $_REQUEST['out'] : 'html';
		$name = isset($_REQUEST['name']) && strlen($_REQUEST['name']) ? $_REQUEST['name'] : 'index';

		$elems = array();
		$elems[] = '<base href="' . $cnf["url"] . '" />';
		$elems[] = '<link rel="canonical" href="' . rtrim($cnf["url"], "/") . '/' . $this->language->getLanguage() . '/' . $name . '.' . $out . '" />';
		return implode("\n", $elems);
	}

	public function getPriority() {
		return 5;
	}
}
