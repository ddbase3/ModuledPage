<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use ModuledPage\Page\AbstractModuleContent;

class PageTestContent extends AbstractModuleContent {

	public function __construct(private readonly string $html = '', private readonly array $dependencies = []) {}

	public function getHtml(): string {
		return $this->data['html'] ?? $this->html;
	}

	public function getRequiredModules(): array {
		return $this->dependencies;
	}
}
