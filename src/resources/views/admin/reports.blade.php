<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zgłoszenia użytkowników</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Menu Administratora</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Podsumowanie
                    </a>
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>Użytkownicy
                    </a>
                    <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="fas fa-flag me-2"></i>Zgłoszenia
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-flag me-2"></i>Zgłoszenia użytkowników</h4>
                </div>

                <div class="card-body">
                    @if($reports->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Brak oczekujących zgłoszeń.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Zgłaszający</th>
                                    <th>Zgłoszony</th>
                                    <th>Powód</th>
                                    <th>Data</th>
                                    <th>Akcje</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->reporter->name }}</td>
                                        <td>
                                            {{ $report->reportedUser->name }}
                                            <div class="small text-muted">
                                                Liczba zgłoszeń: {{ $report->reportedUser->reports_count ?? 0 }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ Str::limit($report->reason, 50) }}
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $report->id }}" class="small d-block">Pokaż więcej</a>
                                        </td>
                                        <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <form action="{{ route('admin.reports.approve', $report->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Czy na pewno chcesz zatwierdzić to zgłoszenie?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Czy na pewno chcesz odrzucić to zgłoszenie?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal dla pełnego powodu zgłoszenia -->
                                    <div class="modal fade" id="reasonModal{{ $report->id }}" tabindex="-1" aria-labelledby="reasonModalLabel{{ $report->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reasonModalLabel{{ $report->id }}">Powód zgłoszenia</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ $report->reason }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
