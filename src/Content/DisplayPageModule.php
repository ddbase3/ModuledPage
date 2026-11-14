<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of ModuledPage for BASE3 Framework.
 *
 * ModuledPage composes BASE3 web pages from reusable page modules.
 * It combines module content using framework display and view contracts.
 * Page composition remains independent of individual host applications.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 **********************************************************************/

namespace Base3\ModuledPage\Content;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IDisplay;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\ModuledPage\Page\AbstractModuleContent;

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

		$defaults = ['display' => '', 'data' => [], 'width' => 'frame'];
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

		$this->view->assign('width', $settings['width'] === 'frame' ? 'frame' : 'full');

		return $this->view->loadTemplate();
	}
}
