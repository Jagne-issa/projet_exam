<form action="{{ path('client_search') }}" method="POST">
    <label for="telephone">Téléphone:</label>
    <input type="text" name="telephone" id="telephone" required>

    <label for="surname">Nom:</label>
    <input type="text" name="surname" id="surname" required>

    <label for="enum">Enum:</label>
    <select name="enum" id="enum" required>
        <option value="option1">Option 1</option>
        <option value="option2">Option 2</option>
    </select>

    <label for="compte">Créer un compte:</label>
    <input type="checkbox" name="compte" id="compte" value="true">

    <button type="submit">Rechercher</button>
</form>
