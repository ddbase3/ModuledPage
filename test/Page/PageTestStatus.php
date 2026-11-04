<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

final class PageTestStatus {

	public array $calls = [];

	public function __construct(private readonly mixed $privacy) {}

	public function get(string $name): mixed {
		$this->calls[] = $name;
		return $this->privacy;
	}
}
