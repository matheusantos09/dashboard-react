<?php

namespace App\Repositories;

use Cache;
use Rossedman\Teamwork\Facades\Teamwork;


class People
{

    public function all()
    {
        $peopleAll = unserialize( Cache::get('peopleAll') );

        if(empty($peopleAll)){
            $peopleAll = Teamwork::people()->all();

            Cache::put('peopleAll', serialize($peopleAll['people']), 7200);

            return $peopleAll['people'];
        }

        return $peopleAll;
    }

    public function find($id)
    {
        $peopleAll = self::all();
        $people = false;

        foreach ($peopleAll as $item)
        {
            if($id == $item['id']){
                $people = $item;
            }
        }

        return $people;
    }

}