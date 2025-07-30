<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gift;
use Illuminate\Support\Facades\Storage;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if gifts table is empty
        if (Gift::count() == 0) {
            // Create default gifts
            $defaultGifts = [
                [
                    'nama_hadiah' => 'Motor Honda',
                    'image_path' => 'gifts/default_motor.jpg'
                ],
                [
                    'nama_hadiah' => 'Uang 100000',
                    'image_path' => 'gifts/default_money.jpg'
                ],
                [
                    'nama_hadiah' => 'HP Samsung',
                    'image_path' => 'gifts/default_phone.jpg'
                ],
                [
                    'nama_hadiah' => 'Voucher Belanja',
                    'image_path' => 'gifts/default_voucher.jpg'
                ],
                [
                    'nama_hadiah' => 'Hadiah Surprise',
                    'image_path' => 'gifts/default_surprise.jpg'
                ]
            ];

            foreach ($defaultGifts as $gift) {
                Gift::create($gift);
            }

            $this->command->info('Default gifts have been seeded successfully!');
        } else {
            $this->command->info('Gifts table already has data. Skipping seeding.');
        }
    }
}