<?php

require("read_json.php");

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
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>';
echo "</head><body>";

foreach ($jsonStrings as $index => $json) {
    $json_render = new jsonRender();
    $json_render->render($json);
    break;
}

echo "</body></html>";
?>
