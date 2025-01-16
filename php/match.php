<!DOCTYPE html>
<html lang="en">
<head>
    <!-- style scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- style links -->
    <link rel="stylesheet" href="./css/globals.css">

    <!-- settings -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- title -->
    <title>Play The Game</title>
</head>

<body>
    <?php
        session_start(); 

        // if no username was set in the POST & in the SESSION, missing data should be detected
        if (!isset($_POST["username"])) {
            if (!isset($_SESSION["username"])) {
                header("Location: ./error.html");
                session_destroy();
                exit();
            }
        }

        // if no username was set in the SESSION, the game has yet to start => set starting variables
        if (!isset($_SESSION["username"])) {
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["userWins"] = 0;
            $_SESSION["computerWins"] = 0;
            $_SESSION["turn"] = 1;
        }

        // if a choice was made
        if (isset($_POST["choice"])) {
            $userChoice = $_POST["choice"];
            $computerChoice = rand(1, 9);
            
            if ($userChoice == $computerChoice || $userChoice == $computerChoice + 1) {
                $_SESSION["userWins"]++;
            } else {
                $_SESSION["computerWins"]++;
            }
            
            $_SESSION["turn"]++;

            // update choices
            $_SESSION["userLastChoice"] = $userChoice;
            $_SESSION["computerLastChoice"] = $computerChoice;
        }

        // the one to win 3 matches wins
        if ($_SESSION["userWins"] >= 3) {
            echo "<p>Congratulations, " . $_SESSION["username"] . "! You won the game.</p>";
            session_destroy();
            exit();
        } elseif ($_SESSION["computerWins"] >= 3) {
            echo "<p>You lost the game, " . $_SESSION["username"];
            session_destroy();
            exit();
        }
    ?>

    <main class="w-full flex flex-col items-center justify-center gap-6 pt-2">
        <h1 class="text-3xl font-bold">Higher number game</h1>

        <p>Username: <?= $_SESSION["username"] ?></p>
        <p>Turn: <?= $_SESSION["turn"] ?></p>

        <div class="flex flex-col justify-center items-center">
            <?php if (isset($_SESSION["userLastChoice"])) echo "<p>User's last choice: " . $_SESSION["userLastChoice"] . "</p>" ?>
            <?php if (isset($_SESSION["computerLastChoice"])) echo "<p>Computer's last choice: " . $_SESSION["computerLastChoice"] . "</p>" ?>
        </div>

        <div class="flex flex-col justify-center items-center">
            <?php if (isset($_SESSION["userWins"])) echo "<p>User Wins: " . $_SESSION["userWins"] . "</p>" ?>
            <?php if (isset($_SESSION["computerWins"])) echo "<p>Computer Wins: " . $_SESSION["computerWins"] . "</p>" ?>
        </div>

        <form action="./match.php" method="POST" class="flex gap-2 items-center">
            <label for="choice">Choose your number </label>
            <select id="choice" name="choice" class="border border-1 rounded-md">
                <option value="1" selected="selected">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
            </select>

            <button 
                type="submit" 
                class="bg-blue-300 text-sm border border-1 px-4 py-1 rounded-md"
            >
                Play
            </button>
        </form>
    </main>
</body>

</html>