<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class DisplayPageModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('displaypagemodule', DisplayPageModule::getName());
	}

	public function testGetHtmlAssignsEmptyContentWhenNoDisplayFoundWithDefaults(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$view = $this->createMock(IMvcView::class);
		$classmap = $this->createMock(IClassMap::class);

		$view->expects(self::once())
			->method('setPath')
			->with(DIR_PLUGIN . 'ModuledPage');

		$view->expects(self::once())
			->method('setTemplate')
			->with('Content/DisplayPageModule.php');

		$classmap->expects(self::once())
			->method('getInstances')
			->with([
				'interface' => IDisplay::class,
				'name' => '',
			])
			->willReturn([]);

		$view->expects(self::once())
			->method('assign')
			->with('content', '');

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new DisplayPageModule($view, $classmap);
		self::assertSame('rendered', $module->getHtml());
	}

	public function testGetHtmlAssignsEmptyContentWhenNoDisplayFoundEvenIfNameGiven(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$view = $this->createMock(IMvcView::class);
		$classmap = $this->createMock(IClassMap::class);

		$classmap->expects(self::once())
			->method('getInstances')
			->with([
				'interface' => IDisplay::class,
				'name' => 'mydisplay',
			])
			->willReturn([]);

		$view->expects(self::once())
			->method('assign')
			->with('content', '');

		$view->method('setPath');
		$view->method('setTemplate');
		$view->method('loadTemplate')->willReturn('ok');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'mydisplay',
			'data' => ['a' => 1],
		]);

		self::assertSame('ok', $module->getHtml());
	}

	public function testGetHtmlRendersDisplayOutputAndPassesData(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$view = $this->createMock(IMvcView::class);
		$classmap = $this->createMock(IClassMap::class);
		$display = $this->createMock(IDisplay::class);

		$view->expects(self::once())
			->method('setPath')
			->with(DIR_PLUGIN . 'ModuledPage');

		$view->expects(self::once())
			->method('setTemplate')
			->with('Content/DisplayPageModule.php');

		$classmap->expects(self::once())
			->method('getInstances')
			->with([
				'interface' => IDisplay::class,
				'name' => 'foo',
			])
			->willReturn([$display]);

		$display->expects(self::once())
			->method('setData')
			->with(['x' => 'y']);

		$display->expects(self::once())
			->method('getOutput')
			->willReturn('DISPLAY-OUTPUT');

		$view->expects(self::once())
			->method('assign')
			->with('content', 'DISPLAY-OUTPUT');

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered-page');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'foo',
			'data' => ['x' => 'y'],
		]);

		self::assertSame('rendered-page', $module->getHtml());
	}

	public function testGetHtmlIgnoresNonDisplayInstances(): void {
		if (!defined('DIR_PLUGIN')) {
			define('DIR_PLUGIN', '/plugins/');
		}

		$view = $this->createMock(IMvcView::class);
		$classmap = $this->createMock(IClassMap::class);

		$notADisplay = new \stdClass();

		$classmap->expects(self::once())
			->method('getInstances')
			->willReturn([$notADisplay]);

		$view->expects(self::once())
			->method('assign')
			->with('content', '');

		$view->method('setPath');
		$view->method('setTemplate');
		$view->method('loadTemplate')->willReturn('ok');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'foo',
			'data' => ['x' => 'y'],
		]);

		self::assertSame('ok', $module->getHtml());
	}
}
