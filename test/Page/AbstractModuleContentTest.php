<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use Base3\Page\Api\IPageModuleContent;
use ModuledPage\Page\AbstractModule;
use ModuledPage\Page\AbstractModuleContent;
use PHPUnit\Framework\TestCase;

final class AbstractModuleContentTest extends TestCase {

	public function testConcreteContentImplementsPageModuleContentContract(): void {
		$content = new PageTestContent('Body');
		self::assertInstanceOf(AbstractModule::class, $content);
		self::assertInstanceOf(AbstractModuleContent::class, $content);
		self::assertInstanceOf(IPageModuleContent::class, $content);
	}

	public function testModuleNameUsesSubclassNameAndContentUsesInitialValue(): void {
		self::assertSame('pagetestcontent', PageTestContent::getName());
		self::assertSame('Initial', (new PageTestContent('Initial'))->getHtml());
	}

	public function testSetDataCanReplaceContentWithoutChangingDependencies(): void {
		$content = new PageTestContent('Initial', ['header']);
		$content->setData(['html' => '<p>Updated</p>']);
		self::assertSame('<p>Updated</p>', $content->getHtml());
		self::assertSame(['header'], $content->getRequiredModules());
	}

	public function testDefaultDependencyListIsEmpty(): void {
		self::assertSame([], (new PageTestContent())->getRequiredModules());
	}
}
