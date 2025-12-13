<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class ModernTeaserModuleTest extends TestCase {

	public function testRequiresModuleReturnsEmptyArray(): void {
		$view = new class implements \Base3\Api\IMvcView {
			public function setPath(string $path = '.') {}
			public function assign(string $key, $value) {}
			public function setTemplate(string $template = 'default') {}
			public function loadTemplate(): string { return ''; }
			public function loadBricks(string $set, string $language = '') {}
			public function getBricks(string $set): ?array { return null; }
		};

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
