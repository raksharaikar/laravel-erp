<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $casts = [
        'child_parts' => 'array',
    ];

    protected $table = 'parts';
    protected $primaryKey = 'partID';
    public $timestamps = false;
    
    protected $fillable = [
        'bomID', 'partID', 'semi_part_bom_version', 'quantity', 'loss_rate', 'parentID', 'bom_version'
    ];

 

    public function bom()
    {
        return $this->belongsTo(Bom::class);
    }

   // public function childparts()
    //{
      
        // return $this->hasMany(Part::class, 'parentID')
        // ->with(['childparts' => function ($query) use (semiPartVesion) {
        //     $query->where('bom_version', $semiPartVesion)
        //           ->with(['childparts' => function ($query) use ($semiPartVesion) {
        //               $query->where('bom_version', $semiPartVesion)
        //                     ->with(['childparts' => function ($query) use ($semiPartVesion) {
        //                         $query->where('bom_version', $semiPartVesion);
        //                     }]);
        //           }]);
        // }])
        // ->where('bom_version', $semiPartVesion);
   // }
 
    public function childparts()
    {
        return $this->hasMany(Part::class, 'parentID')->with(['childparts']);
      
    
    }
}
