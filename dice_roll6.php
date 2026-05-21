<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dice Roll Deluxe</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;

    background:
    linear-gradient(135deg,#432371,#faae7b);

    overflow-x:hidden;
}

/* Main Card */

.container{
    width:100%;
    max-width:900px;

    background:rgba(255,255,255,0.15);

    backdrop-filter:blur(14px);

    border:1px solid rgba(255,255,255,0.2);

    border-radius:28px;

    padding:40px;

    box-shadow:0 20px 50px rgba(0,0,0,0.25);

    color:white;
}

/* Title */

h1{
    text-align:center;
    font-size:3rem;
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    margin-bottom:35px;
    color:#f0f0f0;
}

/* Dice Area */

.dice-wrapper{
    display:flex;
    justify-content:center;
    gap:40px;
    margin-bottom:35px;
    flex-wrap:wrap;
}

.dice{
    width:140px;
    height:140px;

    background:white;

    color:#222;

    border-radius:25px;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:5rem;

    box-shadow:0 10px 25px rgba(0,0,0,0.2);

    transition:0.3s ease;
}

.dice:hover{
    transform:translateY(-5px) scale(1.04);
}

/* Dice Animation */

.roll{
    animation:shake 0.7s ease;
}

@keyframes shake{

    0%{
        transform:rotate(0deg);
    }

    25%{
        transform:rotate(8deg);
    }

    50%{
        transform:rotate(-8deg);
    }

    75%{
        transform:rotate(5deg);
    }

    100%{
        transform:rotate(0deg);
    }

}

/* Buttons */

.button-area{
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:35px;
}

button{
    border:none;
    padding:15px 30px;
    border-radius:14px;
    cursor:pointer;
    font-size:1rem;
    font-weight:bold;
    transition:0.3s ease;
}

.roll-btn{
    background:#ff7b54;
    color:white;
}

.roll-btn:hover{
    background:#ff6333;
    transform:scale(1.05);
}

.reset-btn{
    background:white;
    color:#333;
}

.reset-btn:hover{
    transform:scale(1.05);
}

/* Results */

.results{
    background:rgba(255,255,255,0.1);

    border-radius:20px;

    padding:25px;

    text-align:center;

    margin-bottom:35px;
}

.results h2{
    margin-bottom:10px;
    font-size:2rem;
}

.message{
    font-size:1.2rem;
}

/* Stats */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:20px;
    margin-bottom:35px;
}

.stat-card{
    background:rgba(255,255,255,0.1);

    padding:20px;

    border-radius:20px;

    text-align:center;
}

.stat-card h3{
    margin-bottom:10px;
}

.stat-card p{
    font-size:2rem;
    font-weight:bold;
}

/* Roll History */

.history{
    background:rgba(255,255,255,0.1);

    padding:25px;

    border-radius:20px;
}

.history h2{
    margin-bottom:20px;
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:12px;
    text-align:center;
}

th{
    background:rgba(255,255,255,0.1);
}

tr:nth-child(even){
    background:rgba(255,255,255,0.05);
}

/* Mobile */

@media(max-width:700px){

    h1{
        font-size:2.2rem;
    }

    .dice{
        width:100px;
        height:100px;
        font-size:4rem;
    }

}

</style>
</head>
<body>

<?php

session_start();

/* Session Setup */

if(!isset($_SESSION['rolls'])){
    $_SESSION['rolls'] = 0;
}

if(!isset($_SESSION['doubles'])){
    $_SESSION['doubles'] = 0;
}

if(!isset($_SESSION['bestStreak'])){
    $_SESSION['bestStreak'] = 0;
}

if(!isset($_SESSION['currentStreak'])){
    $_SESSION['currentStreak'] = 0;
}

if(!isset($_SESSION['history'])){
    $_SESSION['history'] = [];
}

/* Reset */

if(isset($_POST['reset'])){

    session_destroy();

    header("Location: " . $_SERVER['PHP_SELF']);

    exit();
}

/* Dice Faces */

$diceFaces = [
    1 => "⚀",
    2 => "⚁",
    3 => "⚂",
    4 => "⚃",
    5 => "⚄",
    6 => "⚅"
];

/* Default */

$die1 = 1;
$die2 = 1;
$total = 2;
$message = "Click Roll Dice to begin!";
$animate = false;

/* Roll Logic */

if(isset($_POST['roll'])){

    $animate = true;

    $die1 = rand(1,6);
    $die2 = rand(1,6);

    $total = $die1 + $die2;

    $_SESSION['rolls']++;

    /* Doubles */

    if($die1 === $die2){

        $_SESSION['doubles']++;

        $message = "🔥 DOUBLE ROLL!";

    }else{

        switch($total){

            case 2:
                $message = "🐍 Snake Eyes!";
                break;

            case 3:
                $message = "🎯 Loose Deuce!";
                break;

            case 5:
                $message = "🔥 Fever Five!";
                break;

            case 7:
                $message = "🎲 Natural Seven!";
                break;

            case 9:
                $message = "⭐ Nina!";
                break;

            case 11:
                $message = "🎉 Yo Eleven!";
                break;

            case 12:
                $message = "🚂 Boxcars!";
                break;

            default:
                $message = "🎲 Roll Again!";
        }

    }

    /* Streak Logic */

    if($total == 7 || $total == 11){

        $_SESSION['currentStreak']++;

        if($_SESSION['currentStreak'] > $_SESSION['bestStreak']){

            $_SESSION['bestStreak'] = $_SESSION['currentStreak'];

        }

    }else{

        $_SESSION['currentStreak'] = 0;
    }

    /* Save History */

    $_SESSION['history'][] = [
        'die1' => $die1,
        'die2' => $die2,
        'total' => $total
    ];

}

?>

<div class="container">

    <h1>🎲 Dice Roll Deluxe</h1>

    <p class="subtitle">
        Interactive Casino Style Dice Game
    </p>

    <div class="dice-wrapper">

        <div class="dice <?php echo $animate ? 'roll' : ''; ?>">
            <?php echo $diceFaces[$die1]; ?>
        </div>

        <div class="dice <?php echo $animate ? 'roll' : ''; ?>">
            <?php echo $diceFaces[$die2]; ?>
        </div>

    </div>

    <div class="button-area">

        <form method="POST">

            <button type="submit" name="roll" class="roll-btn">
                Roll Dice
            </button>

        </form>

        <form method="POST">

            <button type="submit" name="reset" class="reset-btn">
                Reset Game
            </button>

        </form>

    </div>

    <div class="results">

        <h2>Total Score: <?php echo $total; ?></h2>

        <p class="message">
            <?php echo $message; ?>
        </p>

    </div>

    <div class="stats">

        <div class="stat-card">
            <h3>Total Rolls</h3>
            <p><?php echo $_SESSION['rolls']; ?></p>
        </div>

        <div class="stat-card">
            <h3>Doubles Rolled</h3>
            <p><?php echo $_SESSION['doubles']; ?></p>
        </div>

        <div class="stat-card">
            <h3>Current Streak</h3>
            <p><?php echo $_SESSION['currentStreak']; ?></p>
        </div>

        <div class="stat-card">
            <h3>Best Streak</h3>
            <p><?php echo $_SESSION['bestStreak']; ?></p>
        </div>

    </div>

    <div class="history">

        <h2>📜 Roll History</h2>

        <table>

            <tr>
                <th>#</th>
                <th>Die 1</th>
                <th>Die 2</th>
                <th>Total</th>
            </tr>

            <?php

            $count = 1;

            foreach(array_reverse($_SESSION['history']) as $roll){

                echo "
                <tr>
                    <td>$count</td>
                    <td>{$diceFaces[$roll['die1']]}</td>
                    <td>{$diceFaces[$roll['die2']]}</td>
                    <td>{$roll['total']}</td>
                </tr>
                ";

                $count++;
            }

            ?>

        </table>

    </div>

</div>

</body>
</html>