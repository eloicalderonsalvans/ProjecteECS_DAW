<?php

$dirs = ['user', 'torn', 'horari', 'department', 'absencia', 'fixatge', 'h_fixatge'];

foreach ($dirs as $dir) {
    if (!is_dir(__DIR__ . "/resources/views/$dir")) continue;
    $files = glob(__DIR__ . "/resources/views/$dir/*.blade.php");
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Check if it has an HTML structure
        if (strpos($content, '<!DOCTYPE html>') !== false || strpos($content, '<html') !== false) {
            
            // Extract custom styles and scripts from <head>
            $custom_head = '';
            if (preg_match('/<head.*?>(.*?)<\/head>/is', $content, $head_matches)) {
                $head_inner = $head_matches[1];
                
                // Remove meta, title, tailwindcdn
                $head_inner = preg_replace('/<meta.*?>/is', '', $head_inner);
                $head_inner = preg_replace('/<title>.*?<\/title>/is', '', $head_inner);
                $head_inner = preg_replace('/<script src="https:\/\/cdn\.tailwindcss\.com"><\/script>/is', '', $head_inner);
                
                $head_inner = trim($head_inner);
                if (!empty($head_inner)) {
                    // For style tags and non-script things, put them in styles. If they are scripts, they can go in there too or separate.
                    // The simplest is just putting everything extracted into styles/scripts_head
                    $custom_head = "@section('styles')\n" . $head_inner . "\n@endsection\n";
                }
            }
            
            // Get everything between <body> and </body>
            $body_content = '';
            if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $body_matches)) {
                $body_content = trim($body_matches[1]);
            } else {
                // If body not found, just take everything after </head> and replace </html>
                $body_content = preg_replace('/^.*<\/head>/is', '', $content);
                $body_content = preg_replace('/<\/html>/is', '', $body_content);
                $body_content = trim(preg_replace('/.*?<body[^>]*>/is', '', $body_content));
            }
            
            $new_content = "@extends('layouts.app')\n\n";
            if (!empty($custom_head)) {
                $new_content .= $custom_head . "\n";
            }
            
            $new_content .= "@section('content')\n";
            $new_content .= $body_content . "\n";
            $new_content .= "@endsection\n";
            
            $content = $new_content;
        }
        
        // Safely remove "Inici" button: Any anchor tag with route('dashboard') or route('home') with inner text containing Inici or an SVG
        $content = preg_replace('/<a\s+href="\{\{\s*route\(\'(dashboard|home)\'\)\s*\}\}"[^>]*>.*?Inici.*?<\/a>/is', '', $content);
        
        file_put_contents($file, $content);
        echo "Refactored $file\n";
    }
}
