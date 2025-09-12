@extends('layouts.main')

@section('title', 'Edit Tournament Type')

@section('styles')
    <link rel="stylesheet" href="{{ asset('./assets/css/styles.min.css') }}" />
    <style>
        .variable-section {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }

        .subfields {
            padding: 12px;
            margin-top: 10px;
            border: 1px dashed #adb5bd;
            border-radius: 6px;
            background: #ffffff;
        }

        .remove-btn {
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Edit Tournament Type</h5>
            <form id="tournamentForm">
                <input type="hidden" id="tournamentId">

                <!-- Type Label -->
                <div class="mb-3">
                    <label for="typeLabel" class="form-label">Type</label>
                    <input type="text" class="form-control" id="typeLabel" placeholder="Enter type label">
                </div>

                <h5 class="fw-semibold mb-3">Variables</h5>
                <div id="variablesContainer"></div>

                <button type="button" class="btn btn-success mt-3" id="addVariable">Add +</button>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // -----------------------
            // Helper functions
            // -----------------------
            function createVariable(name = "", type = "") {
                return `
      <div class="variable-section">
        <div class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label">Name</label>
            <input type="text" class="form-control variable-name" value="${name}" placeholder="Enter name">
          </div>
          <div class="col-md-5">
            <label class="form-label">Type</label>
            <select class="form-select variable-type">
              <option value="">Select type</option>
              <option value="string" ${type==="string"?"selected":""}>String</option>
              <option value="number" ${type==="number"?"selected":""}>Number</option>
              <option value="boolean" ${type==="boolean"?"selected":""}>Boolean</option>
              <option value="array" ${type==="array"?"selected":""}>Array</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-btn">Remove -</button>
          </div>
        </div>
        <div class="subfields d-none mt-3">
          <div class="subfield-list"></div>
          <button type="button" class="btn btn-sm btn-primary mt-2 add-subfield">Add +</button>
        </div>
      </div>`;
            }

            function createSubfield(name = "", type = "") {
                return `
      <div class="row g-3 align-items-end mb-2">
        <div class="col-md-5">
          <label class="form-label">Sub Name</label>
          <input type="text" class="form-control" value="${name}" placeholder="Enter sub name">
        </div>
        <div class="col-md-5">
          <label class="form-label">Sub Type</label>
          <select class="form-select">
            <option value="">Select type</option>
            <option value="string" ${type==="string"?"selected":""}>String</option>
            <option value="number" ${type==="number"?"selected":""}>Number</option>
            <option value="boolean" ${type==="boolean"?"selected":""}>Boolean</option>
            <option value="array" ${type==="array"?"selected":""}>Array</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-sm btn-danger remove-subfield">Remove -</button>
        </div>
      </div>`;
            }

            // -----------------------
            // Dynamic actions
            // -----------------------
            $('#addVariable').click(function() {
                $('#variablesContainer').append(createVariable());
            });

            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.variable-section').remove();
            });

            $(document).on('change', '.variable-type', function() {
                let section = $(this).closest('.variable-section');
                if ($(this).val() === 'array') {
                    section.find('.subfields').removeClass('d-none');
                    if (section.find('.subfield-list').children().length === 0) {
                        section.find('.subfield-list').append(createSubfield());
                    }
                } else {
                    section.find('.subfields').addClass('d-none').find('.subfield-list').empty();
                }
            });

            $(document).on('click', '.add-subfield', function() {
                $(this).siblings('.subfield-list').append(createSubfield());
            });

            $(document).on('click', '.remove-subfield', function() {
                $(this).closest('.row').remove();
            });

            // -----------------------
            // Load data for editing
            // -----------------------
            async function loadTournamentType(id) {
                try {
                    let response = await apiRequest("", "POST", {
                        request_type: "get_single_tournament_type",
                        id: id
                    });

                    if (response.code === 200 && response.data) {
                        let data = JSON.parse(response.data);
                        $('#tournamentId').val(data.id);
                        $('#typeLabel').val(data.name);

                        let variables = JSON.parse(data.variables);
                        $('#variablesContainer').empty();

                        Object.entries(variables).forEach(([key, val]) => {
                            if (Array.isArray(val)) {
                                // it's an array type
                                let section = $(createVariable(key, "array"));
                                let list = section.find('.subfield-list');

                                // Each array element could have multiple subfields
                                val.forEach(obj => {
                                    Object.entries(obj).forEach(([subName, subType]) => {
                                        list.append(createSubfield(subName, subType));
                                    });
                                });

                                section.find('.subfields').removeClass('d-none');
                                $('#variablesContainer').append(section);
                            } else {
                                $('#variablesContainer').append(createVariable(key, val));
                            }
                        });
                    }
                } catch (err) {
                    console.error("Error fetching tournament:", err);
                }
            }

            // -----------------------
            // Submit Update
            // -----------------------
            $('#tournamentForm').on('submit', async function(e) {
                e.preventDefault();

                let id = $('#tournamentId').val();
                let typeLabel = $('#typeLabel').val().trim();
                if (!typeLabel) {
                    alert("Please enter a tournament type name");
                    return;
                }

                let variables = {};
                $('#variablesContainer .variable-section').each(function() {
                    let varName = $(this).find('.variable-name').val().trim();
                    let varType = $(this).find('.variable-type').val();
                    if (!varName || !varType) return;

                    if (varType === 'array') {
                        let obj = {};
                        $(this).find('.subfield-list .row').each(function() {
                            let subName = $(this).find('input').val().trim();
                            let subType = $(this).find('select').val();
                            if (subName && subType) {
                                obj[subName] = subType;
                            }
                        });
                        variables[varName] = [obj]; // wrap inside array
                    } else {
                        variables[varName] = varType;
                    }

                });

                let payload = {
                    request_type: "update_tournament_type",
                    id: id,
                    name: typeLabel,
                    variables: JSON.stringify(variables)
                };

                try {
                    let response = await apiRequest("", "POST", payload);
                    if (response.code === 200) {
                        alert("Tournament type updated successfully!");
                        window.location.href = "{{ url('/tournamenttypelist') }}";
                    } else {
                        alert("Failed: " + response.message);
                    }
                } catch (err) {
                    console.error(err);
                    alert("Error updating tournament");
                }
            });

            // -----------------------
            // Get ID from URL and load data
            // -----------------------
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            if (id) {
                loadTournamentType(id);
            }

        });
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
