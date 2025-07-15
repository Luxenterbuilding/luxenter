<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim(strip_tags($_POST['review_name'] ?? ''));
    $message = trim(strip_tags($_POST['review_message'] ?? ''));

    if (!$name || !$message) {
        echo "Merci de remplir tous les champs.";
        exit;
    }

    $reviewsFile = __DIR__ . '/reviews.json';

    $reviews = [];
    if (file_exists($reviewsFile)) {
        $reviews = json_decode(file_get_contents($reviewsFile), true);
        if (!is_array($reviews)) $reviews = [];
    }

    // Ajouter un nouvel avis
    $reviews[] = [
        'name' => $name,
        'message' => $message,
        'date' => date('Y-m-d H:i:s')
    ];

    // Sauvegarder dans le fichier JSON
    file_put_contents($reviewsFile, json_encode($reviews, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Redirection vers la page contact avec un param success
    header("Location: contact.php?review=success");
    exit;
} else {
    http_response_code(405);
    echo "Méthode non autorisée.";
}
?>
