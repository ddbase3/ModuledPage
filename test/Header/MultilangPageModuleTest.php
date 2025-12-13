<?php declare(strict_types=1);

namespace ModuledPage\Header;

use Base3\Configuration\Api\IConfiguration;
use Base3\Language\Api\ILanguage;
use PHPUnit\Framework\TestCase;

final class MultilangPageModuleTest extends TestCase {

	protected function tearDown(): void {
		unset($_REQUEST['out'], $_REQUEST['name']);
	}

	public function testGetNameReturnsFixedName(): void {
		self::assertSame('multilangpagemodule', MultilangPageModule::getName());
	}

	public function testGetHtmlUsesDefaultRequestValuesWhenNotProvided(): void {
		$configuration = $this->createMock(IConfiguration::class);
		$language = $this->createMock(ILanguage::class);

		$configuration->expects(self::once())
			->method('get')
			->with('base')
			->willReturn(['url' => 'https://example.com/']);

		$language->expects(self::once())
			->method('getLanguage')
			->willReturn('de');

		unset($_REQUEST['out'], $_REQUEST['name']);

		$module = new MultilangPageModule($configuration, $language);

		$expected = '<base href="https://example.com/" />' . "\n"
			. '<link rel="canonical" href="https://example.com/de/index.html" />';

		self::assertSame($expected, $module->getHtml());
	}

	public function testGetHtmlUsesProvidedRequestValues(): void {
		$configuration = $this->createMock(IConfiguration::class);
		$language = $this->createMock(ILanguage::class);

		$configuration->expects(self::once())
			->method('get')
			->with('base')
			->willReturn(['url' => 'https://example.com']); // no trailing slash

		$language->expects(self::once())
			->method('getLanguage')
			->willReturn('en');

		$_REQUEST['out'] = 'json';
		$_REQUEST['name'] = 'home';

		$module = new MultilangPageModule($configuration, $language);

		$expected = '<base href="https://example.com" />' . "\n"
			. '<link rel="canonical" href="https://example.com/en/home.json" />';

		self::assertSame($expected, $module->getHtml());
	}

	public function testGetPriorityReturns5(): void {
		$configuration = new class implements IConfiguration {
			public function get($configuration = "") {
				return ['url' => 'https://example.com/'];
			}
			public function set($data, $configuration = "") {}
			public function save() {}
		};

		$language = new class implements ILanguage {
			public function getLanguage(): string { return 'de'; }
			public function setLanguage(string $language) {}
			public function getLanguages(): array { return ['de']; }
		};

		$module = new MultilangPageModule($configuration, $language);

		self::assertSame(5, $module->getPriority());
	}
}
