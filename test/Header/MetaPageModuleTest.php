<?php declare(strict_types=1);

namespace ModuledPage\Header;

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
