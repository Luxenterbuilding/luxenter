<?php
session_start();

$contactEmail = 'luxenterbuildingcontracting@gmail.com';
$reviewsFile = __DIR__ . '/reviews.json';

$lang = $_GET['lang'] ?? 'en';
if (!in_array($lang, ['fr','en','ar'])) $lang = 'en';
$dir = ($lang === 'ar') ? 'rtl' : 'ltr';

$t = [
    'fr' => [
        'contact_title' => 'Nous contacter',
        'fullname' => 'Nom complet',
        'email' => 'Email',
        'phone' => 'Téléphone',
        'subject' => 'Sujet',
        'message' => 'Message',
        'send' => 'Envoyer',
        'review_title' => 'Avis clients',
        'review_form_title' => 'Laissez un avis',
        'review_name' => 'Nom',
        'review_message' => 'Votre avis (facultatif)',
        'review_send' => 'Envoyer l’avis',
        'review_rating' => 'Note',
        'contact_success' => 'Merci pour votre message, nous vous répondrons rapidement.',
        'contact_error' => 'Une erreur est survenue lors de l’envoi du message.',
        'review_success' => 'Merci pour votre avis !',
        'review_error' => 'Une erreur est survenue lors de l’ajout de votre avis. Veuillez renseigner au moins votre nom et une note.',
        'no_reviews' => 'Aucun avis pour le moment.',
    ],
    'en' => [
        'contact_title' => 'Contact Us',
        'fullname' => 'Full Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'subject' => 'Subject',
        'message' => 'Message',
        'send' => 'Send',
        'review_title' => 'Customer Reviews',
        'review_form_title' => 'Leave a Review',
        'review_name' => 'Name',
        'review_message' => 'Your Review (optional)',
        'review_send' => 'Submit Review',
        'review_rating' => 'Rating',
        'contact_success' => 'Thank you for your message, we will reply shortly.',
        'contact_error' => 'An error occurred while sending your message.',
        'review_success' => 'Thank you for your review!',
        'review_error' => 'An error occurred while adding your review. Please provide at least your name and a rating.',
        'no_reviews' => 'No reviews yet.',
    ],
    'ar' => [
        'contact_title' => 'اتصل بنا',
        'fullname' => 'الاسم الكامل',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'subject' => 'الموضوع',
        'message' => 'الرسالة',
        'send' => 'إرسال',
        'review_title' => 'آراء العملاء',
        'review_form_title' => 'اترك رأيك',
        'review_name' => 'الاسم',
        'review_message' => 'رأيك (اختياري)',
        'review_send' => 'إرسال الرأي',
        'review_rating' => 'التقييم',
        'contact_success' => 'شكراً لرسالتك، سنرد عليك قريباً.',
        'contact_error' => 'حدث خطأ أثناء إرسال الرسالة.',
        'review_success' => 'شكراً لرأيك!',
        'review_error' => 'حدث خطأ أثناء إضافة رأيك. الرجاء إدخال الاسم والتقييم على الأقل.',
        'no_reviews' => 'لا توجد تقييمات حتى الآن.',
    ]
];

$messages = [
    'contact' => '',
    'review' => ''
];

function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// === Traitement formulaire contact ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $fullname = clean_input($_POST['fullname'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = clean_input($_POST['phone'] ?? '');
    $subject = clean_input($_POST['subject'] ?? '');
    $message = clean_input($_POST['message'] ?? '');

    if ($fullname && $email && $phone && $subject && $message) {
        $mailSubject = "Contact Form: " . $subject;
        $mailBody = "Nom complet: $fullname\nEmail: $email\nTéléphone: $phone\n\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email\r\n";

        if (mail($contactEmail, $mailSubject, $mailBody, $headers)) {
            $messages['contact'] = $t[$lang]['contact_success'];
            $_POST = [];
        } else {
            $messages['contact'] = $t[$lang]['contact_error'];
        }
    } else {
        $messages['contact'] = $t[$lang]['contact_error'];
    }
}

// === Chargement avis ===
$reviews = [];
if (file_exists($reviewsFile)) {
    $reviews = json_decode(file_get_contents($reviewsFile), true);
    if (!is_array($reviews)) $reviews = [];
}

// === Traitement ajout avis ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
    $reviewName = clean_input($_POST['review_name'] ?? '');
    $reviewMessage = clean_input($_POST['review_message'] ?? '');
    $reviewRating = (int)($_POST['review_rating'] ?? 0);
    if ($reviewRating < 1 || $reviewRating > 5) $reviewRating = 0;

    if ($reviewName && $reviewRating > 0) {
        $newReview = [
            'name' => $reviewName,
            'message' => $reviewMessage,
            'rating' => $reviewRating,
            'date' => date('c')
        ];

        $reviews[] = $newReview;

        if (file_put_contents($reviewsFile, json_encode($reviews, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            $messages['review'] = $t[$lang]['review_success'];
            $_POST = [];
        } else {
            $messages['review'] = $t[$lang]['review_error'];
        }
    } else {
        $messages['review'] = $t[$lang]['review_error'];
    }
}
?>
