<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Artisan;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;

#[Title('Run Artisan Command')]
#[Description('Run a safe, read-only Artisan command. Allowed: route:list, migrate:status, db:show, config:show, about, tinker (read), queue:monitor.')]
class RunArtisanTool extends Tool
{
    // Whitelist — chỉ cho phép các lệnh an toàn
    protected array $allowed = [
        'route:list',
        'migrate:status',
        'db:show',
        'config:show',
        'about',
        'queue:monitor',
        'model:show',
        'schema:dump',
    ];

    public function handle(Request $request): Response
    {
        $command = trim($request->string('command'));
        $args    = $request->array('args') ?? [];

        // Validate command is in whitelist
        $base = explode(' ', $command)[0];
        if (!in_array($base, $this->allowed)) {
            return Response::error(
                "Command '{$base}' is not allowed. Allowed commands: " . implode(', ', $this->allowed)
            );
        }

        try {
            Artisan::call($command, $args);
            $output = Artisan::output();
            return Response::text($output ?: '(no output)');
        } catch (\Throwable $e) {
            return Response::error('Artisan error: ' . $e->getMessage());
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'command' => $schema->string()
                ->description('Artisan command name, e.g. "route:list" or "migrate:status".')
                ->required(),
            'args' => $schema->object()
                ->description('Optional key-value arguments for the command.')
                ->default([]),
        ];
    }
}
