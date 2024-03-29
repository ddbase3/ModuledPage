<?php

namespace ModuledPage\Page;

use Page\Api\IPagePostDataProcessor;

abstract class AbstractFormModuleContent extends AbstractModuleContent implements IPagePostDataProcessor {

	public function getUrl() {
		return $this->getName() . ".php";
	}

	public function getOutput($out = "html") {
		$this->processPostData();
		header('Location: ' . $this->getForwardUrl());
		exit;
	}

	public function getHelp() {
		$ps = explode("\\", get_class($this));
		return 'Help of ' . array_pop($ps) . "\n";
	}

	// overwrite if necessary

	public function processPostData() {}

	public function getForwardUrl() {
		return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "/";
	}

}
