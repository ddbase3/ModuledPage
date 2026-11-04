<?php declare(strict_types=1);

namespace ModuledPage\Test\Content;

use Base3\Api\IDisplay;
use Base3\Core\MvcView;
use Base3\Language\Api\ILanguage;
use Base3\Test\Core\ClassMapStub;
use ModuledPage\Content\DisplayPageModule;
use ModuledPage\Content\IframeModule;
use ModuledPage\Content\ModernTeaserModule;
use ModuledPage\Content\PageContent;
use ModuledPage\Content\PanoramaModule;
use ModuledPage\Content\ParallaxModule;
use PHPUnit\Framework\TestCase;

final class ModuledPageRenderedTemplatesTest extends TestCase {

	public function testPageContentRendersBackgroundWidthAndHtml(): void {
		$module = new PageContent($this->view());
		$module->setData([
			'background' => '#abcdef',
			'width' => 'full',
			'content' => '<h2>Hello</h2>'
		]);
		$html = $module->getHtml();
		self::assertStringContainsString('<section style="background:#abcdef;">', $html);
		self::assertStringContainsString('<div class="full">', $html);
		self::assertStringContainsString('<h2>Hello</h2>', $html);
	}

	public function testIframeRendersProvidedAttributes(): void {
		$module = new IframeModule($this->view());
		$module->setData([
			'url' => 'https://example.test/embed',
			'allow' => 'fullscreen',
			'height' => '45vh'
		]);
		$html = $module->getHtml();
		self::assertStringContainsString('src="https://example.test/embed"', $html);
		self::assertStringContainsString('allow="fullscreen"', $html);
		self::assertStringContainsString('height:45vh', $html);
	}

	public function testParallaxOmitsTextWrapperWithoutContent(): void {
		$module = new ParallaxModule($this->view());
		$module->setData(['image' => '/bg.jpg']);
		$html = $module->getHtml();
		self::assertStringContainsString('background-image:url(/bg.jpg)', $html);
		self::assertStringNotContainsString('<div class="frame">', $html);
	}

	public function testParallaxIncludesTextWrapperForNonemptyContent(): void {
		$module = new ParallaxModule($this->view());
		$module->setData(['content' => '<p>Text</p>', 'height' => '50vh']);
		$html = $module->getHtml();
		self::assertStringContainsString('height:50vh', $html);
		self::assertStringContainsString('<div class="frame">', $html);
		self::assertStringContainsString('<p>Text</p>', $html);
	}

	public function testPanoramaRendersImageAndHeightWithoutTextWrapper(): void {
		$module = new PanoramaModule($this->view());
		$module->setData(['image' => '/panorama.jpg', 'height' => '25em']);
		$html = $module->getHtml();
		self::assertStringContainsString('background-image:url(/panorama.jpg)', $html);
		self::assertStringContainsString('height:25em', $html);
		self::assertStringNotContainsString('<div class="frame">', $html);
	}

	public function testModernTeaserRendersAllConfiguredTextAndLinkValues(): void {
		$module = new ModernTeaserModule($this->view());
		$module->setData([
			'image' => '/teaser.jpg',
			'align' => 'right',
			'color1' => '#111',
			'color2' => '#222',
			'headline' => 'A headline',
			'text' => 'Description',
			'url' => '/details',
			'link' => 'Read more'
		]);
		$html = $module->getHtml();
		self::assertStringContainsString('background-image:url(/teaser.jpg)', $html);
		self::assertStringContainsString('<h3>A headline</h3>', $html);
		self::assertStringContainsString('<p>Description</p>', $html);
		self::assertStringContainsString('<a href="/details">Read more</a>', $html);
	}

	public function testDisplayModuleRendersActualDisplayInsideFullWidthTemplate(): void {
		$display = $this->createStub(IDisplay::class);
		$display->method('getOutput')->willReturn('<strong>Display</strong>');
		$classmap = new ClassMapStub();
		$classmap->registerInstance($display, 'test-display', [IDisplay::class]);
		$module = new DisplayPageModule($this->view(), $classmap);
		$module->setData(['display' => 'test-display', 'data' => ['id' => 3], 'width' => 'wide']);
		$html = $module->getHtml();
		self::assertStringContainsString('<div class="full">', $html);
		self::assertStringContainsString('<strong>Display</strong>', $html);
	}

	private function view(): MvcView {
		$language = $this->createStub(ILanguage::class);
		$language->method('getLanguage')->willReturn('de');
		return new MvcView($language);
	}
}
