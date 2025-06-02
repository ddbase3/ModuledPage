<?php declare(strict_types=1);

namespace ModuledPage\Page;

use Base3\Api\IClassMap;
use Base3\Api\IMvcView;
use Base3\Core\ServiceLocator;
use Base3\Language\Api\ILanguage;
use Base3\Page\Api\IPage;

abstract class AbstractModuledPage implements IPage {

	protected $servicelocator;
	protected $statushandler;

	private $title = '';
	private $pageheaders = array();
	private $pagecontents = array();
	private $pagefooters = array();

	public function __construct(
		protected readonly IClassMap $classmap,
		protected readonly IMvcView $view,
		protected readonly ILanguage $language
	) {
		$this->servicelocator = ServiceLocator::getInstance();
		$this->statushandler = $this->servicelocator->get('statushandler');
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

	protected function addFooter($pagefooter) {
		foreach ($this->pagefooters as $f) if ($pagefooter->getName() == $f->getName()) return;  // no duplicates
		$this->checkDependencies($pagefooter);
		$this->pagefooters[] = $pagefooter;
	}

	public function getOutput($out = "html") {
		$this->view->setPath(DIR_PLUGIN . 'ModuledPage');
		$this->view->setTemplate('Page/Page.php');

		$privacy = 50;
		if ($this->statushandler) {
			$privacy = $this->statushandler->get('privacy');
			if (is_null($privacy)) $privacy = 50;
		}
		$this->view->assign("privacy", intval($privacy));

		$this->view->assign('title', $this->title);
		$this->view->assign('headhtml', $this->getHeadHtml());
		$this->view->assign('bodyhtml', $this->getBodyHtml());
		$this->view->assign('foothtml', $this->getFootHtml());
		$this->view->assign('language', $this->language ? $this->language->getLanguage() : null);

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of ' . $this->getName() . "\n";
	}

	// private methods

	private function getHeadHtml() {
		$headhtml = "\n";
		usort($this->pageheaders, fn($a, $b) => $a->getPriority() <=> $b->getPriority());
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

	private function getFootHtml() {
		$foothtml = "\n";
		usort($this->pagefooters, fn($a, $b) => $a->getPriority() <=> $b->getPriority());
		foreach ($this->pagefooters as $pagefooter) {
			$html = $pagefooter->getHtml();
			if (!strlen($html)) continue;
			$lines = explode("\n", $html);
			foreach ($lines as $line) $foothtml .= "\t\t" . $line . "\n";
			$foothtml .= "\n";
		}
		return $foothtml;
	}

	private function checkDependencies($o) {
		if ($o instanceof \Base3\Page\Api\IPageModuleDependent) {
			$reqMods = $o->getRequiredModules();
			foreach ($reqMods as $reqMod) {
				foreach ($this->pageheaders as $h) if ($reqMod == $h->getName()) continue;
				// $this->addHeader($this->servicelocator->get($reqMod));
				$instance = $this->classmap->getInstanceByInterfaceName(\Base3\Page\Api\IPageModuleHeader::class, $reqMod);
				$this->addHeader($instance);
			}
		}
	}

}
