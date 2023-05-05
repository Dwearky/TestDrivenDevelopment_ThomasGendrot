<?php
session_start();

// Initialisation du tableau pour les deux salons
if (!isset($_SESSION["chatrooms"])) {
    $_SESSION["chatrooms"] = array(
        "Salon 1" => array("messages" => array()),
        "Salon 2" => array("messages" => array())
    );
}

// Initialisation du salon par défaut
if (!isset($_SESSION["chatroom"])) {
    $_SESSION["chatroom"] = "Salon 1";
}

// Initialisation de la liste des utilisateurs
if (!isset($_SESSION["users"])) {
    $_SESSION["users"] = array();
}

// Vérifier si l'utilisateur a soumis un message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le message de l'utilisateur
    $message = $_POST["message"];

    // Vérifier que le message a au moins 2 caractères et au plus 2048 caractères
    if (strlen($message) < 2 || strlen($message) > 2048) {
        echo "Le message doit avoir entre 2 et 2048 caractères.";
    } else {
        // Récupérer les informations de l'utilisateur
        $userId = $_SESSION["user_id"];
        $user = isset($_SESSION["users"][$userId]) ? $_SESSION["users"][$userId] : array();

        // Récupérer le dernier message de l'utilisateur pour le salon actuel
        $lastMessageTime = isset($user[$_SESSION["chatroom"]]) ? $user[$_SESSION["chatroom"]] : 0;
        $currentTime = time();
        $timeDiff = $currentTime - $lastMessageTime;

        if ($timeDiff < 86400) {
            // Le délai n'est pas respecté, afficher un message d'erreur
            echo "Vous ne pouvez pas poster deux messages consécutifs. Veuillez attendre " . (86400 - $timeDiff) . " secondes.";
        } else {
            // Ajouter le message au tableau correspondant au salon actuel
            array_push($_SESSION["chatrooms"][$_SESSION["chatroom"]]["messages"], "$userId: $message");

            // Mettre à jour les informations de l'utilisateur
            $user[$_SESSION["chatroom"]] = $currentTime;
            $_SESSION["users"][$userId] = $user;
        }
    }
}


// Vérifier si l'utilisateur a sélectionné un autre salon
if (isset($_GET["chatroom"])) {
    $_SESSION["chatroom"] = $_GET["chatroom"];
}

// Afficher le salon actuel
$currentChatroom = $_SESSION["chatrooms"][$_SESSION["chatroom"]];
echo "<h1>" . $_SESSION["chatroom"] . "</h1>";
echo "<ul>";
foreach ($currentChatroom["messages"] as $message) {
    echo "<li>$message</li>";
}
echo "</ul>";
?>

<!-- Formulaire pour saisir un message -->
<form method="post">
    <!-- <label for="name">Nom :</label>
  <input type="text" id="name" name="name">
  <br> -->
    <label for="message">Message :</label>
    <textarea id="message" name="message"></textarea>
    <br>
    <input type="submit" value="Envoyer">
</form>
<!-- Liens pour changer de salon -->
<a href="?chatroom=Salon 1">Salon 1</a>
<a href="?chatroom=Salon 2">Salon 2</a>