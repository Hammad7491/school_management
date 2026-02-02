<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view classes')->only(['index']);
        $this->middleware('permission:create classes')->only(['create', 'store']);
        $this->middleware('permission:edit classes')->only(['edit', 'update']);
        $this->middleware('permission:delete classes')->only(['destroy']);
    }

    public function index()
    {
        $classes = SchoolClass::latest()->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'fee'  => 'nullable|numeric',
        ]);

        SchoolClass::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'fee'     => $request->fee,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class Added!');
    }

    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('admin.classes.create', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'fee'  => 'nullable|numeric',
        ]);

        $class = SchoolClass::findOrFail($id);
        $class->update([
            'name' => $request->name,
            'fee'  => $request->fee,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class Updated!');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class Deleted!');
    }
}
