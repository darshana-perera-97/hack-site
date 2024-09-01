<?php
// Define the file path where the counter will be stored
$filePath = './counter.txt';

// Check if the file exists
if (file_exists($filePath)) {
    // Read the current counter value
    $currentCount = file_get_contents($filePath);

    // Ensure the current count is a number
    $currentCount = is_numeric($currentCount) ? (int)$currentCount : 0;

    // Increment the counter
    $currentCount++;
} else {
    // If file doesn't exist, start the counter at 1
    $currentCount = 1;
}

// Save the updated counter value back to the file
file_put_contents($filePath, $currentCount);

// Output the current count (optional)
echo "The website has been loaded " . $currentCount . " times.";
