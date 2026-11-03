<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use ModuledPage\Page\AbstractModuleFooter;
use PHPUnit\Framework\TestCase;

class TestFooterModule extends AbstractModuleFooter {

	public function getHtml() {
		return '';
	}

	public function getPriority() {
		return 0;
	}
}
