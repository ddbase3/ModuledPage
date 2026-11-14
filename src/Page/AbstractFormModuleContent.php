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

use Base3\CoreFoundation\Page\Api\IPagePostDataProcessor;

abstract class AbstractFormModuleContent extends AbstractModuleContent implements IPagePostDataProcessor {

	public function getUrl() {
		return $this->getName() . ".php";
	}

	public function getOutput(string $out = 'html', bool $final = false): string {
		$this->processPostData();
		header('Location: ' . $this->getForwardUrl());
		exit;
	}

	public function getHelp(): string {
		$ps = explode("\\", get_class($this));
		return 'Help of ' . array_pop($ps) . "\n";
	}

	// overwrite if necessary

	public function processPostData() {}

	public function getForwardUrl() {
		return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "/";
	}
}
