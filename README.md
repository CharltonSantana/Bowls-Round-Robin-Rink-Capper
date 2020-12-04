# Bowls Round-Robin League Generation - Rink Capper
The problem with the standard round robin algorithm is you are limited to how many teams you have depending on how many rinks you have. 
This code helps you cap the amount of rinks by extending how many matches are played.

Standard Usage: 
```php
$rinkCap = 3;
$teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8'];

// Init new Round Robin
$roundRobin = new RoundRobin($teams);

// Get unrendered schedule
$schedule = $roundRobin->getSchedule();
$schedule = $roundRobin->capSchedule($schedule, $rinkCap);

echo $roundRobin->renderSchedule($schedule);
```


