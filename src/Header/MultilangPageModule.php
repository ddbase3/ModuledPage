<?php

namespace ModuledPage\Header;

use ModuledPage\Page\AbstractModuleHeader;

class MultilangPageModule extends AbstractModuleHeader {

	public function getName() {
		return "multilangpagemodule";
	}

	public function getHtml() {

		$servicelocator = \Base3\ServiceLocator::getInstance();
		$cnf = $servicelocator->get('configuration')->get('base');
		$language = $servicelocator->get('language');

		$out = isset($_REQUEST['out']) && strlen($_REQUEST['out']) ? $_REQUEST['out'] : 'html';
		$name = isset($_REQUEST['name']) && strlen($_REQUEST['name']) ? $_REQUEST['name'] : 'index';

		$elems = array();
		$elems[] = '<base href="' . $cnf["url"] . '" />';
		$elems[] = '<link rel="canonical" href="' . rtrim($cnf["url"], "/") . '/' . $language->getLanguage() . '/' . $name . '.' . $out . '" />';
		return implode("\n", $elems);
	}

	public function getPriority() {
		return 5;
	}

}
