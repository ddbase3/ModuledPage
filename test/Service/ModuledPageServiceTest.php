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

namespace Base3\ModuledPage\Test\Service;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IRequest;
use Base3\CoreFoundation\Api\ISchemaProvider;
use Base3\Test\Core\ClassMapStub;
use Base3\ModuledPage\Service\ModuledPageService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Base3\ModuledPage\Service\ModuledPageService
 */
#[AllowMockObjectsWithoutExpectations]
final class ModuledPageServiceTest extends TestCase {

	public function testGetNameReturnsTechnicalName(): void {
		$this->assertSame('moduledpageservice', ModuledPageService::getName());
	}

	public function testGetHelpReturnsString(): void {
		$service = new ModuledPageService(
			$this->createStub(IClassMap::class),
			$this->createStub(IRequest::class)
		);

		$this->assertSame('Help of ModuledPageService', $service->getHelp());
	}

	public function testGetOutputReturnsEmptyStringWhenOutIsNotJson(): void {
		$service = new ModuledPageService(
			$this->createStub(IClassMap::class),
			$this->createStub(IRequest::class)
		);

		$this->assertSame('', $service->getOutput('html'));
		$this->assertSame('', $service->getOutput('text'));
		$this->assertSame('', $service->getOutput(''));
	}

	public function testGetOutputReturnsEmptyStringWhenMethodIsUnknown(): void {
		$request = $this->createMock(IRequest::class);
		$request->method('get')->willReturnMap([
			['method', '', 'unknown'],
		]);

		$classmap = $this->createStub(IClassMap::class);

		$service = new ModuledPageService($classmap, $request);

		$this->assertSame('', $service->getOutput('json'));
	}

	public function testGetOutputSchemaReturnsEmptyStringWhenPageModuleNameIsMissing(): void {
		$request = $this->createMock(IRequest::class);
		$request->method('get')->willReturnMap([
			['method', '', 'schema'],
			['pagemodule', '', ''],
		]);

		$classmap = new ClassMapStub();
		$service = new ModuledPageService($classmap, $request);

		$this->assertSame('', $service->getOutput('json'));
	}

	public function testGetOutputSchemaReturnsEmptyStringWhenSchemaProviderNotFound(): void {
		$request = $this->createMock(IRequest::class);
		$request->method('get')->willReturnMap([
			['method', '', 'schema'],
			['pagemodule', '', 'does_not_exist'],
		]);

		$classmap = new ClassMapStub();
		$service = new ModuledPageService($classmap, $request);

		$this->assertSame('', $service->getOutput('json'));
	}

	public function testGetOutputSchemaReturnsJsonEncodedSchemaWhenProviderExists(): void {
		$schema = [
			'name' => 'demo',
			'fields' => [
				['name' => 'id', 'type' => 'int'],
				['name' => 'title', 'type' => 'str'],
			]
		];

		$request = $this->createMock(IRequest::class);
		$request->method('get')->willReturnMap([
			['method', '', 'schema'],
			['pagemodule', '', 'demoModule'],
		]);

		$schemaProvider = new ModuledPageServiceTestDouble1($schema);

		$classmap = new ClassMapStub();
		$classmap->registerInstance($schemaProvider, 'demoModule', [ISchemaProvider::class]);

		$service = new ModuledPageService($classmap, $request);

		$this->assertSame(json_encode($schema), $service->getOutput('json'));
	}
}

require_once __DIR__ . '/ModuledPageServiceTestDouble1.php';
