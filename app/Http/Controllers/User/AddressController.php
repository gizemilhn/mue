<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function userAddress()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('home.address', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'address_line' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'phone' => ['required', 'regex:/^5\d{9}$/'],
        ]);

        Address::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'district' => $request->district,
            'phone' => $request->phone,
        ]);

        return redirect()->route('user.address')->with('success', 'Adres eklendi.');
    }
    public function editAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('home.edit_address', compact('address'));
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'address_line' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'phone' => 'required|string',
        ]);

        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $address->update($request->only('title', 'address_line', 'city', 'district', 'phone'));

        return redirect()->route('user.address')->with('success', 'Adres güncellendi.');
    }

    public function destroyAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $address->delete();

        return redirect()->back()->with('success', 'Adres silindi.');
    }
}
