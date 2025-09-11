<?php declare(strict_types=1);

namespace ModuledPage\Route;

use Base3\Route\Api\IRoute;
use Base3\Api\IClassMap;
use Base3\Page\Api\IPageCatchall;

final class ModuledPageRoute implements IRoute {
    public function __construct(private IClassMap $classmap) {}

    public function match(string $path): ?array {
        $path = explode('?', $path, 2)[0];
        $path = ltrim($path, '/');

        if ($path === '' || $path === 'index.php') {
            return ['name' => 'index'];
        }

        // Sprachprefix + Name
        if (preg_match('#^(?P<data>[a-z]{2})/(?P<name>[^/\.]+)\.(php|html|json|xml|help)$#i', $path, $m)) {
            return ['data' => $m['data'], 'name' => $m['name']];
        }

        // Nur Name
        if (preg_match('#^(?P<name>[^/\.]+)\.(php|html|json|xml|help)$#i', $path, $m)) {
            return ['name' => $m['name']];
        }

        return null;
    }

    public function dispatch(array $match): string {
        $name = $match['name'];
        $data = $match['data'] ?? '';

        $_GET['name'] = $name;
        $_REQUEST['name'] = $name;

        if ($data !== '') {
            $_GET['data'] = $data;
            $_REQUEST['data'] = $data;
        }

        $instances = $this->classmap->getInstancesByInterface(IPageCatchall::class);
        $instance = reset($instances);

        if (!is_object($instance)) {
            header('HTTP/1.0 404 Not Found');
            return "404 Not Found\n";
        }

        header('Content-Type: text/html; charset=utf-8');
        return (string)$instance->getOutput('html');
    }
}

