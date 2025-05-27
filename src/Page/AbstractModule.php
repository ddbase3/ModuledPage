<?php declare(strict_types=1);

namespace ModuledPage\Page;

use Base3\Page\Api\IPageModuleDependent;

abstract class AbstractModule implements IPageModuleDependent {

	protected $data = array();

	// Implementation of IPageModule

	public function setData($data) {
		$this->data = $data;
	}

	// Implementation of IPageModuleDependent

	public function getRequiredModules() {
		return array();
	}

	// Implementation of IBase

	public static function getName(): string {
		$fullClass = static::class;
		$parts = explode('\\', $fullClass);
		return strtolower(end($parts));
	}
}
