<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use Base3\Page\Api\IPagePostDataProcessor;
use ModuledPage\Page\AbstractModuleContent;
use PHPUnit\Framework\TestCase;

final class AbstractFormModuleContentTest extends TestCase {

	private ?array $previousServer = null;

	protected function setUp(): void {
		parent::setUp();
		$this->previousServer = $_SERVER ?? null;
		$_SERVER = $this->previousServer ?? [];
		unset($_SERVER['HTTP_REFERER']);
	}

	protected function tearDown(): void {
		if ($this->previousServer === null) {
			unset($_SERVER);
		} else {
			$_SERVER = $this->previousServer;
		}
		parent::tearDown();
	}

	public function testFormModuleExposesPageAndPostProcessorContracts(): void {
		$form = new PageTestForm();
		self::assertInstanceOf(AbstractModuleContent::class, $form);
		self::assertInstanceOf(IPagePostDataProcessor::class, $form);
		self::assertSame('pagetestform', PageTestForm::getName());
		self::assertSame('pagetestform.php', $form->getUrl());
		self::assertSame('<form></form>', $form->getHtml());
	}

	public function testHelpUsesConcreteUnqualifiedClassName(): void {
		self::assertSame("Help of PageTestForm\n", (new PageTestForm())->getHelp());
	}

	public function testMissingReferrerUsesRootAsForwardUrl(): void {
		self::assertSame('/', (new PageTestForm())->getForwardUrl());
	}

	public function testReferrerIsPassedThroughAsForwardUrl(): void {
		$_SERVER['HTTP_REFERER'] = 'https://example.test/form?submitted=1';
		self::assertSame('https://example.test/form?submitted=1', (new PageTestForm())->getForwardUrl());
	}

	public function testEmptyReferrerRemainsEmpty(): void {
		$_SERVER['HTTP_REFERER'] = '';
		self::assertSame('', (new PageTestForm())->getForwardUrl());
	}

	public function testDefaultPostProcessingDoesNotMutateServerState(): void {
		$_SERVER['HTTP_REFERER'] = '/before';
		$form = new PageTestForm();
		self::assertNull($form->processPostData());
		self::assertSame('/before', $form->getForwardUrl());
	}
}
