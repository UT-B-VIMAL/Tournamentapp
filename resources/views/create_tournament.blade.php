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
        <form id="tournamentForm">
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
              <option value="1">Online</option>
              <option value="2">Offline</option>
              <option value="3">Hybrid</option>
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
            <input type="datetime-local" name="register_start_at" class="form-control" id="register_start_at" required>
          </div>

          {{-- Register Close At --}}
          <div class="mb-3">
            <label for="register_close_at" class="form-label">Register Close At</label>
            <input type="datetime-local" name="register_close_at" class="form-control" id="register_close_at" required>
          </div>

          {{-- Result At --}}
          <div class="mb-3">
            <label for="result_at" class="form-label">Result At</label>
            <input type="datetime-local" name="result_at" class="form-control" id="result_at" required>
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
document.getElementById("tournamentForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    alert("Submitting form...");

    const payload = {
        request_type: "add_tournament",
        name: document.getElementById("name").value,
        tournament_mode: document.getElementById("mode").value,
        entry_fee: parseInt(document.getElementById("entry_fee").value),
        start_at: formatDateTime(document.getElementById("register_start_at").value),
        closed_at: formatDateTime(document.getElementById("register_close_at").value),
        result_at: formatDateTime(document.getElementById("result_at").value),
    };

    try {
        const response = await apiRequest("", "POST", payload);
        if (response.code === 200) {
            alert("Tournament created successfully!");
            window.location.href = "{{ url('tournamentlist') }}";
        } else {
            alert("Error: " + response.message);
        }
    } catch (err) {
        console.error("API Error:", err);
        alert("Failed to save tournament");
    }
});

// Format datetime-local → "YYYY-MM-DD HH:mm:ss"
function formatDateTime(datetimeValue) {
    const date = new Date(datetimeValue);
    const year = date.getFullYear();
    const month = String(date.getMonth()+1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}
</script>
@endsection

@section('back')
<div class="navbar-nav mb-3">
    <div class="nav-item d-flex">
        <a class="btn btn-light d-flex align-items-center shadow-sm px-3 rounded-pill" href="{{ url()->previous() }}">
            &#10094; Back
        </a>
    </div>
</div>
@endsection
