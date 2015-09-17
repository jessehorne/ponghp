<?php

class Graphics {
    public function rectangle($mode, $x, $y, $width, $height) {
        $status = Helper::validate([
            $mode => "mode",
            $x => "numeric",
            $y => "numeric",
            $width => "numeric",
            $height => "numeric"
        ]);

        Helper::send(MyGame::$sock, [
            "command" => "rectangle",
            "args" => [$mode, $x, $y, $width, $height]
        ]);
    }
}
