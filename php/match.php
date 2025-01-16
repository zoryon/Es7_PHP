<!DOCTYPE html>
<html lang="en">
<head>
    <!-- style scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- style links -->
    <link rel="stylesheet" href="../css/globals.css">

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

        // validating
        // if no username was set in the POST & in the SESSION, missing data should be detected
        if (!isset($_POST["username"]) && !isset($_SESSION["username"])) {
            header("Location: ./error.html");
            session_destroy();
            exit("No username was set.");
        }

        // if the set username equals to "" & it is not saved in the SESSION, missing data should be detected
        if (empty($_POST["username"]) && !isset($_SESSION["username"])) {
            header("Location: ./error.html");
            session_destroy();
            exit("No username was set.");
        }

        // if no username was set in the SESSION, the game has yet to start => set starting variables
        if (!isset($_SESSION["username"])) {
            $_SESSION["username"] = $_POST["username"]; // the POST has username, otherwise the other if would have been executed
            $_SESSION["userWins"] = 0;
            $_SESSION["computerWins"] = 0;
            $_SESSION["turn"] = 1;
        }

        // game logic
        // if a choice was made
        if (isset($_POST["choice"])) {
            $userChoice = (int)$_POST["choice"]; // cast would not be needed, but it is safer
            $computerChoice = rand(1, 9);
            
            if ($userChoice === $computerChoice || $userChoice === $computerChoice + 1) {
                $_SESSION["userWins"]++;
            } else {
                $_SESSION["computerWins"]++;
            }
            
            if (!hasMatchEnded()) {
                $_SESSION["turn"]++;
            }

            // update choices
            $_SESSION["userLastChoice"] = $userChoice;
            $_SESSION["computerLastChoice"] = $computerChoice;
        }

        $userWon = hasWon("userWins");
        $computerWon = hasWon("computerWins");

        if ($userWon || $computerWon) {
            session_destroy();
        }

        function hasWon($player) {
            if ($player != "userWins" && $player != "computerWins") return null;
            
            return $_SESSION[$player] >= 3;
        }

        function hasMatchEnded() {
            return hasWon("userWins") || hasWon("computerWins");
        }
    ?>

    <main>
        <h1 class="text-3xl font-bold">Higher number game</h1>

        <p>Username: <?= $_SESSION["username"] ?></p>
        <p>Turn: <?= $_SESSION["turn"] ?></p>

        <div class="flex flex-col justify-center items-center">
            <p><?= isset($_SESSION["userLastChoice"]) ? "User's last choice: " . $_SESSION["userLastChoice"] : "" ?></p>
            <p><?= isset($_SESSION["computerLastChoice"]) ? "Computer's last choice: " . $_SESSION["computerLastChoice"] : "" ?></p>

        </div>

        <div class="flex flex-col justify-center items-center font-bold">
            <p>User's wins: <?= $_SESSION["userWins"] ?></p>
            <p>Computer's wins: <?= $_SESSION["computerWins"] ?></p>
        </div>

        <div class="flex flex-col justify-center items-center font-bold underline">
            <p><?= $userWon ? "Congratulations, " . $_SESSION["username"] . "! You won the game." : "" ?></p>
            <p><?= $computerWon ? "You lost the game!" : "" ?></p>
        </div>

        <form action="<?= hasMatchEnded() ? "../index.html" : "./match.php" ?>" method="POST" class="flex flex-col gap-2 justify-center items-center">
            <div>
                <label for="choice">Choose your number </label>

                <select id="choice" name="choice" class="border border-1 rounded-md">
                    <?php for ($i = 1; $i <= 9; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button 
                type="submit" 
                class="bg-blue-300 text-sm border border-1 px-4 py-1 rounded-md"
            >
                <?= hasMatchEnded() ? "Another match" : "Play" ?>
            </button>
        </form>
    </main>
</body>

</html>