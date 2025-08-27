<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VacationRequest;

class VacationRequestController extends Controller
{
    public function index()
    {
        // eager-load class relation for the table
        $requests = VacationRequest::with('class')->latest()->get();

        return view('admin.vacations.index', compact('requests'));
    }

    public function updateStatus($id, $status)
    {
        // normalize casing and validate
        $status = ucfirst(strtolower($status)); // approved -> Approved, rejected -> Rejected
        if (! in_array($status, ['Approved', 'Rejected'], true)) {
            abort(422, 'Invalid status.');
        }

        $req = VacationRequest::findOrFail($id);
        $req->update(['status' => $status]);

        return back()->with('success', "Request {$status} successfully.");
    }
}
