<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Front\Documents;
use App\Models\SysDeposit;

class DocumentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $documents = Documents::active()->get();
        
        $packets    = SysDeposit::active()->get();
        
        return view('cabinet.documents.show',  compact(['documents','packets']));
    }

}
