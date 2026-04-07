<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedemptionTransaction extends Model
{
    protected $fillable = [
        'pawn_transaction_id',
        'total_loan',
        'interest',
        'penalty',
        'total_payment',
        'status',
        'redemption_date',
    ];

    protected $casts = [
        'total_loan' => 'decimal:2',
        'interest' => 'decimal:2',
        'penalty' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'redemption_date' => 'date',
    ];

    public function pawnTransaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }
}
