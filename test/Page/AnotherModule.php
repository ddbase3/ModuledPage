<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use PHPUnit\Framework\TestCase;

class AnotherModule extends AbstractModule {

	public function getHtml() {
		return '';
	}
}
