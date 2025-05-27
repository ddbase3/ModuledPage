<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use ModuledPage\Page\AbstractModuleContent;

class PanoramaModule extends AbstractModuleContent {

	private $view;

	public function __construct(IMvcView $view) {
		$this->view = $view;
	}

	public static function getName(): string {
		return "panoramamodule";
	}

	public function getHtml() {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/PanoramaModule.php');
		$defaults = array("image" => "", "height" => "30vh");
		foreach ($defaults as $tag => $default) $this->view->assign($tag, isset($this->data[$tag]) ? $this->data[$tag] : $default);
		foreach ($this->data as $tag => $content) $this->view->assign($tag, $content);
		return $this->view->loadTemplate();
	}

}
