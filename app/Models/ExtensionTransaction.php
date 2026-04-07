<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionTransaction extends Model
{
    protected $fillable = [
        'pawn_transaction_id',
        'due_date',
        'penalty',
        'status',
        'extension_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'penalty' => 'decimal:2',
        'extension_date' => 'date',
    ];

    public function pawnTransaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }
}
