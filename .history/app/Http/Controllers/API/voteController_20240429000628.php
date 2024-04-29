<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class voteController extends Controller
{
    public function vote_President(Request $request)
{
    $candidateID = $request->input('candidateID');
    $voterID = $request->input('voterID');

    $voteRecord = DB::table('tblvotes')->where('voterID', $voterID)->where('election_type', 'President')->first();
    if ($voteRecord) {
        return response()->json(['success' => false, 'message' => ' You have already Cast your vote for this type of Election'], 404);
    } else {

        tbl::create([
            'code' => $otp,
            'voterID' => $voterID,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Vote exists'], 201);
    }
    
}


public function vote_Governor(Request $request)
{
    $candidateID = $request->input('candidateID');
    $candidate = tblcandidate::where('office', 'Governor')->where('candidateID', $candidateID)->first();
    return response()->json($candidate, 201);
}
}
