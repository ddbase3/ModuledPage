<?php declare(strict_types=1);

namespace ModuledPage\Header;

use PHPUnit\Framework\TestCase;

final class TitlePageModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('titlepagemodule', TitlePageModule::getName());
	}

	public function testGetHtmlRendersCharsetViewportAndTitle(): void {
		$module = new TitlePageModule();
		$module->setData([
			'title' => 'My Page',
		]);

		$expected = '<meta charset="utf-8">' . "\n"
			. '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">' . "\n"
			. '<title>My Page</title>';

		self::assertSame($expected, $module->getHtml());
	}

	public function testAddMetaStoresValueOnObjectButDoesNotAffectGetHtml(): void {
		$module = new TitlePageModule();
		$module->setData([
			'title' => 'My Page',
		]);

		$before = $module->getHtml();

		$module->addMeta('description', 'Hello');

		$after = $module->getHtml();
		self::assertSame($before, $after);

		$ref = new \ReflectionObject($module);
		self::assertTrue($ref->hasProperty('meta'));

		$prop = $ref->getProperty('meta');
		$prop->setAccessible(true);

		$meta = $prop->getValue($module);
		self::assertIsArray($meta);
		self::assertSame('Hello', $meta['description'] ?? null);
	}

	public function testGetPriorityReturns0(): void {
		$module = new TitlePageModule();
		self::assertSame(0, $module->getPriority());
	}
}
