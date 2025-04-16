<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use ModuledPage\Page\AbstractModuleContent;

class ModernTeaserModule extends AbstractModuleContent {

	private $view;

	public function __construct(IMvcView $view) {
		$this->view = $view;
	}

	public function requiresModule() {
		return array();
	}

	public function getName() {
		return "modernteasermodule";
	}

	public function getHtml() {

		// http://labs.zeroseven.de/architektur/html-kann-ganz-schoen-schraeg-sein/

		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/ModernTeaserModule.php');
		foreach ($this->data as $tag => $content) $this->view->assign($tag, $content);
		return $this->view->loadTemplate();
	}

}
