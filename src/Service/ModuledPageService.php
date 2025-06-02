<?php declare(strict_types=1);

namespace ModuledPage\Service;

use Base3\Api\IClassMap;
use Base3\Api\IOutput;
use Base3\Api\IRequest;
use Base3\Api\ISchemaProvider;

class ModuledPageService implements IOutput {

	public function __construct(
		private readonly IClassMap $classmap,
		private readonly IRequest $request
	) {}

	// Implementation of IBase

	public static function getName(): string {
		return 'moduledpageservice';
	}

	// Implementation of IOutput

	public function getOutput($out = 'html'): string {
		if ($out != 'json') return '';

		$method = $this->request->get('method', '');
		switch ($method) {
			case 'schema':
				return $this->getSchema();
		}

		return '';
	}

	public function getHelp(): string {
		return 'Help of ModuledPageService';
	}

	// Private methods
	
	private function getSchema(): string {
		$pagemoduleName = $this->request->get('pagemodule', '');
		$pagemodule = $this->classmap->getInstanceByInterfaceName(ISchemaProvider::class, $pagemoduleName);
		if ($pagemodule == null) return '';
		$schema = $pagemodule->getSchema();
		return json_encode($schema);
	}
}
