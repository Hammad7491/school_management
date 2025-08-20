@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px">
    <h2 style="margin:10px 0;">
        {{ isset($class) ? 'Edit Class' : 'Add New Class' }}
    </h2>

    @if(session('success'))
        <div style="padding:10px;background:#e8ffe8;border:1px solid #b2e5b2;margin-bottom:12px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="padding:10px;background:#ffe8e8;border:1px solid #e5b2b2;margin-bottom:12px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $isEdit = isset($class);
        $options = ['Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10'];
        $selected = old('name', $class->name ?? '');
    @endphp

    <form action="{{ $isEdit ? route('classes.update', $class->id) : route('classes.store') }}"
          method="POST"
          style="border:1px solid #ddd;padding:16px;border-radius:8px;">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Class dropdown --}}
        <div style="margin-bottom:10px;">
            <label for="name"><strong>Class</strong></label><br>
            <select id="name" name="name"
                    style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>
                <option value="">— Select Class —</option>
                @foreach($options as $opt)
                    <option value="{{ $opt }}" {{ $selected == $opt ? 'selected' : '' }}>
                        {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Fee --}}
        <div style="margin-bottom:16px;">
            <label for="fee"><strong>Fee</strong></label><br>
            <input id="fee" type="number" name="fee" step="0.01"
                   value="{{ old('fee', $class->fee ?? '') }}"
                   style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>

        <div style="display:flex;gap:8px;align-items:center;">
            <button type="submit"
                    style="padding:8px 16px;border:0;background:#1e88e5;color:#fff;border-radius:6px;cursor:pointer;">
                {{ $isEdit ? 'Update Class' : 'Add Class' }}
            </button>

            @if($isEdit)
                <a href="{{ route('classes.create') }}"
                   style="padding:8px 16px;border:1px solid #ccc;border-radius:6px;text-decoration:none;">
                    Cancel Edit
                </a>
            @endif

            <a href="{{ route('classes.index') }}"
               style="margin-left:auto;text-decoration:none;">
               View Classes List
            </a>
        </div>
    </form>
</div>
@endsection
