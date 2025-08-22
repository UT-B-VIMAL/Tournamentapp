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
      <h5 class="card-title fw-semibold mb-4">Add Tournament Type</h5>
      <form id="tournamentForm">
        
        <!-- Type Label -->
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Mode Label</label>
                <input type="text" class="form-control" placeholder="Enter name">
              </div>
              <div class="col-md-5">
                <label class="form-label">Tournament Type</label>
                <select class="form-select">
                  <option value="">Select type</option>
                  <option value="string">String</option>
                  <option value="number">Number</option>
                  <option value="boolean">Boolean</option>
                  <option value="array">Array</option>
                </select>
              </div>
        </div>
    
        <h5 class="fw-semibold mb-3 mt-4">Variables</h5>
        <div id="variablesContainer"></div>
        
        <button type="button" class="btn btn-success mt-3" id="addVariable">Add Variable +</button>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
      </form>
    </div>
  </div>
@endsection


@section('scripts')
<script>
$(document).ready(function(){

  // Function to generate variable section
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
          <button type="button" class="btn btn-sm btn-primary mt-2 add-subfield">Add Subfield +</button>
        </div>
      </div>`;
  }

  // Function to generate subfield
  function createSubfield(){
    return `
      <div class="row g-3 align-items-end mb-2">
        <div class="col-md-5">
          <label class="form-label">bet_mode</label>
          <input type="text" class="form-control sub-name" placeholder="Enter bet mode">
        </div>
        <div class="col-md-5">
          <label class="form-label">multiplier</label>
          <input type="text" class="form-control sub-value" placeholder="Enter multiplier">
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-sm btn-danger remove-subfield">X</button>
        </div>
      </div>`;
  }

  // Add new variable
  $('#addVariable').click(function(){
    $('#variablesContainer').append(createVariable());
  });

  // Remove variable
  $(document).on('click', '.remove-btn', function(){
    $(this).closest('.variable-section').remove();
  });

  // Add subfield
  $(document).on('click', '.add-subfield', function(){
    $(this).siblings('.subfield-list').append(createSubfield());
  });

  // Remove subfield
  $(document).on('click', '.remove-subfield', function(){
    $(this).closest('.row').remove();
  });

  // Bind API Response
  function bindApiResponse(data) {
    $('#variablesContainer').empty();

    $.each(data, function(key, value) {
      let variableSection = $(createVariable());
      variableSection.find('.var-name').val(key);

      if (Array.isArray(value)) {
        variableSection.find('.subfields').removeClass('d-none');
        variableSection.find('.var-value').prop('hidden', true).val(''); // disable value input
        let subList = variableSection.find('.subfield-list');

        value.forEach(item => {
          let subfield = $(createSubfield());
          subfield.find('.sub-name').val(item.bet_mode);
          subfield.find('.sub-value').val(item.multiplier);
          subList.append(subfield);
        });
      } else {
        variableSection.find('.var-value').val(value);
      }

      $('#variablesContainer').append(variableSection);
    });
  }

  // Example API Response
  let apiResponse = {
    "arrow_count": 5000,
    "Winner": [
      { "bet_mode": "direct_number", "multiplier": 80 },
      { "bet_mode": "house_ending", "multiplier": 9 },
      { "bet_mode": "odd_even", "multiplier": 1.9 },
      { "bet_mode": "range_bet", "multiplier": 4 },
      { "bet_mode": "hit_guss", "multiplier": 200 }
    ]
  };

  // Bind on load
  bindApiResponse(apiResponse);

  // Serialize form back to JSON
  $('#tournamentForm').submit(function(e){
    e.preventDefault();
    let result = {};

    $('#variablesContainer .variable-section').each(function(){
      let key = $(this).find('.var-name').val();
      let valueInput = $(this).find('.var-value');
      let subfields = $(this).find('.subfield-list .row');

      if(subfields.length > 0){
        let arr = [];
        subfields.each(function(){
          arr.push({
            bet_mode: $(this).find('.sub-name').val(),
            multiplier: $(this).find('.sub-value').val()
          });
        });
        result[key] = arr;
      } else {
        result[key] = valueInput.val();
      }
    });

    console.log("Serialized JSON:", result);
    alert("Check console for JSON output!");
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
