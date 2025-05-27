<?php declare(strict_types=1);

namespace ModuledPage\Page;

class Index extends GeneratedPage {

	public static function getName(): string {
		return "index";
	}

        public function getUrl() {
                return "./";
        }

}
