<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'game',
        'is_paid',
        'price',
        'barcode',
        'close_registration',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class)->select('id', 'name');
    }

    // public function matches()
    // {
    //     return $this->hasMany(Match::class);
    // }

    // public function registrations()
    // {
    //     return $this->hasMany(TournamentRegistration::class);
    // }
}
