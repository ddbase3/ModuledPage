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

use Base3\ModuledPage\Page\AbstractModuleHeader;

class TitlePageModule extends AbstractModuleHeader {

	protected array $meta = [];

	public static function getName(): string {
		return "titlepagemodule";
	}

	public function getHtml() {
		$elems = array();
		$elems[] = '<meta charset="utf-8">';
		$elems[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
		$elems[] = '<title>' . $this->data["title"] . '</title>';
		return implode("\n", $elems);
	}

	public function addMeta($name, $content) {
		$this->meta[$name] = $content;
	}

	public function getPriority() {
		return 0;
	}
}
