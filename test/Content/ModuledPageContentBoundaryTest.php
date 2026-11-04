<?php declare(strict_types=1);

namespace ModuledPage\Test\Content;

use Base3\Api\IMvcView;
use Base3\Test\Core\ClassMapStub;
use ModuledPage\Content\DisplayPageModule;
use ModuledPage\Content\IframeModule;
use ModuledPage\Content\PageContent;
use ModuledPage\Content\PanoramaModule;
use ModuledPage\Content\ParallaxModule;
use PHPUnit\Framework\TestCase;

final class ModuledPageContentBoundaryTest extends TestCase {

	public function testIframeUsesDefaultsWhenExplicitValuesAreNull(): void {
		$assigned = [];
		$module = new IframeModule($this->view($assigned));
		$module->setData(['url' => null, 'height' => '25em', 'allow' => null]);
		self::assertSame('template', $module->getHtml());
		self::assertSame(['url' => '', 'height' => '25em', 'allow' => ''], $assigned);
	}

	public function testPageContentAssignsAdditionalPropertiesAfterDefaults(): void {
		$assigned = [];
		$module = new PageContent($this->view($assigned));
		$module->setData(['content' => 'Text', 'background' => 'red', 'extra' => 'value']);
		self::assertSame('template', $module->getHtml());
		self::assertSame([
			'content' => 'Text',
			'background' => 'red',
			'width' => 'frame',
			'extra' => 'value'
		], $assigned);
	}

	public function testPanoramaDataCanOverrideDefaultsWithNull(): void {
		$assigned = [];
		$module = new PanoramaModule($this->view($assigned));
		$module->setData(['image' => 'custom.jpg', 'height' => null]);
		$module->getHtml();
		self::assertSame(['image' => 'custom.jpg', 'height' => null], $assigned);
	}

	public function testParallaxDataCanOverrideDefaultsWithNull(): void {
		$assigned = [];
		$module = new ParallaxModule($this->view($assigned));
		$module->setData(['image' => 'custom.jpg', 'content' => null, 'height' => '80vh']);
		$module->getHtml();
		self::assertSame(['image' => 'custom.jpg', 'content' => null, 'height' => '80vh'], $assigned);
	}

	public function testDisplayModuleTreatsAnyNonFrameWidthAsFull(): void {
		foreach (['full', 'wide', '', 'FRAME'] as $width) {
			$assigned = [];
			$module = new DisplayPageModule($this->view($assigned), new ClassMapStub());
			$module->setData(['display' => '', 'width' => $width]);
			$module->getHtml();
			self::assertSame('full', $assigned['width']);
			self::assertSame('', $assigned['content']);
		}
	}

	public function testDisplayModulePreservesFrameWhenExplicitlySelected(): void {
		$assigned = [];
		$module = new DisplayPageModule($this->view($assigned), new ClassMapStub());
		$module->setData(['width' => 'frame']);
		$module->getHtml();
		self::assertSame('frame', $assigned['width']);
	}

	private function view(array &$assigned): IMvcView {
		$view = $this->createStub(IMvcView::class);
		$view->method('assign')->willReturnCallback(static function(string $key, mixed $value) use (&$assigned): void {
			$assigned[$key] = $value;
		});
		$view->method('loadTemplate')->willReturn('template');
		return $view;
	}
}
