<!-- resources/views/emails/contactanos.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Contactanos Maileable</title>
</head>

<body>
    <h1>Hola, {{ $nombre }}</h1>
    <p>{{ $mensaje }}</p>
    <p>La ruta {{ $nombreRuta }} se realizará el día {{ $fecha }} a las {{ $hora }}.</p>
</body>

</html>
