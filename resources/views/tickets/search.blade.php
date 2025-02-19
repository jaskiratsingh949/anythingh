@extends('layouts.app')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Search Tickets</h2>
    <form method="GET" action="{{ route('tickets.search') }}" class="row g-3">
        <div class="col-md-2">
            <label for="school_id" class="form-label">School</label>
            <select name="school_id" class="form-select">
                <option value="">All</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="priority" class="form-label">Priority</label>
            <select name="priority" class="form-select">
                <option value="">All</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <div class="col-md-2">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <hr>

    <h3 class="mt-4">Tickets</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>School</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Assigned To</th>
                <th>Assigned By</th>
                <th>Status</th>
                <th>Problem</th>  {{-- Add this --}}
                <th>Action</th>
            </tr>
        </thead>
      
        <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->name }}</td>
            <td>{{ $ticket->school->name ?? 'N/A' }}</td>
            <td>{{ $ticket->category->name ?? 'N/A' }}</td>
            <td>{{ ucfirst($ticket->priority) }}</td>
            <td>{{ $ticket->assignedToUser->name ?? 'N/A' }}</td>
            <td>{{ $ticket->assignedByUser->name ?? 'N/A' }}</td>
            <td>
                <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </td>
            <td>{{ $ticket->problem }}</td>  {{-- ✅ Show Problem --}}
            <td>
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form method="POST" action="{{ route('tickets.updateStatus', $ticket->id) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-warning">
                        {{ $ticket->status == 'open' ? 'Mark as Closed' : 'Reopen' }}
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

    </table>

</div>
<script>
    setTimeout(function() {
        $(".alert").fadeOut("slow");
    }, 3000);
</script>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
