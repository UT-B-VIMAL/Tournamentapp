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
                            <th>Type Label</th>
                            <th>Variables</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Knockout</td>
                            <td>Players, Rounds</td>
                            <td>2025-08-22</td>
                            <td class="table-actions">
                                <i class="fa-solid fa-eye" title="View"></i>
                                <i class="fa-solid fa-pen-to-square" title="Edit"></i>
                                <i class="fa-solid fa-trash" title="Delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>League</td>
                            <td>Teams, Matches</td>
                            <td>2025-08-21</td>
                            <td class="table-actions">
                                <i class="fa-solid fa-eye" title="View"></i>
                                <i class="fa-solid fa-pen-to-square" title="Edit"></i>
                                <i class="fa-solid fa-trash" title="Delete"></i>
                            </td>
                        </tr>
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
        $(document).ready(function() {
            $('#tournamentmodeTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
            });

            // Example action handlers
            $(document).on("click", ".fa-eye", function() {
                alert("View clicked!");
            });
            $(document).on("click", ".fa-pen-to-square", function() {
                alert("Edit clicked!");
            });
            $(document).on("click", ".fa-trash", function() {
                if (confirm("Are you sure you want to delete this?")) {
                    alert("Deleted!");
                }
            });
        });
    </script>
@endsection


