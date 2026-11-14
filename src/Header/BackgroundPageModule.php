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

class BackgroundPageModule extends AbstractModuleHeader {

	public static function getName(): string {
		return "backgroundpagemodule";
	}

	public function getHtml() {
		switch (true) {

			case isset($this->data["image"]):
				return '<style>body { background:url(' . $this->data["image"] . ') center no-repeat; background-size:cover; }</style>';

			case isset($this->data["gradient"]):
				return '<style>body { background:linear-gradient(' . $this->data["gradient"][0] . ', ' . $this->data["gradient"][1] . '); }</style>';

		}
		return '';
	}

	public function getPriority() {
		return 90;
	}
}
