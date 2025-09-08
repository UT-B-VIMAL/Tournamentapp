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
            
            </select>
          </div>

          {{-- Entry Fee --}}
          <div class="mb-3">
            <label for="entry_fee" class="form-label">Entry Fee</label>
            <input type="number" name="entry_fee" class="form-control" id="entry_fee" min="0" required>
          </div>

          {{-- Rounds --}}
<div class="mb-3">
  <label for="rounds" class="form-label">Rounds</label>
  <input type="number" name="rounds" class="form-control" id="rounds" min="1" value="1" required>
</div>

{{-- Dynamic Rounds Container --}}
<div id="roundsContainer"></div>


          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>

  document.addEventListener("DOMContentLoaded", function () {
    const roundsInput = document.getElementById("rounds");
    const roundsContainer = document.getElementById("roundsContainer");

    // Generate fields dynamically
    roundsInput.addEventListener("input", generateRounds);

    function generateRounds() {
        const count = parseInt(roundsInput.value) || 0;
        roundsContainer.innerHTML = ""; // clear old rounds

        for (let i = 1; i <= count; i++) {
            const roundFields = `
              <div class="card mb-3">
                <div class="card-body">
                  
                  <div class="mb-3">
                    <label class="form-label">Register Start At</label>
                    <input type="datetime-local" class="form-control" name="round_${i}_start" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Register Close At</label>
                    <input type="datetime-local" class="form-control" name="round_${i}_close" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Result At</label>
                    <input type="datetime-local" class="form-control" name="round_${i}_result" required>
                  </div>
                </div>
              </div>
            `;
            roundsContainer.insertAdjacentHTML("beforeend", roundFields);
        }
    }

    // Generate initial 1 round
    generateRounds();
});


document.getElementById("tournamentForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const roundsCount = parseInt(document.getElementById("rounds").value) || 0;
    let rounds = [];

    for (let i = 1; i <= roundsCount; i++) {
        rounds.push({
            round_number: i,
            start_at: formatDateTime(document.querySelector(`[name="round_${i}_start"]`).value),
            closed_at: formatDateTime(document.querySelector(`[name="round_${i}_close"]`).value),
            result_at: formatDateTime(document.querySelector(`[name="round_${i}_result"]`).value)
        });
    }

    const payload = {
        request_type: "add_tournament",
        name: document.getElementById("name").value,
        created_by: "172.31.4.234",
        game_name: "TeerShillong",
        operator: "rumblebets",
        tournament_mode: document.getElementById("mode").value,
        entry_fee: parseInt(document.getElementById("entry_fee").value),
        rounds: JSON.stringify(rounds)
    };

    try {
      console.log(payload);

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
