<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WebsiteCustomization;

class AddBackgroundImageSettingsToWebsiteCustomization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add background image and opacity settings
        $backgroundSettings = [
            [
                'setting_name' => 'background_image',
                'setting_value' => '',
                'setting_type' => 'file',
                'category' => 'appearance',
                'description' => 'Background image for the website'
            ],
            [
                'setting_name' => 'background_image_opacity',
                'setting_value' => '0.5',
                'setting_type' => 'number',
                'category' => 'appearance',
                'description' => 'Background image opacity (0.0 to 1.0)'
            ]
        ];

        foreach ($backgroundSettings as $setting) {
            WebsiteCustomization::firstOrCreate(
                ['setting_name' => $setting['setting_name']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        WebsiteCustomization::whereIn('setting_name', [
            'background_image',
            'background_image_opacity'
        ])->delete();
    }
}
