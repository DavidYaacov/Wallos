<?php
require_once '../../includes/connect_endpoint.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die(json_encode([
        "success" => false,
        "message" => translate('session_expired', $i18n)
    ]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);

    if (
        !isset($data["gotify_url"]) || $data["gotify_url"] == "" ||
        !isset($data["token"]) || $data["token"] == ""
    ) {
        $response = [
            "success" => false,
            "message" => translate('fill_mandatory_fields', $i18n)
        ];
        echo json_encode($response);
    } else {
        $enabled = $data["enabled"];
        $url = $data["gotify_url"];
        $token = $data["token"];

        $query = "SELECT COUNT(*) FROM gotify_notifications WHERE user_id = :userId";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":userId", $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result === false) {
            $response = [
                "success" => false,
                "message" => translate('error_saving_notifications', $i18n)
            ];
            echo json_encode($response);
        } else {
            $row = $result->fetchArray();
            $count = $row[0];
            if ($count == 0) {
                $query = "INSERT INTO gotify_notifications (enabled, url, token, user_id)
                              VALUES (:enabled, :url, :token, :userId)";
            } else {
                $query = "UPDATE gotify_notifications
                              SET enabled = :enabled, url = :url, token = :token WHERE user_id = :userId";
            }

            $stmt = $db->prepare($query);
            $stmt->bindValue(':enabled', $enabled, SQLITE3_INTEGER);
            $stmt->bindValue(':url', $url, SQLITE3_TEXT);
            $stmt->bindValue(':token', $token, SQLITE3_TEXT);
            $stmt->bindValue(':userId', $userId, SQLITE3_INTEGER);

            if ($stmt->execute()) {
                $response = [
                    "success" => true,
                    "message" => translate('notifications_settings_saved', $i18n)
                ];
                echo json_encode($response);
            } else {
                $response = [
                    "success" => false,
                    "message" => translate('error_saving_notifications', $i18n)
                ];
                echo json_encode($response);
            }
        }
    }
}
?>