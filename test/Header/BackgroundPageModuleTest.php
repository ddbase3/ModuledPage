<?php declare(strict_types=1);

namespace ModuledPage\Header;

use PHPUnit\Framework\TestCase;

final class BackgroundPageModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('backgroundpagemodule', BackgroundPageModule::getName());
	}

	public function testGetHtmlReturnsImageStyleWhenImageIsSet(): void {
		$module = new BackgroundPageModule();
		$module->setData([
			'image' => 'https://example.com/bg.jpg',
		]);

		$expected = '<style>body { background:url(https://example.com/bg.jpg) center no-repeat; background-size:cover; }</style>';
		self::assertSame($expected, $module->getHtml());
	}

	public function testGetHtmlReturnsGradientStyleWhenGradientIsSet(): void {
		$module = new BackgroundPageModule();
		$module->setData([
			'gradient' => ['red', 'blue'],
		]);

		$expected = '<style>body { background:linear-gradient(red, blue); }</style>';
		self::assertSame($expected, $module->getHtml());
	}

	public function testGetHtmlPrefersImageOverGradientWhenBothAreSet(): void {
		$module = new BackgroundPageModule();
		$module->setData([
			'image' => 'https://example.com/bg.jpg',
			'gradient' => ['red', 'blue'],
		]);

		$expected = '<style>body { background:url(https://example.com/bg.jpg) center no-repeat; background-size:cover; }</style>';
		self::assertSame($expected, $module->getHtml());
	}

	public function testGetHtmlReturnsEmptyStringWhenNoKnownDataIsSet(): void {
		$module = new BackgroundPageModule();
		$module->setData([
			'other' => 'x',
		]);

		self::assertSame('', $module->getHtml());
	}

	public function testGetPriorityReturns90(): void {
		$module = new BackgroundPageModule();
		self::assertSame(90, $module->getPriority());
	}
}
