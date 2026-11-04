<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use ModuledPage\Page\AbstractModuleHeader;

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
