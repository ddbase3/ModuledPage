<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IClassMap;
use Base3\Api\IDisplay;
use Base3\Api\IMvcView;
use ModuledPage\Page\AbstractModuleContent;

class DisplayPageModule extends AbstractModuleContent {

	public function __construct(
		private readonly IMvcView $view,
		private readonly IClassMap $classmap
	) {}

	// Implementation of IBase

	public static function getName(): string {
		return 'displaypagemodule'; 
	}

	// Implementation of IPageModule

	public function getHtml() {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/DisplayPageModule.php');

                $defaults = ['display' => '', 'data' => []];
                $settings = array_merge($defaults, $this->data);

		$content = '';
		$display = $this->classmap->getInstanceByInterfaceName(IDisplay::class, $settings['display']);
		if ($display != null) {
			$display->setData($settings['data']);
			$content = $display->getOutput();
		}
		$this->view->assign('content', $content);

		return $this->view->loadTemplate();
	}
}
