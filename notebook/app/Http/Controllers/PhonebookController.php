<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhoneRecord as PhoneRecord;

class PhonebookController extends Controller
{
    public function index()
	{
		$phoneRecs = PhoneRecord::all();
		
		return view('notebook', compact($phoneRecs));
	}
}
