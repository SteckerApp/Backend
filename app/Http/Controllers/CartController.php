<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;

class CartController extends Controller
{
    use HandleResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        DB::beginTransaction();

        try {

            $reference = generateReference();

            $subscription = subscription::whereIdAndDefault($request->subscription_id, true)->first();
            
            if(!$subscription){
                return $this->errorResponse(null, 'Please add a valid main plan', 409);
            }

            $cart = Cart::create([
                'reference' => $reference,
                'user_id' => $request->user()->id,
                'subtotal' => $subscription->price,
                'total' => $subscription->price,
                'status' => Cart::STATUS[0]
            ]);

            $transaction = Transaction::create([
                'reference' => $reference,
                'subscription_id' => $request->subscription_id,
                'default' => true,
                'duration' => $subscription->type,
                'unit' => 1,
                'total' => $subscription->price
            ]);

            DB::commit();

            return $this->successResponse(
                ['reference' => $reference],
                'Transaction created successfully',
                201
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(null, 'Error processing request, try again', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show($reference)
    {
        $cart =  Cart::whereReference($reference)
        ->with(['transactions','transactions.subscription'])->first();

        $cart->info  =  $cart->transactions->where('default', 1); // 1 is the main subscription
        $cart->services  = $cart->transactions()->where('default', 0)->get(); // 0 is addon subscription
        return ($this->successResponse(
            $cart,
            'Cart transaction retrive successfully',
            200
        ));
    }


    public function create(Request $request)
    {
        $reference = generateReference();

        $cart = Cart::create([
            'reference' => $reference,
            'user_id' => $request->user()->id,
            'subtotal' => 0,
            'total' => 0,
            'status' => Cart::STATUS[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, $reference)
    {
        
        $subscription = subscription::whereId($request->subscription_id)->first();
        $unit = $request->unit ?? 1;

        DB::beginTransaction();

        try {

            if ($request->has('default') && $request->default == true) {
                Transaction::whereReferenceAndDefault($reference, true)
                    ->update([
                        'reference' => $reference,
                        'subscription_id' => $request->subscription_id,
                        'default' => true,
                        'duration' => $subscription->type,
                        'unit' => $unit,
                        'total' => $subscription->price * $unit
                    ]);
            } else {
                Transaction::updateOrCreate([
                    'reference' => $reference,
                    'subscription_id' => $request->subscription_id,
                ], [
                    'default' => false,
                    'duration' => $subscription->type,
                    'unit' => $unit,
                    'total' => $subscription->price * $unit
                ]);
            }

            $transaction = Transaction::whereReference($reference)->get();

            $cart = Cart::whereReference($reference)->with(['transactions','transactions.subscription'])->first();

            $total = $transaction->sum('total');
            $discount = $cart->discounted['amount'] ?? 0;

            $cart->update([
                'subtotal' => $transaction->sum('total'),
                'total' => ($total - $discount),
                'status' => Cart::STATUS[0]
            ]);

            DB::commit();

            $cart = $cart->refresh();

            $cart->info  =  $cart->transactions->where('default', 1); // 1 is the main subscription
            $cart->services  = $cart->transactions()->where('default', 0)->get(); // 0 is addon subscription

            return $this->successResponse($cart->load('transactions'), 'Cart updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(null, 'Error processing request, try again', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function removeItem(Transaction $transaction)
    {
        $transaction->delete();
        return $this->successResponse(null, 'Cart item successfully deleted');
    }


}
