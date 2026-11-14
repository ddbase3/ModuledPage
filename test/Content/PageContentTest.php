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

use Base3\ModuledPage\Content\PageContent;

use Base3\CoreFoundation\Api\IMvcView;
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

		$view->expects(self::exactly(3))
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
			'width' => 'frame',
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

		// 3 defaults + 3 data assignments
		$view->expects(self::exactly(6))
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
			'width' => 'frame',
			'extra' => 'value',
		], $assigned);
	}

	public function testGetSchemaReturnsExpectedJsonSchema(): void {
		$view = new PageContentTestDouble1();

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

require_once __DIR__ . '/PageContentTestDouble1.php';
