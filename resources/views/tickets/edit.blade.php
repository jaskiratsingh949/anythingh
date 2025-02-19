<style>
    .form-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .form-container label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        display: block;
        width: 100%;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    select:disabled,
    input:disabled {
        background: #e9ecef;
        cursor: not-allowed;
    }
</style>

<div class="form-container">
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Name:</label>
        <input type="text" name="name" value="{{ $ticket->name }}" class="form-control" 
            @if($user->role !== 'admin') disabled @endif>

        <label>School:</label>
        <select name="school_id" class="form-control" @if($user->role !== 'admin') disabled @endif>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ $ticket->school_id == $school->id ? 'selected' : '' }}>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>

        <label>Problem:</label>
        <textarea name="problem" class="form-control">{{ $ticket->problem }}</textarea> <!-- Everyone can edit -->

        <label>Status:</label>
        <select name="status" class="form-control" @if($user->role !== 'admin') disabled @endif>
            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>

        @if($user->role == 'admin')  <!-- Show only for admin -->
        <label>Assigned By:</label>
        <select name="assigned_by" class="form-control">
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $ticket->assigned_by == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>

        <label>Assigned To:</label>
        <select name="assigned_to" class="form-control">
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $ticket->assigned_to == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
    @endif
</div>

        <button type="submit" class="btn-primary style="padding: 5px 10px; font-size: 14px; ">Update Ticket</button>
    </form>

    
