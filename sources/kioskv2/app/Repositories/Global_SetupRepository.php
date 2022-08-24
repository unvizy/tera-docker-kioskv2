<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Global_SetupRepository
{
	// lock transaction
    public static function lock($setupName)
    {
        DB::select("SELECT * FROM global_setup WHERE setup_name = :setupName FOR UPDATE", [
            $setupName
        ]);
    }
}