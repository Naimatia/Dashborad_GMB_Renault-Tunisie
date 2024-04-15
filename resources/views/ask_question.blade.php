<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask Question</title>
</head>
<body>
    <form method="POST" action="{{ route('ask.question') }}">
        @csrf
        <label for="question">Posez une question:</label><br>
        <input type="text" id="question" name="question"><br><br>
        <button type="submit">Obtenir la réponse</button>
    </form>

    @isset($answer)
        <div>
            <h3>Réponse:</h3>
            <p>{{ $answer }}</p>
        </div>
    @endisset
</body>
</html>
