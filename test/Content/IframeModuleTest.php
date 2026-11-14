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

use Base3\ModuledPage\Content\IframeModule;

use Base3\CoreFoundation\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class IframeModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('iframemodule', IframeModule::getName());
	}

	public function testGetHtmlAssignsDefaultsAndRendersTemplate(): void {
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
			->with('Content/IframeModule.php');

		$view->expects(self::exactly(3))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('<iframe></iframe>');

		$module = new IframeModule($view);

		self::assertSame('<iframe></iframe>', $module->getHtml());

		self::assertSame([
			'url' => '',
			'height' => '20em',
			'allow' => '',
		], $assigned);
	}

	public function testGetHtmlOverridesDefaultsWithProvidedData(): void {
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
			->with('Content/IframeModule.php');

		$view->expects(self::exactly(3))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new IframeModule($view);
		$module->setData([
			'url' => 'https://example.com',
			'height' => '42em',
			'allow' => 'fullscreen',
		]);

		self::assertSame('rendered', $module->getHtml());

		self::assertSame([
			'url' => 'https://example.com',
			'height' => '42em',
			'allow' => 'fullscreen',
		], $assigned);
	}

	public function testGetHtmlUsesDefaultsForMissingKeys(): void {
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
			->with('Content/IframeModule.php');

		$view->expects(self::exactly(3))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('ok');

		$module = new IframeModule($view);
		$module->setData([
			'url' => 'https://example.com/embed',
		]);

		self::assertSame('ok', $module->getHtml());

		self::assertSame([
			'url' => 'https://example.com/embed',
			'height' => '20em',
			'allow' => '',
		], $assigned);
	}
}
