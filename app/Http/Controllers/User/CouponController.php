<?php
namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $coupons = Coupon::with(['usages' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
            ->where(function($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->orderBy('expires_at', 'asc')
            ->get();

        return view('home.coupons', compact('coupons'));
    }
}
