<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

abstract class Controller
{
    protected function resolveThemeName(Request $request, object $settings): string
    {
        $reqTheme = strtolower((string)$request->query('theme', ''));
        $dbTheme = strtolower((string)($settings->active_theme ?? 'theme1'));

        // URL query param (?theme=theme2) takes temporary preview precedence, otherwise always use Admin DB setting
        $target = $reqTheme ?: $dbTheme;

        $map = [
            'theme1'      => 'theme1',
            'nexus-glass' => 'theme1',
            'nexus_glass' => 'theme1',
            'nexus'       => 'theme1',
            'modern'      => 'theme1',
            'theme3'      => 'theme3',
            'glass'       => 'theme3',
        ];

        return $map[$target] ?? 'theme1';
    }
}
