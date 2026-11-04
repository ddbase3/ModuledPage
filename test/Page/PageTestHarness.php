<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use Base3\Page\Api\IPageModuleContent;
use Base3\Page\Api\IPageModuleFooter;
use Base3\Page\Api\IPageModuleHeader;
use ModuledPage\Page\AbstractModuledPage;

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
