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

use Base3\ModuledPage\Page\AbstractModule;
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

require_once __DIR__ . '/TestModule.php';
require_once __DIR__ . '/AnotherModule.php';
