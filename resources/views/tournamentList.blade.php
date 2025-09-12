@extends('layouts.main')

@section('title', 'Tournament List')

@section('styles')
<link rel="stylesheet" href="{{ asset('./assets/css/styles.min.css') }}" />
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    /* Table Custom Look */
    #tournamentTable {
        border-radius: 10px;
        overflow: hidden;
    }

    #tournamentTable thead {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        color: #fff;
    }

    #tournamentTable thead th {
        font-weight: 600;
        text-align: center;
        padding: 12px;
    }

    #tournamentTable tbody tr {
        background: #f9f9ff;
    }

    #tournamentTable tbody tr:nth-child(even) {
        background: #eef3ff;
    }

    #tournamentTable tbody td {
        vertical-align: middle;
        text-align: center;
        padding: 10px;
    }

    #tournamentTable tbody tr:hover {
        background: #d6e4ff;
        transition: 0.3s;
    }

    /* Action Icons */
    .table-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .table-actions i {
        cursor: pointer;
        font-size: 18px;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease-in-out;
    }

    .table-actions .fa-eye {
        color: #fff;
        background: #0dcaf0;
    }

    .table-actions .fa-eye:hover {
        background: #0bb7da;
        transform: scale(1.15);
    }

    .table-actions .fa-pen-to-square {
        color: #fff;
        background: #ffc107;
    }

    .table-actions .fa-pen-to-square:hover {
        background: #e0a800;
        transform: scale(1.15);
    }

    .table-actions .fa-trash {
        color: #fff;
        background: #dc3545;
    }

    .table-actions .fa-trash:hover {
        background: #bb2d3b;
        transform: scale(1.15);
    }

    /* Add Button */
    .add-btn {
        float: right;
        margin-bottom: 15px;
        background: linear-gradient(90deg, #28a745, #20c997);
        color: #fff;
        border: none;
        border-radius: 25px;
        padding: 8px 18px;
        font-weight: 600;
        transition: 0.3s ease-in-out;
    }

    .add-btn i {
        margin-right: 6px;
    }

    .add-btn:hover {
        background: linear-gradient(90deg, #218838, #198754);
        transform: scale(1.05);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title fw-semibold mb-0">Tournaments</h5>
            <a href="{{ url('/createTournament') }}" class="btn add-btn">
                <i class="fa-solid fa-plus"></i> Add Tournament
            </a>
        </div>

        <div class="table-responsive">
            <table id="tournamentTable" class="table nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Round</th>
                        <th>Tournament Mode</th>
                        <th>Entry Fee</th>
                        <th>Started At</th>
                        <th>Closed At</th>
                        <th>Result At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tournamentBody">
                    <!-- Fetched data will go here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Tournament Modal -->
<div class="modal fade" id="viewTournamentModal" tabindex="-1" aria-labelledby="viewTournamentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewTournamentModalLabel">Tournament Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Tournament details content will be injected dynamically -->
        <div id="tournamentDetails">
        <p><strong>ID:</strong> <span id="tournament_id"></span></p>
        <p><strong>Name:</strong> <span id="tournament_name"></span></p>
        <p><strong>Round:</strong> <span id="tournament_round"></span></p>
        <p><strong>Mode:</strong> <span id="tournament_mode"></span></p>
        <p><strong>Entry Fee:</strong> <span id="tournament_entry_fee"></span></p>
        <p><strong>Start Time:</strong> <span id="tournament_start_time"></span></p>
        <p><strong>End Time:</strong> <span id="tournament_end_time"></span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<!-- jQuery + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#tournamentTable').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
        });

        // ✅ Load tournaments list
        async function loadTournaments() {
            try {
                const response = await apiRequest("", "POST", { request_type: "get_tournament" });

                if (response.code === 200) {
                    let tournaments = JSON.parse(response.data);

                    // Clear old rows
                    table.clear();

                    // Add new rows
                    tournaments.forEach(t => {
                        table.row.add([
                            t.id,
                            t.tournament_name,
                            (t.round_name) ? t.round_name : "-",
                            t.tournament_mode,
                            t.entry_fee,
                            formatDate(t.start_at),
                            formatDate(t.closed_at),
                            formatDate(t.result_at),
                            mapStatus(t.status),
                            `
                            <i class="fa-solid fa-eye text-info" title="View"
                               onclick="viewTournament(${t.id})"
                               data-bs-toggle="modal" data-bs-target="#viewTournamentModal"></i>

                            <a href="{{ url('edit-tournament') }}/${t.id}">
                                <i class="fa-solid fa-pen-to-square text-warning" title="Edit"></i>
                            </a>
                            <i class="fa-solid fa-trash text-danger delete-btn" data-id="${t.id}"
                               style="cursor:pointer;" title="Delete"></i>
                            `
                        ]);
                    });

                    table.draw();
                }
            } catch (err) {
                console.error("Error fetching tournaments:", err);
            }
        }

        // ✅ Format Date
        function formatDate(dateString) {
            const date = new Date(dateString);
            let month = (date.getMonth() + 1).toString().padStart(2, "0");
            let day = date.getDate().toString().padStart(2, "0");
            let year = date.getFullYear();

            let hours = date.getHours();
            let minutes = date.getMinutes().toString().padStart(2, "0");
            let seconds = date.getSeconds().toString().padStart(2, "0");
            const ampm = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12;

            return `${month}-${day}-${year}, ${hours}:${minutes}:${seconds} ${ampm}`;
        }

        // ✅ Map Status
        function mapStatus(status) {
            switch (status) {
                case 0: return "Register Start";
                case 1: return "Register Close";
                case 3: return "Publish";
                default: return status;
            }
        }

        // ✅ Load tournaments on page load
        loadTournaments();

        // ✅ View Tournament Details
        window.viewTournament = async function (id) {
            try {
                const response = await apiRequest("", "POST", {
                    request_type: "get_single_tournament",
                    id: id
                });

                if (response.code === 200 && response.data) {
                    const t = JSON.parse(response.data); // 🔥 parse data

                    document.getElementById("tournament_id").textContent = t.id;
                    document.getElementById("tournament_name").textContent = t.name;
                    document.getElementById("tournament_round").textContent = t.round_name;
                    document.getElementById("tournament_mode").textContent = t.tournament_mode;
                    document.getElementById("tournament_entry_fee").textContent = t.entry_fee;
                    document.getElementById("tournament_start_time").textContent = formatDate(t.start_at);
                    document.getElementById("tournament_end_time").textContent = formatDate(t.closed_at);
                } else {
                    Swal.fire("Error!", "Failed to load tournament details.", "error");
                }
            } catch (error) {
                console.error("Error fetching tournament:", error);
                Swal.fire("Error!", "Something went wrong while fetching details.", "error");
            }
        }

        // ✅ Delete Tournament with SweetAlert
        $(document).on("click", ".delete-btn", async function () {
            const id = $(this).data("id");

            Swal.fire({
                title: "Are you sure?",
                text: "This tournament will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const payload = {
                        request_type: "delete_tournament",
                        id: id
                    };

                    try {
                        const response = await apiRequest("", "POST", payload);

                        if (response.code === 200) {
                            Swal.fire("Deleted!", "Tournament has been deleted.", "success");
                            loadTournaments(); // 🔄 reload table
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    } catch (error) {
                        Swal.fire("Error!", "Something went wrong while deleting.", "error");
                        console.error("Delete error:", error);
                    }
                }
            });
        });
    });
</script>


@endsection
