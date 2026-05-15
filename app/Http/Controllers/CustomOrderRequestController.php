<?php

namespace App\Http\Controllers;

use App\Models\CustomOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrderRequestController extends Controller
{
    public function show(CustomOrderRequest $request)
    {
        return view('user.custom-order.show', [
            'order' => $request
        ]);
    }
}