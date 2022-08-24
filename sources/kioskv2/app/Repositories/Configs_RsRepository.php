<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Configs_RsRepository
{
	 /**
     * Get config by config name.
     *
     * @param   string  $key
     *
     * @return  object|null
     */
    public static function findByName(string $key)
    {
        $sql = "SELECT * FROM configs_rs WHERE confname = :key";
        $bindings = [
            'key' => $key,
        ];

        return DB::selectOne($sql, $bindings);
    }
}