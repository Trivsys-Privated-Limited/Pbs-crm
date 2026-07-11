<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Mail::to('trivsys44@gmail.com')->send(new \App\Mail\LoginNotificationMail('Test', 'test@test.com', now()->format('Y-m-d H:i:s'), '127.0.0.1'));
    echo "Mail sent successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
