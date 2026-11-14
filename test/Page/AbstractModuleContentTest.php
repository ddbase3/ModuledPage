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

use Base3\CoreFoundation\Page\Api\IPageModuleContent;
use Base3\ModuledPage\Page\AbstractModule;
use Base3\ModuledPage\Page\AbstractModuleContent;
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
