<?php

use App\Models\MoonshineUserRole;

function isAdmin(): bool
{
    return in_array(auth('moonshine')->user()->moonshine_user_role_id, [
        MoonshineUserRole::ADMIN_ROLE_ID,
        MoonshineUserRole::HR_ROLE_ID,
    ], 1);
}

/**
 * @throws JsonException
 */
function getAi(string $question): string
{
    // Удаляем все переносы строк
    $question = str_replace("\n", "\r", $question);
    /** @var string $question */
    // Заменяем два или более пробела на один
    $question = preg_replace('/\s+/', ' ', $question);
    $strLen = mb_strlen($question);
    if ($strLen > 2000) {
        throw new RuntimeException('очень большой текст. Максимум 2000символов. Вы передали: ' . $strLen);
    }
    $yourApiKey = env('OPENAI_API_KEY');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.proxyapi.ru/openai/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'gpt-3.5-turbo',
        'messages' => [['role' => 'user', 'content' => $question]],
        'temperature' => 0.7
    ], JSON_THROW_ON_ERROR));

    $headers = [];
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer ' . $yourApiKey;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);


    $arr = json_decode($result, 1, 512, JSON_THROW_ON_ERROR);

    return $arr['choices'][0]['message']['content'];
}
