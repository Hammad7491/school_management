<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view subjects')->only(['index']);
        $this->middleware('permission:create subjects')->only(['create', 'store']);
        $this->middleware('permission:edit subjects')->only(['edit', 'update']);
        $this->middleware('permission:delete subjects')->only(['destroy']);
    }

    public function index()
    {
        $subjects = Subject::latest()->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        Subject::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject added successfully!');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.create', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
