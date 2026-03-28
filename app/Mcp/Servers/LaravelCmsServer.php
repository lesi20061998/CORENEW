<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\GetModelInfoTool;
use App\Mcp\Tools\ListRoutesTool;
use App\Mcp\Tools\QueryDatabaseTool;
use App\Mcp\Tools\RunArtisanTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Laravel CMS Server')]
#[Version('1.0.0')]
#[Instructions('MCP server for the Laravel CMS project. Provides tools to inspect the database schema, run read-only SQL queries, list routes, and execute safe Artisan commands. Use these tools to understand the application structure before making code changes.')]
class LaravelCmsServer extends Server
{
    protected array $tools = [
        GetModelInfoTool::class,   // Inspect tables & columns
        QueryDatabaseTool::class,  // Run SELECT queries
        ListRoutesTool::class,     // List registered routes
        RunArtisanTool::class,     // Run safe Artisan commands
    ];

    protected array $resources = [];

    protected array $prompts = [];
}
