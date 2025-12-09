<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $accounts = Account::all();

        return Inertia::render('accounts/index', [
            'accounts' => $accounts
        ]);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return back()->with('success', 'Аккаунт успешно удален');
    }
}
