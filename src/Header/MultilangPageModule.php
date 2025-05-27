<?php declare(strict_types=1);

namespace ModuledPage\Header;

use Base3\Configuration\Api\IConfiguration;
use Base3\Language\Api\ILanguage;
use ModuledPage\Page\AbstractModuleHeader;

class MultilangPageModule extends AbstractModuleHeader {

	private $configuration;
	private $language;

	public function __construct(
		IConfiguration $configuration,
		ILanguage $language
	) {
		$this->configuration = $configuration;
		$this->language = $language;
	}

	public static function getName(): string {
		return "multilangpagemodule";
	}

	public function getHtml() {
		$cnf = $this->configuration->get('base');

		$out = isset($_REQUEST['out']) && strlen($_REQUEST['out']) ? $_REQUEST['out'] : 'html';
		$name = isset($_REQUEST['name']) && strlen($_REQUEST['name']) ? $_REQUEST['name'] : 'index';

		$elems = array();
		$elems[] = '<base href="' . $cnf["url"] . '" />';
		$elems[] = '<link rel="canonical" href="' . rtrim($cnf["url"], "/") . '/' . $this->language->getLanguage() . '/' . $name . '.' . $out . '" />';
		return implode("\n", $elems);
	}

	public function getPriority() {
		return 5;
	}
}
