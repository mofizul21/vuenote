<?php

include "../Note.php";

$note = new Note;

$notes = $note->getNotes();
echo json_encode(['status' => 'success', 'data' => $notes]);