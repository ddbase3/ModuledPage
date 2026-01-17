<?php declare(strict_types=1);

namespace Test\ModuledPage\Service;

use Base3\Api\IClassMap;
use Base3\Api\IRequest;
use Base3\Api\ISchemaProvider;
use ModuledPage\Service\ModuledPageService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ModuledPage\Service\ModuledPageService
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

		// IClassMap Interface hat getInstanceByInterfaceName() nicht.
		// Der Service ruft sie aber auf, daher implementieren wir sie "zusätzlich" im Stub.
		$classmap = new class implements IClassMap {
			public static function getName(): string { return 'classmap_stub'; }

			public function instantiate(string $class) {
				return null;
			}

			public function &getInstances(array $criteria = []) {
				$empty = [];
				return $empty;
			}

			public function getPlugins() {
				return [];
			}

			public function getInstanceByInterfaceName(string $iface, string $name) {
				return null;
			}
		};

		$service = new ModuledPageService($classmap, $request);

		$this->assertSame('', $service->getOutput('json'));
	}

	public function testGetOutputSchemaReturnsEmptyStringWhenSchemaProviderNotFound(): void {
		$request = $this->createMock(IRequest::class);
		$request->method('get')->willReturnMap([
			['method', '', 'schema'],
			['pagemodule', '', 'does_not_exist'],
		]);

		$classmap = new class implements IClassMap {
			public static function getName(): string { return 'classmap_stub'; }

			public function instantiate(string $class) {
				return null;
			}

			public function &getInstances(array $criteria = []) {
				$empty = [];
				return $empty;
			}

			public function getPlugins() {
				return [];
			}

			public function getInstanceByInterfaceName(string $iface, string $name) {
				return null;
			}
		};

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

		$schemaProvider = new class($schema) implements ISchemaProvider {
			public function __construct(private array $schema) {}
			public static function getName(): string { return 'schemaprovider_stub'; }
			public function getSchema(): array { return $this->schema; }
		};

		$classmap = new class($schemaProvider) implements IClassMap {
			public function __construct(private ISchemaProvider $provider) {}
			public static function getName(): string { return 'classmap_stub'; }

			public function instantiate(string $class) {
				return null;
			}

			public function &getInstances(array $criteria = []) {
				$empty = [];
				return $empty;
			}

			public function getPlugins() {
				return [];
			}

			public function getInstanceByInterfaceName(string $iface, string $name) {
				if ($iface === ISchemaProvider::class && $name === 'demoModule') {
					return $this->provider;
				}
				return null;
			}
		};

		$service = new ModuledPageService($classmap, $request);

		$this->assertSame(json_encode($schema), $service->getOutput('json'));
	}
}
