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

use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\ISchemaProvider;
use Base3\ModuledPage\Page\AbstractModuleContent;

class ParallaxModule extends AbstractModuleContent implements ISchemaProvider {

	public function __construct(private readonly IMvcView $view) {}

	// Implementation of IBase

	public static function getName(): string {
		return "parallaxmodule";
	}

	// Implementation of IPageModule

	public function getHtml() {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Content/ParallaxModule.php');
		$defaults = array("image" => "", "content" => "", "height" => "30vh");
		foreach ($defaults as $tag => $default) $this->view->assign($tag, isset($this->data[$tag]) ? $this->data[$tag] : $default);
		foreach ($this->data as $tag => $content) $this->view->assign($tag, $content);
		return $this->view->loadTemplate();
	}

	// Implementation of ISchemaProvider

	public function getSchema(): array {
		$schema = [
			'$schema' => 'https://json-schema.org/draft-2020-12/schema',
			'type' => 'object',
			'properties' => [
				'image' => [
					'type' => 'string',
					'description' => 'Image URL',
					'maxLength' => 200,
				],
				'content' => [
					'type' => 'string',
					'description' => 'Content',
					'maxLength' => 200,
				],
				'height' => [
					'type' => 'string',
					'description' => 'Height',
					'maxLength' => 20,
					'default' => '30vh',
				],
			],
			'required' => ['image'],
		];
		return $schema;
	}
}
