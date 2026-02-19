<?php
// includes/profile.php
require_once __DIR__ . '/../config/database.php';

function getProfile($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT p.*, u.email, u.role, u.created_at FROM profiles p JOIN users u ON u.id = p.user_id WHERE p.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function updateProfile($userId, $data)
{
    try {
        $pdo = getDBConnection();
        $fields = [];
        $params = [];

        if (array_key_exists('full_name', $data)) {
            $fields[] = "full_name = ?";
            $params[] = $data['full_name'];
        }
        if (array_key_exists('bio', $data)) {
            $fields[] = "bio = ?";
            $params[] = $data['bio'];
        }
        if (array_key_exists('profession', $data)) {
            $fields[] = "profession = ?";
            $params[] = $data['profession'];
        }
        if (array_key_exists('country', $data)) {
            $fields[] = "country = ?";
            $params[] = $data['country'];
        }
        if (array_key_exists('skills', $data)) {
            $fields[] = "skills = ?";
            $params[] = $data['skills'];
        }
        if (array_key_exists('photo_url', $data)) {
            $fields[] = "photo_url = ?";
            $params[] = $data['photo_url'];
        }
        if (array_key_exists('social_links', $data)) {
            $fields[] = "social_links = ?";
            $params[] = $data['social_links'];
        }
        if (array_key_exists('is_public', $data)) {
            $fields[] = "is_public = ?";
            $params[] = $data['is_public'];
        }

        if (empty($fields)) {
            return ['status' => false, 'message' => 'No changes to update'];
        }

        $params[] = $userId;
        $sql = "UPDATE profiles SET " . implode(", ", $fields) . " WHERE user_id = ?";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            return ['status' => true, 'message' => 'Profile updated successfully'];
        }
        return ['status' => false, 'message' => 'Database error'];

    } catch (Exception $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

function searchMembers($query = '', $country = null, $profession = null)
{
    $pdo = getDBConnection();
    $sql = "SELECT p.* FROM profiles p WHERE p.is_public = 1";
    $params = [];

    if (!empty($query)) {
        $sql .= " AND (p.full_name LIKE ? OR p.skills LIKE ?)";
        $params[] = "%$query%";
        $params[] = "%$query%";
    }

    if (!empty($country)) {
        $sql .= " AND p.country LIKE ?";
        $params[] = "%$country%";
    }

    if (!empty($profession)) {
        $sql .= " AND p.profession LIKE ?";
        $params[] = "%$profession%";
    }

    $sql .= " ORDER BY p.full_name ASC LIMIT 50";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
