<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCustomization extends Model
{
    use HasFactory;

    protected $table = 'website_customization';
    
    protected $fillable = [
        'setting_name',
        'setting_value',
        'setting_type',
        'category',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all settings grouped by category
     */
    public static function getGroupedSettings()
    {
        $settings = self::where('setting_name', '!=', 'site_title')
                       ->orderBy('category')
                       ->orderBy('setting_name')
                       ->get();
        
        return $settings->groupBy('category');
    }

    /**
     * Get a specific setting value
     */
    public static function getSetting($settingName, $default = null)
    {
        $setting = self::where('setting_name', $settingName)->first();
        
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a specific setting value
     */
    public static function setSetting($settingName, $settingValue)
    {
        return self::updateOrCreate(
            ['setting_name' => $settingName],
            ['setting_value' => $settingValue]
        );
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults()
    {
        $defaults = [
            ['setting_name' => 'background_color', 'setting_value' => '#ffffff', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Main background color of the website'],
            ['setting_name' => 'background_image', 'setting_value' => '', 'setting_type' => 'file', 'category' => 'appearance', 'description' => 'Background image for the website'],
            ['setting_name' => 'background_image_opacity', 'setting_value' => '0.5', 'setting_type' => 'number', 'category' => 'appearance', 'description' => 'Background image opacity (0.0 to 1.0)'],
            ['setting_name' => 'text_color', 'setting_value' => '#333333', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Primary text color'],
            ['setting_name' => 'header_background_color', 'setting_value' => '#f8f9fa', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Header background color'],
            ['setting_name' => 'header_text_color', 'setting_value' => '#212529', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Header text color'],
            ['setting_name' => 'button_background_color', 'setting_value' => '#007bff', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Primary button background color'],
            ['setting_name' => 'button_text_color', 'setting_value' => '#ffffff', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Primary button text color'],
            ['setting_name' => 'link_color', 'setting_value' => '#007bff', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Link color'],
            ['setting_name' => 'link_hover_color', 'setting_value' => '#0056b3', 'setting_type' => 'color', 'category' => 'appearance', 'description' => 'Link hover color'],
            ['setting_name' => 'website_image', 'setting_value' => '', 'setting_type' => 'file', 'category' => 'appearance', 'description' => 'Website logo/image displayed above title'],
            ['setting_name' => 'font_family', 'setting_value' => 'Arial, sans-serif', 'setting_type' => 'text', 'category' => 'typography', 'description' => 'Primary font family'],
            ['setting_name' => 'font_size', 'setting_value' => '16', 'setting_type' => 'number', 'category' => 'typography', 'description' => 'Base font size in pixels'],

            ['setting_name' => 'enable_dark_mode', 'setting_value' => 'false', 'setting_type' => 'boolean', 'category' => 'general', 'description' => 'Enable dark mode toggle'],
            ['setting_name' => 'show_time_display', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'general', 'description' => 'Show time display on homepage']
        ];

        foreach ($defaults as $default) {
            self::firstOrCreate(
                ['setting_name' => $default['setting_name']],
                $default
            );
        }
    }

    /**
     * Reset all settings to defaults
     */
    public static function resetToDefaults()
    {
        $defaults = [
            'background_color' => '#ffffff',
            'background_image' => '',
            'background_image_opacity' => '0.5',
            'text_color' => '#333333',
            'header_background_color' => '#f8f9fa',
            'header_text_color' => '#212529',
            'button_background_color' => '#007bff',
            'button_text_color' => '#ffffff',
            'link_color' => '#007bff',
            'link_hover_color' => '#0056b3',
            'website_image' => '',
            'font_family' => 'Arial, sans-serif',
            'font_size' => '16',

            'enable_dark_mode' => 'false',
            'show_time_display' => 'true'
        ];

        foreach ($defaults as $name => $value) {
            self::where('setting_name', $name)->update(['setting_value' => $value]);
        }
    }

    /**
     * Generate CSS from current settings
     */
    public static function generateCSS()
    {
        $settings = self::all()->pluck('setting_value', 'setting_name');
        
        $cssVariables = $settings->map(function ($value, $name) {
            return "  --" . str_replace('_', '-', $name) . ": {$value};";
        })->implode("\n");

        $darkMode = $settings->get('enable_dark_mode', 'false') === 'true';
        $backgroundImage = $settings->get('background_image', '');
        $backgroundImageOpacity = $settings->get('background_image_opacity', '0.5');
        
        // Generate background image CSS if image is set
        $backgroundImageCSS = '';
        if (!empty($backgroundImage)) {
            $backgroundImageCSS = "
/* Background Image Styling */
html body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: url('" . asset('storage/' . $backgroundImage) . "');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  opacity: {$backgroundImageOpacity};
  z-index: -1;
  pointer-events: none;
}";
        }
        
        return "
:root {
{$cssVariables}
}

{$backgroundImageCSS}

/* Apply customization variables with high specificity */
html body {
  background-color: var(--background-color) !important;
  color: var(--text-color) !important;
  font-family: var(--font-family) !important;
  font-size: var(--font-size, 16)px !important;
}

html body header {
  background-color: var(--header-background-color) !important;
  color: var(--header-text-color) !important;
}

html body .header {
  color: var(--header-text-color) !important;
}

html body .btn-primary {
  background-color: var(--button-background-color) !important;
  color: var(--button-text-color) !important;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
}

html body .btn-primary:hover {
  opacity: 0.9;
}

html body a {
  color: var(--link-color) !important;
  text-decoration: none;
}

html body a:hover {
  color: var(--link-hover-color) !important;
}

/* Specific text elements customization */
html body .form-container h3 {
  color: var(--header-text-color) !important;
  text-shadow: 0 0 10px var(--header-text-color) !important;
}

html body .rules-content h2 {
  color: var(--header-text-color) !important;
  text-shadow: 0 0 10px var(--header-text-color) !important;
}

html body .rules-content h3 {
  color: var(--text-color) !important;
}

html body .rules-content p,
html body .rules-content li,
html body .rules-content ol,
html body .rules-content ul {
  color: var(--text-color) !important;
}

html body .rules-text {
  color: var(--text-color) !important;
}

html body .rules-text strong {
  color: var(--header-text-color) !important;
}

html body input[type=\"text\"] {
  color: var(--text-color) !important;
  border-color: var(--button-background-color) !important;
  background: var(--header-background-color) !important;
}

html body input[type=\"text\"]:focus {
  border-color: var(--button-background-color) !important;
  background: var(--header-background-color) !important;
}

html body input[type=\"text\"]::placeholder {
  color: var(--text-color) !important;
  opacity: 0.7;
}

html body .notice {
  color: var(--text-color) !important;
}

html body .congrats-content h2,
html body .congrats-content p {
  color: var(--text-color) !important;
}

html body .prize-display h3 {
  color: var(--header-text-color) !important;
}

html body .time-display h3 {
  color: var(--header-text-color) !important;
}

html body .time-display p {
  color: var(--text-color) !important;
}

html body .current-time {
  color: var(--text-color) !important;
}

/* Override hardcoded colors when dark mode is disabled */
" . ($darkMode ? "" : "
html body {
  background: var(--background-color) !important;
  animation: none !important;
}

html body::before {
  display: none !important;
}

html body .container {
  background: var(--header-background-color) !important;
  border-color: var(--button-background-color) !important;
  animation: none !important;
}

html body .casino-coin,
html body .slot-symbol {
  display: none !important;
}
") . "

/* Additional overrides for specific elements */
html body {
  background: var(--background-color) !important;
}

html body.antialiased {
  background: var(--background-color) !important;
  background-color: var(--background-color) !important;
}
";
    }
}