<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\TransactionCharge;
use App\Models\UserWithdrawMethod;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Agent;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $pageTitle = "Exchange Money";

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1)->where('method_code', '>=', 1000);
        })->with('method')->orderBy('name')->get();

        $withdrawMethod = WithdrawMethod::active()->get();

        $exchangeCharge = TransactionCharge::where('slug', 'exchange_charge')->first();
        $userMethods = UserWithdrawMethod::myWithdrawMethod()->with('withdrawMethod', 'currency')->get();
        return view($this->activeTemplate . 'user.currency_exchange.form', compact('pageTitle', 'withdrawMethod', 'gatewayCurrency', 'exchangeCharge', 'user', 'userMethods'));
    }

    public function exchangeInsert(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
            'withdraw_method_id' => 'required',
        ]);

        $user = userGuard()['user'];

        // Deposit process
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
            
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        // dd($gate);
        if (!$gate) {
            $notify[] = ['error', 'Invalid deposit gateway'];
            return back()->withNotify($notify);
        }
        $currency = Currency::where('currency_code', $request->currency)->firstOrFail();
        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $bonus = GeneralSetting::first();
        if ($user && $user->getReferBy && $user->getReferBy->username && $user->bonus_status == 0 && $bonus->min_amount_bonus <= $request->amount) {
            $data->bonus_user = $bonus->bonus_amount;
            $data->bonus_refer_by = $bonus->bonus_amount;
            $data->final_amo = $final_amo + $bonus->bonus_amount + $request->amount;
            $user->bonus_status = 1;
            $user->save();
        } else {
            $data->bonus_user = 0;
            $data->bonus_refer_by = 0;
            $data->final_amo = $final_amo;
        }
        $data->user_type = userGuard()['type'];
        $data->wallet_id = $request->wallet_id;
        $data->currency_id = $currency->id;
        $data->exchange = 1;

        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = 1;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->save();


        // return to_route(strtolower(userGuard()['type']) . 'currency.exchange.deposit.confirm');

        // Withdrawal process
        $withdrawMethod = WithdrawMethod::where('id', $request->withdraw_method_id)->where('status', 1)->first();
        if (!$withdrawMethod) {
            $notify[] = ['error', 'Invalid withdrawal method'];
            return back()->withNotify($notify);
        }


        $withdrawCharge = $withdrawMethod->fixed_charge + ($request->amount * $withdrawMethod->percent_charge / 100);
        $withdrawPayable = $request->amount - $withdrawCharge;

        if ($withdrawPayable < $withdrawMethod->min_limit || $withdrawPayable > $withdrawMethod->max_limit) {
            $notify[] = ['error', 'Please follow withdrawal limit'];
            return back()->withNotify($notify);
        }
        $withdraw = new Withdrawal();
        $withdraw->method_id = $withdrawMethod->id;
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $withdrawMethod->currency;
        $withdraw->exchange = 1;
        $withdraw->rate = $withdrawMethod->rate;
        $withdraw->charge = $withdrawCharge;
        $withdraw->final_amount = $withdrawPayable;
        $withdraw->after_charge = $withdrawPayable;
        $withdraw->trx = getTrx();
        $withdraw->status = 0;
        $withdraw->save();

        // dd($withdraw, $data);
        session()->put('Track', $data->trx);
        session()->put('wtrx', $withdraw->trx);
        return to_route(strtolower(userGuard()['type']) . '.currency.exchange.manual.confirm');
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }

        $data = Deposit::where('id', $id)->where('status', 0)->orderBy('id', 'DESC')->firstOrFail();

        if ($data->user_type == 'USER') {
            $user = User::findOrFail($data->user_id);
            Auth::login($user);
            logoutAnother('user');
        } elseif ($data->user_type == 'AGENT') {
            $user = Agent::findOrFail($data->user_id);
            Auth::guard('agent')->login($user);
            logoutAnother('agent');
        }

        session()->put('Track', $data->trx);
        return to_route(strtolower(userGuard()['type']) . '.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route(strtolower(userGuard()['type']) . '.deposit.manual.confirm');
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($deposit, $isManual = null)
    {
        if ($deposit->status == 0 || $deposit->status == 2) {
            $deposit->status = 1;
            $deposit->save();

            if ($deposit->user_type == 'USER') {
                $user = User::find($deposit->user_id);
                $userType = 'USER';
            } elseif ($deposit->user_type == 'AGENT') {
                $user = Agent::find($deposit->user_id);
                $userType = 'AGENT';
            }

            $user->balance += $deposit->amount;
            $user->save();
            if ($user && $user->getReferBy && $user->getReferBy->username) {
                $userReferWallet = Wallet::find($user->getReferBy->id);
                $userReferWallet->balance += $deposit->bonus_refer_by;
                $userReferWallet->save();
            }
            $transaction = new Transaction();
            $transaction->user_id = $user->id;

            $transaction->user_type = $deposit->user_type;
            $transaction->wallet_id = 0;
            $transaction->currency_id = $deposit->currency_id;
            $transaction->before_charge = $deposit->amount;

            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'Add money via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx = $deposit->trx;
            $transaction->remark = 'add_money';
            $transaction->save();

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_type = $userType;
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'Deposit successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amo, getCurrency($deposit->method_currency)),
                'amount' => showAmount($deposit->amount, $deposit->currency),
                'charge' => showAmount($deposit->charge, $deposit->currency),
                'currency' => $deposit->currency->currency_code,
                'rate' => showAmount($deposit->rate),
                'trx' => $deposit->trx,
                'post_balance' => showAmount($user->balance, $deposit->currency)
            ]);
        }
    }

    public function manualDepositConfirmolddd()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->firstOrFail();
        // dd($data, $withdraw);
        return view($this->activeTemplate . 'user.currency_exchange.preview', compact('pageTitle', 'withdraw'));

        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        if ($data->method_code > 999) {
            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view($this->activeTemplate . gatewayView('manual_confirm', true), compact('data', 'pageTitle', 'method', 'gateway'));
        }

        abort(404);
    }
    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->first();

        if (!$data && !$withdraw) {
            return to_route(gatewayRedirectUrl());
        }

        $pageTitle = 'Confirmation';

        if ($data && $data->method_code > 999) {
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
        }

        return view($this->activeTemplate . 'user.currency_exchange.preview', compact('data', 'withdraw', 'pageTitle', 'method', 'gateway'));
    }

    public function manualDepositUpdate(Request $request)
    {

        // dd($request->all());
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->first();
        $withdraw->status = 2;
        $withdraw->save();
        $userType = 'USER';
        $user = User::find($data->user_id);
        // if ($data->user_type == 'USER') {
        //     $user = User::find($data->user_id);
        //     $userType = 'USER';
        // } elseif ($data->user_type == 'AGENT') {
        //     $user = Agent::find($data->user_id);
        //     $userType = 'AGENT';
        // }

        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = 2; // pending
        $data->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_type = $userType;
        $adminNotification->user_id = $data->getUser->id;
        $adminNotification->title = 'Exchange from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->getUser, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amo),
            getCurrency($data->method_currency),
            'amount' => showAmount($data->amount, $data->convertedCurrency),
            'charge' => showAmount($data->charge, $data->convertedCurrency),
            'rate' => showAmount(1, $data->convertedCurrency),
            'trx' => $data->trx,
            'currency' => $data->convertedCurrency->currency_code,
        ]);

        $notify[] = ['success', 'Your exchange request has been taken'];
        return to_route(strtolower(userGuard()['type']) . '.deposit.history')->withNotify($notify);
    }
}
