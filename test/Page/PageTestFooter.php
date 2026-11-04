<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use ModuledPage\Page\AbstractModuleFooter;

class PageTestFooter extends AbstractModuleFooter {

	public function __construct(private readonly string $html = '', private readonly int $priority = 0) {}

	public function getHtml(): string {
		return $this->data['html'] ?? $this->html;
	}

	public function getPriority(): int {
		return $this->priority;
	}
}
