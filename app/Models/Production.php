<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'reference_no',
        'warehouse_id',
        'production_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
    ];

    public function entries()
    {
        return $this->hasMany(ProductionEntry::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
