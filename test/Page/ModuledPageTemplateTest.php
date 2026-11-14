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

use Base3\Core\MvcView;
use Base3\Core\ServiceLocator;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\Test\Core\ClassMapStub;
use PHPUnit\Framework\TestCase;

final class ModuledPageTemplateTest extends TestCase {

	private ServiceLocator $previousLocator;
	private array $previousRequest;

	protected function setUp(): void {
		parent::setUp();
		$this->previousLocator = ServiceLocator::getInstance();
		ServiceLocator::useInstance(new ServiceLocator());
		$this->previousRequest = $_REQUEST ?? [];
		$_REQUEST = [];
	}

	protected function tearDown(): void {
		ServiceLocator::useInstance($this->previousLocator);
		$_REQUEST = $this->previousRequest;
		parent::tearDown();
	}

	public function testDefaultTemplateRendersLanguageHeaderContentAndFooter(): void {
		$page = $this->page();
		$page->setPageTitle('Not part of the template');
		$page->appendHeader(new PageTestHeader('<meta name="demo" content="test">'));
		$page->appendContent(new PageTestContent('<main>Hello</main>'));
		$page->appendFooter(new PageTestFooter('<script>sample()</script>'));

		$html = $page->getOutput();
		self::assertStringContainsString('<!doctype html>', $html);
		self::assertStringContainsString('<html lang="de">', $html);
		self::assertStringContainsString('<meta name="demo" content="test">', $html);
		self::assertStringContainsString('<main>Hello</main>', $html);
		self::assertStringContainsString('<script>sample()</script>', $html);
		self::assertStringNotContainsString('class="embed"', $html);
		self::assertStringNotContainsString('noprivacy', $html);
	}

	public function testEmbeddedPagesReceiveEmbedClass(): void {
		$_REQUEST['embed'] = '1';
		$html = $this->page()->getOutput();
		self::assertStringContainsString('<body class="embed">', $html);
	}

	public function testLowPrivacyProducesNoPrivacyClass(): void {
		ServiceLocator::getInstance()->set('statushandler', new PageTestStatus(9), ServiceLocator::SHARED);
		$html = $this->page()->getOutput();
		self::assertStringContainsString('<body class="noprivacy">', $html);
	}

	public function testPrivacyThresholdTenDoesNotAddNoPrivacyClass(): void {
		ServiceLocator::getInstance()->set('statushandler', new PageTestStatus(10), ServiceLocator::SHARED);
		$html = $this->page()->getOutput();
		self::assertStringNotContainsString('noprivacy', $html);
	}

	public function testEmbedAndNoPrivacyClassesCombineInThatOrder(): void {
		$_REQUEST['embed'] = '';
		ServiceLocator::getInstance()->set('statushandler', new PageTestStatus(0), ServiceLocator::SHARED);
		$html = $this->page()->getOutput();
		self::assertStringContainsString('<body class="embed noprivacy">', $html);
	}

	private function page(): PageTestHarness {
		$language = $this->createStub(ILanguage::class);
		$language->method('getLanguage')->willReturn('de');
		return new PageTestHarness(new ClassMapStub(), new MvcView($language), $language);
	}
}
