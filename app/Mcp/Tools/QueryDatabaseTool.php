<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Title('Query Database')]
#[Description('Run a read-only SQL SELECT query against the database and return results as JSON. Only SELECT statements are allowed.')]
#[IsReadOnly]
class QueryDatabaseTool extends Tool
{
    public function handle(Request $request): Response
    {
        $sql = trim($request->string('sql'));

        // Only allow SELECT
        if (!preg_match('/^\s*SELECT\s/i', $sql)) {
            return Response::error('Only SELECT queries are allowed.');
        }

        // Block dangerous keywords
        $blocked = ['DROP', 'DELETE', 'UPDATE', 'INSERT', 'TRUNCATE', 'ALTER', 'CREATE', 'EXEC'];
        foreach ($blocked as $kw) {
            if (stripos($sql, $kw) !== false) {
                return Response::error("Keyword '{$kw}' is not allowed.");
            }
        }

        try {
            $results = DB::select($sql);
            $count   = count($results);
            $json    = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return Response::text("Rows returned: {$count}\n\n{$json}");
        } catch (\Throwable $e) {
            return Response::error('Query error: ' . $e->getMessage());
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'sql' => $schema->string()
                ->description('A valid SQL SELECT statement to execute.')
                ->required(),
        ];
    }
}
