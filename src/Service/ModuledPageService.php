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

namespace Base3\ModuledPage\Service;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IOutput;
use Base3\CoreFoundation\Api\IRequest;
use Base3\CoreFoundation\Api\ISchemaProvider;

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

	public function getOutput(string $out = 'html', bool $final = false): string {
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
