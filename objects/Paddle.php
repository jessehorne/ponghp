<?php

class Paddle {
    public $start_x, $start_y, $x, $y;
    public $width, $height;
    public $rgba;
    public $movingUp = false;
    public $movingDown = false;
    public $points = 0;
    public $points_x, $points_y;

    function __construct($x, $y, $width, $height, $rgba) {
        $this->start_x = $x;
        $this->start_y = $y;

        $this->x = $this->start_x;
        $this->y = $this->start_y;

        $this->rgba = $rgba;

        $this->width = $width;
        $this->height = $height;
    }

    public function update($dt) {
        if ($this->movingUp) {
            $this->y -= 400*$dt;
        } else if ($this->movingDown) {
            $this->y += 400*$dt;
        }
    }

    public function draw() {
        Game::set_color($this->rgba);
        Game::rectangle("fill", $this->x, $this->y, $this->width, $this->height);
        Game::set_color([255,255,255,255]);
        Game::print_string($this->points, $this->points_x, $this->points_y);
    }
}
