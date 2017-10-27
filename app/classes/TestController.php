<?php

class TestController
{
  protected static $allowedTables = ['News', 'Session'];
  
  public static function getTable()
  {
    $table = $_GET['table'];
    if (!in_array($table, self::$allowedTables)) {
      return Response::send('error', [], "Нет доступа к таблице $table");
    }
    
    $id = $_GET['id'];
    if (is_null($id)) {
      // Вернем все элементы таблицы
      $result = DB::runQuery("SELECT * from $table")->fetchAll();
      return Response::send('ok', $result);
    }
    
    // Иначен найдем элемент по id
    $result = DB::runQuery("SELECT * from $table WHERE id = ?", [$id])->fetch();
    if (!$result) {
      return Response::send('error', [], "Запись с id $id не найдена в таблице $table");
    }
    
    return Response::send('ok', $result);
  }
  
  public function sessionSubscribe()
  {
    $sessionId = $_GET['sessionId'];
    $email = $_GET['userEmail'];
    $user = DB::runQuery("SELECT * from Participant WHERE Email = ?", [$email])->fetch();
    if (!$user) {
      return Response::send('error', [], "Пользователь с указанным email не зарегистрирован");
    }
  
    $session = DB::runQuery("SELECT * from Session WHERE ID = ?", [$sessionId])->fetch();
    if (!$session) {
      return Response::send('error', [], "Сессия с таким id не найдена");
    }
    
    $checkIfSubscribed = DB::runQuery("SELECT COUNT(*) as c from Participant_Session WHERE (session_id = ? AND participant_id = ?)", [$sessionId, $user['ID']])->fetch();
    if ($checkIfSubscribed['c']) {
      return Response::send('error', [], "Этот пользователь уже записан");
    }
    
    $countUsers = DB::runQuery("SELECT COUNT(*) as c from Participant_Session WHERE session_id = ?", [$sessionId])->fetch();
    if ($countUsers['c'] >= $session['max_participants']) {
      return Response::send('error', [], "Нет свободных мест");
    }
    
    DB::runQuery("INSERT INTO Participant_Session (session_id, participant_id) VALUES (?, ?)", [$sessionId, $user['ID']]);
    return Response::send('ok', [], "Пользователь записан на сессию");
  }
  
}