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

namespace Base3\ModuledPage\Test\Page;

use Base3\CoreFoundation\Accesscontrol\Api\IAccesscontrol;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\Core\ServiceLocator;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\CoreFoundation\Page\Api\IPageModuleContent;
use Base3\CoreFoundation\Page\Api\IPageModuleFooter;
use Base3\CoreFoundation\Page\Api\IPageModuleHeader;
use Base3\Test\Core\ClassMapStub;
use Base3\ModuledPage\Page\GeneratedPage;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class GeneratedPageTest extends TestCase {

	private ServiceLocator $previousLocator;
	private array $previousRequest;
	private string $pageDirectory;
	private bool $createdPageDirectory = false;
	private array $createdFiles = [];

	protected function setUp(): void {
		parent::setUp();
		$this->previousLocator = ServiceLocator::getInstance();
		$this->previousRequest = $_REQUEST ?? [];
		$_REQUEST = [];

		if (!defined('DIR_LOCAL')) {
			define('DIR_LOCAL', sys_get_temp_dir() . '/moduledpage-test-' . bin2hex(random_bytes(8)) . '/');
		}
		$this->pageDirectory = rtrim(DIR_LOCAL, DIRECTORY_SEPARATOR) . '/Page';
		$this->createdPageDirectory = !is_dir($this->pageDirectory);
		if ($this->createdPageDirectory && !mkdir($this->pageDirectory, 0777, true) && !is_dir($this->pageDirectory)) {
			self::fail('Cannot create page test directory.');
		}

		ServiceLocator::useInstance(new ServiceLocator());
		$accesscontrol = $this->createStub(IAccesscontrol::class);
		$accesscontrol->method('getUserId')->willReturn(7);
		$language = $this->createStub(ILanguage::class);
		$language->method('getLanguage')->willReturn('de');
		ServiceLocator::getInstance()->set('accesscontrol', $accesscontrol, ServiceLocator::SHARED);
		ServiceLocator::getInstance()->set('language', $language, ServiceLocator::SHARED);
	}

	protected function tearDown(): void {
		foreach ($this->createdFiles as $file) {
			if (is_file($file)) unlink($file);
		}
		if ($this->createdPageDirectory) {
			@rmdir($this->pageDirectory);
		}
		$_REQUEST = $this->previousRequest;
		ServiceLocator::useInstance($this->previousLocator);
		parent::tearDown();
	}

	public function testNameAndDefaultUrlArePublic(): void {
		self::assertSame('generatedpage', GeneratedPage::getName());
		self::assertSame('', $this->page(new ClassMapStub(), $this->createStub(IMvcView::class))->getUrl());
	}

	public function testMissingPageNameReturnsEmptyWithoutLoadingTemplate(): void {
		$view = $this->createMock(IMvcView::class);
		$view->expects(self::never())->method('loadTemplate');
		$view->expects(self::never())->method('assign');
		self::assertSame('', $this->page(new ClassMapStub(), $view)->getOutput());
	}

	public function testEmptyPageConfigurationRendersWithDefaultSections(): void {
		$this->writePage([]);
		$assigned = [];
		$view = $this->view($assigned);
		self::assertSame('rendered-page', $this->page(new ClassMapStub(), $view)->getOutput('html', true));
		self::assertSame("\n", $assigned['headhtml']);
		self::assertSame("\n\n", $assigned['bodyhtml']);
		self::assertSame("\n", $assigned['foothtml']);
		self::assertSame(50, $assigned['privacy']);
		self::assertSame('de', $assigned['language']);
	}

	public function testHeaderContentFooterSelectionHonorsUserLanguageAndActiveFlags(): void {
		$classmap = new ClassMapStub();
		$classmap->registerInstance(new PageTestHeader('default header', 10), PageTestHeader::getName(), [IPageModuleHeader::class]);
		$classmap->registerInstance(new PageTestSecondHeader('excluded header', 3), PageTestSecondHeader::getName(), [IPageModuleHeader::class]);
		$classmap->registerInstance(new PageTestContent('default content'), PageTestContent::getName(), [IPageModuleContent::class]);
		$classmap->registerInstance(new PageTestFooter('default footer', 7), PageTestFooter::getName(), [IPageModuleFooter::class]);
		$classmap->registerInstance(new PageTestSecondFooter('excluded footer', 1), PageTestSecondFooter::getName(), [IPageModuleFooter::class]);
		$this->writePage([
			'pageheaders' => [
				['name' => PageTestHeader::getName(), 'data' => ['html' => 'visible header'], 'active' => true, 'user' => [7]],
				['name' => PageTestSecondHeader::getName(), 'user' => [8]],
				['name' => PageTestSecondHeader::getName(), 'active' => false]
			],
			'pagecontents' => [
				['name' => PageTestContent::getName(), 'data' => ['html' => 'visible content'], 'user' => [7], 'language' => ['de']],
				['name' => PageTestContent::getName(), 'data' => ['html' => 'hidden language'], 'language' => ['en']],
				['name' => PageTestContent::getName(), 'data' => ['html' => 'hidden user'], 'user' => [8]],
				['name' => PageTestContent::getName(), 'data' => ['html' => 'inactive content'], 'active' => false]
			],
			'pagefooters' => [
				['name' => PageTestFooter::getName(), 'data' => ['html' => 'visible footer'], 'user' => [7]],
				['name' => PageTestSecondFooter::getName(), 'user' => [8]],
				['name' => PageTestSecondFooter::getName(), 'active' => false]
			]
		]);

		$assigned = [];
		self::assertSame('rendered-page', $this->page($classmap, $this->view($assigned))->getOutput());
		self::assertSame("\n\t\tvisible header\n\n", $assigned['headhtml']);
		self::assertSame("\nvisible content\n\n", $assigned['bodyhtml']);
		self::assertSame("\n\t\tvisible footer\n\n", $assigned['foothtml']);
		self::assertStringNotContainsString('hidden', json_encode($assigned));
	}

	public function testLanguageAndUserRestrictionsOnlyApplyWhenListsAreArrays(): void {
		$classmap = new ClassMapStub();
		$classmap->registerInstance(new PageTestContent('allowed'), PageTestContent::getName(), [IPageModuleContent::class]);
		$this->writePage(['pagecontents' => [
			['name' => PageTestContent::getName(), 'language' => 'en', 'user' => '8']
		]]);
		$assigned = [];
		$this->page($classmap, $this->view($assigned))->getOutput();
		self::assertSame("\nallowed\n\n", $assigned['bodyhtml']);
	}

	public function testUnfilteredModulesRenderWithTheirProvidedData(): void {
		$classmap = new ClassMapStub();
		$classmap->registerInstance(new PageTestHeader('default'), PageTestHeader::getName(), [IPageModuleHeader::class]);
		$classmap->registerInstance(new PageTestContent('default'), PageTestContent::getName(), [IPageModuleContent::class]);
		$classmap->registerInstance(new PageTestFooter('default'), PageTestFooter::getName(), [IPageModuleFooter::class]);
		$this->writePage([
			'pageheaders' => [['name' => PageTestHeader::getName(), 'data' => ['html' => 'header data']]],
			'pagecontents' => [['name' => PageTestContent::getName(), 'data' => ['html' => 'content data']]],
			'pagefooters' => [['name' => PageTestFooter::getName(), 'data' => ['html' => 'footer data']]]
		]);
		$assigned = [];
		self::assertSame('rendered-page', $this->page($classmap, $this->view($assigned))->getOutput());
		self::assertStringContainsString('header data', $assigned['headhtml']);
		self::assertStringContainsString('content data', $assigned['bodyhtml']);
		self::assertStringContainsString('footer data', $assigned['foothtml']);
	}

	public function testContentModuleMayRequireHeaderResolvedByClassMap(): void {
		$classmap = new ClassMapStub();
		$classmap->registerInstance(new PageTestHeader('required header'), PageTestHeader::getName(), [IPageModuleHeader::class]);
		$classmap->registerInstance(new PageTestContent('body', [PageTestHeader::getName()]), PageTestContent::getName(), [IPageModuleContent::class]);
		$this->writePage(['pagecontents' => [['name' => PageTestContent::getName()]]]);
		$assigned = [];
		$this->page($classmap, $this->view($assigned))->getOutput();
		self::assertStringContainsString('required header', $assigned['headhtml']);
		self::assertStringContainsString('body', $assigned['bodyhtml']);
	}

	public function testStatusPrivacyIsPassedToRenderedPage(): void {
		ServiceLocator::getInstance()->set('statushandler', new PageTestStatus(6), ServiceLocator::SHARED);
		$this->writePage([]);
		$assigned = [];
		$this->page(new ClassMapStub(), $this->view($assigned))->getOutput();
		self::assertSame(6, $assigned['privacy']);
	}

	private function writePage(array $configuration): void {
		$name = 'unit-page-' . bin2hex(random_bytes(8));
		$path = $this->pageDirectory . '/page-' . $name . '.json';
		if (file_put_contents($path, json_encode($configuration, JSON_THROW_ON_ERROR)) === false) {
			self::fail('Unable to create page configuration fixture.');
		}
		$this->createdFiles[] = $path;
		$_REQUEST['name'] = $name;
	}

	private function page(ClassMapStub $classmap, IMvcView $view): GeneratedPage {
		$language = $this->createStub(ILanguage::class);
		$language->method('getLanguage')->willReturn('de');
		return new GeneratedPage($classmap, $view, $language);
	}

	private function view(array &$assigned): IMvcView {
		$view = $this->createStub(IMvcView::class);
		$view->method('assign')->willReturnCallback(static function(string $key, mixed $value) use (&$assigned): void {
			$assigned[$key] = $value;
		});
		$view->method('loadTemplate')->willReturn('rendered-page');
		return $view;
	}
}
