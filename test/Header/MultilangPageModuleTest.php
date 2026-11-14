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

namespace Base3\ModuledPage\Test\Header;

use Base3\ModuledPage\Header\MultilangPageModule;

use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\Test\Configuration\ConfigurationStub;
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
		$configuration = new ConfigurationStub();

		$language = new MultilangPageModuleTestDouble1();

		$module = new MultilangPageModule($configuration, $language);

		self::assertSame(5, $module->getPriority());
	}
}

require_once __DIR__ . '/MultilangPageModuleTestDouble1.php';
