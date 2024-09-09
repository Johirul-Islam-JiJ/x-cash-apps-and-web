<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\TransactionCharge;
use App\Http\Controllers\Controller;

class ExchangeController extends Controller
{
  
    public function index()
    {
        $user = auth()->user();
        $pageTitle = "Exchange Money";
        $exchangeCharge = TransactionCharge::where('slug', 'exchange_charge')->first();
        return view($this->activeTemplate . 'user.currency_exchange.form', compact('pageTitle', 'exchangeCharge', 'user'));
    }

    public function create()
    {
        //  
    }


    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
