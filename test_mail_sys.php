<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Attempting to send mail via SMTP...\n";

    // Apply DB Settings
    \App\Services\MailConfigService::applySettings();

    $to = '';
    $res = \Illuminate\Support\Facades\Mail::raw("Đây là nội dung TEST hệ thống từ CLI.\nThời gian: " . date('Y-m-d H:i:s'), function ($m) use ($to) {
        $m->to($to)->subject('Tinker Mail Test Sys');
    });

    echo "Mail result sent successfully!\n";
} catch (\Throwable $t) {
    echo "ERROR CAUGHT: " . $t->getMessage() . "\n";
    echo "IN FILE: " . $t->getFile() . " on line " . $t->getLine() . "\n";
    echo "STACK TRACE:\n" . $t->getTraceAsString() . "\n";
}
