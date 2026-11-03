<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use PHPUnit\Framework\TestCase;

final class PageContentTestDouble1 implements \Base3\Api\IMvcView  {
			public function setPath(string $path = '.') {}
			public function assign(string $key, $value) {}
			public function setTemplate(string $template = 'default') {}
			public function loadTemplate(): string { return ''; }
			public function loadBricks(string $set, string $language = '') {}
			public function getBricks(string $set): ?array { return null; }
		}
