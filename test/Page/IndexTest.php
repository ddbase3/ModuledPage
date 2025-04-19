<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use PHPUnit\Framework\TestCase;
use ModuledPage\Page\Index;
use Base3\Api\IClassMap;
use Base3\Api\IMvcView;
use Base3\Language\Api\ILanguage;

class IndexTest extends TestCase
{
    public function testGetName(): void
    {
        // Mocken der Abhängigkeiten
        $classMapMock = $this->createMock(IClassMap::class);
        $viewMock = $this->createMock(IMvcView::class);
        $languageMock = $this->createMock(ILanguage::class);

        // Instanziierung der Index-Klasse mit den gemockten Abhängigkeiten
        $index = new Index($classMapMock, $viewMock, $languageMock);

        $this->assertSame('index', $index->getName());
    }

    public function testGetUrl(): void
    {
        // Mocken der Abhängigkeiten
        $classMapMock = $this->createMock(IClassMap::class);
        $viewMock = $this->createMock(IMvcView::class);
        $languageMock = $this->createMock(ILanguage::class);

        // Instanziierung der Index-Klasse mit den gemockten Abhängigkeiten
        $index = new Index($classMapMock, $viewMock, $languageMock);

        $this->assertSame('./', $index->getUrl());
    }
}

