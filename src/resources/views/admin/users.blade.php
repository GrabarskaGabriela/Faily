<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zarządzanie użytkownikami</title>
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
                    <h4 class="mb-0"><i class="fas fa-users me-2"></i>Zarządzanie użytkownikami</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Email</th>
                                <th>Rola</th>
                                <th>Status</th>
                                <th>Zgłoszenia</th>
                                <th>Akcje</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-purple">Administrator</span>
                                        @else
                                            <span class="badge bg-primary">Użytkownik</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status === 'banned')
                                            <span class="badge bg-danger">Zablokowany</span>
                                        @else
                                            <span class="badge bg-success">Aktywny</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->reports_count ?? 0 }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if ($user->status !== 'banned' && $user->role !== 'admin')
                                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Czy na pewno chcesz zablokować tego użytkownika?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @elseif ($user->status === 'banned')
                                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($user->role !== 'admin' && $user->status !== 'banned')
                                                <form action="{{ route('admin.users.make-admin', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Czy na pewno chcesz nadać uprawnienia administratora?')">
                                                        <i class="fas fa-user-shield"></i>
                                                    </button>
                                                </form>
                                            @elseif ($user->role === 'admin' && $user->id !== Auth::id())
                                                <form action="{{ route('admin.users.remove-admin', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Czy na pewno chcesz odebrać uprawnienia administratora?')">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
