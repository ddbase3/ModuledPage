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

namespace Base3\ModuledPage\Test\Page;

use Base3\ModuledPage\Page\AbstractModuleHeader;

class PageTestHeader extends AbstractModuleHeader {

	public function __construct(
		private readonly string $html = '',
		private readonly int $priority = 0,
		private readonly array $dependencies = []
	) {}

	public function getHtml(): string {
		return $this->data['html'] ?? $this->html;
	}

	public function getPriority(): int {
		return $this->priority;
	}

	public function getRequiredModules(): array {
		return $this->dependencies;
	}
}
