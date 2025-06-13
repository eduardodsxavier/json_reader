<?php
class JsonRender {
    public function renderJson($value) {
        if (!is_array($value)) {
            $this->renderScalar($value);
            return;
        } 

        if (!array_is_list($value)) { 
            $this->renderObject($value);
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
            $this->renderListOfObjects($value);
            return;
        }

        foreach ($value as $elem) {
            if (!is_scalar($elem)) {
                $this->renderListOfMixed($value);
                return;
            }
        }

        $this->renderListOfScalars($value);
    }

    private function renderObject($assoc) {
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
                $this->renderJson($cell);
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

    private function renderListOfObjects($listOfAssoc) {
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
                   $this->renderJson($cell);
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

    private function renderListOfScalars($list) {
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

    private function renderListOfMixed($list) {
        echo "<table class='nested-table'>";
        echo "<tr><th>Value</th></tr>";
        foreach ($list as $elem) {
            echo "<tr><td>";
            if (is_array($elem)) {
                $this->renderJson($elem);
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

    private function renderScalar($scalar) {
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
}
?>
