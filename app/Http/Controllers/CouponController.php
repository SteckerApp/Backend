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
            $code = Coupon::whereCode($request->code)->firstOrFail();
            $today = Carbon::now();
            $authUser = $request->user();

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
                            $cart->total = ($cart->total - $code->amount);
                            $cart->discounted = [
                                'amount' =>  $code->amount,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  $code->amount,
                                'percentage' => null,
                                'total_before_discount' => $old_total,
                                'total_after_discount' => $cart->total,
                            ];
                            break;

                        case 'percentage':
                            $percentage_value = (($code->percentage / 100) * $cart->total);

                            $cart->total = ($cart->total - $percentage_value);
                            $cart->discounted = [
                                'amount' => $percentage_value,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  null,
                                'percentage' => $code->percentage,
                                'total_before_discount' => $old_total,
                                'total_after_discount' => $cart->total,
                            ];
                            break;

                        case 'both':
                            $percentage_value = (($code->percentage / 100) * $cart->total);
                            $total_discount = (($cart->total - $code->amount) - $percentage_value);

                            $cart->total = $total_discount;
                            $cart->discounted = [
                                'amount' => $total_discount,
                                'coupon_reference' =>  $code->id,
                                'flat' =>  $code->amount,
                                'percentage' => $code->percentage,
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
            $this->errorResponse($th->getMessage(), 500);
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
            $cart->total = $cart->discounted['total_before_discount'];

            $coupon_id = $cart->discounted['coupon_reference'];

            $cart->discounted = null;
            $cart->update();

            DB::table('coupon_transaction')
                ->where('id', $request->user()->id)
                ->where('coupon_id', $coupon_id)
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
        Cart::whereNotNull('discounted')->where("");
    }
}
