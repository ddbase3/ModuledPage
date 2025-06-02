<?php declare(strict_types=1);

namespace ModuledPage\Page;

use Base3\Page\Api\IPageCatchall;
use Base3\Page\Api\IPageModuleHeader;
use Base3\Page\Api\IPageModuleContent;
use Base3\Page\Api\IPageModuleFooter;

class GeneratedPage extends AbstractModuledPage implements IPageCatchall {

	// Implementation of IBase

	public static function getName(): string {
		return "generatedpage";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$accesscontrol = $this->servicelocator->get('accesscontrol');
		$language = $this->servicelocator->get('language');

		$userid = $accesscontrol->getUserId();

		$pagecfg = $this->getPageCfg();
		if ($pagecfg == null) return '';

		foreach ($pagecfg['pageheaders'] as $pageheader) {
			$pagemoduleheader = $this->classmap->getInstanceByInterfaceName(IPageModuleHeader::class, $pageheader["name"]);
			if (isset($pageheader["active"]) && !$pageheader["active"]) continue;
			if (isset($pageheader["user"]) && is_array($pageheader["user"])) {
				if (!in_array($userid, $pageheader["user"])) continue;
			}
			if (isset($pageheader["data"])) $pagemoduleheader->setData($pageheader["data"]);
			$this->addHeader($pagemoduleheader);
		}

		foreach ($pagecfg['pagecontents'] as $pagecontent) {
			$pagemodulecontent = $this->classmap->getInstanceByInterfaceName(IPageModuleContent::class, $pagecontent["name"]);
			if (isset($pagecontent["active"]) && !$pagecontent["active"]) continue;
			if (isset($pagecontent["user"]) && is_array($pagecontent["user"])) {
				if (!in_array($userid, $pagecontent["user"])) continue;
			}
			if (isset($pagecontent["language"]) && is_array($pagecontent["language"])) {
				$l = $language->getLanguage();
				if (!in_array($l, $pagecontent["language"])) continue;
			}
			if (isset($pagecontent["data"])) $pagemodulecontent->setData($pagecontent["data"]);
			$this->addContent($pagemodulecontent);
		}

		foreach ($pagecfg['pagefooters'] as $pagefooter) {
			$pagemodulefooter = $this->classmap->getInstanceByInterfaceName(IPageModuleFooter::class, $pagefooter["name"]);
			if (isset($pagefooter["active"]) && !$pagefooter["active"]) continue;
			if (isset($pagefooter["user"]) && is_array($pagefooter["user"])) {
				if (!in_array($userid, $pagefooter["user"])) continue;
			}
			if (isset($pagefooter["data"])) $pagemodulefooter->setData($pagefooter["data"]);
			$this->addFooter($pagemodulefooter);
		}

		return parent::getOutput($out);
	}

	// Private methods

	private function getPageCfg() {
		if (!isset($_REQUEST["name"])) return null;
		$files = array(rtrim(DIR_LOCAL, DIRECTORY_SEPARATOR) . "/Page/page-" . $_REQUEST["name"] . ".json");
		foreach ($this->classmap->getPlugins() as $plugin)
			$files[] = rtrim(DIR_PLUGIN, DIRECTORY_SEPARATOR) . "/" . $plugin . "/local/Page/page-" . $_REQUEST["name"] . ".json";
		$pagecfgfile = "";
		foreach ($files as $file) {
			if (!file_exists($file)) continue;
			$pagecfgfile = $file;
			break;
		}
		if (empty($pagecfgfile)) {
			header("HTTP/1.0 404 Not Found");
			die("404 Not Found\n");
		}
		$baseContent = ['pageheaders' => [], 'pagecontents' => [], 'pagefooters' => []];
		$content = file_get_contents($pagecfgfile);
		$parsedContent = json_decode($content, true);
		return array_merge($baseContent, $parsedContent);
	}
}
