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

namespace Base3\ModuledPage\Test\Content;

use Base3\CoreFoundation\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class PageContentTestDouble1 implements \Base3\CoreFoundation\Api\IMvcView  {
			public function setPath(string $path = '.') {}
			public function assign(string $key, $value) {}
			public function setTemplate(string $template = 'default') {}
			public function loadTemplate(): string { return ''; }
			public function loadBricks(string $set, string $language = '') {}
			public function getBricks(string $set): ?array { return null; }
		}
