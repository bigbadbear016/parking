<?php
header('Content-Type: application/json');

$file = 'slots.json';
$defaultSlots = [
    'A1' => 0,
    'A2' => 1,
    'B1' => 2,
    'B2' => 0
];

function save_slots($filePath, $slots)
{
    file_put_contents($filePath, json_encode($slots, JSON_PRETTY_PRINT));
}

function is_valid_state($state)
{
    return in_array($state, [0, 1, 2], true);
}

function normalize_slot_id($slotId)
{
    return strtoupper(trim((string)$slotId));
}

function normalize_zone($zone)
{
    return strtoupper(trim((string)$zone));
}

$slots = [];
if (file_exists($file)) {
    $decoded = json_decode(file_get_contents($file), true);
    if (is_array($decoded)) {
        foreach ($decoded as $slotId => $state) {
            $normalized = normalize_slot_id($slotId);
            $stateInt = (int)$state;
            if (preg_match('/^[A-Z]+\d+$/', $normalized) && is_valid_state($stateInt)) {
                $slots[$normalized] = $stateInt;
            }
        }
    }
}

if (count($slots) === 0) {
    $slots = $defaultSlots;
    save_slots($file, $slots);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => 'Latest slots retrieved']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$action = $_POST['action'] ?? 'update';

if ($action === 'update') {
    $slotId = normalize_slot_id($_POST['slot'] ?? '');
    $state = isset($_POST['state']) ? (int)$_POST['state'] : null;

    if (!$slotId || $state === null || !preg_match('/^[A-Z]+\d+$/', $slotId) || !is_valid_state($state)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Valid slot and state are required']);
        exit;
    }

    $slots[$slotId] = $state;
    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Slot $slotId updated"]);
    exit;
}

if ($action === 'addSlot') {
    $slotId = normalize_slot_id($_POST['slot'] ?? '');
    $state = isset($_POST['state']) ? (int)$_POST['state'] : 0;

    if (!$slotId || !preg_match('/^[A-Z]+\d+$/', $slotId)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Slot must be like A1 or B12']);
        exit;
    }
    if (!is_valid_state($state)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid state']);
        exit;
    }
    if (array_key_exists($slotId, $slots)) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => "Slot $slotId already exists"]);
        exit;
    }

    $slots[$slotId] = $state;
    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Slot $slotId created"]);
    exit;
}

if ($action === 'deleteSlot') {
    $slotId = normalize_slot_id($_POST['slot'] ?? '');
    if (!$slotId || !array_key_exists($slotId, $slots)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Slot not found']);
        exit;
    }

    unset($slots[$slotId]);
    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Slot $slotId deleted"]);
    exit;
}

if ($action === 'renameSlot') {
    $oldSlot = normalize_slot_id($_POST['oldSlot'] ?? '');
    $newSlot = normalize_slot_id($_POST['newSlot'] ?? '');

    if (!$oldSlot || !$newSlot || !preg_match('/^[A-Z]+\d+$/', $newSlot)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Valid old and new slot IDs are required']);
        exit;
    }
    if (!array_key_exists($oldSlot, $slots)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => "Slot $oldSlot not found"]);
        exit;
    }
    if ($oldSlot !== $newSlot && array_key_exists($newSlot, $slots)) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => "Slot $newSlot already exists"]);
        exit;
    }

    $state = $slots[$oldSlot];
    unset($slots[$oldSlot]);
    $slots[$newSlot] = $state;
    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Slot renamed to $newSlot"]);
    exit;
}

if ($action === 'addZone') {
    $zone = normalize_zone($_POST['zone'] ?? '');
    $count = isset($_POST['count']) ? (int)$_POST['count'] : 0;
    $state = isset($_POST['state']) ? (int)$_POST['state'] : 0;

    if (!$zone || !preg_match('/^[A-Z]+$/', $zone)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Zone must contain letters only']);
        exit;
    }
    if ($count < 1 || $count > 200) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Zone slot count must be 1-200']);
        exit;
    }
    if (!is_valid_state($state)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid state']);
        exit;
    }

    for ($i = 1; $i <= $count; $i++) {
        $candidate = $zone . $i;
        if (array_key_exists($candidate, $slots)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => "Slot $candidate already exists. Choose another zone or rename existing slots."]);
            exit;
        }
    }

    for ($i = 1; $i <= $count; $i++) {
        $slots[$zone . $i] = $state;
    }

    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Zone $zone created with $count slots"]);
    exit;
}

if ($action === 'renameZone') {
    $oldZone = normalize_zone($_POST['oldZone'] ?? '');
    $newZone = normalize_zone($_POST['newZone'] ?? '');

    if (!$oldZone || !$newZone || !preg_match('/^[A-Z]+$/', $oldZone) || !preg_match('/^[A-Z]+$/', $newZone)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Valid old and new zones are required']);
        exit;
    }

    $zoneSlots = [];
    foreach ($slots as $slotId => $state) {
        if (strpos($slotId, $oldZone) === 0 && preg_match('/^' . preg_quote($oldZone, '/') . '\\d+$/', $slotId)) {
            $suffix = substr($slotId, strlen($oldZone));
            $zoneSlots[$slotId] = [$newZone . $suffix, $state];
        }
    }

    if (count($zoneSlots) === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => "Zone $oldZone not found"]);
        exit;
    }

    foreach ($zoneSlots as $oldSlot => $next) {
        $newSlot = $next[0];
        if ($oldSlot !== $newSlot && array_key_exists($newSlot, $slots)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => "Cannot rename zone. Slot $newSlot already exists"]);
            exit;
        }
    }

    foreach ($zoneSlots as $oldSlot => $next) {
        unset($slots[$oldSlot]);
    }
    foreach ($zoneSlots as $next) {
        $slots[$next[0]] = $next[1];
    }

    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Zone $oldZone renamed to $newZone"]);
    exit;
}

if ($action === 'deleteZone') {
    $zone = normalize_zone($_POST['zone'] ?? '');
    if (!$zone || !preg_match('/^[A-Z]+$/', $zone)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Valid zone is required']);
        exit;
    }

    $removed = 0;
    foreach (array_keys($slots) as $slotId) {
        if (strpos($slotId, $zone) === 0 && preg_match('/^' . preg_quote($zone, '/') . '\\d+$/', $slotId)) {
            unset($slots[$slotId]);
            $removed++;
        }
    }

    if ($removed === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => "Zone $zone not found"]);
        exit;
    }

    save_slots($file, $slots);
    echo json_encode(['status' => 'success', 'slots' => $slots, 'message' => "Zone $zone deleted"]);
    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Unsupported action']);
?>
