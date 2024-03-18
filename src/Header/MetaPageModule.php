<?php

namespace ModuledPage\Header;

use Page\Moduled\AbstractModuleHeader;

class MetaPageModule extends AbstractModuleHeader {

	public function getName() {
		return "metapagemodule";
	}

	public function getHtml() {
		$elems = array();
		foreach ($this->data as $name => $content) $elems[] = '<meta name="' . $name . '" content="' . $content . '" />';
		return implode("\n", $elems);
	}

	public function getPriority() {
		return 1;
	}

}
