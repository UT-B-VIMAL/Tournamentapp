@extends('layouts.main')

@section('title', 'Edit Tournament')

@section('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Edit Tournament</h5>
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
            <input type="datetime-local" name="register_start_at" class="form-control" id="start_date" required>
          </div>

          {{-- Register Close At --}}
          <div class="mb-3">
            <label for="register_close_at" class="form-label">Register Close At</label>
            <input type="datetime-local" name="register_close_at" class="form-control" id="closed_at" required>
          </div>

          {{-- Result At --}}
          <div class="mb-3">
            <label for="result_at" class="form-label">Result At</label>
            <input type="datetime-local" name="result_at" class="form-control" id="result_at" required>
          </div>

          {{-- Hidden ID for edit --}}
          <input type="hidden" id="tournament_id" name="tournament_id" value="">

          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script>
  async function tournamentModes() {
    try {
        const response = await apiRequest("", "POST", { request_type: "get_tournament_mode" });

        if (response.code === 200) {
            // Parse the JSON string in response.data
            let tournaments = JSON.parse(response.data);

            // Get the select element
            let select = document.getElementById("mode");

            // Clear old options except the first
            select.innerHTML = '<option value="">Select type</option>';

            // Loop and add options
            tournaments.forEach(t => {
                let option = document.createElement("option");
                option.value = t.id;          // you can use id as value
                option.textContent = t.name;  // show tournament name
                select.appendChild(option);
            });
        }
    } catch (err) {
        console.error("Error fetching tournaments:", err);
    }
}
tournamentModes();
document.addEventListener("DOMContentLoaded", async function() {
    try {
        const id = "{{ $id }}";
        console.log("ID:", id);

        // Fetch tournament data
        const response = await apiRequest("", "POST", {
            request_type: "get_single_tournament",
            id: id
        });

        if (response.code === 200 && response.data) {
            const tournament = JSON.parse(response.data);

            document.getElementById("tournament_id").value = tournament.id;
            document.getElementById("name").value = tournament.name;
            document.getElementById("entry_fee").value = tournament.entry_fee;
            document.getElementById("mode").value = tournament.tournament_mode;

            document.getElementById("start_date").value = formatDateForInput(tournament.start_at);
            document.getElementById("closed_at").value = formatDateForInput(tournament.closed_at);
            document.getElementById("result_at").value = formatDateForInput(tournament.result_at);
        }
    } catch (err) {
        console.error("Error fetching:", err);
    }
});

// 🟢 Handle Update Submit
document.getElementById("tournamentForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const payload = {
        request_type: "update_tournament",
        id: document.getElementById("tournament_id").value,
        name: document.getElementById("name").value,
        created_by: "172.31.4.234",
        game_name: "TeerShillong",
        operator: "rumblebets",
        tournament_mode: document.getElementById("mode").value,
        entry_fee: parseInt(document.getElementById("entry_fee").value),
        start_at: formatDateTime(document.getElementById("start_date").value),
        closed_at: formatDateTime(document.getElementById("closed_at").value),
        result_at: formatDateTime(document.getElementById("result_at").value),
    };

    console.log("Update Payload:", payload);

    try {
        const response = await apiRequest("", "POST", payload);
        if (response.code === 200) {
            alert("Tournament updated successfully!");
            window.location.href = "{{ url('tournamentlist') }}";
        } else {
            alert("Error: " + response.message);
        }
    } catch (err) {
        console.error("API Error:", err);
        alert("Failed to update tournament");
    }
});

// Format API UTC → datetime-local
function formatDateForInput(dateString) {
    const date = new Date(dateString);
    const local = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
    return local.toISOString().slice(0, 16);
}

// Format datetime-local → MySQL "YYYY-MM-DD HH:mm:ss"
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
