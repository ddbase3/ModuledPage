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

final class ModuledPageServiceTestDouble1 implements ISchemaProvider {
			public function __construct(private array $schema) {}
			public function getSchema(): array { return $this->schema; }
		}
