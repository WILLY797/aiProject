<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountActivationRequest;

class AccountActivationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'account_number' => 'nullable|string|max:50',
        ]);

        AccountActivationRequest::create($data);

        return back()->with('status', 'Activation request submitted. We’ll be in touch soon.');
    }
}
