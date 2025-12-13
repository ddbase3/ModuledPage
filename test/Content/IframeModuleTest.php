<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
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
