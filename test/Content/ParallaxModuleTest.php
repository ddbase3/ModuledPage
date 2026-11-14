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

namespace Base3\ModuledPage\Test\Content;

use Base3\ModuledPage\Content\ParallaxModule;

use Base3\CoreFoundation\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class ParallaxModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('parallaxmodule', ParallaxModule::getName());
	}

	public function testGetHtmlAssignsDefaultsThenRendersWhenNoData(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$assigned = [];

		$view = $this->createMock(IMvcView::class);

		$view->expects(self::once())
			->method('setPath')
			->with(DIR_PLUGIN . 'ModuledPage');

		$view->expects(self::once())
			->method('setTemplate')
			->with('Content/ParallaxModule.php');

		$view->expects(self::exactly(3))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new ParallaxModule($view);

		self::assertSame('rendered', $module->getHtml());

		self::assertSame([
			'image' => '',
			'content' => '',
			'height' => '30vh',
		], $assigned);
	}

	public function testGetHtmlAssignsDefaultsThenOverridesWithDataAndRenders(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$assigned = [];

		$view = $this->createMock(IMvcView::class);

		$view->expects(self::once())
			->method('setPath')
			->with(DIR_PLUGIN . 'ModuledPage');

		$view->expects(self::once())
			->method('setTemplate')
			->with('Content/ParallaxModule.php');

		$data = [
			'image' => '/img/bg.jpg',
			'content' => '<h1>Hi</h1>',
			'height' => '60vh',
			'extra' => 'value',
		];

		// 3 defaults + 4 data assignments
		$view->expects(self::exactly(7))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('ok');

		$module = new ParallaxModule($view);
		$module->setData($data);

		self::assertSame('ok', $module->getHtml());

		self::assertSame([
			'image' => '/img/bg.jpg',
			'content' => '<h1>Hi</h1>',
			'height' => '60vh',
			'extra' => 'value',
		], $assigned);
	}

	public function testGetSchemaReturnsExpectedJsonSchema(): void {
		$view = new ParallaxModuleTestDouble1();

		$module = new ParallaxModule($view);

		$schema = $module->getSchema();

		self::assertIsArray($schema);
		self::assertSame('https://json-schema.org/draft-2020-12/schema', $schema['$schema'] ?? null);
		self::assertSame('object', $schema['type'] ?? null);

		self::assertSame(['image'], $schema['required'] ?? null);

		self::assertArrayHasKey('properties', $schema);
		self::assertArrayHasKey('image', $schema['properties']);
		self::assertArrayHasKey('content', $schema['properties']);
		self::assertArrayHasKey('height', $schema['properties']);

		self::assertSame('string', $schema['properties']['image']['type'] ?? null);
		self::assertSame(200, $schema['properties']['image']['maxLength'] ?? null);

		self::assertSame('string', $schema['properties']['content']['type'] ?? null);
		self::assertSame(200, $schema['properties']['content']['maxLength'] ?? null);

		self::assertSame('string', $schema['properties']['height']['type'] ?? null);
		self::assertSame(20, $schema['properties']['height']['maxLength'] ?? null);
		self::assertSame('30vh', $schema['properties']['height']['default'] ?? null);
	}
}

require_once __DIR__ . '/ParallaxModuleTestDouble1.php';
