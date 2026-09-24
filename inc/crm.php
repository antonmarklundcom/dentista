<?php
/*
 * VenderCRM: POST {vcrm_url}/api/v1/leads con la clave del sitio.
 * La clave vive en config.php del servidor o en la variable de entorno
 * VENDERCRM_API_KEY. Nunca bloquea al visitante: devuelve el estado y
 * enviar.php sigue igual si falla.
 */

function vcrm_key(): string
{
    return (string)(cfg('vcrm_api_key') ?: getenv('VENDERCRM_API_KEY') ?: '');
}

/** Paraguayan local numbers (0981 123 456) → +595981123456. */
function phone_e164(string $raw): string
{
    $d = preg_replace('/\D/', '', $raw);
    if ($d === '') return '';
    if (str_starts_with($raw, '+')) return '+' . $d;
    if (str_starts_with($d, '595')) return '+' . $d;
    if (str_starts_with($d, '0')) return '+595' . substr($d, 1);
    return '+595' . $d;
}

/** First-touch attribution written by the CRM's vc-attribution.js. */
function vcrm_attribution(): array
{
    $a = json_decode((string)($_COOKIE['vc_attr'] ?? ''), true);
    return is_array($a) ? $a : [];
}

/**
 * @return array{0:int,1:string} [http status (0 = not sent), response body or error]
 */
function vcrm_send(array $payload): array
{
    $key = vcrm_key();
    if ($key === '' || !function_exists('curl_init')) return [0, 'not configured'];

    $payload = array_filter($payload, static fn($v) => $v !== null && $v !== '' && $v !== []);
    $ch = curl_init(rtrim((string)cfg('vcrm_url'), '/') . '/api/v1/leads');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'X-Api-Key: ' . $key],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
    $body   = (string)curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($status !== 200 && $status !== 201) {
        error_log(sprintf('VenderCRM lead failed [%d] %s %s', $status, mb_substr($body, 0, 500), $err));
    }
    return [$status, $err !== '' ? $err : $body];
}
