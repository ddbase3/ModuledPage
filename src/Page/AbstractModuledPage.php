<?php

namespace ModuledPage\Page;

use Page\Api\IPage;

abstract class AbstractModuledPage implements IPage {

	protected $servicelocator;
	protected $classmap;
	private $view;
	private $statushandler;
	private $language;

	private $title = '';
	private $pageheaders = array();
	private $pagecontents = array();

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');
		$this->view = $this->servicelocator->get('view');
		$this->statushandler = $this->servicelocator->get('statushandler');
		$this->language = $this->servicelocator->get('language');
	}

	// Implementation of IPage

	public function getUrl() {
		return "";
	}

	// protected methods

	protected function setTitle($title) {
		$this->title = $title;
	}

	protected function addHeader($pageheader) {
		foreach ($this->pageheaders as $h) if ($pageheader->getName() == $h->getName()) return;  // no duplicates
		$this->checkDependencies($pageheader);
		$this->pageheaders[] = $pageheader;
	}

	protected function addContent($pagecontent) {
		$this->checkDependencies($pagecontent);
		$this->pagecontents[] = $pagecontent;
	}

	public function getOutput($out = "html") {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Page/Page.php');

		$privacy = $this->statushandler->get('privacy');
		if (is_null($privacy)) $privacy = 50;
		$this->view->assign("privacy", intval($privacy));

		$this->view->assign('title', $this->title);
		$this->view->assign('headhtml', $this->getHeadHtml());
		$this->view->assign('bodyhtml', $this->getBodyHtml());
		$this->view->assign('language', $this->language->getLanguage());

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of ' . $this->getName() . "\n";
	}

	// private methods

	private function getHeadHtml() {
		$headhtml = "\n";
		usort($this->pageheaders, function($a, $b) {
			if ($a->getPriority() == $b->getPriority()) return 0;
			return $a->getPriority() < $b->getPriority() ? -1 : 1;
		});
		foreach ($this->pageheaders as $pageheader) {
			$html = $pageheader->getHtml();
			if (!strlen($html)) continue;
			$lines = explode("\n", $html);
			foreach ($lines as $line) $headhtml .= "\t\t" . $line . "\n";
			$headhtml .= "\n";
		}
		return $headhtml;
	}

	private function getBodyHtml() {
		$bodyhtml = "\n";
		foreach ($this->pagecontents as $pagecontent) $bodyhtml .= $pagecontent->getHtml() . "\n";
		$bodyhtml .= "\n";
		return $bodyhtml;
	}

	private function checkDependencies($o) {
		if ($o instanceof \Page\Api\IPageModuleDependent) {
			$reqMods = $o->getRequiredModules();
			foreach ($reqMods as $reqMod) {
				foreach ($this->pageheaders as $h) if ($reqMod == $h->getName()) continue;
				// $this->addHeader($this->servicelocator->get($reqMod));
				$instance = $this->classmap->getInstanceByInterfaceName("Page\\Api\\IPageModuleHeader", $reqMod);
				$this->addHeader($instance);
			}
		}
	}

}
