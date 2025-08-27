@extends('layouts.app')

@section('content')
<div class="container" style="max-width:800px">
    <h3 class="mb-3">New Notification</h3>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.notifications.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required maxlength="200">
                </div>
                <div class="col-12">
                    <label class="form-label">Body *</label>
                    <textarea name="body" rows="6" class="form-control" required>{{ old('body') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}>
                        <span class="form-check-label">Publish now</span>
                    </label>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
