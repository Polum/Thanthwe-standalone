<?php

namespace App\Exports;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use App\Offering;
use App\OfferingType;

class IncomeStatement implements FromCollection, ShouldAutoSize, WithStrictNullComparison, WithEvents
{
    use Exportable;

    public function __construct(int $year){
        $this->year = $year;
    }

    public function collection()
    {
        $data = [];
        $offers = [];
        $income_total_row = array('Income Total');
        $sundayBaskets = $this->sundayBasket();

        $offerings = DB::table('offerings')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->join('offering_types', 'offering_types.id', '=', 'offering_type_id')
        ->whereYear('transaction_date', '=', $this->year)
        ->get()
        ->groupBy('offering_type_id');

        foreach($offerings as $type => $value){
            $quartery = array_fill(0,4,0);
            $monthly = array_fill(0,12,0);

            $title = OfferingType::find($type);
            $monthly_row = array($title->offering_title);

            foreach($value as $offering){
                $date = Carbon::parse($offering->offering_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $offering->offering_amount;
            }

            foreach($value as $month){
                $date = Carbon::parse($month->offering_date)->month-1;
                $monthly[$date] = $monthly[$date] + $month->offering_amount;
            }

            $monthly['q1'] = $quartery[0];
            $monthly['q2'] = $quartery[1];
            $monthly['q3'] = $quartery[2];
            $monthly['q4'] = $quartery[3];

            foreach($monthly as $m){
                array_push($monthly_row, $m); //pushing each quarter of the expense total to an expense row for excel export
            }
            array_push($offers, $monthly_row);
        }

        $income_total = array_fill(0,16,0);
        foreach($offers as $rtt){
            for($i = 1; $i < 17; $i++){
                $idx = $i;
                $income_total[$idx-1] = $income_total[$idx-1] + $rtt[$idx];
            }
        }
        for($i = 1; $i < 17; $i++){
            $idx = $i;
            $income_total[$idx-1] = $income_total[$idx-1] + $sundayBaskets[$idx];
        }
        foreach($income_total as $rt){
            array_push($income_total_row, $rt);
        }

        array_push($data, array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Q1','Q2','Q3','Q4'));
        array_push($data, array('INCOME'));

        array_push($data, $this->sundayBasket());
        array_push($data, $offers);
        array_push($data, array(''));
        array_push($data, $income_total_row);
        array_push($data, array(''));


        $expenses = $this->expenditure();
        array_push($data, array('EXPENDITURE'));
        array_push($data, $expenses);

        $expense_total_row = array('Total');
        $total = array_fill(0,16,0);
        foreach($expenses as $expense){
            for($i = 1; $i < 17; $i++){
                $idx = $i -1;
                $total[$idx] = $total[$idx] + $expense[$i];
            }
        }
        foreach($total as $t){
            array_push($expense_total_row, $t);
        }
        array_push($data, array(''));
        array_push($data, $expense_total_row);
        array_push($data, array(''));


        // Gross total
        $gross = array('Gross total');
        for($g=1; $g <17; $g++ ){
            $grand = $income_total_row[$g] - $expense_total_row[$g];
            array_push($gross, $grand);
        }
        array_push($data, $gross);


        return new Collection($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange);
            },
        ];
    }

    private function expenditure()
    {
        $expenditureData = [];
        $expenses = DB::table('expenses')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('expense_date', '=', $this->year)
        ->get()
        ->groupBy('expense_type'); // Getting all expenses in given year grouped by the expense type

        // Looping through the expense array by expense type to handle each type separately
        foreach($expenses as $type => $value){
            $quartery = array_fill(0,4,0);
            $monthly = array_fill(0,12,0);

            $monthly_row = array($type); //assign the expense type(key) to the begining of an array
            foreach($value as $expense){ //loop throug the expenses(value) to calculate the total per each quarter of the year
                $date = Carbon::parse($expense->expense_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $expense->expense_amount;
            }

            foreach($value as $month){
                $date = Carbon::parse($month->expense_date)->month-1;
                $monthly[$date] = $monthly[$date] + $month->expense_amount;
            }

            $monthly['q1'] = $quartery[0];
            $monthly['q2'] = $quartery[1];
            $monthly['q3'] = $quartery[2];
            $monthly['q4'] = $quartery[3];

            foreach($monthly as $m){
                array_push($monthly_row, $m); //pushing each quarter of the expense total to an expense row for excel export
            }

            array_push($expenditureData, $monthly_row);

        }
        $paymentVoucher = $this->paymentVoucher();
        array_push($expenditureData, $paymentVoucher);
        return $expenditureData;
    }

    private function paymentVoucher()
    {
        $quartery = array_fill(0,4,0);
        $monthly = array_fill(0,12,0);
        $row = array('Payment voucher');

        $expenses = DB::table('payment_vouchers')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('expenditure_date', '=', $this->year)
        ->get();

        $monthly_expenses = $expenses
                            ->groupBy(function($date) {
                                return Carbon::parse($date->expenditure_date)->month;
                            });
        $quartery_expenses = $expenses
                            ->groupBy(function($date) {
                                return Carbon::parse($date->expenditure_date)->quarter;
                            });

        foreach($monthly_expenses as $month_expense){
            foreach($month_expense as $voucher){
                $day = Carbon::parse($voucher->expenditure_date)->month-1;
                $monthly[$day] = $monthly[$day] + $voucher->amount;
            }
        }

        foreach($quartery_expenses as $quarter_expense){
            foreach($quarter_expense as $quarter){
                $date = Carbon::parse($quarter->expenditure_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $quarter->amount;
            }
        }

        $monthly['q1'] = $quartery[0];
        $monthly['q2'] = $quartery[1];
        $monthly['q3'] = $quartery[2];
        $monthly['q4'] = $quartery[3];

        foreach($monthly as $m){
            array_push($row, $m);
        }

        return $row;
    }

    private function sundayBasket()
    {
        $quartery = array_fill(0,4,0);
        $monthly = array_fill(0,12,0);
        $row = array('Sunday basket');

        $baskets = DB::table('sunday_baskets')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('offering_date', '=', $this->year)
        ->get();

        $monthly_expenses = $baskets
                            ->groupBy(function($date) {
                                return Carbon::parse($date->offering_date)->month;
                            });
        $quartery_expenses = $baskets
                            ->groupBy(function($date) {
                                return Carbon::parse($date->offering_date)->quarter;
                            });

        foreach($monthly_expenses as $month_expense){
            foreach($month_expense as $offering){
                $day = Carbon::parse($offering->offering_date)->month-1;
                $monthly[$day] = $monthly[$day] + $offering->total_offerings;
            }
        }

        foreach($quartery_expenses as $quarter_expense){
            foreach($quarter_expense as $quarter){
                $date = Carbon::parse($quarter->offering_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $quarter->total_offerings;
            }
        }

        $monthly['q1'] = $quartery[0];
        $monthly['q2'] = $quartery[1];
        $monthly['q3'] = $quartery[2];
        $monthly['q4'] = $quartery[3];

        foreach($monthly as $m){
            array_push($row, $m);
        }

        return $row;
    }
}
