<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/26/2018
 * Time: 7:26 PM
 */

namespace App\Traits\Amsl;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait DashboardTrait
{

   public function getMonthlySales(){
       $currentMonth = date('m');
       $data=DB::table("incomes")
           ->whereRaw('MONTH(income_date) = ?',[$currentMonth])
           ->sum('amount');
       return $data;
   }

   public function getWeeklySales(){
       $data=DB::table("incomes")
           ->whereBetween('income_date',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
           ->sum('amount');
       return $data;
   }

   public function getMonthlyExpense(){
       $currentMonth = date('m');
       $data=DB::table("accounts")
           ->select('id as aid','accounts.name',
               DB::raw("(SELECT SUM(amount) from expenses where account_id=aid and MONTH(expense_date) = '$currentMonth') as expenseAmount"),
               DB::raw("(Select SUM(amount) from assets where expense_id=aid and MONTH(asset_date) = '$currentMonth') as expenseAssetAmount"))
           ->where('account_type','=','Expense')
           ->groupBy('accounts.id')
           ->get();
       return $data;
   }

   public function getWeeklyExpense(){
       $startWeek=Carbon::now()->startOfWeek();
       $endWeek=Carbon::now()->endOfWeek();

      $data=DB::table("accounts")
           ->select('id as aid','accounts.name',
               DB::raw("(SELECT SUM(amount) from expenses where account_id=aid and expense_date between '$startWeek' and '$endWeek') as expenseAmount"),
               DB::raw("(Select SUM(amount) from assets where expense_id=aid and asset_date between '$startWeek' and '$endWeek') as expenseAssetAmount"))
           ->where('account_type','=','Expense')
           ->groupBy('accounts.id')
           ->get();
        return $data;
   }
}
