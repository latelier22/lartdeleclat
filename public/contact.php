<?php
declare(strict_types=1);

$to = 'contact@lartdeleclat.fr';

function clean_value(string $value): string
{
    $value = trim($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect_with_status(string $status): void
{
    header('Location: /contact?status=' . urlencode($status));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_status('error');
}

// Anti-spam invisible
if (!empty($_POST['website'] ?? '')) {
    redirect_with_status('ok');
}

$nom = clean_value((string)($_POST['nom'] ?? ''));
$telephone = clean_value((string)($_POST['telephone'] ?? ''));
$email = filter_var((string)($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$vehicule = clean_value((string)($_POST['vehicule'] ?? ''));
$prestation = clean_value((string)($_POST['prestation'] ?? ''));
$messageClient = trim((string)($_POST['message'] ?? ''));
$rgpd = isset($_POST['rgpd']);

if ($nom === '' || $telephone === '' || !$email || $prestation === '' || $messageClient === '' || !$rgpd) {
    redirect_with_status('missing');
}

$messageClientSafe = htmlspecialchars($messageClient, ENT_QUOTES, 'UTF-8');

$subject = 'Nouvelle demande de devis - L’art de l’éclat';

$body = "
Nouvelle demande depuis le site www.lartdeleclat.fr

Nom :
{$nom}

Téléphone :
{$telephone}

Email :
{$email}

Véhicule :
{$vehicule}

Prestation souhaitée :
{$prestation}

Message :
{$messageClientSafe}
";

$headers = [];
$headers[] = 'From: Site L’art de l’éclat <contact@lartdeleclat.fr>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$sent = mail($to, $subject, html_entity_decode($body, ENT_QUOTES, 'UTF-8'), implode("\r\n", $headers));

if ($sent) {
    redirect_with_status('ok');
}

redirect_with_status('error');