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

namespace Base3\ModuledPage\Test\Header;

use Base3\ModuledPage\Header\MetaPageModule;

use PHPUnit\Framework\TestCase;

final class MetaPageModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('metapagemodule', MetaPageModule::getName());
	}

	public function testGetHtmlReturnsMetaTagsForAllDataInOrder(): void {
		$module = new MetaPageModule();
		$module->setData([
			'description' => 'Hello',
			'robots' => 'index,follow',
		]);

		$expected = '<meta name="description" content="Hello" />' . "\n"
			. '<meta name="robots" content="index,follow" />';

		self::assertSame($expected, $module->getHtml());
	}

	public function testGetHtmlReturnsEmptyStringWhenNoData(): void {
		$module = new MetaPageModule();
		self::assertSame('', $module->getHtml());
	}

	public function testGetPriorityReturns1(): void {
		$module = new MetaPageModule();
		self::assertSame(1, $module->getPriority());
	}
}
