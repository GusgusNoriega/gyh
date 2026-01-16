<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SmtpSetting extends Model
{
    protected $table = 'smtp_settings';

    /**
     * Atributos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Obtener el valor de una configuración específica.
     *
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public static function getValue(string $key, $default = null): ?string
    {
        return Cache::remember("smtp_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Establecer el valor de una configuración.
     *
     * @param string $key
     * @param string $value
     * @param string|null $description
     * @return \App\Models\SmtpSetting
     */
    public static function setValue(string $key, string $value, ?string $description = null): SmtpSetting
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
            ]
        );

        Cache::forget("smtp_setting_{$key}");

        return $setting;
    }

    /**
     * Limpiar toda la cache de configuraciones SMTP.
     */
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("smtp_setting_{$setting->key}");
        }
    }

    /**
     * Obtener la configuración SMTP completa lista para usar.
     *
     * @return array{host: string|null, port: int|null, encryption: string|null, username: string|null, password: string|null, from_address: string|null, from_name: string|null}
     */
    public static function smtpConfig(): array
    {
        $host = static::getValue('smtp_host', env('MAIL_HOST'));
        $port = static::getValue('smtp_port', (string) env('MAIL_PORT', 2525));
        $encryption = static::getValue('smtp_encryption', env('MAIL_ENCRYPTION'));
        $username = static::getValue('smtp_username', env('MAIL_USERNAME'));
        $password = static::getValue('smtp_password', env('MAIL_PASSWORD'));
        $fromAddress = static::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS'));
        $fromName = static::getValue('smtp_from_name', env('MAIL_FROM_NAME'));

        return [
            'host' => $host,
            'port' => $port !== null ? (int) $port : null,
            'encryption' => $encryption,
            'username' => $username,
            'password' => $password,
            'from_address' => $fromAddress,
            'from_name' => $fromName,
        ];
    }
}