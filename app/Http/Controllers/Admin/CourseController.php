<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view courses')->only(['index']);
        $this->middleware('permission:create courses')->only(['create', 'store']);
        $this->middleware('permission:edit courses')->only(['edit', 'update']);
        $this->middleware('permission:delete courses')->only(['destroy']);
    }

    // List courses
    public function index()
    {
        $courses = Course::latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    // Create page (form)
    public function create()
    {
        return view('admin.courses.create');
    }

    // Store new course
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'fee'         => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
            'image_path'  => $path,
            'status'      => null,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added!');
    }

    // Edit page
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.create', compact('course'));
    }

    // Update course
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'fee'         => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $course = Course::findOrFail($id);

        $data = [
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($course->image_path && Storage::disk('public')->exists($course->image_path)) {
                Storage::disk('public')->delete($course->image_path);
            }
            $data['image_path'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated!');
    }

    // Delete course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image_path && Storage::disk('public')->exists($course->image_path)) {
            Storage::disk('public')->delete($course->image_path);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted!');
    }
}
