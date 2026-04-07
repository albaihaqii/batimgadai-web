<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PawnTransaction extends Model
{
    protected $fillable = [
        'no_sbg',
        'customer_id',
        'item_data',
        'item_photos',
        'officer_appraisal_min',
        'officer_appraisal_max',
        'loan_amount',
        'final_appraisal',
        'status',
        'branch_id',
        'officer_id',
        'admin_id',
        'transaction_date',
        'approval_date',
    ];

    protected $casts = [
        'item_data' => 'array',
        'item_photos' => 'array',
        'officer_appraisal_min' => 'decimal:2',
        'officer_appraisal_max' => 'decimal:2',
        'loan_amount' => 'decimal:2',
        'final_appraisal' => 'decimal:2',
        'transaction_date' => 'date',
        'approval_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
