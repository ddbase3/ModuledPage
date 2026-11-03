<?php declare(strict_types=1);

namespace Test\ModuledPage\Service;

use Base3\Api\IClassMap;
use Base3\Api\IRequest;
use Base3\Api\ISchemaProvider;
use Base3\Test\Core\ClassMapStub;
use ModuledPage\Service\ModuledPageService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

final class ModuledPageServiceTestDouble1 implements ISchemaProvider {
			public function __construct(private array $schema) {}
			public function getSchema(): array { return $this->schema; }
		}
