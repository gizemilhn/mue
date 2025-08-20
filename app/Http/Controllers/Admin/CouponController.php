<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index( Request $request )
    {   $query = Coupon::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('code', 'like', "%{$search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $coupons = $query->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.coupons.create',compact('users'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount_type' => 'required|in:percent,amount',
            'discount_value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
        ]);
        $validated['min_amount'] = $request->min_amount ?? 0;
        Coupon::create($validated);
        toastr()->closeButton()->success('Kupon başarıyla oluşturuldu.');
        return redirect()->route('admin.coupons.index');
    }

    public function edit(Coupon $coupon)
    {
        $users = User::all();
        return view('admin.coupons.edit', compact('coupon','users'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percent,amount',
            'discount_value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
        ]);
        $validated['min_amount'] = $request->min_amount ?? 0;
        $coupon->update($validated);
        toastr()->closeButton()->success('Kupon başarıyla güncellendi.');
        return redirect()->route('admin.coupons.index');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        toastr()->closeButton()->success('Kupon Başarıyla Silindi');
        return redirect()->back();
    }

}
