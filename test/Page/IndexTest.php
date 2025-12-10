<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use PHPUnit\Framework\TestCase;
use ModuledPage\Page\Index;
use ReflectionClass;

class IndexTest extends TestCase {

	public function testGetName(): void {
		$this->assertSame('index', Index::getName());
	}

	public function testGetUrl(): void {
		// Create instance without calling parent constructor (requires dependencies)
		$ref = new ReflectionClass(Index::class);
		$index = $ref->newInstanceWithoutConstructor();

		$this->assertSame('./', $index->getUrl());
	}

}
