<?php
// app/Http/Controllers/Admin/TransactionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repayment;

class TransactionController extends Controller
{
    public function index()
    {
        $payments = Repayment::with([
                'expectedSchedule.application.client'
            ])
            ->orderBy('created_at','desc')
            ->get();

        return view('admin.transaction', compact('payments'));
    }
}
