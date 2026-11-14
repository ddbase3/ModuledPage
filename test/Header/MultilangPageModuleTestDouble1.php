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

namespace Base3\ModuledPage\Test\Header;

use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\Test\Configuration\ConfigurationStub;
use PHPUnit\Framework\TestCase;

final class MultilangPageModuleTestDouble1 implements ILanguage  {
			public function getLanguage(): string { return 'de'; }
			public function setLanguage(string $language) {}
			public function getLanguages(): array { return ['de']; }
		}
