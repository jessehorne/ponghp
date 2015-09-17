<?php

class Helper {
    public function error($errors) {
        foreach ($errors as $error) {
            echo "[ERROR] " . $error . "\n";
        }
        exit();
    }

    public function socket_error() {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("[$errorcode] $errormsg\n");
    }

    public function packet($data) {
        return json_encode($data);
    }

    public function validate($data) {
        foreach ($data as $i => $v) {
            if ($v == "mode") {
                if ($i != "fill" && $i != "line") {
                    Helper::error("Argument [$i] must be 'fill' or 'line'.");
                }
            }
            elseif ($v == "numeric") {
                if (!is_numeric($i)) {
                    Helper::error("Argument [$i] must be numeric");
                }
            }
            elseif ($v == "string") {
                if (!is_string($i)) {
                    Helper::error("Argument [$i] must be a string");
                }
            }
        }
    }

    public function bbox($x1, $y1, $w1, $h1, $x2, $y2, $w2, $h2) {
        return $x1 < $x2+$w2 and
               $x2 < $x1+$w1 and
               $y1 < $y2+$h2 and
               $y2 < $y1+$h1;
    }
}
