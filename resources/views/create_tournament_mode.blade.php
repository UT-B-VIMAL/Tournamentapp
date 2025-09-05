@extends('layouts.main')

@section('title', 'Tournament')

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
    .variable-section h6 {
      margin-bottom: 10px;
      font-weight: 600;
      color: #495057;
    }
    .subfields {
      padding: 12px;
      margin-top: 10px;
      border: 1px dashed #adb5bd;
      border-radius: 6px;
      background: #ffffff;
    }
    .subfields h6 {
      font-size: 14px;
      color: #0d6efd;
    }
    .remove-btn {
      margin-top: 30px;
    }
  </style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-4">Add Tournament Mode</h5>
      <form id="tournamentForm">
        
        <!-- Type Label -->
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Mode Label</label>
                <input type="text" class="form-control" placeholder="Enter name" id="modeLabel">
              </div>
              <div class="col-md-5">
                <label class="form-label">Tournament Type</label>
                <select class="form-select" id ="tournamentType">
                  <option value="">Select type</option>
                </select>
              </div>
        </div>
    
        <h5 class="fw-semibold mb-3 mt-4 variable-lable"></h5>
        <div id="variablesContainer"></div>
        
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
      </form>
    </div>
  </div>
@endsection


@section('scripts')
<script>
$(document).ready(function(){

// Variable section generator
function createVariable(){
  return `
    <div class="variable-section">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label">Name</label>
          <input type="text" class="form-control var-name" placeholder="Enter name">
        </div>
        <div class="col-md-4 var-value">
          <label class="form-label">Value</label>
          <input type="text" class="form-control var-value" placeholder="Enter Value">
        </div>
        <div class="col-md-3 simple-status">
          <label class="form-label d-block">Status</label>
          <div class="form-check form-switch">
            <input class="form-check-input var-status" type="checkbox" checked>
            <label class="form-check-label">Enabled</label>
          </div>
        </div>
      </div>
      <div class="subfields d-none mt-3">
        <div class="subfield-list"></div>
        <button type="button" class="btn btn-sm btn-success add-subfield mt-2">+ Add Subfield</button>
      </div>
    </div>`;
}

// Subfield row generator
function createSubfield(obj = {}) {
  let html = `<div class="row g-3 align-items-end mb-2 subfield-row">`;

  Object.keys(obj).forEach(key => {
    html += `
      <div class="col-md-4">
        <label class="form-label">${key}</label>
        <input type="text" class="form-control sub-field" 
               data-key="${key}" value="" placeholder="Enter value">
      </div>
    `;
  });

  // Status switch per subfield record
  html += `
    <div class="col-md-2">
      <label class="form-label d-block">Status</label>
      <div class="form-check form-switch">
        <input class="form-check-input subfield-status" type="checkbox" checked>
        <label class="form-check-label">Enabled</label>
      </div>
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-sm btn-danger remove-subfield">X</button>
    </div>
  </div>`;

  return html;
}

// Handle Add Subfield button
$(document).on('click', '.add-subfield', function(){
  let subList = $(this).siblings('.subfield-list');
  let firstRow = subList.find('.subfield-row').first();
  if(firstRow.length){
    let newRow = firstRow.clone();
    newRow.find('input[type=text]').val('');
    newRow.find('.subfield-status').prop('checked', true);
    subList.append(newRow);
  }
});

// Remove subfield
$(document).on('click', '.remove-subfield', function(){
  $(this).closest('.row').remove();
});

// Bind API Response
function bindApiResponse(data) {
  $('.variable-lable').empty();
  $('#variablesContainer').empty();
  $('.variable-lable').append('<h5 class="fw-semibold mb-3 mt-4">Variables</h5>');
  $.each(data, function(key, value) {
    let variableSection = $(createVariable());
    variableSection.find('.var-name').val(key);

    if (Array.isArray(value)) {
      // hide simple value & simple status
      variableSection.find('.subfields').removeClass('d-none');
      variableSection.find('.var-value').prop('hidden', true);
      variableSection.find('.simple-status').prop('hidden', true);

      let subList = variableSection.find('.subfield-list');
      value.forEach(item => {
        let subfield = $(createSubfield(item));
        subList.append(subfield);
      });
    }
    $('#variablesContainer').append(variableSection);
  });
}

// Tournament type change
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
            let parsed = JSON.parse(response.data);
            let variables = JSON.parse(parsed.variables);
            bindApiResponse(variables);
        }
    } catch (err) {
        console.error("Error fetching single tournament type:", err);
    }
});

// Form submit
$('#tournamentForm').submit(async function(e){
    e.preventDefault();

    let modeLabel = $('#modeLabel').val().trim();
    let tournamentType = $('#tournamentType').val();

    if (!modeLabel) {
        alert("Mode Label is required!");
        return;
    }
    if (!tournamentType) {
        alert("Please select a Tournament Type!");
        return;
    }

    let result = {};
    let valid = true;

    $('#variablesContainer .variable-section').each(function(){
        let key = $(this).find('.var-name').val().trim();
        let valueInput = $(this).find('.var-value input');
        let subfields = $(this).find('.subfield-list .row');

        if (!key) {
            alert("Variable name cannot be empty!");
            valid = false;
            return false;
        }

        if(subfields.length > 0){
            let arr = [];
            subfields.each(function(){
                let obj = {};
                let hasEmpty = false;
                $(this).find('.sub-field').each(function(){
                    let val = $(this).val().trim();
                    if(!val){
                        alert("All subfield values are required!");
                        hasEmpty = true;
                        return false;
                    }
                    obj[$(this).data('key')] = val;
                });
                if(hasEmpty){
                    valid = false;
                    return false;
                }
                obj["status"] = $(this).find('.subfield-status').is(':checked') ? 1 : 0;
                arr.push(obj);
            });
            result[key] = arr;
        } else {
            let val = valueInput.val().trim();
            if(!val){
                alert("Value is required for variable: " + key);
                valid = false;
                return false;
            }
            let status = $(this).find('.var-status').is(':checked') ? 1 : 0;
            result[key] = { status: status, value: val };
        }
    });

    if (!valid) return;

    let payload = {
        request_type: "add_tournament_mode",
        name: modeLabel,
        tournament_type: tournamentType,
        data: JSON.stringify(result)
    };

    console.log("Payload to send:", payload);

    try {
        const response = await apiRequest("", "POST", payload);

        if(response.code === 200){
            alert("Tournament mode added successfully!");
            $('#tournamentForm')[0].reset();
            $('#variablesContainer').empty();
            $('.variable-lable').empty();
        } else {
            alert("Error: " + (response.message || "Something went wrong"));
        }
    } catch (err) {
        console.error("Error submitting form:", err);
        alert("Failed to submit form!");
    }
});

});

// Load tournament types
async function tournamentTypes() {
    try {
        const response = await apiRequest("", "POST", { request_type: "get_tournament_type" });

        if (response.code === 200) {
            let tournaments = JSON.parse(response.data);
            let select = document.getElementById("tournamentType");
            select.innerHTML = '<option value="">Select type</option>';
            tournaments.forEach(t => {
                let option = document.createElement("option");
                option.value = t.id;
                option.textContent = t.name;
                select.appendChild(option);
            });
        }
    } catch (err) {
        console.error("Error fetching tournaments:", err);
    }
}

tournamentTypes();
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
