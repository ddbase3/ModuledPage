<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use ModuledPage\Page\AbstractModuleContent;

class IframeModule extends AbstractModuleContent {

	private $view;

	public function __construct(IMvcView $view) {
		$this->view = $view; 
	}

	public function getName() {
		return "iframemodule";
	}

	public function getHtml() {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/IframeModule.php');
		$defaults = array("url" => "", "height" => "20em", "allow" => "");
		foreach ($defaults as $tag => $default) $this->view->assign($tag, isset($this->data[$tag]) ? $this->data[$tag] : $default);
		return $this->view->loadTemplate();
	}

}
