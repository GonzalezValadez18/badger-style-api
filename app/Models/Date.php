<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['asunto', 'fecha', 'hora', 'user_id'];

    /**
     * Get the service associated with the date.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'asunto');
    }

    /**
     * Get the user associated with the date.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}