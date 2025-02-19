public function edit(User $user, Ticket $ticket)
{
    return $user->role === 'admin'; // Only admin can edit all fields
}

public function editProblem(User $user, Ticket $ticket)
{
    return true; // All members can edit the problem
}
