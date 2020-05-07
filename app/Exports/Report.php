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

class Report implements FromCollection, ShouldAutoSize, WithStrictNullComparison, WithEvents
{
    use Exportable;

    public function __construct(int $year){
        $this->year = $year;
    }

    public function collection()
    {

        $quartery = array_fill(0,4,0);
        $months = array_fill(0,12,0);
        $data = [];

        $tithe_id = OfferingType::where('offering_title', '=', 'tithe')->first()->id;

        $tithes = DB::table('offerings')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('transaction_date', '=', $this->year)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->transaction_date)->quarter;
        });

        $tithes_monthly = DB::table('offerings')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('transaction_date', '=', $this->year)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->transaction_date)->month;
        });

        foreach($tithes as $quarter){
            foreach($quarter as $tithe){
                $date = Carbon::parse($tithe->offering_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $tithe->offering_amount;
            }
        }

        foreach($tithes_monthly as $monthly){
            foreach($monthly as $month){
                $day = Carbon::parse($month->offering_date)->month-1;
                $months[$day] = $months[$day] + $month->offering_amount;
            }
        }

        array_push($data, array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Q1','Q2','Q3','Q4'));

        $months['q1'] = $quartery[0];
        $months['q2'] = $quartery[1];
        $months['q3'] = $quartery[2];
        $months['q4'] = $quartery[3];
        array_push($data, $months);

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
}
