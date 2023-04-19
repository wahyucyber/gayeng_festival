<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function index()
    {
        return view("staff.ticket.index");
    }

    public function scan()
    {
        return view("staff.ticket.scan");
    }
}
