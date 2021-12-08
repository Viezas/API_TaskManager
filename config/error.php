<?php

function error_template(string $message, int $status_code) {
  return [
    'message' => $message,
    'status_code' => $status_code
  ];
}

return [
  'login' => error_template('Identifians inconnus', 401),
  'task' =>  error_template('Tâche inconnu', 404),
];