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

use Base3\ModuledPage\Content\DisplayPageModule;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IDisplay;
use Base3\CoreFoundation\Api\IMvcView;
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

		$assigned = [];
		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(static function(string $name, mixed $value) use (&$assigned): void {
				$assigned[$name] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new DisplayPageModule($view, $classmap);
		self::assertSame('rendered', $module->getHtml());
		self::assertSame(['content' => '', 'width' => 'frame'], $assigned);
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

		$assigned = [];
		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(static function(string $name, mixed $value) use (&$assigned): void {
				$assigned[$name] = $value;
			});

		$view->method('setPath');
		$view->method('setTemplate');
		$view->method('loadTemplate')->willReturn('ok');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'mydisplay',
			'data' => ['a' => 1],
		]);

		self::assertSame('ok', $module->getHtml());
		self::assertSame(['content' => '', 'width' => 'frame'], $assigned);
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

		$assigned = [];
		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(static function(string $name, mixed $value) use (&$assigned): void {
				$assigned[$name] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered-page');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'foo',
			'data' => ['x' => 'y'],
		]);

		self::assertSame('rendered-page', $module->getHtml());
		self::assertSame(['content' => 'DISPLAY-OUTPUT', 'width' => 'frame'], $assigned);
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

		$assigned = [];
		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(static function(string $name, mixed $value) use (&$assigned): void {
				$assigned[$name] = $value;
			});

		$view->method('setPath');
		$view->method('setTemplate');
		$view->method('loadTemplate')->willReturn('ok');

		$module = new DisplayPageModule($view, $classmap);
		$module->setData([
			'display' => 'foo',
			'data' => ['x' => 'y'],
		]);

		self::assertSame('ok', $module->getHtml());
		self::assertSame(['content' => '', 'width' => 'frame'], $assigned);
	}
}
