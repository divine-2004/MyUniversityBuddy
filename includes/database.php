<?php
/**
 * Database connection for SNSU-FRMS
 * Uses MySQLi with fallback for offline development
 */

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'snsu_frms');

/**
 * Get database connection
 * @return mysqli|null Database connection or null if failed
 */
function getDBConnection() {
    static $connection = null;

    if ($connection === null) {
        try {
            $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($connection->connect_error) {
                // Log error for debugging (in production, use proper logging)
                error_log("Database connection failed: " . $connection->connect_error);
                $connection = null;
            } else {
                $connection->set_charset('utf8mb4');
            }
        } catch (Exception $e) {
            error_log("Database connection exception: " . $e->getMessage());
            $connection = null;
        }
    }

    return $connection;
}

/**
 * Execute a query with error handling
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return mysqli_result|bool Query result or false on failure
 */
function executeQuery($query, $params = []) {
    $conn = getDBConnection();

    if (!$conn) {
        return false;
    }

    if (!empty($params)) {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }

        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();

        if ($result) {
            return $stmt->get_result();
        } else {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
    } else {
        $result = $conn->query($query);
        if (!$result) {
            error_log("Query failed: " . $conn->error);
        }
        return $result;
    }
}

/**
 * Get single row from query
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return array|null Associative array or null if no result
 */
function getSingleRow($query, $params = []) {
    $result = executeQuery($query, $params);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

/**
 * Get multiple rows from query
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return array Array of associative arrays
 */
function getMultipleRows($query, $params = []) {
    $result = executeQuery($query, $params);
    $rows = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}

/**
 * Get count from query
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return int Count value or 0 on failure
 */
function getCount($query, $params = []) {
    $result = executeQuery($query, $params);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_row();
        return (int) $row[0];
    }

    return 0;
}

/**
 * Insert data and return insert ID
 * @param string $query SQL insert query
 * @param array $params Parameters for prepared statement
 * @return int|bool Insert ID or false on failure
 */
function insertData($query, $params = []) {
    $conn = getDBConnection();

    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $types = '';
    foreach ($params as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_float($param)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
    }

    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->insert_id;
    } else {
        error_log("Insert failed: " . $stmt->error);
        return false;
    }
}

/**
 * Update data
 * @param string $query SQL update query
 * @param array $params Parameters for prepared statement
 * @return bool True on success, false on failure
 */
function updateData($query, $params = []) {
    $conn = getDBConnection();

    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $types = '';
    foreach ($params as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_float($param)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
    }

    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();

    if (!$result) {
        error_log("Update failed: " . $stmt->error);
    }

    return $result;
}

/**
 * Delete data
 * @param string $query SQL delete query
 * @param array $params Parameters for prepared statement
 * @return bool True on success, false on failure
 */
function deleteData($query, $params = []) {
    $conn = getDBConnection();

    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $types = '';
    foreach ($params as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_float($param)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
    }

    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();

    if (!$result) {
        error_log("Delete failed: " . $stmt->error);
    }

    return $result;
}

/**
 * Escape string for safe output
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeString($string) {
    $conn = getDBConnection();
    return $conn ? $conn->real_escape_string($string) : htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize input data
 * @param mixed $data Data to sanitize
 * @return mixed Sanitized data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }

    if (is_string($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    return $data;
}
?>