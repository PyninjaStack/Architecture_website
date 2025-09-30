<?php
/**
 * Global search logic for projects table.
 * Usage:
 *   require_once "../includes/search_projects.php";
 *   $projects = searchProjects($conn, $search, $role, $userId);
 */

function searchProjects($conn, $search, $role, $userId = null) {
    $params = [];
    $sql = "SELECT p.*, u.username, u.full_name 
            FROM projects p
            JOIN users u ON p.created_by = u.id";

    // ✅ Role-based restrictions
    if ($role === 'content_creator') {
        $sql .= " WHERE p.created_by = :uid";
        $params['uid'] = $userId;
    } else {
        $sql .= " WHERE 1=1"; // for editor & administrator (see all projects)
    }

    // ✅ Search conditions
    if (!empty($search)) {
        $sql .= " AND (
            LOWER(p.title) LIKE :search OR
            LOWER(p.code) LIKE :search OR
            LOWER(p.location) LIKE :search OR
            LOWER(p.category) LIKE :search OR
            LOWER(p.status) LIKE :search OR
            LOWER(p.latitude) LIKE :search OR
            LOWER(p.longitude) LIKE :search OR
            LOWER(p.description) LIKE :search
        )";
        $params['search'] = "%$search%";
    }

    $sql .= " ORDER BY p.year DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
