<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibrarySetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return [$r, $g, $b];
    }

    public static function adjustBrightness($hex, $steps)
    {
        $steps = max(-255, min(255, $steps));
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                   . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                   . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }

    public static function getCustomCssVariables()
    {
        $primary = static::get('theme_primary_color', '#2E7D32');
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $primary)) {
            $primary = '#2E7D32';
        }
        
        $rgb = static::hexToRgb($primary);
        $rgbStr = implode(',', $rgb);
        $darker = static::adjustBrightness($primary, -35);
        $lighter = static::adjustBrightness($primary, 30);
        $accent = static::adjustBrightness($primary, 55);

        $heroImage = static::get('auth_hero_image');
        $heroImgUrl = ($heroImage && file_exists(public_path($heroImage))) ? asset($heroImage) : 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80';

        return "<style>
        :root {
            --primary: {$primary} !important;
            --primary-hover: {$darker} !important;
            --primary-secondary: {$lighter} !important;
            --primary-accent: {$accent} !important;
            --primary-light: rgba({$rgbStr}, 0.08) !important;
            --primary-glow: rgba({$rgbStr}, 0.25) !important;
            --shadow-primary: 0 10px 24px -4px rgba({$rgbStr}, 0.35) !important;
        }
        .login-split-left {
            background: linear-gradient(135deg, rgba({$rgbStr}, 0.90) 0%, rgba({$rgbStr}, 0.80) 100%), url('{$heroImgUrl}') center/cover no-repeat !important;
        }
        .btn-primary-tzuchi {
            background: linear-gradient(135deg, {$primary} 0%, {$lighter} 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 10px 24px -4px rgba({$rgbStr}, 0.35) !important;
        }
        .btn-primary-tzuchi:hover {
            background: linear-gradient(135deg, {$darker} 0%, {$primary} 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 12px 28px -4px rgba({$rgbStr}, 0.45) !important;
        }
        .sidebar-item.active a {
            background: linear-gradient(135deg, {$primary} 0%, {$lighter} 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 10px 24px -4px rgba({$rgbStr}, 0.35) !important;
        }
        .sidebar-submenu li.active-sub a {
            background-color: rgba({$rgbStr}, 0.12) !important;
            color: {$primary} !important;
        }
        .sidebar-submenu li.active-sub a i {
            color: {$primary} !important;
        }
        .brand-title {
            color: {$primary} !important;
        }
        .nav-link:hover, .nav-link.active {
            color: {$primary} !important;
            background-color: rgba({$rgbStr}, 0.12) !important;
        }
        .badge-success {
            background: rgba({$rgbStr}, 0.12) !important;
            color: {$primary} !important;
            border: 1px solid rgba({$rgbStr}, 0.25) !important;
        }
        .featured-banner-wrapper {
            background: linear-gradient(135deg, {$darker} 0%, {$primary} 50%, {$lighter} 100%) !important;
        }
        </style>";
    }
}
