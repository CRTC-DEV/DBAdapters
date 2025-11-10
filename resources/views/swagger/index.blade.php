<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Adapters - API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #2c3e50;
        }
        .swagger-ui .topbar .link {
            color: #ffffff;
        }
        .custom-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 0;
        }
        .custom-header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .custom-header p {
            margin: 10px 0 0 0;
            font-size: 1.2em;
            opacity: 0.9;
        }
        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
        }
        .info-container {
            background: white;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .endpoints-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        .endpoint-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        .endpoint-card h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .endpoint-card .method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 10px;
        }
        .method.get { background: #61affe; color: white; }
        .method.post { background: #49cc90; color: white; }
        .method.put { background: #fca130; color: white; }
        .method.delete { background: #f93e3e; color: white; }
        .method.patch { background: #50e3c2; color: white; }
    </style>
</head>
<body>
    <div class="custom-header">
        <h1>DB Adapters API</h1>
        <p>Comprehensive API for Airport Operations & Baggage Management</p>
    </div>

    <div class="info-container">
        <h2>🚀 Quick Start Guide</h2>
        <p>This API provides comprehensive endpoints for managing airport operations, including:</p>
        <ul>
            <li><strong>Barcode Processing:</strong> Process barcode data for baggage recheck operations</li>
            <li><strong>TagRecheck Management:</strong> CRUD operations for baggage recheck records</li>
            <li><strong>Airlines Management:</strong> Manage airline information and codes</li>
            <li><strong>Aircraft Management:</strong> Manage aircraft and aircraft type data</li>
            <li><strong>Airport Management:</strong> Manage airport and route information</li>
        </ul>
        <p><strong>Base URL:</strong> <code>{{ url('/api') }}</code></p>
    </div>

    <div class="endpoints-summary">
        <div class="endpoint-card">
            <h3>🔍 Barcode Processing</h3>
            <div><span class="method get">GET</span> /process-barcode</div>
            <p>Process barcode data to determine recheck requirements with multi-language support.</p>
        </div>
        
        <div class="endpoint-card">
            <h3>🏷️ TagRecheck Management</h3>
            <div><span class="method get">GET</span> /tag-recheck/{date}</div>
            <div><span class="method post">POST</span> /tag-recheck</div>
            <div><span class="method put">PUT</span> /tag-recheck/{id}</div>
            <div><span class="method delete">DELETE</span> /tag-recheck/{id}</div>
            <p>Complete CRUD operations for baggage recheck records.</p>
        </div>
        
        <div class="endpoint-card">
            <h3>✈️ Airlines Management</h3>
            <div><span class="method get">GET</span> /airlines</div>
            <div><span class="method get">GET</span> /airlines/iata/{code}</div>
            <div><span class="method post">POST</span> /airlines</div>
            <div><span class="method put">PUT</span> /airlines/{id}</div>
            <p>Manage airline information, codes, and configurations.</p>
        </div>
        
        <div class="endpoint-card">
            <h3>🛩️ Aircraft Management</h3>
            <div><span class="method get">GET</span> /aircrafts</div>
            <div><span class="method get">GET</span> /aircrafts/registration/{reg}</div>
            <div><span class="method post">POST</span> /aircrafts</div>
            <div><span class="method put">PUT</span> /aircrafts/{id}</div>
            <p>Manage aircraft information and specifications.</p>
        </div>
    </div>

    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: '{{ url("/api/documentation/json") }}',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null,
                docExpansion: "list",
                operationsSorter: "alpha",
                tagsSorter: "alpha",
                filter: true,
                showRequestHeaders: true,
                showCommonExtensions: true,
                tryItOutEnabled: true,
                requestInterceptor: function(request) {
                    // Add custom headers if needed
                    request.headers['Accept'] = 'application/json';
                    return request;
                },
                responseInterceptor: function(response) {
                    // Handle responses if needed
                    return response;
                }
            });

            // Custom styling for better appearance
            setTimeout(() => {
                const style = document.createElement('style');
                style.textContent = `
                    .swagger-ui .info {
                        margin: 20px 0;
                    }
                    .swagger-ui .scheme-container {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 6px;
                    }
                `;
                document.head.appendChild(style);
            }, 1000);
        }
    </script>
</body>
</html>
