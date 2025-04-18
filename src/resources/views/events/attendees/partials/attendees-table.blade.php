<div class="table-responsive mt-3">
    <table class="table table-dark table-striped">
        <thead>
        <tr>
            <th>Użytkownik</th>
            <th>Liczba osób</th>
            <th>Wiadomość</th>
            <th>Status</th>
            <th>Data zgłoszenia</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        @forelse($attendees as $attendee)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $attendee->user->avatar }}" class="rounded-circle me-2" width="40" alt="{{ $attendee->user->name }}">
                        <div>
                            <div>{{ $attendee->user->name }}</div>
                            <small>{{ $attendee->user->email }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $attendee->attendees_count }}</td>
                <td>{{ $attendee->message ?? 'Brak wiadomości' }}</td>
                <td>
                    @if($attendee->status == 'pending')
                        <span class="badge bg-warning">Oczekujące</span>
                    @elseif($attendee->status == 'accepted')
                        <span class="badge bg-success">Zaakceptowane</span>
                    @elseif($attendee->status == 'rejected')
                        <span class="badge bg-danger">Odrzucone</span>
                    @endif
                </td>
                <td>{{ $attendee->created_at->format('d.m.Y H:i') }}</td>
                <td>
                    @if($attendee->status == 'pending')
                        <div class="btn-group" role="group">
                            <form action="{{ route('events.attendees.update', ['event' => $event, 'attendee' => $attendee]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-sm btn-success">Akceptuj</button>
                            </form>

                            <form action="{{ route('events.attendees.update', ['event' => $event, 'attendee' => $attendee]) }}" method="POST" class="ms-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-sm btn-danger">Odrzuć</button>
                            </form>
                        </div>
                    @endif

                    <form action="{{ route('events.attendees.destroy', ['event' => $event, 'attendee' => $attendee]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1">Usuń</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Brak zgłoszeń</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
