<?php declare(strict_types=1);

namespace ModuledPage\Test\Page;

use ModuledPage\Page\AbstractFormModuleContent;

final class PageTestForm extends AbstractFormModuleContent {

	public function getHtml(): string {
		return '<form></form>';
	}
}
