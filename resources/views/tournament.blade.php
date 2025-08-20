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
        <div class="mb-3">
          <label for="typeLabel" class="form-label">Type label</label>
          <input type="text" class="form-control" id="typeLabel" placeholder="Enter type label">
        </div>
        
        <h5 class="fw-semibold mb-3">Variables</h5>
        <div id="variablesContainer">
          <!-- Default variable section -->
          <div class="variable-section">
            <div class="row g-3 align-items-end">
              <div class="col-md-5">
                <label class="form-label">Name</label>
                <input type="text" class="form-control variable-name" placeholder="Enter name">
              </div>
              <div class="col-md-5">
                <label class="form-label">Type</label>
                <select class="form-select variable-type">
                  <option value="">Select type</option>
                  <option value="string">String</option>
                  <option value="number">Number</option>
                  <option value="boolean">Boolean</option>
                  <option value="array">Array</option>
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
          </div>
        </div>
        
        <button type="button" class="btn btn-success mt-3" id="addVariable">Add +</button>
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
            <input type="text" class="form-control variable-name" placeholder="Enter name">
          </div>
          <div class="col-md-5">
            <label class="form-label">Type</label>
            <select class="form-select variable-type">
              <option value="">Select type</option>
              <option value="string">String</option>
              <option value="number">Number</option>
              <option value="boolean">Boolean</option>
              <option value="array">Array</option>
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

  // Function to generate subfield
  function createSubfield(){
    return `
      <div class="row g-3 align-items-end mb-2">
        <div class="col-md-5">
          <label class="form-label">Sub Name</label>
          <input type="text" class="form-control" placeholder="Enter sub name">
        </div>
        <div class="col-md-5">
          <label class="form-label">Sub Type</label>
          <select class="form-select">
            <option value="">Select type</option>
            <option value="string">String</option>
            <option value="number">Number</option>
            <option value="boolean">Boolean</option>
            <option value="array">Array</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-sm btn-danger remove-subfield">Remove -</button>
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

  // Show subfields if type == array
  $(document).on('change', '.variable-type', function(){
    let section = $(this).closest('.variable-section');
    if($(this).val() === 'array'){
      section.find('.subfields').removeClass('d-none');
      // If no subfields, add one by default
      if(section.find('.subfield-list').children().length === 0){
        section.find('.subfield-list').append(createSubfield());
      }
    } else {
      section.find('.subfields').addClass('d-none');
      section.find('.subfield-list').empty();
    }
  });

  // Add subfield
  $(document).on('click', '.add-subfield', function(){
    $(this).siblings('.subfield-list').append(createSubfield());
  });

  // Remove subfield
  $(document).on('click', '.remove-subfield', function(){
    $(this).closest('.row').remove();
  });

});
</script>
@endsection