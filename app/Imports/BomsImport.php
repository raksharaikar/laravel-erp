<?php

namespace App\Imports;

use App\Models\Bom;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithStartRow;

class BomsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        return new Bom([


            'bom_id' => $row[1],
            'part_id' => $row[2],
            'semi_part_bom_version' => ($row[3] === 'null' || is_null($row[3])) ? null : $row[3],
            'quantity' => $row[4],
            'loss_rate' => $row[5],
            'parent_id' => (($row[6] === "null") ? "null" : ($row[6] === 0) ? 0 : $row[6]),

        ]);
    }
}