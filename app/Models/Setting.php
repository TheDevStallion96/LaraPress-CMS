<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property bool $autoload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['key', 'value', 'autoload'])]
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    /**
     * The attributes that should be set to their default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'autoload' => true,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'autoload' => 'boolean',
        ];
    }

    /**
     * Get the value of a setting by key, or the given default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting->value ?? $default;
    }

    /**
     * Set the value of a setting by key, creating it when missing.
     */
    public static function set(string $key, ?string $value, bool $autoload = true): Setting
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'autoload' => $autoload],
        );
    }
}
