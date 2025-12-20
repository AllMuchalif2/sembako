<?php
header('Content-Type: application/json');
echo json_encode([
    "name" => "My - Mart",
    "short_name" => "MyMart",
    "start_url" => "/",
    "background_color" => "#ffffff",
    "description" => "Aplikasi belanja sembako murah dan terpercaya.",
    "display" => "standalone",
    "theme_color" => "#ffffff",
    "icons" => [
        [
            "src" => "images/logo.png",
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "any maskable"
        ]
    ],
    "screenshots" => [
        [
            "src" => "images/desktop.png",
            "sizes" => "1920x1080",
            "type" => "image/png",
            "form_factor" => "wide",
            "label" => "Tampilan Desktop MyMart"
        ],
        [
            "src" => "images/mobile.png",
            "sizes" => "1080x1920",
            "type" => "image/png",
            "form_factor" => "narrow",
            "label" => "Tampilan Mobile MyMart"
        ]
    ]
]);
