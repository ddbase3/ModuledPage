<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
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
		$view = new class implements \Base3\Api\IMvcView {
			public function setPath(string $path = '.') {}
			public function assign(string $key, $value) {}
			public function setTemplate(string $template = 'default') {}
			public function loadTemplate(): string { return ''; }
			public function loadBricks(string $set, string $language = '') {}
			public function getBricks(string $set): ?array { return null; }
		};

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
