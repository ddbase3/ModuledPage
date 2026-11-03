<?php declare(strict_types=1);

namespace ModuledPage\Header;

use Base3\Configuration\Api\IConfiguration;
use Base3\Language\Api\ILanguage;
use Base3\Test\Configuration\ConfigurationStub;
use PHPUnit\Framework\TestCase;

final class MultilangPageModuleTestDouble1 implements ILanguage  {
			public function getLanguage(): string { return 'de'; }
			public function setLanguage(string $language) {}
			public function getLanguages(): array { return ['de']; }
		}
