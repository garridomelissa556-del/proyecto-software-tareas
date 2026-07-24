<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'fecha_limite',
        'prioridad',
        'user_id',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBuscar($query, ?string $texto)
    {
        return $query->when($texto, function ($q) use ($texto) {
            $q->where(function ($q) use ($texto) {
                $q->where('titulo', 'like', "%{$texto}%")
                    ->orWhere('descripcion', 'like', "%{$texto}%");
            });
        });
    }

    public function scopeDeEstado($query, ?string $estado)
    {
        return $query->when($estado, fn ($q) => $q->where('estado', $estado));
    }

    public function scopeDePrioridad($query, ?string $prioridad)
    {
        return $query->when($prioridad, fn ($q) => $q->where('prioridad', $prioridad));
    }
}
