<?php

declare(strict_types=1);

http_response_code(404);
require dirname(__DIR__) . '/bootstrap/app.php';

$pageTitle = 'Page Not Found';
$currentNav = '';

layout_head_only(compact('pageTitle', 'currentNav'));
require VIEW_PATH . '/pages/errors/not-found.php';
layout_close();
