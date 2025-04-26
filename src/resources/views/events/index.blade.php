@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-white">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Zarządzanie uczestnikami: {{ $event->title }}</h2>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">Powrót do wydarzenia</a>
            </div>
        </div>

        <div class="card custom-card-bg text-white">
            <div class="card-header">
                <h4>Informacje o wydarzeniu</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tytuł:</strong> {{ $event->title }}</p>
                        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                        <p><strong>Miejsce:</strong> {{ $event->location_name }}</p>
                    </div>
                    <div class="col-md-6">
                        @php
                            $totalAttendees = $event->acceptedAttendees()->sum('attendees_count');
                            $availableSpots = max(0, $event->people_count - $totalAttendees);
                        @endphp
                        <p><strong>Limit miejsc:</strong> {{ $event->people_count }}</p>
                        <p><strong>Zajęte miejsca:</strong> {{ $totalAttendees }}</p>
                        <p><strong>Dostępne miejsca:</strong> {{ $availableSpots }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h4>Lista zgłoszeń ({{ $attendees->count() }})</h4>

            <div class="mt-3">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Wszystkie</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Oczekujące</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button" role="tab" aria-controls="accepted" aria-selected="false">Zaakceptowane</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Odrzucone</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        @include('events.attendees.partials.attendees-table', ['attendees' => $attendees])
                    </div>
                    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @include('events.attendees.partials.attendees-table', ['attendees' => $attendees->where('status', 'pending')])
                    </div>
                    <div class="tab-pane fade" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                        @include('events.attendees.partials.attendees-table', ['attendees' => $attendees->where('status', 'accepted')])
                    </div>
                    <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                        @include('events.attendees.partials.attendees-table', ['attendees' => $attendees->where('status', 'rejected')])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
