<?php

// app/Models/Device.php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- 1. Importar HasOne
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes; // <-- 2. Usar el trait

    protected $fillable = [
        'team_id',
        'name',
        'serial_number',
        'status',
    ];

    /**
     * The "booted" method of the model.
     * Aplica un scope global para el aislamiento de datos (multi-tenancy).
     */
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $builder) {
            if (Auth::check() && Auth::user()->currentTeam) {
                $builder->where('team_id', Auth::user()->currentTeam->id);
            }
        });
    }

    /**
     * Get the team that the device belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all of the measurements for the device.
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    /**
     * --- INICIO DE CORRECCIÓN ---
     * Define la relación para obtener solo la última medición.
     * (Se ordena por 'time' como se corrigió en el error anterior).
     */
    public function latestMeasurement(): HasOne
    {
        return $this->hasOne(Measurement::class)->orderBy('time', 'desc');
    }
    // --- FIN DE CORRECCIÓN ---
}