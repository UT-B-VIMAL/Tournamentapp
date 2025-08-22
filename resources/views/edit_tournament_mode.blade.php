@extends('layouts.main')

@section('title', 'Edit Tournament Mode')

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
  </style>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Edit Tournament Mode</h5>
    <form id="tournamentForm">
        <input type="hidden" id="modeId" value="{{ $id }}"> <!-- Pass edit ID -->

        <!-- Type Label -->
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Mode Label</label>
                <input type="text" class="form-control" id="modeLabel" placeholder="Enter name">
            </div>
            <div class="col-md-5">
                <label class="form-label">Tournament Type</label>
                <input type="text" class="form-control" id="tournamentTypeVal" placeholder="Enter name" readonly>
                <input type="text" class="form-control" id="tournamentType" placeholder="Enter name" hidden>
                <!-- <select class="form-select" id="tournamentType" disabled>
                  <option value="">Select type</option>
                </select> -->
            </div>
        </div>

        <h5 class="fw-semibold mb-3 mt-4 variable-lable"></h5>
        <div id="variablesContainer"></div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
  </div>
</div>
@endsection


@section('scripts')
<script>
$(document).ready(function(){

  // same createVariable and createSubfield functions as before ...
  function createVariable(){
    return `
      <div class="variable-section">
        <div class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label">Name</label>
            <input type="text" class="form-control var-name" placeholder="Enter name">
          </div>
          <div class="col-md-5 var-value" >
            <label class="form-label">Value</label>
            <input type="text" class="form-control var-value" placeholder="Enter Value">
          </div>
        </div>
        <div class="subfields d-none mt-3">
          <div class="subfield-list"></div>
        </div>
      </div>`;
  }

  // Function to generate subfield
// Function to generate subfield dynamically from an object
function createSubfield(obj = {}) {
  let html = `<div class="row g-3 align-items-end mb-2">`;

  // Loop through each key in the object
  Object.keys(obj).forEach(key => {
    html += `
      <div class="col-md-5">
        <label class="form-label">${key}</label>
        <input type="text" class="form-control sub-field" 
               data-key="${key}" value="" placeholder="Enter value">
      </div>
    `;
  });

  html += `
  </div>`;

  return html;
}
  function bindApiResponse(data) {
    $('.variable-lable').empty();
    $('#variablesContainer').empty();
    $('.variable-lable').append('<h5 class="fw-semibold mb-3 mt-4">Variables</h5>');
    $.each(data, function(key, value) {
      let variableSection = $(createVariable());
      variableSection.find('.var-name').val(key);

      if (Array.isArray(value)) {
        variableSection.find('.subfields').removeClass('d-none');
        variableSection.find('.var-value').prop('hidden', true).val('');
        let subList = variableSection.find('.subfield-list');
        value.forEach(item => {
          let subfield = $(createSubfield(item));
          // fill values back
          Object.keys(item).forEach(k => {
            subfield.find(`[data-key="${k}"]`).val(item[k]);
          });
          subList.append(subfield);
        });
      } else {
        variableSection.find('.var-value input').val(value);
      }
      $('#variablesContainer').append(variableSection);
    });
  }
async function tournamentTypes() {
    try {
        const response = await apiRequest("", "POST", { request_type: "get_tournament_type" });

        if (response.code === 200) {
            // Parse the JSON string in response.data
            let tournaments = JSON.parse(response.data);

            // Get the select element
            let select = document.getElementById("tournamentType");

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

  // Load tournament types
  tournamentTypes();

  // Fetch existing mode data for edit
  async function loadTournamentMode() {
    let id = $("#modeId").val();
    try {
      const response = await apiRequest("", "POST", {
        request_type: "get_single_tournament_mode",
        id: id
      });

      if (response.code === 200) {
        let parsed = JSON.parse(response.data);
        $("#modeLabel").val(parsed.name);
        $("#tournamentType").val(parsed.tournament_type);
        $("#tournamentTypeVal").val(parsed.tournament_type_name);

        let variables = JSON.parse(parsed.data);
        bindApiResponse(variables);
      }
    } catch (err) {
      console.error("Error fetching mode:", err);
    }
  }

  loadTournamentMode();
$('#tournamentType').on('change', async function () {
    let selectedId = $(this).val();
    if (!selectedId) {
        $('#variablesContainer').empty();
        $('.variable-lable').empty();
        return;
    }

    try {
        const response = await apiRequest("", "POST", {
            request_type: "get_single_tournament_type",
            id: selectedId
        });

        if (response.code === 200) {
            // First parse the wrapper object
            let parsed = JSON.parse(response.data);

            // Then parse variables string
            let variables = JSON.parse(parsed.variables);

            // Bind into form
            bindApiResponse(variables);
        }
    } catch (err) {
        console.error("Error fetching single tournament type:", err);
    }
});

  // Update submit
  $('#tournamentForm').submit(async function(e){
    e.preventDefault();

    let id = $("#modeId").val();
    let modeLabel = $("#modeLabel").val().trim();
    let tournamentType = $('#tournamentType').val();

    if (!modeLabel) return alert("Mode Label required");
    if (!tournamentType) return alert("Select Tournament Type");

    let result = {};
    $('#variablesContainer .variable-section').each(function(){
      let key = $(this).find('.var-name').val();
      let valueInput = $(this).find('.var-value input');
      let subfields = $(this).find('.subfield-list .row');

      if(subfields.length > 0){
        let arr = [];
        subfields.each(function(){
          let obj = {};
          $(this).find('.sub-field').each(function(){
            obj[$(this).data('key')] = $(this).val();
          });
          arr.push(obj);
        });
        result[key] = arr;
      } else {
        result[key] = valueInput.val();
      }
    });

    let payload = {
      request_type: "update_tournament_mode",
      id: id,
      name: modeLabel,
      tournament_type: tournamentType,
      data: JSON.stringify(result)
    };

    console.log("Update payload:", payload);

    try {
      const res = await apiRequest("", "POST", payload);
      if (res.code === 200) {
        alert("Tournament mode updated successfully!");
      } else {
        alert("Error: " + res.message);
      }
    } catch (err) {
      console.error("Error updating:", err);
    }
  });
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

