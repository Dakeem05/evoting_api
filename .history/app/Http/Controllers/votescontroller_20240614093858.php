<?php

namespace App\Http\Controllers;
use 
use Illuminate\Http\Request;

class votescontroller extends Controller
{
    public function votesRecordView()
    {
        $data = tblvote::all();
        return view('admin.voters.voter_record', ['page_name' => 'Voter Records','data' => $data]);
    }
    public function ViewAllVoterRecords($id)
    {
        $data = tblvote::where('id', '=', $id)->get(); // replace 'ome_id_value' with the actual ID value
        return view('admin.voters.viewAllVoter', ['page_name' => 'Voter Complete Details','data' => $data]);
    }
}
