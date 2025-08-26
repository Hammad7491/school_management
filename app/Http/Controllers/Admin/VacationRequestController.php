<?php
// app/Http/Controllers/Admin/VacationRequestController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VacationRequest;

class VacationRequestController extends Controller
{
    public function index() {
        $requests = VacationRequest::with('class')->latest()->get();
        return view('admin.vacations.index', compact('requests'));
    }

    public function updateStatus($id, $status) {
        $req = VacationRequest::findOrFail($id);
        $req->update(['status' => $status]);
        return back()->with('success', "Request {$status} successfully.");
    }
}
