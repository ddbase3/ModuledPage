<?php declare(strict_types=1);

namespace ModuledPage\Route;

use Base3\Api\IClassMap;
use Base3\Page\Api\IPageCatchall;
use Base3\Test\Core\ClassMapStub;
use PHPUnit\Framework\TestCase;

final class ModuledPageRouteTest extends TestCase {

	protected function setUp(): void {
		$_GET = [];
		$_REQUEST = [];
	}

	protected function tearDown(): void {
		unset($_REQUEST['out'], $_REQUEST['name'], $_GET['data'], $_REQUEST['data']);
	}

	public function testMatchReturnsIndexForEmptyPathAndIndexPhp(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		self::assertSame(['name' => 'index'], $route->match(''));
		self::assertSame(['name' => 'index'], $route->match('/'));
		self::assertSame(['name' => 'index'], $route->match('index.php'));
		self::assertSame(['name' => 'index'], $route->match('/index.php'));
	}

	public function testMatchStripsQueryString(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		self::assertSame(['name' => 'index'], $route->match('index.php?x=1'));
		self::assertSame(['name' => 'foo'], $route->match('foo.html?bar=baz'));
	}

	public function testMatchLanguagePrefixAndName(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		self::assertSame(['data' => 'de', 'name' => 'home'], $route->match('de/home.html'));
		self::assertSame(['data' => 'EN', 'name' => 'home'], $route->match('EN/home.php'));
		self::assertSame(['data' => 'fr', 'name' => 'x'], $route->match('/fr/x.json'));
		self::assertSame(['data' => 'it', 'name' => 'helpme'], $route->match('it/helpme.help'));
		self::assertSame(['data' => 'de', 'name' => 'foo-bar'], $route->match('de/foo-bar.xml'));
	}

	public function testMatchNameOnly(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		self::assertSame(['name' => 'home'], $route->match('home.html'));
		self::assertSame(['name' => 'home'], $route->match('/home.php'));
		self::assertSame(['name' => 'home'], $route->match('home.json'));
		self::assertSame(['name' => 'home'], $route->match('home.xml'));
		self::assertSame(['name' => 'help'], $route->match('help.help'));
		self::assertSame(['name' => 'foo-bar'], $route->match('foo-bar.html'));
	}

	public function testMatchReturnsNullForUnsupportedPaths(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		self::assertNull($route->match('de/home')); // missing extension
		self::assertNull($route->match('de/home.txt')); // unsupported extension
		self::assertNull($route->match('de/sub/home.html')); // extra slash
		self::assertNull($route->match('a/home.html')); // not 2-letter language

		self::assertSame(['name' => 'index'], $route->match(''));
	}

	public function testDispatchSetsGlobalsAndReturns404WhenNoCatchall(): void {
		$route = new ModuledPageRoute($this->createDummyClassMap([]));

		$out = $route->dispatch(['name' => 'index']);

		self::assertSame("404 Not Found\n", $out);

		self::assertSame('index', $_GET['name'] ?? null);
		self::assertSame('index', $_REQUEST['name'] ?? null);
	}

	public function testDispatchSetsGlobalsIncludingDataAndReturnsCatchallOutput(): void {
		$catchall = new class implements IPageCatchall {
			public static function getName(): string { return 'catchall_stub'; }
			public function getUrl() { return null; }
			public function getHelp() { return ''; }
			public function getOutput($out = 'html') { return 'OUT:' . $out; }
		};

		$route = new ModuledPageRoute($this->createDummyClassMap([$catchall]));

		$out = $route->dispatch(['name' => 'home', 'data' => 'de']);

		self::assertSame('OUT:html', $out);

		self::assertSame('home', $_GET['name'] ?? null);
		self::assertSame('home', $_REQUEST['name'] ?? null);

		self::assertSame('de', $_GET['data'] ?? null);
		self::assertSame('de', $_REQUEST['data'] ?? null);
	}

	/**
	 * @param array<int, object> $instances
	 */
	private function createDummyClassMap(array $instances): IClassMap {
		$cm = new ClassMapStub();

		foreach ($instances as $inst) {
			$cm->registerInstance($inst, null, [IPageCatchall::class]);
		}

		return $cm;
	}
}
