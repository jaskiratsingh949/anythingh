<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\School;
use App\Models\Category;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Designation;

class TicketController extends Controller
{


public function search(Request $request)
{
    $user = auth()->user(); // Get the logged-in user

    // Base query with necessary relationships
    $query = Ticket::with(['assignedToUser', 'assignedByUser', 'school', 'category']);

    // If the user is NOT an admin, filter tickets assigned to them
    if ($user->role !== 'admin') {
        $query->where('assigned_to', $user->id);
    }

    // Apply filters if provided
    if ($request->school_id) $query->where('school_id', $request->school_id);
    if ($request->category_id) $query->where('category_id', $request->category_id);
    if ($request->priority) $query->where('priority', $request->priority);
    if ($request->status) $query->where('status', $request->status);
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    // Get the results
    $tickets = $query->get();
    $schools = School::all();
    $categories = Category::all();

    return view('tickets.search', compact('tickets', 'schools', 'categories'));
}

    
    
    public function updateStatus($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = ($ticket->status == 'open') ? 'closed' : 'open';
        $ticket->save();
    
        return redirect()->route('tickets.search')->with('success', 'Status updated successfully.');
    }
    


    // Show the ticket creation form
    public function create()
    {
        $schools = School::all();
        $categories = Category::all();
        $users = User::all(); // Fetch all users for "Assigned To" dropdown
        $designations = Designation::all();
         $loggedInUser = auth()->user(); // Get the logged-in user
        return view('tickets.create', compact('schools', 'categories','users', 'loggedInUser','designations'));
    }
 

    // Store the ticket in the database
    public function store(Request $request)
    {

        
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'contact_no' => 'required|string|max:15',
        //     'school_id' => 'required|string',
        //     'category_id' => 'required|string',
        //     'problem' => 'required|string',
        //     'priority' => 'required|string',
        //     // 'assigned_to' => 'required|exists:users,id',
        //     'assigned_to' => 'nullable|exists:users,id', // Ensure assigned user exists
        //      'status' => 'required|in:open,closed'
        // ]);

        // $ticket = Ticket::create([
        //     'name' => $request->name,
        //     'contact_no' => $request->contact_no,
        //     'school_id' => $request->school_id,
        //     'category_id' => $request->category_id,
        //     'problem' => $request->problem,
        //     'priority' => $request->priority,
        //     'assigned_to' => $request->assigned_to,
        //     'assigned_by' => auth()->id(), // Automatically assign the logged-in user
        //     'status' => $request->status
        // ]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255', // Ensure correct validation
            'contact_no' => 'required|string|max:15',
            'school_id' => 'required|integer',
            'category_id' => 'required|integer',
            'problem' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:open,closed'
        ]);
    
        $ticket = Ticket::create([
            'name' => $request->name,
            'designation' => $request->designation, // Ensure designation is stored
            'contact_no' => $request->contact_no,
            'school_id' => $request->school_id,
            'category_id' => $request->category_id,
            'problem' => $request->problem,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => auth()->id(),
            'status' => $request->status
        ]);
    
      
        // Assign the ticket to a random ERP Team member
        // $assignedTo = User::where('role', 'ERP Team')->inRandomOrder()->first();

        // $ticket = Ticket::create(array_merge($validated, [
        //     'assigned_to' => $assignedTo->id,
        // ]));

       //  Send SMS notification to the user
        $this->sendSMS($request->contact_no, "Your ticket has been created successfully! Ticket ID: " . $ticket->id);

        return redirect()->route("tickets.search")->with('success', 'Ticket created successfully!');
      // return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }





    // public function edit($id)
    // {
    //     $ticket = Ticket::findOrFail($id);
    //     return view('tickets.edit', compact('ticket'));
    // }

    // public function edit($id)
    // {
    //     $ticket = Ticket::findOrFail($id);
    //     $user = auth()->user();
    
    //     // Fetch schools only if required
    //     $schools = School::all(); // Remove this if not needed in the view
    
    //     return view('tickets.edit', compact('ticket', 'schools', 'user'));
    // }

    public function edit($id)
{
    $ticket = Ticket::findOrFail($id);
    $user = auth()->user();

    // Fetch schools if required
    $schools = School::all();

    // Fetch users to assign tickets (if needed)
    $users = User::all();

    return view('tickets.edit', compact('ticket', 'schools', 'user', 'users'));
}

    
// public function edit(Ticket $ticket)
// {
//     $user = auth()->user();

//     if ($user->role === 'admin') {
//         $schools = School::all(); // Fetch schools
//         $categories = Category::all(); // Fetch categories if needed
//         return view('tickets.edit', compact('ticket', 'schools', 'categories'));
//     }

//     return view('tickets.edit_problem_only', compact('ticket'));
// }


    
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'problem' => 'required|string',
    //     ]);
    
    //     $ticket = Ticket::findOrFail($id);
    //     $ticket->problem = $request->problem;
    //     $ticket->save();
    
    //     return redirect()->route('tickets.search')->with('success', 'Problem updated successfully.');
    // }
    
    public function update(Request $request, Ticket $ticket)
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        // Admin can update everything
        $ticket->update($request->all());
    } else {
        // User can only update the problem field
        $ticket->update([
            'problem' => $request->problem
        ]);
    }

    return redirect()->route('tickets.search')->with('success', 'Ticket updated successfully');
}

//     public function updateProblem(Request $request, $id)
// {
//     $ticket = Ticket::findOrFail($id);
//     $ticket->problem = $request->problem;
//     $ticket->save();

//     return response()->json(['success' => true]);
// }

    // Method to send SMS using Twilio
    public function sendSMS($to, $message)
    {
        $sid = env('TWILIO_SID'); // Your Twilio SID
        $token = env('TWILIO_AUTH_TOKEN'); // Your Twilio Auth Token
        $twilio = new Client($sid, $token);

        $twilio->messages->create($to, [
            'from' => env('TWILIO_PHONE_NUMBER'), // Your Twilio phone number
            'body' => $message,
        ]);
    }
}