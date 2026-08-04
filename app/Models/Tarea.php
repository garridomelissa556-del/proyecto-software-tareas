<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'etiqueta_tarea');
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

    public function scopeDeEtiqueta($query, ?string $etiquetaId)
    {
        return $query->when(
            $etiquetaId,
            fn ($q) => $q->whereHas('etiquetas', fn ($q) => $q->where('etiquetas.id', $etiquetaId))
        );
    }
}