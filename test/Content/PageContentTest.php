<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class PageContentTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('pagecontent', PageContent::getName());
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
			->with('Content/PageContent.php');

		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new PageContent($view);

		self::assertSame('rendered', $module->getHtml());

		self::assertSame([
			'content' => '',
			'background' => 'none',
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
			->with('Content/PageContent.php');

		$data = [
			'content' => '<p>Hello</p>',
			'background' => 'blue',
			'extra' => 'value',
		];

		// 2 defaults + 3 data assignments
		$view->expects(self::exactly(5))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('ok');

		$module = new PageContent($view);
		$module->setData($data);

		self::assertSame('ok', $module->getHtml());

		// Final assigned values must reflect overrides from $data
		self::assertSame([
			'content' => '<p>Hello</p>',
			'background' => 'blue',
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

		$module = new PageContent($view);

		$schema = $module->getSchema();

		self::assertIsArray($schema);
		self::assertSame('https://json-schema.org/draft-2020-12/schema', $schema['$schema'] ?? null);
		self::assertSame('object', $schema['type'] ?? null);

		self::assertArrayHasKey('properties', $schema);
		self::assertArrayHasKey('content', $schema['properties']);
		self::assertSame('string', $schema['properties']['content']['type'] ?? null);

		self::assertSame(['content'], $schema['required'] ?? null);
	}
}
