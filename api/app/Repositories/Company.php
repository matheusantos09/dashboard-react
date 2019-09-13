<?php

namespace App\Repositories;

use Cache;
use Rossedman\Teamwork\Facades\Teamwork;


class Company
{

    public function all()
    {
        $companyAll = unserialize( Cache::get('companyAll') );

        if(empty($companyAll)){
            $companyAll = Teamwork::company()->all();

            Cache::put('companyAll', serialize($companyAll['companies']), 7200);

            return $companyAll['companies'];
        }

        return $companyAll;
    }

    public function find($id)
    {
        $companyAll = self::all();
        $company = false;

        foreach ($companyAll as $item)
        {
            if($id == $item['id']){
                $company = $item;
            }
        }

        return $company;
    }

    public function findName($name)
    {
        $companyAll = self::all();
        $company = false;

        foreach ($companyAll as $item)
        {
            $company[] = $item['name'].' | '.$item['email_one'].' | '.$item['phone'];
        }

        //$companies = self::recursive_array_search($name, $companyAll);

        return $company;
    }

    private function recursive_array_search($needle,$haystack) {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && self::recursive_array_search($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

}