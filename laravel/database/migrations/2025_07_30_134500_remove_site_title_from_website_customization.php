<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WebsiteCustomization;

class RemoveSiteTitleFromWebsiteCustomization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove site_title setting from website_customization table
        WebsiteCustomization::where('setting_name', 'site_title')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore site_title setting if needed
        WebsiteCustomization::firstOrCreate(
            ['setting_name' => 'site_title'],
            [
                'setting_value' => 'My Website',
                'setting_type' => 'text',
                'category' => 'general',
                'description' => 'Website title'
            ]
        );
    }
}