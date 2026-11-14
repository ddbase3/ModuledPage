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

use Base3\CoreFoundation\Api\IMvcView;
use Base3\Core\ServiceLocator;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\CoreFoundation\Page\Api\IPageModuleHeader;
use Base3\Test\Core\ClassMapStub;
use PHPUnit\Framework\TestCase;

final class AbstractModuledPageTest extends TestCase {

	private ServiceLocator $previousLocator;

	protected function setUp(): void {
		parent::setUp();
		$this->previousLocator = ServiceLocator::getInstance();
		ServiceLocator::useInstance(new ServiceLocator());
	}

	protected function tearDown(): void {
		ServiceLocator::useInstance($this->previousLocator);
		parent::tearDown();
	}

	public function testEmptyPageAssignsDefaultPrivacyLanguageAndEmptyHtml(): void {
		$assigned = [];
		$view = $this->view($assigned);
		$page = $this->page($view, new ClassMapStub());

		self::assertSame('rendered-page', $page->getOutput());
		self::assertSame([
			'privacy' => 50,
			'title' => '',
			'headhtml' => "\n",
			'bodyhtml' => "\n\n",
			'foothtml' => "\n",
			'language' => 'de'
		], $assigned);
	}

	public function testPageExposesUrlHelpAndTechnicalName(): void {
		$page = $this->page($this->createStub(IMvcView::class), new ClassMapStub());
		self::assertSame('pagetestharness', PageTestHarness::getName());
		self::assertSame('', $page->getUrl());
		self::assertSame("Help of pagetestharness\n", $page->getHelp());
	}

	public function testHeaderAndFooterAreOrderedByPriorityWhileContentKeepsInsertionOrder(): void {
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->setPageTitle('Custom title');
		$page->appendHeader(new PageTestHeader("later\nline two", 90));
		$page->appendHeader(new PageTestSecondHeader('earlier', 1));
		$page->appendContent(new PageTestContent('<section>first</section>'));
		$page->appendContent(new PageTestContent('<section>second</section>'));
		$page->appendFooter(new PageTestFooter('last footer', 200));
		$page->appendFooter(new PageTestSecondFooter('first footer', 10));

		self::assertSame('rendered-page', $page->getOutput('html', true));
		self::assertSame('Custom title', $assigned['title']);
		self::assertSame("\n\t\tearlier\n\n\t\tlater\n\t\tline two\n\n", $assigned['headhtml']);
		self::assertSame("\n<section>first</section>\n<section>second</section>\n\n", $assigned['bodyhtml']);
		self::assertSame("\n\t\tfirst footer\n\n\t\tlast footer\n\n", $assigned['foothtml']);
	}

	public function testDuplicateHeaderAndFooterNamesAreIgnored(): void {
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->appendHeader(new PageTestHeader('unique header'));
		$page->appendHeader(new PageTestHeader('ignored header'));
		$page->appendFooter(new PageTestFooter('unique footer'));
		$page->appendFooter(new PageTestFooter('ignored footer'));
		$page->getOutput();

		self::assertStringContainsString('unique header', $assigned['headhtml']);
		self::assertStringNotContainsString('ignored header', $assigned['headhtml']);
		self::assertStringContainsString('unique footer', $assigned['foothtml']);
		self::assertStringNotContainsString('ignored footer', $assigned['foothtml']);
	}

	public function testEmptyHeaderAndFooterHtmlAreSkippedButEmptyContentRemainsRepresented(): void {
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->appendHeader(new PageTestHeader(''));
		$page->appendFooter(new PageTestFooter(''));
		$page->appendContent(new PageTestContent(''));
		$page->getOutput();

		self::assertSame("\n", $assigned['headhtml']);
		self::assertSame("\n\n\n", $assigned['bodyhtml']);
		self::assertSame("\n", $assigned['foothtml']);
	}

	public function testDependentContentAutomaticallyAddsRequiredHeader(): void {
		$assigned = [];
		$classmap = new ClassMapStub();
		$header = new PageTestHeader('<meta name="required" />', 4);
		$classmap->registerInstance($header, PageTestHeader::getName(), [IPageModuleHeader::class]);
		$page = $this->page($this->view($assigned), $classmap);
		$page->appendContent(new PageTestContent('dependent content', [PageTestHeader::getName()]));
		$page->getOutput();

		self::assertStringContainsString('<meta name="required" />', $assigned['headhtml']);
		self::assertStringContainsString('dependent content', $assigned['bodyhtml']);
		self::assertSame(1, $this->countHeaderLookups($classmap));
	}

	public function testRequiredHeaderCanItselfRequireAnotherHeader(): void {
		$assigned = [];
		$classmap = new ClassMapStub();
		$root = new PageTestHeader('root', 1);
		$child = new PageTestSecondHeader('child', 10, [PageTestHeader::getName()]);
		$classmap->registerInstance($root, PageTestHeader::getName(), [IPageModuleHeader::class]);
		$classmap->registerInstance($child, PageTestSecondHeader::getName(), [IPageModuleHeader::class]);
		$page = $this->page($this->view($assigned), $classmap);
		$page->appendContent(new PageTestContent('content', [PageTestSecondHeader::getName()]));
		$page->getOutput();

		self::assertSame("\n\t\troot\n\n\t\tchild\n\n", $assigned['headhtml']);
		self::assertSame(2, $this->countHeaderLookups($classmap));
	}

	public function testRepeatedDependencyDoesNotDuplicateOutputHeader(): void {
		$assigned = [];
		$classmap = new ClassMapStub();
		$classmap->registerInstance(new PageTestHeader('required'), PageTestHeader::getName(), [IPageModuleHeader::class]);
		$page = $this->page($this->view($assigned), $classmap);
		$page->appendContent(new PageTestContent('first', [PageTestHeader::getName()]));
		$page->appendContent(new PageTestContent('second', [PageTestHeader::getName()]));
		$page->getOutput();

		self::assertSame(1, substr_count($assigned['headhtml'], 'required'));
		self::assertSame("\nfirst\nsecond\n\n", $assigned['bodyhtml']);
	}

	public function testPrivacyIsReadFromStatusAndConvertedToInteger(): void {
		$status = new PageTestStatus('7.8');
		ServiceLocator::getInstance()->set('statushandler', $status, ServiceLocator::SHARED);
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->getOutput();

		self::assertSame(7, $assigned['privacy']);
		self::assertSame(['privacy'], $status->calls);
	}

	public function testMissingPrivacySettingUsesDefault(): void {
		$status = new PageTestStatus(null);
		ServiceLocator::getInstance()->set('statushandler', $status, ServiceLocator::SHARED);
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->getOutput();

		self::assertSame(50, $assigned['privacy']);
		self::assertSame(['privacy'], $status->calls);
	}

	public function testZeroPrivacySettingIsNotTreatedAsMissing(): void {
		ServiceLocator::getInstance()->set('statushandler', new PageTestStatus(0), ServiceLocator::SHARED);
		$assigned = [];
		$page = $this->page($this->view($assigned), new ClassMapStub());
		$page->getOutput();

		self::assertSame(0, $assigned['privacy']);
	}

	private function countHeaderLookups(ClassMapStub $classmap): int {
		return count(array_filter($classmap->calls, static fn(array $call): bool => $call['method'] === 'getInstanceByInterfaceName'));
	}

	private function page(IMvcView $view, ClassMapStub $classmap): PageTestHarness {
		$language = $this->createStub(ILanguage::class);
		$language->method('getLanguage')->willReturn('de');
		return new PageTestHarness($classmap, $view, $language);
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
