<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataProviderXResource;
use App\Http\Resources\DataProviderYResource;
use App\Http\Traits\ApiDesignTrait;


use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Collection;

class DataController extends Controller
{
    //

    use ApiDesignTrait;

    private $user;
    private $data;

    private $resultProvider;
    private $resultStatus;
    private $resultBalance;
    private $resultCurrency;

    public function __construct(User $user, Data $data) {

        $this->user = $user;
        $this->data = $data;
    }


    public function index(Request $request)
    {
        $urlX = 'http://localhost/DataProviderX.json';
        $urlY = 'http://localhost/DataProviderY.json';

//        $dataX = Http::get($urlX)->json();

//        $dataY = Http::get($urlY)->json();

        $dataX = json_decode(file_get_contents($urlX), true);
        $dataX = collect($dataX);
        $dataY = json_decode(file_get_contents($urlY), true);
        $dataY = collect($dataY);

        $arr1 = [];
        $arr2 = [];
        foreach ($dataX as $key => $value) {
            $arr1[] = $value;
        }

        foreach ($dataY as $key => $value) {
            $arr2[] = $value;
        }

        $arrCollection1 = collect($arr1);
        $arrCollection2 = collect($arr2);
        $dataCollection = $arrCollection1->merge($arrCollection2);
        $data = array_merge($arr1, $arr2);

        $query = $dataCollection;

        if($request->provider) {
            $name = '\modules\\' . $request->provider . '\\Models\\' . $request->provider;
            $urlProvider = 'http://localhost/' . $request->provider . '.json';
            $query = json_decode(file_get_contents($urlProvider), true);
            $query = collect($query);
            $this->resultProvider = $query;
        }

        if($request->status) {

            if($request->status == 'authorised'){
                $statusQueryX = $this->json_paramsX('statusCode' , $arr1, 1);
                $statusQueryY = $this->json_paramsY('status' , $arr2, 100);
            }elseif($request->status == 'decline'){
                $statusQueryX = $this->json_paramsX('statusCode' , $arr1, 2);
                $statusQueryY = $this->json_paramsY('status' , $arr2, 200);
            }elseif($request->status == 'refunded'){
                $statusQueryX = $this->json_paramsX('statusCode' , $arr1, 3);
                $statusQueryY = $this->json_paramsY('status' , $arr2, 300);
            }

            $statusQueryX = collect($statusQueryX);
            $statusQueryY = collect($statusQueryY);
            $query = $statusQueryX->merge($statusQueryY);
            $this->resultStatus = $query;
        }

        if($request->balance_from and $request->balance_to) {

            $query = $this->getResultBalance('balance_from', 'balance_to', $arr2);
            $this->resultBalance = $query;
        }

        if($request->currency) {

            $resultCurrencyX = $this->getCurrencyX('currency', $arr1);
            $resultCurrencyY = $this->getCurrencyY('currency', $arr2);
            $query = $resultCurrencyX->merge($resultCurrencyY);
            $this->resultCurrency= $query;
        }

        $results = $query
            ->union($this->resultProvider)
            ->union($this->resultCurrency)
            ->union($this->resultBalance)
            ->union($this->resultStatus);


        return $this->ApiResponse(200, 'users', null, $results);
    }


    function json_paramsX($status, $arr1, $statusVal){
        foreach ($arr1 as $key=>$value){
            $statusQueryX = [];
            foreach ($value as $itemKey=>$itemValue){
//                    $dataStatusX = array_search(1, array_keys($itemValue));

                $dataStatusX = $this->search_array($itemValue, $status, $statusVal);

                $statusQueryX[] = $dataStatusX;
            }
                return $statusQueryX;
            }
        }


    function json_paramsY($status, $arr2, $statusVal){
        foreach ($arr2 as $key=>$value){
            $statusQueryY = [];
            foreach ($value as $itemKey=>$itemValue){

                $dataStatusY = $this->search_array($itemValue, $status, $statusVal);

                $statusQueryY[] = $dataStatusY;

            }
            return $statusQueryY;
        }
    }


    function search_array ( $array, $key, $value )
    {
        $results = array();

        if ( is_array($array) )
        {
            if ( $array[$key] == $value )
            {
                $results[] = $array;
            } else {
                foreach ($array as $subarray)
                    $results = array_merge( $results, $this->search_array($subarray, $key, $value) );
            }
        }

        return $results;
    }


    function getResultBalance($from, $to, $arr2){
        foreach ($arr2 as $key=>$value){
            $balanceQueryY = [];
            foreach ($value as $itemKey=>$itemValue){

                $balanceQueryY[] = $itemValue;
            }
            $balanceQueryY = collect($balanceQueryY);
            $query = $balanceQueryY->whereBetween('balance', [request($from),request($to)]);
//            dd($query);
            return $query;
        }
    }


    function getCurrencyX($currency, $arr1){
        foreach ($arr1 as $key=>$value){
            $currencyQueryX = [];
            foreach ($value as $itemKey=>$itemValue){

                $currencyQueryX[] = $itemValue;
            }

            $currencyQueryX = collect($currencyQueryX);
//            dd($currencyQueryX);
            $query = $currencyQueryX->where('currency', request($currency));
//            dd($query);
            return $query;
        }
    }



    function getCurrencyY($currency, $arr2){
        foreach ($arr2 as $key=>$value){
            $currencyQueryy = [];
            foreach ($value as $itemKey=>$itemValue){

                $currencyQueryy[] = $itemValue;
            }

            $currencyQueryy = collect($currencyQueryy);
//            dd($currencyQueryX);
            $query = $currencyQueryy->where('currency', request($currency));
//            dd($query);
            return $query;
        }
    }

}


