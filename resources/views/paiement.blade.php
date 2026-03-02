<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
</head>
<body>
    <h1>Effectuer un paiement</h1>

    <form action="{{ url('/api/paiement') }}" method="POST">
        @csrf
        <label for="commande_id">ID Commande:</label>
        <input type="number" name="commande_id" id="commande_id" required>
        <br><br>

        <label for="montant">Montant:</label>
        <input type="number" name="montant" id="montant" required>
        <br><br>

        <label for="methode">Méthode de paiement:</label>
        <select name="methode" id="methode">
            <option value="carte">Carte</option>
            <option value="paypal">PayPal</option>
            <option value="om">Orange Money</option>
        </select>
        <br><br>

        <button type="submit">Payer</button>
    </form>
</body>
</html>