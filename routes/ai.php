<?php

use App\Mcp\Servers\LaravelCmsServer;
use Laravel\Mcp\Facades\Mcp;

/*
|--------------------------------------------------------------------------
| MCP Routes
|--------------------------------------------------------------------------
| Web server: accessible via HTTP POST — dùng cho Kiro, Claude Desktop, etc.
| Local server: chạy qua Artisan stdio — dùng cho local AI tools.
*/

// HTTP endpoint (dùng cho Kiro MCP config)
Mcp::web('/mcp', LaravelCmsServer::class);

// Local stdio (dùng cho Claude Desktop / local tools)
Mcp::local('cms', LaravelCmsServer::class);
