<?php
class SimpleCalendar {
    private $currentYear;
    private $currentMonth;
    private $sessions = [];

    public function __construct($year = null, $month = null) {
        $this->currentYear = $year ?: date("Y");
        $this->currentMonth = $month ?: date("m");
    }

    // Add an event
    public function addSession($eventText, $eventDate, $sessionId, $tutorId, $cssClass = '') {
        $this->sessions[$eventDate][] = ['text' => $eventText, 'session' => $sessionId, 'tutor' => $tutorId, 'class' => $cssClass];
    }

    public function render() {
        // Days of the week
        $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        // First day of the month
        $firstDayOfMonth = mktime(0, 0, 0, $this->currentMonth, 1, $this->currentYear);
        // Number of days in the month
        $numberDays = date("t", $firstDayOfMonth);
        // Getting the numeric representation of the day of the week of the first day of the month
        $dayOfWeek = date("w", $firstDayOfMonth);

        $dateToday = date("Y-m-d");

        // Create the opening wrapper divs
        $calendar = "<div class='calendar'>";

        // Create the header
        $calendar .= "<div class='header'><h2>".date("F Y", $firstDayOfMonth)."</h2></div>";
        $calendar .= "<div class='body'>";
        // Create the header row for the days of the week
        foreach($daysOfWeek as $day) {
            $calendar .= "<div class='day_name'>$day</div>";
        }

        // The variable $dayOfWeek will make sure that there must be only 7 days on our calendar
        if ($dayOfWeek > 0) {
            for($k = 0; $k < $dayOfWeek; $k++){
                $calendar .= "<div class='day_num ignore'></div>";
            }
        }

        $currentDay = 1;

        while ($currentDay <= $numberDays) {
            // Seventh column (Saturday) reached. Start a new row.
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$this->currentYear-$this->currentMonth-$currentDayRel";

            $calendar .= "<div class='day_num".($dateToday == $date ? " selected" : "")."'>";
            $calendar .= "<span>$currentDay</span>";

            if (isset($this->sessions[$date])) {
                foreach ($this->sessions[$date] as $event) {
                    $calendar .= "<div class='event " . $event['class'] . "'>" . $event['text'] . "<a href='ViewSession.php?sessionId=" . $event['session'] . "&tutorId=" . $event['tutor'] . "'>" . "View Session" . "</a>" . "</div>";
                    
                }
            }

            $calendar .= "</div>";

            // Increment counters
            $currentDay++;
            $dayOfWeek++;
        }

        // Complete the row of the last week in month if necessary
        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for($l=1; $l <= $remainingDays; $l++){
                $calendar .= "<div class='day_num ignore'></div>";
            }
        }

        $calendar .= "</div>"; // Close the body div
        $calendar .= "</div>"; // Close the calendar div

        return $calendar;
    }
}
?>