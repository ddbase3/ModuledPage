<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class ModernTeaserModuleTest extends TestCase {

	public function testRequiresModuleReturnsEmptyArray(): void {
		$view = new ModernTeaserModuleTestDouble1();

		$module = new ModernTeaserModule($view);

		self::assertSame([], $module->requiresModule());
	}

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('modernteasermodule', ModernTeaserModule::getName());
	}

	public function testGetHtmlSetsPathTemplateAssignsAllDataAndRenders(): void {
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
			->with('Content/ModernTeaserModule.php');

		$view->expects(self::exactly(3))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new ModernTeaserModule($view);
		$module->setData([
			'title' => 'Hello',
			'text' => 'World',
			'cta' => 'Click',
		]);

		self::assertSame('rendered', $module->getHtml());

		self::assertSame([
			'title' => 'Hello',
			'text' => 'World',
			'cta' => 'Click',
		], $assigned);
	}

	public function testGetHtmlAssignsNothingWhenNoDataAndRenders(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$view = $this->createMock(IMvcView::class);

		$view->expects(self::once())
			->method('setPath')
			->with(DIR_PLUGIN . 'ModuledPage');

		$view->expects(self::once())
			->method('setTemplate')
			->with('Content/ModernTeaserModule.php');

		$view->expects(self::never())
			->method('assign');

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('ok');

		$module = new ModernTeaserModule($view);

		self::assertSame('ok', $module->getHtml());
	}
}

require_once __DIR__ . '/ModernTeaserModuleTestDouble1.php';
