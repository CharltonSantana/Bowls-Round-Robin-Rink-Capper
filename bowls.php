<style>
table, td, th {
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 15px;
}
tr:nth-child(even){background-color: #f2f2f2}

input.submit-button {
	width: 100px;
    margin: 0px 0px 0px 115px;
}

input, textarea {
	padding: 5px;
	width: 471px;
	font-family: Helvetica, sans-serif;
	font-size: 1.4em;
	margin: 0px 0px 10px 0px;
	border: 2px solid #ccc;
}

textarea {
	height: 200px;
}

textarea:focus, input:focus {
	border: 2px solid #900;
}

label {
	float: left;
	text-align: right;
	margin-right: 15px;
	width: 100px;
	padding-top: 5px;
	font-size: 1.4em;
}
</style>


<?php
if(isset($_GET['Teams']) && isset($_GET['Rinks'])){
    include 'roundrobin.php';

    $text = trim($_GET['Teams']);
    $textAr = explode("\n", $text);
    $teams = array_filter($textAr, 'trim');

    //$teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8', 'Team 9', 'Team 10', 'Team 11', 'Team 12'];
    $rinkCap = $_GET['Rinks'];

    if(count($teams) / 2 < $rinkCap){
        echo "There are too many rinks inputed for the amount of teams!";
        die();
    }

    // Init new Round Robin
    $roundRobin = new RoundRobin($teams);

    // Get unrendered schedule
    $schedule = $roundRobin->getSchedule();
    $schedule = $roundRobin->capSchedule($schedule, $rinkCap);

    echo $roundRobin->renderSchedule($schedule);

    echo '<h1 style="color:red;">'.$roundRobin->errors.'</h1>';


} else {
    ?>
    <form>
    <br><br>
        <form method="GET" action="bowls.php">
            <label for="Rinks">Rinks:</label>
            <input type="neumeric" name="Rinks" id="Name" value="3" required>
            <br>
            <label for="Teams">Teams:</label><br />
            <textarea name="Teams" rows="50" cols="20" id="Message" required>
Team 1
Team 2
Team 3
Team 4
Team 5
Team 6
Team 7
Team 8
            </textarea>
            <br>
            <input type="submit" name="submit" value="Submit" class="submit-button" />
        </form>
    </form>

    <?php
}
