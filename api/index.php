<?php

// Pastikan direktori view cache di /tmp tersedia untuk lingkungan serverless Vercel
if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

// Forward Vercel serverless request ke entry point public/index.php Laravel
require __DIR__.'/../public/index.php';
