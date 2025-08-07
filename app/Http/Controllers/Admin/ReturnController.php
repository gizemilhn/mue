<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Notifications\RefundRequestCompleted;
use App\Notifications\RefundRequestRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::query()->with(['user', 'order']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $returns = $query->latest()->paginate(10);

        return view('admin.returns.index', compact('returns'));
    }
    public function show($id)
    {
        $returnRequest = ReturnRequest::with('user', 'order')->findOrFail($id);
        return view('admin.returns.show', compact('returnRequest'));
    }
    public function approve(Request $request, $id)
    {
        $request->validate([
            'cargo_company' => 'required|string',
        ]);

        $return = ReturnRequest::findOrFail($id);

        $return->status = 'approved';
        $return->cargo_company = $request->cargo_company;
        $return->return_code = 'IADE-' . strtoupper(Str::random(6));
        $return->save();
        $user = $return->user;
        $user->notify(new RefundRequestCompleted($return));
        toastr()->closeButton()->success('İade talebi kabul edildi.');
        return redirect()->route('admin.returns.index');
    }
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'rejected';
        $returnRequest->rejection_reason = $request->rejection_reason;
        $returnRequest->save();

        $user = $returnRequest->user;
        $user->notify(new RefundRequestRejected($returnRequest));

        toastr()->closeButton()->success('İade talebi reddedildi.');
        return redirect()->route('admin.returns.index');
    }

    public function complete($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->status = 'completed';
        $return->save();

        toastr()->closeButton()->success('İade süreci tamamlandı.');
        return redirect()->route('admin.returns.index');
    }
}
