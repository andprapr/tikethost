<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'website_title',
        'favicon_path',
        'game_rules_content',
        'whatsapp_number',
        'telegram_number'
    ];
    
    /**
     * Get the singleton instance of website settings
     */
    public static function getInstance()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create([
                'website_title' => 'Event Hoki Talas89',
                'favicon_path' => null,
                'game_rules_content' => null
            ]);
        }
        return $settings;
    }
}
