<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingController extends Controller
{
    // 1. Tampilkan Halaman Setting
    public function index()
    {
        // Ambil semua setting dari database
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    // 2. Simpan Perubahan
    public function update(Request $request)
    {
        // Ambil semua data input kecuali token dan method
        $data = $request->except(['_token', '_method']);

        // A. Update data yang dikirim (Text/Number/Checkbox Checked)
        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // B. Handle Checkbox yang TIDAK dicentang (Switch OFF)
        // HTML Form tidak mengirim data jika checkbox mati, jadi harus kita paksa set ke '0'
        $booleanKeys = Setting::where('type', 'boolean')->pluck('key');
        
        foreach ($booleanKeys as $key) {
            if (!$request->has($key)) {
                Setting::where('key', $key)->update(['value' => '0']);
            }
        }

        return back()->with('success', 'Konfigurasi sistem berhasil disimpan.');
    }
}