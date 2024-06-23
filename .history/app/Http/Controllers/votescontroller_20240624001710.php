<?php

namespace App\Http\Controllers;
use App\Models\tblvote;
use Illuminate\Http\Request;


class votescontroller extends Controller
{
    public function votesRecordView()
    {
        $data = tblvote::join('tblvoters', 'tblvoters.voterID', '=', 'tblvotes.voterID')
        ->join('tblcandidates', 'tblvotes.candidateID', '=', 'tblcandidates.candidateID')
       // ->select('tblvotes.*', 'tblvoters.*', 'tblcandidates.*') // Select all columns from all three tables
        //->get();
        ->get(['tblvotes.country_name', 'tblvoters.vot', 'tblcandidates.city_name']);

        return view('admin.votes.votes_record', ['page_name' => 'Vote(s) Records','data' => $data]);
    }
    public function ViewAllVotesRecords($id)
    {
    
    }
    
}
