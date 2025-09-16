<?php
// search_bar.php
// Show search bar only in allowed pages
if (basename($_SERVER['PHP_SELF']) != 'checkout.php' &&
    basename($_SERVER['PHP_SELF']) != 'payment.php' &&
    basename($_SERVER['PHP_SELF']) != 'status.php' &&
    strpos($_SERVER['PHP_SELF'], 'admin') === false) {
?>
    <style>
        /* ---------- Search Bar Styling ---------- */
        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            max-width: 500px;
            background: #fff;
            border: 2px solid #c7e8a9; /* light lime green border */
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .search-bar input {
            flex: 1;
            padding: 12px 18px;
            border: none;
            outline: none;
            font-size: 16px;
            background: transparent;
            color: #333;
        }

        .search-bar input::placeholder {
            color: #888;
        }

        .search-bar button {
            background: #9acd32; /* lime green */
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .search-bar button:hover {
            background: #7cbf25; /* darker lime green */
        }
    </style>

    <form action="search.php" method="get" class="search-bar">
        <input type="text" name="q" placeholder="🔍 Search for food..." required>
        <button type="submit">Search</button>
    </form>
<?php
}
?>
