<?php

namespace App\Traits;

use App\Models\Engineer;

trait Action
{
    function createEngineer($data_id)
    {
        $engineer = Engineer::create([
            'engineer_assignment_id'        => $data_id['engineer_assignment_id'] ?? null,
            'engineer_assignment_changes_id'=> $data_id['engineer_assignment_changes_id'] ?? null,
            'user_id'                       => $data_id['user_id'] ?? null,
        ]);

        return $engineer;
    }
}
