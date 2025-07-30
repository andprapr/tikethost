<!DOCTYPE html>
<html>
<head>
    <title>Admin Debug</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        .debug-section { margin-bottom: 20px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Admin Debug Information</h1>

    <div class="debug-section">
        <h2>Database Status</h2>
        <pre>{{ $dbStatus }}</pre>
    </div>

    <div class="debug-section">
        <h2>Loaded Controllers</h2>
        <ul>
            @foreach($controllers as $controller)
                <li>{{ $controller }}</li>
            @endforeach
        </ul>
    </div>

    <div class="debug-section">
        <h2>Routes</h2>
        <ul>
            @foreach($routes as $name => $route)
                <li>{{ $name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="debug-section">
        <h2>PHP Info</h2>
        <p>Version: {{ $phpInfo['version'] }}</p>
        <h3>Extensions:</h3>
        <ul>
            @foreach($phpInfo['extensions'] as $ext)
                <li>{{ $ext }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
