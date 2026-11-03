<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use ModuledPage\Page\AbstractModuleHeader;
use PHPUnit\Framework\TestCase;

class TestHeaderModule extends AbstractModuleHeader {

	public function getHtml() {
		return '';
	}

	public function getPriority() {
		return 0;
	}
}
