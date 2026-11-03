<?php declare(strict_types=1);

namespace ModuledPage\Route;

use Base3\Api\IClassMap;
use Base3\Page\Api\IPageCatchall;
use Base3\Test\Core\ClassMapStub;
use PHPUnit\Framework\TestCase;

final class ModuledPageRouteTestDouble1 implements IPageCatchall  {
			public static function getName(): string { return 'catchall_stub'; }
			public function getUrl() { return null; }
			public function getHelp(): string { return ''; }
			public function getOutput(string $out = 'html', bool $final = false): string { return 'OUT:' . $out; }
		}
