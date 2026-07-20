<?php

// ------------------------------------
// DATABASE CONNECTION
// ------------------------------------

$servername = "sql312.infinityfree.com";
$username = "if0_42403494";
$password = "NMxwth6SXfdxlu";
$dbname = "if0_42403494_datastorage";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ------------------------------------
// INSERT NEW USER
// ------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_user"])) {

    $name = ucfirst(strtolower(trim($_POST["name"])));
    $age = (int) $_POST["age"];

    if ($name !== "" && $age >= 1 && $age <= 120) {

        $stmt = $conn->prepare(
            "INSERT INTO `user` (`name`, `age`, `status`)
             VALUES (?, ?, 0)"
        );

        $stmt->bind_param("si", $name, $age);

        if (!$stmt->execute()) {
            die("Insert failed: " . $stmt->error);
        }

        $stmt->close();
    }

    // Prevent duplicate insertion when refreshing
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ------------------------------------
// TOGGLE STATUS
// ------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["toggle_user"])) {

    $id = (int) $_POST["id"];

    $stmt = $conn->prepare(
        "UPDATE `user`
         SET `status` = IF(`status` = 0, 1, 0)
         WHERE `id` = ?"
    );

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Update failed: " . $stmt->error);
    }

    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ------------------------------------
// GET ALL USERS
// ------------------------------------

$result = $conn->query(
    "SELECT `id`, `name`, `age`, `status`
     FROM `user`
     ORDER BY `id` ASC"
);

if (!$result) {
    die("Select failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Information</title>

    <style>
        :root {
            --card: rgba(15, 23, 42, 0.9);
            --border: rgba(255, 255, 255, 0.14);
            --text: #f8fafc;
            --muted: #94a3b8;
            --primary: #7c3aed;
            --secondary: #22d3ee;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                linear-gradient(
                    135deg,
                    #0f172a 0%,
                    #1e293b 50%,
                    #334155 100%
                );
        }

        .card {
            width: min(100%, 1000px);
            padding: 40px;
            border: 1px solid var(--border);
            border-radius: 26px;
            background: var(--card);
            box-shadow: 0 20px 45px rgba(2, 8, 23, 0.35);
        }

        h1 {
            margin: 0 0 10px;
            font-size: 2.2rem;
        }

        .subtitle {
            margin: 0 0 30px;
            color: var(--muted);
            font-size: 1.05rem;
        }

        .user-form {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 34px;
        }

        label {
            font-weight: 700;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="number"] {
            padding: 13px 16px;
            border: 1px solid rgba(209, 213, 219, 0.5);
            border-radius: 12px;
            outline: none;
            font-size: 1rem;
            background: rgba(248, 250, 252, 0.95);
            color: #1e293b;
        }

        input[type="text"] {
            width: 290px;
        }

        input[type="number"] {
            width: 190px;
        }

        input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.18);
        }

        .submit-button,
        .toggle-button {
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.2s ease;
        }

        .submit-button:hover,
        .toggle-button:hover {
            transform: translateY(-2px);
        }

        .submit-button {
            padding: 13px 24px;
            color: white;
            background:
                linear-gradient(
                    90deg,
                    var(--primary),
                    var(--secondary)
                );
        }

        .toggle-button {
            padding: 9px 17px;
            color: white;
            background: rgba(124, 58, 237, 0.7);
            border: 1px solid rgba(34, 211, 238, 0.3);
            transition: all 0.3s ease;
        }

        .toggle-button:hover {
            background: rgba(124, 58, 237, 0.9);
            border-color: rgba(34, 211, 238, 0.6);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(15, 23, 42, 0.7);
            color: #cbd5e1;
        }

        th,
        td {
            padding: 15px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            text-align: center;
        }

        th {
            background: rgba(30, 41, 59, 0.6);
            font-size: 1rem;
            color: #cbd5e1;
        }

        td {
            background: rgba(20, 30, 48, 0.5);
        }

        .status-zero {
            color: #f87171;
            font-weight: 700;
        }

        .status-one {
            color: #86efac;
            font-weight: 700;
        }

        .empty-row {
            color: #64748b;
        }

        @media (max-width: 760px) {
            .card {
                padding: 25px;
            }

            .user-form {
                display: grid;
            }

            input[type="text"],
            input[type="number"],
            .submit-button {
                width: 100%;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.user-form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                formData.append('add_user', 'true');
                
                fetch('index.php', {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    // Clear form immediately
                    document.getElementById('name').value = '';
                    document.getElementById('age').value = '';
                    
                    // Reload page after 500ms to show new data
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</head>

<body>

<div class="card">

    <h1>User Information</h1>

    <p class="subtitle">
        Enter a name and age to save the record in the database.
    </p>

    <form
        class="user-form"
        action="index.php"
        method="POST"
    >

        <label for="name">Name</label>

        <input
            type="text"
            id="name"
            name="name"
            placeholder="Enter your name"
            maxlength="255"
            autocomplete="off"
            required
        >

        <label for="age">Age</label>

        <input
            type="number"
            id="age"
            name="age"
            placeholder="Age"
            min="1"
            max="120"
            autocomplete="off"
            required
        >

        <button
            type="submit"
            name="add_user"
            class="submit-button"
        >
            Submit
        </button>

    </form>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo (int) $row["id"]; ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                ucfirst(strtolower($row["name"])),
                                ENT_QUOTES,
                                "UTF-8"
                            );
                            ?>
                        </td>

                        <td>
                            <?php echo (int) $row["age"]; ?>
                        </td>

                        <td>
                            <?php if ((int) $row["status"] === 1): ?>

                                <span class="status-one">1</span>

                            <?php else: ?>

                                <span class="status-zero">0</span>

                            <?php endif; ?>
                        </td>

                        <td>

                            <form
                                action="index.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo (int) $row["id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    name="toggle_user"
                                    class="toggle-button"
                                >
                                    Toggle
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td
                        colspan="5"
                        class="empty-row"
                    >
                        No records found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>

<?php
$conn->close();
?>