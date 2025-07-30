<?php
$secret = 'abc123rahasia'; // Samakan dengan secret GitHub

function getHeader($key) {
    $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
    return $_SERVER[$key] ?? null;
}

$payload = file_get_contents('php://input');
$signature256 = getHeader('X-Hub-Signature-256');

if (!$signature256) {
    http_response_code(403);
    exit('❌ Tidak ada signature!');
}

$hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (!hash_equals($hash, $signature256)) {
    http_response_code(403);
    exit('❌ Signature tidak cocok!');
}

// GANTI 'main' → 'master'
$output = shell_exec("cd /home/andprapr/dua.niemaggg.space && git pull origin master 2>&1");

file_put_contents("/home/andprapr/dua.niemaggg.space/deploy_log.txt", date('c') . "\n" . $output . "\n\n", FILE_APPEND);
echo "<pre>$output</pre>";
