<?php
class JsonRender {
    public function render($json) {
        try {
            $decoded = json_decode($json, true);
            $this->renderJson($decoded);
        } catch (Exception $err) {
            echo "<div class='alert alert-danger'>Error decoding JSON: " . htmlspecialchars($err->getMessage()) . "</div>";
        } finally {
            echo "<hr class='my-4'>";
        }
    }

    private function renderJson($value) {
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

    private function renderObject(array $assoc) {
        $columns = array_keys($assoc);

        echo "<table class='table table-bordered my-3'>";
        echo "<thead class='table-light'><tr>";
        foreach ($columns as $col) {
            echo "<th scope='col'>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead><tbody><tr>";
        foreach ($columns as $col) {
            echo "<td>";
            $cell = $assoc[$col];
            if (is_array($cell)) {
                $this->renderJson($cell);
            } elseif (is_null($cell)) {
                echo "<pre class='mb-0'>null</pre>";
            } elseif (is_bool($cell)) {
                echo "<pre class='mb-0'>" . ($cell ? "true" : "false") . "</pre>";
            } else {
                echo "<pre class='mb-0'>" . htmlspecialchars((string)$cell) . "</pre>";
            }
            echo "</td>";
        }
        echo "</tr></tbody></table>";
    }

    private function renderListOfObjects(array $listOfAssoc) {
        $allKeys = [];
        foreach ($listOfAssoc as $row) {
            foreach (array_keys($row) as $k) {
                if (!in_array($k, $allKeys, true)) {
                    $allKeys[] = $k;
                }
            }
        }

        echo "<table class='table table-striped my-3'>";
        echo "<thead class='table-light'><tr><th scope='col'>#</th>";
        foreach ($allKeys as $col) {
            echo "<th scope='col'>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead><tbody>";

        foreach ($listOfAssoc as $i => $row) {
            echo "<tr><th scope='row'>" . ($i + 1) . "</th>";
            foreach ($allKeys as $col) {
                echo "<td>";
                if (!array_key_exists($col, $row)) {
                    echo "</td>";
                    continue;
                }

                $cell = $row[$col];
                if (is_array($cell)) {
                    $this->renderJson($cell);
                } elseif (is_null($cell)) {
                    echo "<pre class='mb-0'>null</pre>";
                } elseif (is_bool($cell)) {
                    echo "<pre class='mb-0'>" . ($cell ? "true" : "false") . "</pre>";
                } else {
                    echo "<pre class='mb-0'>" . htmlspecialchars((string)$cell) . "</pre>";
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    private function renderListOfScalars(array $list) {
        echo "<table class='table table-bordered my-3'>";
        echo "<thead class='table-light'><tr><th scope='col'>Value</th></tr></thead><tbody>";
        foreach ($list as $elem) {
            echo "<tr><td>";
            if (is_null($elem)) {
                echo "<pre class='mb-0'>null</pre>";
            } elseif (is_bool($elem)) {
                echo "<pre class='mb-0'>" . ($elem ? "true" : "false") . "</pre>";
            } else {
                echo "<pre class='mb-0'>" . htmlspecialchars((string)$elem) . "</pre>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    }

    private function renderListOfMixed(array $list) {
        echo "<table class='table table-bordered my-3'>";
        echo "<thead class='table-light'><tr><th scope='col'>Value</th></tr></thead><tbody>";
        foreach ($list as $elem) {
            echo "<tr><td>";
            if (is_array($elem)) {
                $this->renderJson($elem);
            } elseif (is_null($elem)) {
                echo "<pre class='mb-0'>null</pre>";
            } elseif (is_bool($elem)) {
                echo "<pre class='mb-0'>" . ($elem ? "true" : "false") . "</pre>";
            } else {
                echo "<pre class='mb-0'>" . htmlspecialchars((string)$elem) . "</pre>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    }

    private function renderScalar($scalar) {
        echo "<table class='table table-bordered my-3'>";
        echo "<thead class='table-light'><tr><th scope='col'>Value</th></tr></thead>";
        echo "<tbody><tr><td>";
        if (is_null($scalar)) {
            echo "<pre class='mb-0'>null</pre>";
        } elseif (is_bool($scalar)) {
            echo "<pre class='mb-0'>" . ($scalar ? "true" : "false") . "</pre>";
        } else {
            echo "<pre class='mb-0'>" . htmlspecialchars((string)$scalar) . "</pre>";
        }
        echo "</td></tr></tbody></table>";
    }
}
?>
