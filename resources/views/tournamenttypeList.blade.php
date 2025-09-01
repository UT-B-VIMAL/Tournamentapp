@extends('layouts.main')

@section('title', 'Tournament Type')

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

        table#tournamentlistTable td:nth-child(3) pre {
            white-space: pre-wrap;
            word-break: break-word;
            overflow: visible;
            margin: 0;
            max-width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
        }


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
                <h5 class="card-title fw-semibold mb-0">Tournament Types</h5>

                <!-- Add Button (Top Right) -->
                <a href="{{ url('/tournament') }}" class="btn add-btn">
                    <i class="fa-solid fa-plus"></i> Add Tournament Type
                </a>
            </div>



            <div class="table-responsive">
                <table id="tournamentlistTable" class="table nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Variables</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

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
        $(document).ready(function() {
            async function loadTournamentTypes() {
                const tbody = document.querySelector("#tournamentlistTable tbody");

                tbody.innerHTML = `<tr><td colspan="5" class="text-center">Loading...</td></tr>`;

                try {
                    const response = await apiRequest("", "POST", {
                        request_type: "get_tournament_type"
                    });

                    if (response.code === 200 && response.data) {
                        const tournaments = JSON.parse(response.data); // parse outer array
                        tbody.innerHTML = "";

                        tournaments.forEach(t => {
                            let variables = "-";
                            if (t.variables) {
                                try {
                                    const varsObj = JSON.parse(t.variables); // parse inner JSON
                                    variables =
                                        `<pre class="mb-0">${JSON.stringify(varsObj, null, 2)}</pre>`; // pretty-print
                                } catch (e) {
                                    variables = `<pre class="mb-0">${t.variables}</pre>`; // fallback
                                }
                            }

                            let row = `
                    <tr>
                        <td>${t.id}</td>
                        <td>${t.name}</td>
                        <td>${variables}</td>
                        <td class="table-actions">
                            <i class="fa-solid fa-eye view-icon" title="View" data-bs-toggle="modal" data-id="${t.id}" data-bs-target="#viewModal"></i>
                            <i class="fa-solid fa-pen-to-square edit-icon" title="Edit" data-id="${t.id}"></i>
                            <i class="fa-solid fa-trash delete-icon" title="Delete" data-id="${t.id}"></i>
                        </td>
                    </tr>
                `;

                            tbody.insertAdjacentHTML("beforeend", row);
                        });

                        $('#tournamentlistTable').DataTable({
                            responsive: true,
                            pageLength: 5,
                            lengthMenu: [5, 10, 25, 50],
                            destroy: true 
                            // columnDefs: [{
                            //         targets: 2,
                            //         width: '50%'
                            //     } 
                            //]
                        });

                    } else {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted">No records found</td></tr>`;
                    }
                } catch (error) {
                    console.error("Error loading tournaments:", error);
                    tbody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger">Failed to fetch data</td></tr>`;
                }
            }



            // Load tournaments on page load
            loadTournamentTypes();

            $(document).on("click", ".edit-icon", function() {
                let id = $(this).data("id");
                window.location.href = `/edit-tournamenttype?id=${id}`;
            });

            $(document).on("click", ".view-icon", async function() {
                let id = $(this).data("id");

                try {
                    // Fetch single tournament data
                    const response = await apiRequest("", "POST", {
                        request_type: "get_single_tournament_type",
                        id: id
                    });

                    if (response.code === 200 && response.data) {
                        const t = JSON.parse(response.data);

                        // Fill the modal fields
                        $('#viewModalLabel').html(`<i class="fa-solid fa-eye"></i> ${t.name} Details`);
                        $('.modal-body').html(`
                        <p><strong>Name:</strong> ${t.name}</p>
                        <p><strong>Variables:</strong> <pre>${t.variables}</pre></p>
                        <p><strong>Status:</strong> ${t.status === 0 ? 'Inactive' : 'Active'}</p>
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
                                request_type: "delete_tournament_type",
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
    </script>
@endsection
