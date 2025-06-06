<?php
$jsonStrings = [
    '{"name": "Alice", "age": 25}',

    '["apple", "banana", "cherry"]',

    '[{"name": "Alice", "age": 25}, {"name": "Bob", "age": 30}]',

    '{"person": {"name": "Bob", "details": {"age": 30, "city": "Paris"}}}',

    '{"person": [{"name": "Bob", "details": {"age": 30, "born_in": {"city": "paris", "country": "france"}}}, {"name": "alice", "details": {"age": 18, "city": "xique-xique bahia"}}]}',

    '{"fruits": ["apple", {"type": "citrus", "name": "orange"}]}',

    '[]',

    '{"value": null}',

    '{"isValid": true, "isEmpty": false}',

    '{"int": 42, "float": 3.14, "scientific": 1e6}',

    '{"": "emptyKey", "123": "numberKey", "true": "boolKey"}',

    '{"quote": "She said: \\"Hello\\"", "newline": "Line1\\nLine2"}',

    '{"a": [{"b": {"c": [1, 2, {"d": "end"}]}}]}',

    '"Just a plain string, not JSON object or array"',

    '{}',

    '[ [1, 2, 3], ["x", "y", "z"] ]',

    '[ 1, {"a": [2, 3]}, true, null, ["nested", {"b": false}] ]',

    '{"message": "こんにちは", "escape": "\\tTabbed"}',

    '{"0": "zero", "1.5": "one point five", "-1": "minus one"}',

    '[ {}, {} ]',

    '{"organization": {
    "name": "GlobalTech",
        "founded": 2001,
        "isActive": true,
        "departments": [
            {
                "deptName": "Research & Development",
                    "employees": [
                        {
                            "id": 101,
                                "name": "Dr. Alice Smith",
                                "role": "Lead Scientist",
                                "skills": ["AI", "ML", "Data Science"],
                                "contact": {
                                "email": "alice.smith@globaltech.com",
                                    "phones": ["+1-555-0100", "+1-555-0101"]
                        },
                        "projects": [
                            {
                                "projName": "Project X",
                                    "startDate": "2020-01-15",
                                    "endDate": null,
                                    "tasks": [
                                        {
                                            "taskId": 1,
                                                "description": "Literature Review",
                                                "status": "completed"
                                    },
                                    {
                                        "taskId": 2,
                                            "description": "Prototype Design",
                                            "status": "inProgress",
                                            "subtasks": [
                                                {
                                                    "subtaskId": 2.1,
                                                        "desc": "UI Mockup",
                                                        "done": true
                                            },
                                            {
                                                "subtaskId": 2.2,
                                                    "desc": "Algorithm Draft",
                                                    "done": false
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                            {
                                "id": 102,
                                    "name": "Bob Johnson",
                                    "role": "Research Engineer",
                                    "skills": ["Python", "C++"],
                                    "contact": {
                                    "email": "bob.johnson@globaltech.com",
                                        "phones": ["+1-555-0110"]
                        },
                        "projects": []
                    }
                ]
            },
                    {
                        "deptName": "Sales",
                            "employees": [
                    {
                        "id": 201,
                            "name": "Carol Lee",
                            "role": "Sales Manager",
                            "targets": [
                    {"year": 2023, "amount": 500000},
                    {"year": 2024, "amount": 600000}
],
"regions": ["North America", "Europe"],
"contact": {
"email": "carol.lee@globaltech.com"
                        }
                    },
                        {
                            "id": 202,
                                "name": "David Perez",
                                "role": "Sales Representative",
                                "targets": [],
                                "regions": ["Asia"],
                                "contact": {
                                "email": "david.perez@globaltech.com",
                                    "fax": null
                        }
                    }
                ]
            }
        ],
            "offices": [
            {
                "location": "New York",
                    "address": {
                    "street": "123 Fifth Ave",
                        "city": "New York",
                        "zip": "10001"
                }
            },
                {
                    "location": "Berlin",
                        "address": {
                        "street": "Unter den Linden 10",
                            "city": "Berlin",
                            "zip": "10117"
                }
            },
                {
                    "location": "Tokyo",
                        "address": {
                        "street": "1-1-1 Marunouchi",
                            "city": "Chiyoda",
                            "zip": "100-0005"
                }
            }
        ]
    }}'
];

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

foreach ($jsonStrings as $index => $json) {
    echo "<p><strong>Original JSON:</strong><br><pre>" . htmlspecialchars($json) . "</pre></p>";

    try {
        $decoded = json_decode($json, true);
        renderJson($decoded);
    } catch(Exception $err) {
        echo "<p class='error'>Error decoding JSON: " . $err . "</p>";
    } finally { 
        echo "<hr>";
    }

}

echo "</body></html>";


function renderJson($value) {
    if (!is_array($value)) {
        renderScalar($value);
        return;
    } 

    if (!array_is_list($value)) { 
        renderObject($value);
        return;
    }

    $allAssoc = true;
    foreach ($value as $elem) {
        if (!is_array($elem)) {
            $allAssoc = false;
            break;
        }
    }

    if ($allAssoc) {
        renderListOfObjects($value);
        return;
    }

    foreach ($value as $elem) {
        if (!is_scalar($elem)) {
            renderListOfMixed($value);
            return;
        }
    }

    renderListOfScalars($value);
}

function renderObject(array $assoc) {
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
            renderJson($cell);
        }
        elseif (is_null($cell)) {
            echo "<pre style='margin:0;'>null</pre>";
        }
        elseif (is_bool($cell)) {
            echo "<pre style='margin:0;'>" . ($cell ? "true" : "false") . "</pre>";
        }
        else {
            echo "<pre style='margin:0;'>" . htmlspecialchars((string)$cell) . "</pre>";
        }
        echo "</td>";
    }
    echo "</tr></table>";
}

function renderListOfObjects(array $listOfAssoc) {
    $allKeys = [];
    foreach ($listOfAssoc as $row) {
        foreach (array_keys($row) as $k) {
            if (!in_array($k, $allKeys, true)) {
                $allKeys[] = $k;
            }
        }
    }

    echo "<table class='nested-table'>";
    echo "<tr><th>#</th>";
    foreach ($allKeys as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr>";

    foreach ($listOfAssoc as $i => $row) {
        echo "<tr><td>" . ($i + 1) . "</td>";
        foreach ($allKeys as $col) {
            echo "<td>";
            if (!array_key_exists($col, $row)) {
                echo "</td>";
                continue;
            } 

            $cell = $row[$col];
            if (is_array($cell)) {
                renderJson($cell);
            }
            elseif (is_null($cell)) {
                echo "<pre style='margin:0;'>null</pre>";
            }
            elseif (is_bool($cell)) {
                echo "<pre style='margin:0;'>" . ($cell ? "true" : "false") . "</pre>";
            }
            else {
                echo "<pre style='margin:0;'>" . htmlspecialchars((string)$cell) . "</pre>";
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function renderListOfScalars(array $list) {
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

function renderListOfMixed(array $list) {
    echo "<table class='nested-table'>";
    echo "<tr><th>Value</th></tr>";
    foreach ($list as $elem) {
        echo "<tr><td>";
        if (is_array($elem)) {
            renderJson($elem);
        }
        elseif (is_null($elem)) {
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

function renderScalar($scalar) {
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
