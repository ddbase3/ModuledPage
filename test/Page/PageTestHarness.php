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

namespace Base3\ModuledPage\Test\Page;

use Base3\CoreFoundation\Page\Api\IPageModuleContent;
use Base3\CoreFoundation\Page\Api\IPageModuleFooter;
use Base3\CoreFoundation\Page\Api\IPageModuleHeader;
use Base3\ModuledPage\Page\AbstractModuledPage;

final class PageTestHarness extends AbstractModuledPage {

	public static function getName(): string {
		return 'pagetestharness';
	}

	public function setPageTitle(string $title): void {
		$this->setTitle($title);
	}

	public function appendHeader(IPageModuleHeader $module): void {
		$this->addHeader($module);
	}

	public function appendContent(IPageModuleContent $module): void {
		$this->addContent($module);
	}

	public function appendFooter(IPageModuleFooter $module): void {
		$this->addFooter($module);
	}
}
