<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use ModuledPage\Page\AbstractModuleFooter;
use PHPUnit\Framework\TestCase;

class AbstractModuleFooterTest extends TestCase {

	public function testIsInstanceOfAbstractModule(): void {
		$module = new TestFooterModule();
		self::assertInstanceOf(AbstractModule::class, $module);
	}

	public function testInheritedBehaviorWorks(): void {
		$module = new TestFooterModule();

		$data = ['x' => 1];
		$module->setData($data);

		$ref = new \ReflectionClass($module);
		$prop = $ref->getProperty('data');
		$prop->setAccessible(true);

		self::assertSame($data, $prop->getValue($module));
		self::assertSame([], $module->getRequiredModules());
		self::assertSame('testfootermodule', TestFooterModule::getName());
	}

	public function testFooterModuleImplementsGetHtmlAndPriority(): void {
		$module = new TestFooterModule();

		self::assertSame('', $module->getHtml());
		self::assertSame(0, $module->getPriority());
	}
}

class TestFooterModule extends AbstractModuleFooter {

	public function getHtml() {
		return '';
	}

	public function getPriority() {
		return 0;
	}
}
