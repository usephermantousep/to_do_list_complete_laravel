<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function jenistodo()
    {
        return $this->belongsTo(Jenistodo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        return $this->belongsTo(User::class,'approved_id');
    }
}
