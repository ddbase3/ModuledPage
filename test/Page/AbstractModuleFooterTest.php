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
use Base3\ModuledPage\Page\AbstractModuleFooter;
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

require_once __DIR__ . '/TestFooterModule.php';
