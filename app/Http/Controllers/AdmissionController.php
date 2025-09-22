<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name'   => 'required|string|max:255',
            'gender'         => 'required|in:Male,Female',
            'school_name'    => 'required|string|max:255',
            'class'          => 'required|string|max:100',
            'parent_name'    => 'required|string|max:255',
            'parent_contact' => 'required|string|max:20',
            'parent_email'   => 'required|email|max:255',
        ]);

        Admission::create($validated);

        return back()->with('success', 'Admission form submitted successfully! Our campus representative will contact you.');
    }
}
