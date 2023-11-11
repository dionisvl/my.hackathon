<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Вам назначена задача</title>
</head>
<body>
<h1>Вам назначена задача "{{ $taskTitle }}"</h1>
<p>Описание задачи: {{ $taskDescription }}</p>
<p>Срок выполнения задачи: {{ $taskDeadline }}</p>
<p><a href="{{ $taskUrl }}">Перейти к задаче</a></p>
</body>
</html>
