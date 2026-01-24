<?php declare(strict_types=1);

namespace Test\ModuledPage;

use Base3\Api\IContainer;
use Base3\Api\IMvcView;
use Base3\Language\Api\ILanguage;
use Base3\Test\Language\LanguageStub;
use ModuledPage\ModuledPagePlugin;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ModuledPage\ModuledPagePlugin
 */
#[AllowMockObjectsWithoutExpectations]
final class ModuledPagePluginTest extends TestCase {

	public function testGetNameReturnsTechnicalName(): void {
		$this->assertSame('moduledpageplugin', ModuledPagePlugin::getName());
	}

	public function testInitRegistersPluginViewAliasAndChecks(): void {
		$container = $this->createMock(IContainer::class);

		$calls = [];

		$container->method('set')
			->willReturnCallback(function (string $name, $classDefinition, $flags = 0) use (&$calls, $container) {
				$calls[] = [
					'name' => $name,
					'def' => $classDefinition,
					'flags' => $flags
				];
				return $container;
			});

		$plugin = new ModuledPagePlugin($container);
		$plugin->init();

		$this->assertGreaterThanOrEqual(4, count($calls));

		// Plugin registration
		$this->assertSame(ModuledPagePlugin::getName(), $calls[0]['name']);
		$this->assertSame($plugin, $calls[0]['def']);
		$this->assertSame(IContainer::SHARED, $calls[0]['flags']);

		// IMvcView factory first (closure expects container argument)
		$this->assertSame(IMvcView::class, $calls[1]['name']);
		$this->assertIsCallable($calls[1]['def']);
		$this->assertSame(0, (int)$calls[1]['flags']);

		// Then alias 'view' -> IMvcView
		$this->assertSame('view', $calls[2]['name']);
		$this->assertSame(IMvcView::class, $calls[2]['def']);
		$this->assertSame(IContainer::ALIAS, $calls[2]['flags']);

		// Checks
		$this->assertSame('moduledpagechecks', $calls[3]['name']);
		$this->assertIsArray($calls[3]['def']);
		$this->assertSame(0, (int)$calls[3]['flags']);

		$checkFactories = $calls[3]['def'];
		$this->assertCount(1, $checkFactories);
		$this->assertIsCallable($checkFactories[0]);
	}

	public function testInitViewFactoryReturnsInstanceImplementingIMvcView(): void {
		$container = $this->createMock(IContainer::class);

		$viewFactory = null;

		$container->method('set')
			->willReturnCallback(function (string $name, $classDefinition, $flags = 0) use (&$viewFactory, $container) {
				if ($name === IMvcView::class) {
					$viewFactory = $classDefinition;
				}
				return $container;
			});

		$container->method('get')
			->willReturnCallback(function (string $name) {
				if ($name === ILanguage::class) {
					return new LanguageStub('en');
				}
				return null;
			});

		$plugin = new ModuledPagePlugin($container);
		$plugin->init();

		$this->assertIsCallable($viewFactory);

		$view = $viewFactory($container);
		$this->assertInstanceOf(IMvcView::class, $view);
	}
}
