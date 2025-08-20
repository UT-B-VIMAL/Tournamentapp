@extends('layouts.main')

@section('title', 'Create Tournament')

@section('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Create Tournament</h5>
    <div class="card">
      <div class="card-body">
        <form>
           {{-- Name --}}
          <div class="mb-3">
            <label for="name" class="form-label">Tournament Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
          </div>

          {{-- Mode --}}
          <div class="mb-3">
            <label for="mode" class="form-label">Mode</label>
            <select name="mode" id="mode" class="form-select" required>
              <option value="">-- Select Mode --</option>
              <option value="online">Online</option>
              <option value="offline">Offline</option>
              <option value="hybrid">Hybrid</option>
            </select>
          </div>

          {{-- Entry Fee --}}
          <div class="mb-3">
            <label for="entry_fee" class="form-label">Entry Fee</label>
            <input type="number" name="entry_fee" class="form-control" id="entry_fee" min="0" required>
          </div>

          {{-- Register Start At --}}
          <div class="mb-3">
            <label for="register_start_at" class="form-label">Register Start At</label>
            <input type="date" name="register_start_at" class="form-control" id="register_start_at" required>
          </div>

          {{-- Register Close At --}}
          <div class="mb-3">
            <label for="register_close_at" class="form-label">Register Close At</label>
            <input type="date" name="register_close_at" class="form-control" id="register_close_at" required>
          </div>

          {{-- Result At --}}
          <div class="mb-3">
            <label for="result_at" class="form-label">Result At</label>
            <input type="date" name="result_at" class="form-control" id="result_at" required>
          </div>

          {{-- Status --}}
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
              <option value="0">Register Not Started</option>
              <option value="1">Register Started</option>
              <option value="2">Register Closed</option>
              <option value="3">Result Published</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  console.log("Create Tournament page loaded");
</script>
@endsection
