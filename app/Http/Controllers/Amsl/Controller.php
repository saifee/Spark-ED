<?php

namespace App\Http\Controllers\Amsl;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getListForUI($query, Request $request)
    {
        $data = null;
        if($request->input('searchValue')){

            if (strpos($request->input('searchKey'), '.') !== false) {
                $searchValue=$request->input('searchValue');
                $explodeSarchKey=explode('.',$request->input('searchKey'));
                $query->whereHas($explodeSarchKey[0], function($q) use ($searchValue,$explodeSarchKey){
                    $q->where($explodeSarchKey[1], 'LIKE', "%$searchValue%");
                });
            }else{
                $query = $query->where($request->input('searchKey'), 'LIKE','%'.$request->input('searchValue').'%');
            }

        }
        //For Date Search
        if($request->input('fromDate')&& $request->input('toDate')){
            $query = $query->whereBetween($request->input('fieldName'),[Carbon::parse($request->input('fromDate')),Carbon::parse($request->input('toDate'))->addHour(23)->addMinute(59)->addSeconds(59)]);
        }

        if ($request->input('paging')) {
            if ($request->input('resultPerPage')=='All'){
                $data = $query->orderBy($request->input('sortName'), $request->input('sortOrder'))->paginate($query->count());
            }else{
                $data = $query->orderBy($request->input('sortName'), $request->input('sortOrder'))->paginate($request->input('resultPerPage'));
            }

        } else {
            $data = $query->orderBy($request->input('sortName'), $request->input('sortOrder'))->get();
        }
        return response()->json($data);
    }




    public function inputSearch($query,$request){

        if($request->input('searchValue')){
                $query = $query->where($request->input('searchKey'), 'LIKE','%'.$request->input('searchValue').'%');
            return $query;
        }else{
            return $query;
        }

    }
    public function dateSearch($field,$query,$request){

        $this->inputSearch($query,$request);
        if(request()->input('fromDate')&& request()->input('toDate')) {
            $query = $query->whereBetween($field,[Carbon::parse($request->input('fromDate')),Carbon::parse($request->input('toDate'))->addHour(23)->addMinute(59)->addSeconds(59)]);
            return $query;
        }else{
            return $query;
        }

    }

    public function makePaginate($query){

        $page = Input::get('page', 1);
        $query=$query->get()->toArray();
        if(request()->input('resultPerPage')=='All'){
            $slice = array_slice($query, count($query) * ($page - 1), count($query));
            $query = new Paginator($slice, count($query), count($query));
        }else{
            $slice = array_slice($query, request()->input('resultPerPage') * ($page - 1), request()->input('resultPerPage'));
            $query = new Paginator($slice, count($query), request()->input('resultPerPage'));
        }

        return $query;
    }


    public function getCalculationQuery($query){
       $calQuery= $query->skip(request()->input('resultPerPage')=='All'?
           count($query->get()):(intval(request()->input('resultPerPage')))*(intval(request()->input('page'))==0?1:intval(request()->input('page'))))->take(PHP_INT_MAX)->get();
       return $calQuery;
    }




    public function getFormDateAndToDate(){
        if(request()->input('fromDate')&& request()->input('toDate')){
            $fromDate=Carbon::parse(request()->input('fromDate'));
            $toDate=Carbon::parse(request()->input('toDate'))->addHour(23)->addMinute(59)->addSeconds(59);
        }else{
            $fromDate=Carbon::parse('1971-01-01');
            $toDate=Carbon::parse('2071-01-01');
        }
        $date=['fromDate'=>$fromDate,'toDate'=>$toDate];
        return $date;
    }

    public function getFromDateToDateForBalanceSheet(){
        if(request()->input('fromDate')&& request()->input('toDate')){
            $fromDate=Carbon::parse('1971-01-01');
            $toDate=Carbon::parse(request()->input('toDate'))->addHour(23)->addMinute(59)->addSeconds(59);
        }else{
            $fromDate=Carbon::parse('1971-01-01');
            $toDate=Carbon::parse('2071-01-01');
        }
        $date=['fromDate'=>$fromDate,'toDate'=>$toDate];
        return $date;
    }
}
