@extends('layouts.main')

@section('title', 'Tournament Mode')

@section('styles')
    <link rel="stylesheet" href="{{ asset('./assets/css/styles.min.css') }}" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Table Custom Look */
        #tournamentmodeTable {
            border-radius: 10px;
            overflow: hidden;
        }

        #tournamentmodeTable thead {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            color: #fff;
        }

        #tournamentmodeTable thead th {
            font-weight: 600;
            text-align: center;
            padding: 12px;
        }

        #tournamentmodeTable tbody tr {
            background: #f9f9ff;
        }

        #tournamentmodeTable tbody tr:nth-child(even) {
            background: #eef3ff;
        }

        #tournamentmodeTable tbody td {
            vertical-align: middle;
            text-align: center;
            padding: 10px;
        }

        #tournamentmodeTable tbody tr:hover {
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
                <h5 class="card-title fw-semibold mb-0">Tournament Modes</h5>

                <!-- Add Button (Top Right) -->
                <a href="{{ url('/tournamentMode') }}" class="btn add-btn">
                    <i class="fa-solid fa-plus"></i> Add Tournament Mode
                </a>
            </div>

            <div class="table-responsive">
                <table id="tournamentmodeTable" class="table nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Tournament Type</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tournamentBody">
                        <!-- Data will be loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewModalLabel">
                        <i class="fa-solid fa-eye"></i> Tournament Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <p><strong>Name:</strong> Summer Championship</p>
                    <p><strong>Type:</strong> Knockout</p>
                    <p><strong>Start Date:</strong> 25-Aug-2025</p>
                    <p><strong>Status:</strong> Active</p>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i> Close
                    </button>
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
            let table = $('#tournamentmodeTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
            });

         async function loadTournaments() {
        try {
            const response = await apiRequest("", "POST", { request_type: "get_tournament_mode" });

            if (response.code === 200) {
                let tournaments = JSON.parse(response.data);

                // clear old data
                table.clear();

                tournaments.forEach(t => {
                let dataPreview = t.data ? t.data.substring(0, 40) + (t.data.length > 40 ? "..." : "") : "";
                let dataTooltip = t.data
                ? $('<div/>').text(t.data).html().replace(/"/g, '&quot;').replace(/'/g, '&#39;')
                : "";

                    table.row.add([
                        t.id,
                        t.name,
                        t.tournament_type,
                        `<span title="${dataTooltip}">${dataPreview}</span>`,
                        `<div class="table-actions">
                            <i class="fa-solid fa-eye view-icon" title="View" data-bs-toggle="modal" data-id="${t.id}" data-bs-target="#viewModal"></i>
                            <a href="/edit-tournament-mode/${t.id}"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                            <i class="fa-solid fa-trash delete-icon" title="Delete"  data-id="${t.id}"></i>
                        </div>`
                    ]);
                });

                // redraw table with new data
                table.draw();
            }
        } catch (err) {
            console.error("Error fetching tournaments:", err);
        }
    }


            // Load tournaments on page load
            loadTournaments();
     $(document).on("click", ".delete-icon", function() {
                let id = $(this).data("id"); // Make sure you add data-id in your delete icon

                Swal.fire({
                    title: "Are you sure?",
                    text: "Want to delete this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await apiRequest("", "POST", {
                                request_type: "delete_tournament_mode",
                                id: id
                            });

                            if (response.code === 200) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your record has been deleted.",
                                    icon: "success"
                                });

                                // remove row from table
                                $(this).closest("tr").remove();
                            } else {
                                Swal.fire("Failed", response.message || "Something went wrong",
                                    "error");
                            }
                        } catch (error) {
                            console.error("Delete error:", error);
                            Swal.fire("Error", "Failed to delete record.", "error");
                        }
                    }
                });
            });
          
        });
        $(document).on("click", ".view-icon", async function() {
                let id = $(this).data("id");

                try {
                    // Fetch single tournament data
                    const response = await apiRequest("", "POST", {
                        request_type: "get_single_tournament_mode",
                        id: id
                    });

                    if (response.code === 200 && response.data) {
                        const t = JSON.parse(response.data);

                        // Fill the modal fields
                        $('#viewModalLabel').html(`<i class="fa-solid fa-eye"></i> ${t.name} Details`);
                        $('.modal-body').html(`
                        <p><strong>Name:</strong> ${t.name}</p>
                        <p><strong>Tournament Type</strong> ${t.tournament_type_name}</p>
                        <p><strong>Data:</strong> ${t.data}</p>
                    `);

                        // Show the modal
                        $('#viewModal').modal('show');
                    } else {
                        Swal.fire("Error", response.message || "Failed to fetch tournament", "error");
                    }
                } catch (error) {
                    console.error("Fetch single tournament error:", error);
                    Swal.fire("Error", "Something went wrong", "error");
                }
            });
    </script>
@endsection
