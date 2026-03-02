<!DOCTYPE html>
<html>
<head>
    <title>Facture {{ $commande->numero_commande }}</title>
</head>
<body>
    <h1>Facture: {{ $commande->numero_commande }}</h1>
    <p>Client: {{ $commande->client }}</p>
    <p>Total: {{ $commande->total }} FCFA</p>
    <hr>
    <p>Date: {{ date('d/m/Y') }}</p>
</body>
</html>