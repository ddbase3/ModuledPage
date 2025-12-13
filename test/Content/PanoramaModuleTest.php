<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class PanoramaModuleTest extends TestCase {

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('panoramamodule', PanoramaModule::getName());
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
			->with('Content/PanoramaModule.php');

		$view->expects(self::exactly(2))
			->method('assign')
			->willReturnCallback(function (string $key, $value) use (&$assigned): void {
				$assigned[$key] = $value;
			});

		$view->expects(self::once())
			->method('loadTemplate')
			->willReturn('rendered');

		$module = new PanoramaModule($view);

		self::assertSame('rendered', $module->getHtml());

		self::assertSame([
			'image' => '',
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
			->with('Content/PanoramaModule.php');

		$data = [
			'image' => '/img/pano.jpg',
			'height' => '50vh',
			'caption' => 'Nice view',
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

		$module = new PanoramaModule($view);
		$module->setData($data);

		self::assertSame('ok', $module->getHtml());

		self::assertSame([
			'image' => '/img/pano.jpg',
			'height' => '50vh',
			'caption' => 'Nice view',
		], $assigned);
	}
}
