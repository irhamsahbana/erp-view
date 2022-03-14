<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends LaravelController
{
        /*
     * Default generator ref no PREFIX+XXXX
     *
     * @return String
     */
    public function generateRefNo($table, $digitLength = 4, $prefix = null, $postfix = null)
    {
        // $digitLength = 4;
        $refNo = null;
        // pattern for SQL where LIKE
        $pattern = sprintf('%s' . str_repeat('_', $digitLength) . '%s', $prefix, $postfix);

        $index = 1;
        $row = DB::table($table)
                ->orderBy('ref_no', 'desc')
                ->where('ref_no', 'like', $pattern)
                ->first();

        // Loop until get one unique ref no
        $refNo = null;
        while(!empty($row)) {
            // Increase XXXXX(index) by +1
            $formatted = str_replace($prefix, '', str_replace($postfix, '', $row->ref_no));
            $index = (int) $formatted;
            $index++;

            $refNo = sprintf("%s%s%s", $prefix, sprintf('%0' . $digitLength . 'd', $index), $postfix);

            // Verify that ref no is unique
            $row = DB::table($table)->where('ref_no', $refNo)->first();
        };

        // When ref no is empty then it means this date doesn't have any
        // ref no with YYMM-XXXXX format
        if(empty($refNo))
            $refNo = sprintf("%s%s%s", $prefix, sprintf('%0' . $digitLength . 'd', $index), $postfix);

        return $refNo;
    }
}