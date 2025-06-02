<?php declare(strict_types=1);

namespace ModuledPage\Content;

use Base3\Api\IMvcView;
use Base3\Api\ISchemaProvider;
use ModuledPage\Page\AbstractModuleContent;

class ParallaxModule extends AbstractModuleContent implements ISchemaProvider {

	private $view;

	public function __construct(IMvcView $view) {
		$this->view = $view;
	}

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
