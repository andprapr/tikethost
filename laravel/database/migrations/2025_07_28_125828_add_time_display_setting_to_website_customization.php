<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WebsiteCustomization;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add the time display setting if it doesn't exist
        WebsiteCustomization::firstOrCreate(
            ['setting_name' => 'show_time_display'],
            [
                'setting_value' => 'true',
                'setting_type' => 'boolean',
                'category' => 'general',
                'description' => 'Show time display on homepage'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the time display setting
        WebsiteCustomization::where('setting_name', 'show_time_display')->delete();
    }
};