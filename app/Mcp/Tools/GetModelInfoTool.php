<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Schema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Title('Get Model / Table Info')]
#[Description('Get column definitions and row count for a database table, or list all tables.')]
#[IsReadOnly]
class GetModelInfoTool extends Tool
{
    public function handle(Request $request): Response
    {
        $table = trim($request->string('table', ''));

        if (!$table) {
            // List all tables
            $tables = Schema::getTables();
            $names  = array_column($tables, 'name');
            sort($names);
            return Response::text("Tables (" . count($names) . "):\n" . implode("\n", $names));
        }

        if (!Schema::hasTable($table)) {
            return Response::error("Table '{$table}' does not exist.");
        }

        $columns = Schema::getColumns($table);
        $count   = \Illuminate\Support\Facades\DB::table($table)->count();

        $colInfo = array_map(fn($c) => [
            'name'     => $c['name'],
            'type'     => $c['type'],
            'nullable' => $c['nullable'],
            'default'  => $c['default'],
        ], $columns);

        $output = json_encode([
            'table'   => $table,
            'rows'    => $count,
            'columns' => $colInfo,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return Response::text($output);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'table' => $schema->string()
                ->description('Table name to inspect. Leave empty to list all tables.')
                ->default(''),
        ];
    }
}
