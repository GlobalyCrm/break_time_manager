<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakLogs extends Model
{
    use HasFactory;

    protected $table = 'break_logs';


    public function getBreakStart(){
        return $this->hasOne(BreakLogs::class, 'id', 'break_start_id');
    }

    public function getBreakEnd(){
        return $this->hasOne(BreakLogs::class, 'break_start_id', 'id');
    }
}
