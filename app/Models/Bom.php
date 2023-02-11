<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    use HasFactory;
  
    protected $casts = [
        'child_parts' => 'array',
    ];
    protected $fillable = [
        'bomID','partID', 'semi_part_bom_version', 'quantity', 'loss_rate', 'parentID', 'bom_version'
    ];
    public function bom_parts()
    {
        return $this->hasMany(Part::class);
    }
}
