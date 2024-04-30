<!-- resources/views/auth/google_error.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Erreur de connexion avec Google</title>
</head>
<body>
    <h1>Erreur de connexion avec Google</h1>
    @if(session('error'))
        <p>{{ session('error') }}</p>
    @else
        <p>Une erreur inattendue s'est produite lors de la connexion avec Google.</p>
    @endif
    <a href="/auth/google">Réessayer</a>
</body>
</html>
