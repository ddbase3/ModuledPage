<?php declare(strict_types=1);

namespace ModuledPage\Header;

use ModuledPage\Page\AbstractModuleHeader;

class BackgroundPageModule extends AbstractModuleHeader {

	public static function getName(): string {
		return "backgroundpagemodule";
	}

	public function getHtml() {
		switch (true) {

			case isset($this->data["image"]):
				return '<style>body { background:url(' . $this->data["image"] . ') center no-repeat; background-size:cover; }</style>';

			case isset($this->data["gradient"]):
				return '<style>body { background:linear-gradient(' . $this->data["gradient"][0] . ', ' . $this->data["gradient"][1] . '); }</style>';

		}
		return '';
	}

	public function getPriority() {
		return 90;
	}
}
