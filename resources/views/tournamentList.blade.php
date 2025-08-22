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
                            <th>Type Label</th>
                            <th>Variables</th>
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

            async function loadTournaments() {
                
                const tbody = document.getElementById("tournamentBody");
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">Loading...</td></tr>`;

                try {
                   
                    const response = await apiRequest("", "POST", { request_type: "get_tournament_type" });

                    if (response.code === 200) {
                        let tournaments = JSON.parse(response.data);
                        tbody.innerHTML = "";

                        tournaments.forEach(t => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${t.id}</td>
                                    <td>${t.name}</td>
                                    <td><pre class="mb-0">${t.variables || '-'}</pre></td>
                                    <td>${t.status == 0 ? 'Inactive' : 'Active'}</td>
                                    <td class="table-actions">
                                        <i class="fa-solid fa-eye text-primary" title="View"></i>
                                        <i class="fa-solid fa-pen-to-square text-warning" title="Edit"></i>
                                        <i class="fa-solid fa-trash text-danger" title="Delete"></i>
                                    </td>
                                </tr>
                            `;
                        });

                        // Refresh DataTable
                        table.clear().destroy();
                        $('#tournamentTable').DataTable({
                            responsive: true,
                            pageLength: 5,
                            lengthMenu: [5, 10, 25, 50],
                        });
                    }
                } catch (err) {
                    console.error("Error fetching tournaments:", err);
                    tbody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger">Failed to load data</td></tr>`;
                }
            }

            // Load tournaments on page load
            loadTournaments();

            // Action handlers
            $(document).on("click", ".fa-eye", () => alert("View clicked!"));
            $(document).on("click", ".fa-pen-to-square", () => alert("Edit clicked!"));
            $(document).on("click", ".fa-trash", () => {
                if (confirm("Are you sure you want to delete this?")) {
                    alert("Deleted!");
                }
            });
        });
    </script>
@endsection
