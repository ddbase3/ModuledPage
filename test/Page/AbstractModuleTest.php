<?php declare(strict_types=1);

namespace ModuledPageTest\Page;

use ModuledPage\Page\AbstractModule;
use PHPUnit\Framework\TestCase;

class AbstractModuleTest extends TestCase {

	public function testSetDataStoresDataInProtectedProperty(): void {
		$module = new TestModule();

		$data = ['a' => 1, 'b' => 'x'];
		$module->setData($data);

		$ref = new \ReflectionClass($module);
		$prop = $ref->getProperty('data');
		$prop->setAccessible(true);

		self::assertSame($data, $prop->getValue($module));
	}

	public function testGetRequiredModulesReturnsEmptyArrayByDefault(): void {
		$module = new TestModule();

		self::assertSame([], $module->getRequiredModules());
	}

	public function testGetNameReturnsLowercasedShortClassName(): void {
		self::assertSame('testmodule', TestModule::getName());
	}

	public function testGetNameUsesLateStaticBindingForDifferentSubclass(): void {
		self::assertSame('anothermodule', AnotherModule::getName());
	}

	public function testGetHtmlIsImplementedInConcreteTestSubclass(): void {
		$module = new TestModule();
		self::assertSame('', $module->getHtml());
	}
}

class TestModule extends AbstractModule {

	public function getHtml() {
		return '';
	}
}

class AnotherModule extends AbstractModule {

	public function getHtml() {
		return '';
	}
}
