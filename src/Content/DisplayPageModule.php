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

	public static function getName(): string {
		return 'displaypagemodule';
	}

	public function getHtml() {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/DisplayPageModule.php');

		$defaults = ['display' => '', 'data' => []];
		$settings = array_merge($defaults, $this->data);

		$content = '';

		$instances = $this->classmap->getInstances([
			'interface' => IDisplay::class,
			'name' => (string)$settings['display'],
		]);

		$display = $instances[0] ?? null;

		if ($display instanceof IDisplay) {
			$display->setData($settings['data']);
			$content = $display->getOutput();
		}

		$this->view->assign('content', $content);

		return $this->view->loadTemplate();
	}
}
