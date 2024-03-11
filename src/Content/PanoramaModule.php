<?php

namespace ModuledPage\Content;

use Page\Moduled\AbstractModuleContent;

class PanoramaModule extends AbstractModuleContent {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	public function getName() {
		return "panoramamodule";
	}

	public function getHtml() {
		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'ModuledPage');
		$view->setTemplate('Content/PanoramaModule.php');
		$defaults = array("image" => "", "height" => "30vh");
		foreach ($defaults as $tag => $default) $view->assign($tag, isset($this->data[$tag]) ? $this->data[$tag] : $default);
		foreach ($this->data as $tag => $content) $view->assign($tag, $content);
		return $view->loadTemplate();
	}

}
