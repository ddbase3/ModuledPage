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

namespace Base3\ModuledPage\Page;

use Base3\CoreFoundation\Page\Api\IPageModuleDependent;

abstract class AbstractModule implements IPageModuleDependent {

	protected $data = array();

	// Implementation of IPageModule

	public function setData($data) {
		$this->data = $data;
	}

	// Implementation of IPageModuleDependent

	public function getRequiredModules() {
		return array();
	}

	// Implementation of IBase

	public static function getName(): string {
		$fullClass = static::class;
		$parts = explode('\\', $fullClass);
		return strtolower(end($parts));
	}
}
