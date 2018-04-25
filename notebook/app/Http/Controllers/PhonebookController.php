<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhoneRecord as PhoneRecord;

class PhonebookController extends Controller
{
    public function index(Request $request)
	{
	    $modifyRecId = $request->input('modifyRecId', null);
        $fio = $request->input('fio', null);
        $phonenum = $request->input('phonenum', null);

		$phoneRecs = PhoneRecord::all();
		return view('notebook', compact('phoneRecs', 'modifyRecId', 'fio', 'phonenum'));
	}

	public function addRecord(Request $request)
    {
        $fio = $request->input('fio', '');
        $phoneNumber = $request->input('phonenum', '');

        if (!empty($fio) && !empty($phoneNumber))
        {
            $phoneRecord = new PhoneRecord;
            $phoneRecord->fio = $fio;
            $phoneRecord->phonenum  = $phoneNumber;
            $phoneRecord->save();
        }

        return  redirect('/');
    }

    public function modifyRecord(Request $request)
    {
        $recId = $request->input('recId');
        $fio = $request->input('fio', '');
        $phoneNumber = $request->input('phonenum', '');

        if (!empty($fio) && !empty($phoneNumber))
        {
            $phoneRecord = PhoneRecord::find($recId);

            $phoneRecord->fio = $fio;
            $phoneRecord->phonenum  = $phoneNumber;
            $phoneRecord->save();
        }

        return  redirect('/');
    }

    public function deleteRecord(Request $request)
    {
        $recId = $request->input('recId');

        PhoneRecord::destroy($recId);

        return  redirect('/');
    }
}
