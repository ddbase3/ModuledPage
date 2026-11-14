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

namespace Base3\ModuledPage\Test\Route;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Page\Api\IPageCatchall;
use Base3\Test\Core\ClassMapStub;
use PHPUnit\Framework\TestCase;

final class ModuledPageRouteTestDouble1 implements IPageCatchall  {
			public static function getName(): string { return 'catchall_stub'; }
			public function getUrl() { return null; }
			public function getHelp(): string { return ''; }
			public function getOutput(string $out = 'html', bool $final = false): string { return 'OUT:' . $out; }
		}
