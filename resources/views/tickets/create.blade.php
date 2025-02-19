@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Create a Ticket</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>

<!-- Designation -->
                        <label>Designation:</label>
<select name="designation" class="form-control">
    @foreach($designations as $designation)
        <option value="{{ $designation->name }}">{{ $designation->name }}</option>
    @endforeach
</select>

                        
                        <!-- Contact Number Field -->
                        <div class="mb-3">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" value="+91" placeholder="+91XXXXXXXXXX" required>
                        </div>
                        
                        <!-- School Dropdown -->
                        <div class="mb-3">
                            <label for="school_id" class="form-label">Select School</label>
                            <select class="form-select" id="school_id" name="school_id" required>
                                <option value="" selected disabled>Select School</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
    <label for="assigned_by">Assigned By</label>
    <input type="text" class="form-control" id="assigned_by" name="assigned_by" value="{{ $loggedInUser->name }}" readonly>
</div>

<!-- <div class="form-group">
    <label for="assigned_to">Assigned To</label>
    <select class="form-control" id="assigned_to" name="assigned_to" required>
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div> -->

<label>Assigned To:</label>
<select name="assigned_to" class="form-control">
    @foreach($users as $u)
        <option value="{{ $u->id }}" 
            {{ $u->id == auth()->user()->id ? 'selected' : '' }}>
            {{ $u->name }}
        </option>
    @endforeach
</select>


                        
                        <!-- Category Dropdown -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Problem Field -->
                        <div class="mb-3">
                            <label for="problem" class="form-label">Describe the Problem</label>
                            <textarea class="form-control" id="problem" name="problem" rows="4" placeholder="Describe the issue in detail" required></textarea>
                        </div>
                        
                        <!-- Priority Dropdown -->
                        <div class="mb-3">
                            <label for="priority" class="form-label">Select Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="" selected disabled>Select Priority</option>
                                <option value="low">Low</option>
                                <option value="moderate">Moderate</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <!-- status change -->
                        
                        <label>Status:</label>
<select name="status" class="form-control">
    <option value="open">Open</option>
    <option value="closed">Closed</option>
</select>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection