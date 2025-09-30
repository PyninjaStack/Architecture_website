<?php
function renderYearDropdown($name, $selectedYear = null) {
    $start = 2015;
    $end = 2115;
    echo "<select name='{$name}' id='{$name}' required>";
    echo "<option value=''>Select Year</option>";
    for ($y = $start; $y <= $end; $y++) {
        $sel = ($selectedYear == $y) ? "selected" : "";
        echo "<option value='{$y}' {$sel}>{$y}</option>";
    }
    echo "</select>";
}
