<?php

namespace Database\Seeders;
use App\Models\Property;


use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Property::create([
                'title' => "Property $i",
                'price' => rand(100000, 500000),
                'floor_area' => rand(500, 2000),
                'bedroom' => rand(1, 5),
                'bathroom' => rand(1, 3),
                'created_by' => 1, // Adjust as needed
                'city' => 'Mumbai',
                'address' => "4th Floor, The Ruby, 29, Senapati Bapat Marg, Dadar West, Mumbai - 400028",
                'description' => "Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Cras ultricies ligula sed magna dictum porta. Curabitur aliquet quam id dui posuere blandit. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar quam id dui posuere blandit.",
                'featured_image' => 'featured_image.jpg',
                'nearby_place' => "nearby dadar station",
            ]);
        }
    }
}
