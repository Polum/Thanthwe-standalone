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
use App\Member;

class MemberTitheReport implements FromCollection, ShouldAutoSize, WithStrictNullComparison, WithEvents
{
    use Exportable;

    public function __construct(int $year){
        $this->year = $year;
    }

    public function collection()
    {
        $data = [];
        $memberTithe = [];
        array_push($data, array('Member name', 'Envelop No','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'));

        $tithe_id = OfferingType::where('offering_title', '=', 'tithe')->first()->id;

        $memberList = DB::table('offerings')->distinct()
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->where('offerings.offering_type_id', '=', $tithe_id)
        ->whereYear('transaction_date', '=', $this->year)
        ->get(['offerings.member_id']);

        foreach($memberList as $item){
            $member_id = $item->member_id;
            $member = Member::where('id', '=', $member_id)
            ->with(['offering' => function($query) use ($tithe_id){
                $query->select('member_id', 'offering_amount', 'offering_date')
                ->where('offerings.offering_type_id', '=', $tithe_id);
            }])
            ->select('id', 'first_name', 'last_name', 'envelop_no')
            ->get();

            foreach($member as $row){
                $details = array();
                array_push($details, $row->first_name.' '.$row->last_name);
                array_push($details, $row->envelop_no);

                $months = array_fill(0,12,0);
                foreach($row->offering as $offer){
                    $date = Carbon::parse($offer->offering_date)->month-1;
                    $months[$date] = $months[$date] + $offer->offering_amount;
                }

                foreach($months as $tithe_total){
                    array_push($details, $tithe_total);
                }
                array_push($data, $details);
            }
        }

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
