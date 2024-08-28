<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function book()
    {
        return view('bookings.book');
    }
}
