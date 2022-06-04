<?php

namespace App\Http\Controllers;

use App\Mail\MyTestMail;
use App\Models\Journals;
use App\Models\Receivable;
use App\Models\SubJournal;
use App\Models\Voucher;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function index()
    {
    $voucer = Voucher::select("*")->get();
    $receivable = Receivable::select('*')->get();
    $subjournal = SubJournal::select('*')->get();
// $today = new \DateTime(Carbon::now());
    $today = Carbon::now()->format("Y-m-d");
// dd($today);
    $voucerIncome = 0;
    $voucerOut = 0;
    $totalIncome = 0;
    $totalOut = 0;

    // $voucerIncome = $voucer->where('created',$today)->where('type', 1)->sum('amount');
    // $voucerOut = $voucer->where('created', $today)->where('type', 2)->sum('amount');
    // $totalIncome =
    foreach($voucer as $voucer) {
        if( $voucer->created == $today) {
            if($voucer->type == 1) {
                $voucerIncome += $voucer->amount;
            }
            else if($voucer->type == 2) {
                $voucerOut += $voucer->amount;
            }
        }
        if($voucer->type == 1) {

            $totalIncome += $voucer->amount;

            }  else if ($voucer->type == 2) {
                $totalOut += $voucer->amount;
             }
    }
    $totalCash = $totalIncome - $totalOut;
    // dd($voucerIncome, $voucerOut, $totalCash);


    $emailDesteny = ["otnielp33@gmail.com"];


    DB::beginTransaction();
    try {
        foreach ($emailDesteny as $email) {
            Mail::to($email)->send(new \App\Mail\MyTestMail($voucerIncome, $voucerOut));

        }
        DB::commit();
    } catch (Error $e) {
        DB::rollBack();
        dd($e);}
        // self::voucer();
        return redirect()->back()->with('f-msg', 'Kelompok mata anggaran berhasil disimpan.');
    }

    public function voucer()
    {
        $voucer = Voucher::select("*");
        $subjournal = SubJournal::select('*');

        $today = Carbon::now();

        // $voucerIncome = 0;
        $voucerIncome = $voucer->where('created', $today)->where('type', 1)->sum('amount');
        $voucerOut = $voucer->where('created', $today)->where('type', 2)->sum('amount');
        $totalIncome =

        $datas = [
            "otnielpangkung@yahoo.co.id"
        ];

        $details = [
        'title' => 'Laporan Keuangan Besok ini',
        ];
        DB::beginTransaction();
        try {
            foreach ($datas as $data) {
                Mail::to($data)->send(new \App\Mail\MyTestMail($details));

            }
            DB::commit();
        } catch (Error $e) {
            DB::rollBack();
            dd($e);}

            return redirect()->back()->with('f-msg', 'Kelompok mata anggaran berhasil disimpan.');
        }
}
