<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyerStoreRequest;
use App\Mail\BuyerMail;
use App\Models\Buyer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buyers = Buyer::all();
        return view('buyers.index', compact('buyers')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $buyer = new Buyer();
        return view('buyers.create', compact('buyer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerStoreRequest $request)
    {
        Buyer::create($request->validated());
        return redirect()->route('buyer.index')->with(['status'=>'Success', 'color' => 'green', 'message'=>'Email Send Sucessfully']);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buyer $buyer)
    {
        try {
            $buyer->delete();
            $result = ['status'=>'Success', 'color' => 'green','message'=>'Buyer Deleted Sucessfully'];
        } catch(Exception $e) {
            $result = ['status'=>'Success', 'color' => 'red','message'=>'Buyer cannot be delete'];
        } 
        return redirect()->route('buyer.index')->with($result);
    }

    public function completeSend(Request $request, Buyer $buyer) {        
        try {
            Mail::to($buyer->email)
                ->queue(new BuyerMail());
            $result = ['status' => 'success', 'color' => 'green', 'message' => 'Mail sent successfully'];
        } catch (\Exception $e) {
            $result = ['status' => 'success', 'color' => 'red', 'message' => $e->getMessage()];
        }

        return redirect()->route('buyer.index')->with($result);
    }
}
