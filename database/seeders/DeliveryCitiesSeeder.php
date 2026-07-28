<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $cities = [
        ['name' => 'Casablanca',    'price' => 25],
        ['name' => 'Rabat',         'price' => 35],
        ['name' => 'Marrakech',     'price' => 40],
        ['name' => 'Fès',           'price' => 40],
        ['name' => 'Tanger',        'price' => 45],
        ['name' => 'Agadir',        'price' => 50],
        ['name' => 'Meknès',        'price' => 40],
        ['name' => 'Oujda',         'price' => 50],
        ['name' => 'Kenitra',       'price' => 35],
        ['name' => 'Tétouan',       'price' => 45],
        ['name' => 'Safi',          'price' => 45],
        ['name' => 'El Jadida',     'price' => 35],
        ['name' => 'Beni Mellal',   'price' => 45],
        ['name' => 'Nador',         'price' => 50],
        ['name' => 'Settat',        'price' => 35],
        ['name' => 'Khouribga',     'price' => 40],
        ['name' => 'Laâyoune',      'price' => 60],
        ['name' => 'Dakhla',        'price' => 70],
    ];

    foreach ($cities as $city) {
        \App\Models\DeliveryCity::create($city);
    }
}
}
