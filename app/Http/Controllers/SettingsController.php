<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        $settings = Setting::query()->pluck('value', 'key')->all();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'brand_primary' => ['nullable', 'string', 'max:20'],
            'brand_secondary' => ['nullable', 'string', 'max:20'],
            'home_headline' => ['nullable', 'string', 'max:200'],
            'home_subheadline' => ['nullable', 'string', 'max:500'],
            'cta_primary_text' => ['nullable', 'string', 'max:100'],
            'cta_primary_url' => ['nullable', 'string', 'max:255'],
            'cta_secondary_text' => ['nullable', 'string', 'max:100'],
            'cta_secondary_url' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'maintenance_enabled' => ['nullable', 'string', 'in:1'],
            'registrations_locked' => ['nullable', 'string', 'in:1'],
            'maintenance_start_at' => ['nullable', 'date'],
            'maintenance_end_at' => ['nullable', 'date', 'after_or_equal:maintenance_start_at'],
            'logo_width' => ['nullable', 'integer', 'min:40', 'max:400'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'logo_alt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'favicon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,ico,svg', 'max:1024'],
            'home_image_1' => ['nullable', 'image', 'max:4096'],
            'home_image_2' => ['nullable', 'image', 'max:4096'],
            'home_image_3' => ['nullable', 'image', 'max:4096'],
            'login_image' => ['nullable', 'image', 'max:4096'],
            'maintenance_image' => ['nullable', 'image', 'max:4096'],
            'maintenance_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'remove_home_image' => ['nullable', 'string', 'in:1'],
            'remove_home_image_1' => ['nullable', 'string', 'in:1'],
            'remove_home_image_2' => ['nullable', 'string', 'in:1'],
            'remove_home_image_3' => ['nullable', 'string', 'in:1'],
            'remove_login_image' => ['nullable', 'string', 'in:1'],
            'remove_maintenance_image' => ['nullable', 'string', 'in:1'],
            'remove_maintenance_logo' => ['nullable', 'string', 'in:1'],
        ]);

        $textKeys = [
            'site_title',
            'site_tagline',
            'site_description',
            'brand_primary',
            'brand_secondary',
            'home_headline',
            'home_subheadline',
            'contact_email',
            'contact_phone',
            'contact_whatsapp',
            'maintenance_message',
            'maintenance_start_at',
            'maintenance_end_at',
            'logo_width',
        ];

        foreach ($textKeys as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::setValue($key, $validated[$key], 'text');
            }
        }

        Setting::setValue('maintenance_enabled', $request->has('maintenance_enabled') ? '1' : '0', 'bool');
        Setting::setValue('registrations_locked', $request->has('registrations_locked') ? '1' : '0', 'bool');

        $fileKeys = [
            'logo',
            'logo_alt',
            'favicon',
            'home_image_1',
            'home_image_2',
            'home_image_3',
            'login_image',
            'maintenance_image',
            'maintenance_logo',
        ];
        foreach ($fileKeys as $key) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::setValue($key, $path, 'file');
            }
        }

        if ($request->has('remove_home_image')) {
            $current = Setting::getValue('home_image');
            if ($current) {
                Storage::disk('public')->delete($current);
            }
            Setting::setValue('home_image', null, 'file');
        }

        foreach (['home_image_1', 'home_image_2', 'home_image_3', 'login_image', 'maintenance_image', 'maintenance_logo'] as $key) {
            if ($request->has('remove_' . $key)) {
                $current = Setting::getValue($key);
                if ($current) {
                    Storage::disk('public')->delete($current);
                }
                Setting::setValue($key, null, 'file');
            }
        }

        return redirect()->route('settings.index')->with('success', 'Configuración guardada.');
    }

    public function downloadBackup()
    {
        $this->authorizeAccess();

        if (config('database.default') !== 'mysql') {
            return redirect()->route('settings.index')->withErrors(['backup' => 'Solo se soporta backup para MySQL.']);
        }

        $conn = config('database.connections.mysql');
        $host = $conn['host'] ?? '127.0.0.1';
        $port = $conn['port'] ?? 3306;
        $database = $conn['database'] ?? '';
        $username = $conn['username'] ?? '';
        $password = $conn['password'] ?? '';

        if (!$database || !$username) {
            return redirect()->route('settings.index')->withErrors(['backup' => 'Configuración de base de datos incompleta.']);
        }

        $dir = storage_path('app/backups');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = 'backup-' . $database . '-' . now()->format('Ymd-His') . '.sql';
        $path = $dir . '/' . $filename;

        $cmd = [
            'mysqldump',
            '-h', $host,
            '-P', (string) $port,
            '-u', $username,
        ];
        if ($password !== '') {
            $cmd[] = '--password=' . $password;
        }
        $cmd[] = $database;

        $escaped = array_map('escapeshellarg', $cmd);
        $command = implode(' ', $escaped) . ' > ' . escapeshellarg($path) . ' 2>&1';
        exec($command, $output, $code);

        if ($code !== 0 || !file_exists($path)) {
            return redirect()->route('settings.index')->withErrors(['backup' => 'No se pudo generar el backup.']);
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin) {
            abort(403);
        }
    }
}
