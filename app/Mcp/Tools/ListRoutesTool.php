<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Route;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Title('List Routes')]
#[Description('List all registered Laravel routes, optionally filtered by URI prefix or method.')]
#[IsReadOnly]
class ListRoutesTool extends Tool
{
    public function handle(Request $request): Response
    {
        $filter = strtolower($request->string('filter', ''));
        $method = strtoupper($request->string('method', ''));

        $routes = collect(Route::getRoutes())->map(fn($r) => [
            'method'  => implode('|', $r->methods()),
            'uri'     => $r->uri(),
            'name'    => $r->getName() ?? '',
            'action'  => $r->getActionName(),
        ]);

        if ($filter) {
            $routes = $routes->filter(fn($r) => str_contains($r['uri'], $filter) || str_contains($r['name'], $filter));
        }
        if ($method) {
            $routes = $routes->filter(fn($r) => str_contains($r['method'], $method));
        }

        $routes = $routes->values();
        $output = json_encode($routes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return Response::text("Total: {$routes->count()} routes\n\n{$output}");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'filter' => $schema->string()
                ->description('Optional URI or name substring to filter routes.')
                ->default(''),
            'method' => $schema->string()
                ->description('Optional HTTP method to filter (GET, POST, PUT, DELETE...).')
                ->default(''),
        ];
    }
}
