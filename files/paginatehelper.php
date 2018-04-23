<?php


use Bindeveloperz\Core\Helper\PaginateHelper;

if(!function_exists("paginate"))
{
    function paginate($totalCount,$perPage = 10,$page = 1,$url = '?')
    {
        return PaginateHelper::paginate($totalCount,$perPage,$page,$url);
    }
}
