<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Contact;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('q');

        $users = User::where(function ($query) use ($term) {
            $query->where('name', 'like', "%$term%")
                ->orWhere('surname', 'like', "%$term%");
        })->take(3)->get(['id', 'name', 'surname']);

        $products = Product::where(function ($query) use ($term) {
            $query->where('name', 'like', "%$term%")
                ->orWhere('category', 'like', "%$term%");
        })->take(3)->get(['id', 'name']);

        $orders = Order::where('id', 'like', "%$term%")->take(3)->get(['id', 'id']);

        $returns = ReturnRequest::where('return_code', 'like', "%$term%")->take(3)->get(['id', 'return_code']);

        $contacts = Contact::where(function ($query) use ($term) {
            $query->where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%");
        })->take(3)->get(['id', 'name', 'email']);

        return response()->json([
            'users' => $users,
            'products' => $products,
            'contacts' => $contacts,
            'orders' => $orders,
            'returns' => $returns,
        ]);
    }
}
