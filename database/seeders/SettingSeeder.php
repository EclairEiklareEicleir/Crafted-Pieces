<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'platform_fee_percent'],
            [
                'label' => 'Platform Fee (%)',
                'type' => 'percent',
                'value' => 5,
                'description' => 'Percentage fee applied to all orders'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'delivery_fee'],
            [
                'label' => 'Delivery Fee',
                'type' => 'number',
                'value' => 50,
                'description' => 'Flat delivery charge per order'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'vat_enabled'],
            [
                'label' => 'Enable VAT',
                'type' => 'boolean',
                'value' => 0,
                'description' => 'Toggle VAT calculation'
            ]
        );
    }
}
