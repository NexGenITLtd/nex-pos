<?php

// app/Imports/UserDataImport.php

namespace App\Imports;

use App\Models\UserData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;

class UserDataImport implements ToCollection
{
    use Importable;

    protected $column;

    // Accept the column name from the controller
    public function __construct($column)
    {
        $this->column = $column;
    }

    public function collection(Collection $rows)
    {
        // Loop through each row
        foreach ($rows as $row) {
            // Check if the selected column exists and is not empty
            if (isset($row[$this->column]) && !empty($row[$this->column])) {
                $phone = $row[$this->column];

                // Check if the phone number already exists in the database
                $existingPhone = UserData::where('phone', $phone)->first();

                // If the phone number does not exist, store it
                if (!$existingPhone) {
                    UserData::create(['phone' => $phone]);
                }
            }
        }
    }
}
