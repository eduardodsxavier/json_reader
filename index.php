<?php
/**
 * Renders any decoded JSON (scalar, associative array, list of objects, list of scalars,
 * nested structures, etc.) as an HTML table.  If a cell is another array/object,
 * it is rendered as a nested sub‐table.
 */

// A list of example JSON strings. Feel free to add or remove entries here:
$jsonStrings = [
    '{"name": "Alice", "age": 25}',

    '["apple", "banana", "cherry"]',

    // A perfect "list of objects" → multi‐row table:
    '[{"name": "Alice", "age": 25}, {"name": "Bob", "age": 30}]',

    // JSON #4: an object whose value is a nested object
    '{"person": {"name": "Bob", "details": {"age": 30, "city": "Paris"}}}',


    '{"person": [{"name": "Bob", "details": {"age": 30, "city": "Paris"}}, {"name": "alice", "details": {"age": 18, "city": "xique-xique bahia"}}]}',

    // JSON #5: an object with an array that mixes scalar and object
    '{"fruits": ["apple", {"type": "citrus", "name": "orange"}]}',

    '[]',

    '{"value": null}',

    '{"isValid": true, "isEmpty": false}',

    '{"int": 42, "float": 3.14, "scientific": 1e6}',

    '{"": "emptyKey", "123": "numberKey", "true": "boolKey"}',

    '{"quote": "She said: \\"Hello\\"", "newline": "Line1\\nLine2"}',

    '{"a": [{"b": {"c": [1, 2, {"d": "end"}]}}]}',

    '"Just a plain string, not JSON object or array"'
];

// HTML header + basic styling for tables:
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Structured JSON Tables</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h2 { margin-top: 40px; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
    th { background-color: #f2f2f2; text-align: left; }
    pre { margin: 0; white-space: pre-wrap; word-wrap: break-word; }
    .error { color: red; font-weight: bold; }
    .nested-table { margin: 10px 0; }
</style>";
echo "</head><body>";
echo "<h1>JSON → HTML Table (with nested structures)</h1>";

// -----------------------------------------------------------
// Loop over each JSON string, decode it, and render as table.
// -----------------------------------------------------------
foreach ($jsonStrings as $index => $json) {
    echo "<h2>JSON #".($index+1)."</h2>";
    echo "<p><strong>Original JSON:</strong><br><pre>" . htmlspecialchars($json) . "</pre></p>";

    $decoded = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Show decoding error
        echo "<p class='error'>Error decoding JSON: " . json_last_error_msg() . "</p>";
    } else {
        // Valid JSON → render it as a table (possibly with nested subtables)
        renderJsonAsTable($decoded);
    }

    echo "<hr>";
}

echo "</body></html>";


/**
 * Renders any PHP value (result of json_decode) as an HTML table.
 *
 * Rules:
 *  - If $value is a flat associative array → single‐row table (keys = columns).
 *  - If $value is a list of associative arrays → multi‐row table (union of all keys).
 *  - If $value is a list of scalars → one‐column table (“Value”).
 *  - Otherwise (nested or mixed) → one‐column table, each cell is passed back to renderJsonAsTable() recursively.
 *  - If $value is a lone scalar (string/int/float/bool/null) → one‐cell table.
 */
function renderJsonAsTable($value) {
    // Helper: check if an array is a “list” (PHP 8.1 has array_is_list, but this works on older versions):
    $array_is_list = function(array $arr): bool {
        return array_keys($arr) === range(0, count($arr) - 1);
    };

    // If we have an array at all:
    if (is_array($value)) {
        if ($array_is_list($value)) {
            // It's a numeric‐indexed “list”
            //  • Maybe a list of assoc arrays (→ perfect table)
            //  • Maybe a list of scalars (→ one‐column)
            //  • Otherwise a mixed/nested list (→ one‐column of nested tables)

            // Check if EVERY element is itself an associative array:
            $allAssoc = true;
            foreach ($value as $elem) {
                if (!is_array($elem) || $array_is_list($elem)) {
                    $allAssoc = false;
                    break;
                }
            }

            if ($allAssoc && count($value) > 0) {
                // Case: list of associative arrays 
                renderListOfAssocAsTable($value, $array_is_list);
            }
            else {
                // Not a “list of assoc arrays.” Check if this list is purely scalars:
                $allScalars = true;
                foreach ($value as $elem) {
                    if (!is_scalar($elem) && !is_null($elem)) {
                        $allScalars = false;
                        break;
                    }
                }
                if ($allScalars) {
                    // Case: list of scalars
                    renderListOfScalarsAsTable($value);
                } else {
                    // Case: mixed or nested → one‐column table, each cell recurses
                    renderListOfMixedAsTable($value);
                }
            }
        } else {
            // It's an associative array (object‐like)
            renderAssocAsTable($value, $array_is_list);
        }
    }
    else {
        // It's a lone scalar (string/int/float/bool/null)
        renderScalarAsTable($value);
    }
}

/**
 * Renders a single‐row table for an associative array.
 *	If a value is itself an array, we recursively call renderJsonAsTable()
 *	and place that nested table inside the <td>.
 */
function renderAssocAsTable(array $assoc, callable $array_is_list) {
    // Collect column headers:
    $columns = array_keys($assoc);

    echo "<table class='nested-table'>";
    echo "<tr>";
    foreach ($columns as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr><tr>";
    foreach ($columns as $col) {
        echo "<td>";
        $cell = $assoc[$col];
        if (is_array($cell)) {
            // Nested array/object → render as a sub‐table
            renderJsonAsTable($cell);
        }
        elseif (is_null($cell)) {
            echo "<pre style='margin:0;'>null</pre>";
        }
        elseif (is_bool($cell)) {
            echo "<pre style='margin:0;'>" . ($cell ? "true" : "false") . "</pre>";
        }
        else {
            // string or number
            echo "<pre style='margin:0;'>" . htmlspecialchars((string)$cell) . "</pre>";
        }
        echo "</td>";
    }
    echo "</tr></table>";
}

/**
 * Renders a multi‐row table for a “list of associative arrays.”
 * Finds the union of all keys, uses them as columns, and prints each element as a row.
 * If a cell is an array, recurses into renderJsonAsTable().
 */
function renderListOfAssocAsTable(array $listOfAssoc, callable $array_is_list) {
    // 1) Collect all unique keys across every row:
    $allKeys = [];
    foreach ($listOfAssoc as $row) {
        foreach (array_keys($row) as $k) {
            if (!in_array($k, $allKeys, true)) {
                $allKeys[] = $k;
            }
        }
    }

    // 2) Render header row:
    echo "<table class='nested-table'>";
    echo "<tr><th>#</th>";
    foreach ($allKeys as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr>";

    // 3) Render each data row:
    foreach ($listOfAssoc as $i => $row) {
        echo "<tr><td>" . ($i + 1) . "</td>";
        foreach ($allKeys as $col) {
            echo "<td>";
            if (!array_key_exists($col, $row)) {
                // Missing field → blank cell
                echo "";
            } else {
                $cell = $row[$col];
                if (is_array($cell)) {
                    // Nested array/object → recursive subtable
                    renderJsonAsTable($cell);
                }
                elseif (is_null($cell)) {
                    echo "<pre style='margin:0;'>null</pre>";
                }
                elseif (is_bool($cell)) {
                    echo "<pre style='margin:0;'>" . ($cell ? "true" : "false") . "</pre>";
                }
                else {
                    // string or number
                    echo "<pre style='margin:0;'>" . htmlspecialchars((string)$cell) . "</pre>";
                }
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

/**
 * Renders a single‐column table for a list of scalars (string/int/float/bool/null).
 */
function renderListOfScalarsAsTable(array $list) {
    echo "<table class='nested-table'>";
    echo "<tr><th>Value</th></tr>";
    foreach ($list as $elem) {
        echo "<tr><td>";
        if (is_null($elem)) {
            echo "<pre style='margin:0;'>null</pre>";
        }
        elseif (is_bool($elem)) {
            echo "<pre style='margin:0;'>" . ($elem ? "true" : "false") . "</pre>";
        }
        else {
            echo "<pre style='margin:0;'>" . htmlspecialchars((string)$elem) . "</pre>";
        }
        echo "</td></tr>";
    }
    echo "</table>";
}

/**
 * Renders a single‐column table for a “mixed or nested” list:
 * Each element is either a scalar or some array.  
 * Scalars are printed directly; arrays recurse into renderJsonAsTable().
 */
function renderListOfMixedAsTable(array $list) {
    echo "<table class='nested-table'>";
    echo "<tr><th>Value</th></tr>";
    foreach ($list as $elem) {
        echo "<tr><td>";
        if (is_array($elem)) {
            // Nested structure → recursive subtable
            renderJsonAsTable($elem);
        }
        elseif (is_null($elem)) {
            echo "<pre style='margin:0;'>null</pre>";
        }
        elseif (is_bool($elem)) {
            echo "<pre style='margin:0;'>" . ($elem ? "true" : "false") . "</pre>";
        }
        else {
            // scalar
            echo "<pre style='margin:0;'>" . htmlspecialchars((string)$elem) . "</pre>";
        }
        echo "</td></tr>";
    }
    echo "</table>";
}

/**
 * Renders a one‐cell table for a lone scalar (string/int/float/bool/null).
 */
function renderScalarAsTable($scalar) {
    echo "<table class='nested-table'>";
    echo "<tr><th>Value</th></tr><tr><td>";
    if (is_null($scalar)) {
        echo "<pre style='margin:0;'>null</pre>";
    }
    elseif (is_bool($scalar)) {
        echo "<pre style='margin:0;'>" . ($scalar ? "true" : "false") . "</pre>";
    }
    else {
        echo "<pre style='margin:0;'>" . htmlspecialchars((string)$scalar) . "</pre>";
    }
    echo "</td></tr></table>";
}

