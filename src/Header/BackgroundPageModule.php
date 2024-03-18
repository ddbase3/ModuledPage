<?php

namespace ModuledPage\Header;

use Page\Moduled\AbstractModuleHeader;

class BackgroundPageModule extends AbstractModuleHeader {

	public function getName() {
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
