<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Notifications\RefundRequestReceived;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $existingRequest = ReturnRequest::where('order_id', $order->id)->first();

        if ($existingRequest) {
            return back()->with('error', 'Bu sipariş için daha önce bir iade talebinde bulundunuz.');
        }
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'photos' => 'required|array|max:3',
            'photos.*' => 'required|image|mimes:jpeg,png|max:2048'
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('returns/'.$order->id, 'public');
                $photoPaths[] = $path;
            }
        }

        $returnRequest=ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'photos' => $photoPaths,
            'status' => 'pending',
        ]);

        $user = auth()->user();
        $user->notify(new RefundRequestReceived($returnRequest));
        return back()->with('success', 'İade talebiniz alındı. En kısa sürede dönüş yapılacaktır.');
    }

}
