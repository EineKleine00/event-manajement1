<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'EventApp',
                'type' => 'text',
                'label' => 'Nama Aplikasi',
            ],
            [
                'key' => 'registration_open',
                'value' => '1', // 1 = True, 0 = False
                'type' => 'boolean',
                'label' => 'Buka Pendaftaran User Baru',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'label' => 'Mode Perbaikan (Maintenance)',
            ],
            [
                'key' => 'max_upload_size',
                'value' => '2048', // Dalam KB
                'type' => 'number',
                'label' => 'Maksimal Ukuran Upload (KB)',
            ],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
