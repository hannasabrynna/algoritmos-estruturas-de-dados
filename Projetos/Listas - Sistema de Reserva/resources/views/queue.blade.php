

<!DOCTYPE html>
<html>
<head>
    <title>Fila Virtual - Parque</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h1 class="mb-4">Sistema de Fila Virtual</h1>

    {{-- Entrar na fila --}}
    <div class="card mb-4">
        <div class="card-header">1. Entrar na fila</div>
        <div class="card-body">
            <form method="POST" action="{{ route('queue.enter') }}">
                @csrf
                <div class="mb-3">
                    <label for="visitor_id" class="form-label">ID do Visitante</label>
                    <input type="number" name="visitor_id" id="visitor_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="attraction_id" class="form-label">ID da Atração</label>
                    <input type="number" name="attraction_id" id="attraction_id" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar na Fila</button>
            </form>
        </div>
    </div>

    {{-- Ver fila --}}
    <div class="card mb-4">
        <div class="card-header">2. Ver Fila Atual</div>
        <div class="card-body">
            <form method="GET" action="{{ route('queue.show') }}">
                <div class="mb-3">
                    <label for="view_attraction_id" class="form-label">ID da Atração</label>
                    <input type="number" name="attraction_id" id="view_attraction_id" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-secondary">Ver Fila</button>
            </form>
        </div>
    </div>

    {{-- Chamar próximo --}}
    <div class="card mb-4">
        <div class="card-header">3. Chamar Próximo Visitante</div>
        <div class="card-body">
            <form method="GET" action="{{ route('queue.call-next') }}">
                <div class="mb-3">
                    <label for="call_attraction_id" class="form-label">ID da Atração</label>
                    <input type="number" name="attraction_id" id="call_attraction_id" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger">Chamar Próximo</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
