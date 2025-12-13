<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use ModuledPage\Page\AbstractModuleHeader;
use PHPUnit\Framework\TestCase;

class AbstractModuleHeaderTest extends TestCase {

	public function testIsInstanceOfAbstractModule(): void {
		$module = new TestHeaderModule();
		self::assertInstanceOf(AbstractModule::class, $module);
	}

	public function testInheritedBehaviorWorks(): void {
		$module = new TestHeaderModule();

		$data = ['y' => 2];
		$module->setData($data);

		$ref = new \ReflectionClass($module);
		$prop = $ref->getProperty('data');
		$prop->setAccessible(true);

		self::assertSame($data, $prop->getValue($module));
		self::assertSame([], $module->getRequiredModules());
		self::assertSame('testheadermodule', TestHeaderModule::getName());
	}

	public function testHeaderModuleImplementsGetHtmlAndPriority(): void {
		$module = new TestHeaderModule();

		self::assertSame('', $module->getHtml());
		self::assertSame(0, $module->getPriority());
	}
}

class TestHeaderModule extends AbstractModuleHeader {

	public function getHtml() {
		return '';
	}

	public function getPriority() {
		return 0;
	}
}
