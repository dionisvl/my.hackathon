<!DOCTYPE html>
<html>
<head>
    <title>Задача выполнена</title>
</head>
<body>
<h1>Задача "{{ $taskTitle }}" выполнена</h1>
<p>Описание задачи: {{ $taskDescription }}</p>
<p>Срок выполнения задачи: {{ $taskDeadline }}</p>
<p>Результат выполнения задачи: {{ $taskResult }}</p>
<p>Задачу выполнил: {{ $taskAssigneeName }}</p>
</body>
</html>
