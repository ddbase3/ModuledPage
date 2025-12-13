<?php declare(strict_types=1);

namespace ModuledPage\Header;

use ModuledPage\Page\AbstractModuleHeader;

class TitlePageModule extends AbstractModuleHeader {

	protected array $meta = [];

	public static function getName(): string {
		return "titlepagemodule";
	}

	public function getHtml() {
		$elems = array();
		$elems[] = '<meta charset="utf-8">';
		$elems[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
		$elems[] = '<title>' . $this->data["title"] . '</title>';
		return implode("\n", $elems);
	}

	public function addMeta($name, $content) {
		$this->meta[$name] = $content;
	}

	public function getPriority() {
		return 0;
	}
}
