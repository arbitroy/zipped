<?php
// Get the requested URL path
$request_uri = $_SERVER['REQUEST_URI'];

// Define routes and corresponding PHP files
$routes = [
	'/' => 'index.php',
	'/index' => 'index.php',
    '/add-family' => 'add-family.php',
    '/add-memory' => 'add-memory.php',
    '/collaboration' => 'collaboration.php',
    '/dashboard' => 'dashboard.php',
    '/family-tree' => 'family-tree.php',
    '/forgot-password' => 'forgot-password.php',
    '/invitations' => 'invitations.php',
    '/memory-builder' => 'memory-builder.php',
    '/notifications' => 'notifications.php',
    '/settings' => 'settings.php',
    '/signup' => 'signup.php',
    '/user-profile' => 'user-profile.php',
    '/onboarding' => 'onboarding.php',
    '/timeline' => 'timeline.php',

];

// Check if the requested path matches a route
if (isset($routes[$request_uri])) {
    $target_file = $routes[$request_uri];
    // Include the corresponding PHP file
    require_once $target_file;
} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo '404 Not Found';
}
