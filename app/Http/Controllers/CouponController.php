<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Coupon;

use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    use HandleResponse;

    public function verify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'reference' => 'required|exists:carts,reference'
        ]);

        DB::beginTransaction();
        try {
            //check the coupon
            $code = Coupon::whereCode($request->code)->first();
            $today = Carbon::now();
            $authUser = $request->user();

            if(!$code){
                return $this->errorResponse('Invalid Coupon code', 422);
            }
            //check coupon if is valid for a user or company
            if ($code && ($code->user_id != null ||  $code->company_id != null)) {
                if ($code->user_id != null &&  $code->user_id != $authUser->id) {
                    return $this->errorResponse('Coupon is not valid for this user', 422);
                } elseif ($code->company_id != null && !$authUser->companies()->whereId($code->company_id)->exists()) {
                    return $this->errorResponse('Coupon is not valid for this company', 422);
                }
            }

            $checkRecord = DB::table('coupon_transaction')
                ->where(function ($query) use ($authUser) {
                    return $query->where('user_id', $authUser->id)
                        ->orwhere('user_phone_number', $authUser->phone_number);
                })
                ->where('coupon_id', $code->id)->exists();

            if ($checkRecord) {
                return $this->errorResponse('Coupon code already used by you', 422);
            }

            if ($today->gte($code->start)  && ($today->lte($code->ends) || $code->ends == null)) {

                $cart = Cart::whereReference($request->reference)->first();
                if ($cart->promo_code == null) {
                    $old_total = $cart->total;

                    switch ($code->type) {
                        case 'flat':
                            $amount = ($code->amount > $code->cap)? $code->cap : $code->amount;
                            $cart->total = ($cart->total - $amount);
                            $cart->discounted = [
                                'amount' =>  $amount,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  $amount,
                                'percentage' => null,
                                'cap' => $code->cap,
                                'total_before_discount' => $old_total,
                                'total_after_discount' => $cart->total,
                            ];
                            break;

                        case 'percentage':
                            $percentage_value = (($code->percentage / 100) * $cart->total);
                            $amount = ($percentage_value > $code->cap)? $code->cap : $percentage_value;
                            $cart->total = ($cart->total - $amount);
                            $cart->discounted = [
                                'amount' => $amount,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  null,
                                'percentage' => $code->percentage,
                                'cap' => $code->cap,
                                'total_before_discount' => $old_total,
                                'total_after_discount' => $cart->total,
                            ];
                            break;

                        case 'both':
                            $percentage_value = (($code->percentage / 100) * $cart->total);
                            $total_value = $percentage_value + $code->amount;

                            $amount = ($total_value > $code->cap)? $code->cap : $total_value;

                            $total_discount = ($cart->total - $amount);

                            $cart->total = $total_discount;
                            $cart->discounted = [
                                'amount' => $amount,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  $code->amount,
                                'percentage' => $code->percentage,
                                'cap' => $code->cap,
                                'total_before_discount' => $old_total,
                                'total_after_discount' => $cart->total,
                            ];
                            break;
                    }

                    $cart->promo_code = $code->code;

                    $cart->update();
                    DB::table('coupon_transaction')->insert([
                        'user_id' => $authUser->id,
                        'user_phone_number' => $authUser->phone_number,
                        'coupon_id' => $code->id,
                        'transaction_id' => $cart->id
                    ]);
                    $cart = $cart->refresh();


                    DB::commit();
                    return $this->successResponse($cart, 'Coupon applied to cart successfully');
                } else {
                    return $this->errorResponse(null, 'Only one coupon code can be apply to a cart', 400);
                }
            } else {
                return $this->errorResponse(null, 'Coupon expired or not valid', 400);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Please Try again later', 500);
        }
    }


    public function remove(Request $request)
    {
        $this->validate($request, [
            'reference' => 'required|exists:carts,reference'
        ]);
        DB::beginTransaction();
        try {
            $cart = Cart::whereReference($request->reference)->first();

            $cart->promo_code = null;
            if (!$cart->discounted) {
                return $this->errorResponse(null, 'No coupon code is apply on this transaction');
            }
            $cart->total = $cart->discounted['total_before_discount'];

            $coupon_id = $cart->discounted['coupon_reference'];

            $cart->discounted = null;
            $cart->update();

            DB::table('coupon_transaction')
                ->where('user_id', $request->user()->id)
                ->where('coupon_id', $coupon_id)
                ->where('transaction_id', $cart->id)
                ->delete();

            DB::commit();

            return $this->successResponse(null, 'Coupon code remove from cart successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(null, $th->getMessage(), 500);
        }
    }


    public function index(Request $request)
    {
        $redemption = Cart::whereNotNull('discounted')->where("status", 'paid');
        $response = [
            'redemption_amount'  => $redemption->sum('discounted->amount'),
            'redemption_total' => $redemption->count(),
            'redemption_new' => $redemption->whereBetween('created_at', [
                Carbon::now()->subDays(30)->startOfDay()->toDateString(),
                Carbon::now()->addDay(1)->endOfDay()->toDateString()
            ])->count(),
            'coupons' => Coupon::limit(10)->get()
        ];

        return $this->successResponse($response);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'sometimes|exists:users,id',
            'company_id' => 'sometimes|exists:companies,id',
            'code' => 'required|string',
            'type' => 'required|string',
            'amount' => 'sometimes|string|nullable',
            'percentage' => 'sometimes|integer|nullable|max:100',
            'cap' => 'sometimes|string',
            'start' => 'required|date',
            'ends' => 'sometimes|date|nullable',
        ]);

        $request->merge([
            'created_by' => $request->user()->id
        ]);

        $created = Coupon::create($request->only([
            'created_by',
            'user_id',
            'company_id',
            'code',
            'type',
            'amount',
            'percentage',
            'cap',
            'start',
            'ends',
            'status',
        ]));

        return $this->successResponse($created);
    }

    
    public function update(Request $request, Coupon $coupon)
    {
        $this->validate($request, [
            'user_id' => 'sometimes|exists:users,id',
            'company_id' => 'sometimes|exists:companies,id',
            'code' => 'sometimes|string',
            'type' => 'sometimes|string',
            'amount' => 'sometimes|string',
            'percentage' => 'sometimes|integer|max:100',
            'cap' => 'sometimes|string',
            'start' => 'sometimes|date',
            'ends' => 'sometimes|date',
            'status' => 'sometimes|string',
        ]);

        $coupon->fill(
            $request->only([
                'user_id',
                'company_id',
                'code',
                'type',
                'amount',
                'percentage',
                'cap',
                'start',
                'ends',
                'status',
            ])
        );
        $coupon->update();
        return $this->successResponse($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return $this->successResponse($coupon);
    }

    public function show(Coupon $coupon)
    {
        return $this->successResponse($coupon);
    }

}
