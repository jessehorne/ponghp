<?php

class Ball {
    public $start_x, $start_y, $x, $y;
    public $w, $h;
    public $x_speed, $y_speed;
    public $rgba;

    function __construct($x, $y, $rgba) {
        $this->w = 20;
        $this->h = 20;

        $this->start_x = $x;
        $this->start_y = $y;

        $this->x = $this->start_x;
        $this->y = $this->start_y;

        $this->x_speed = 500;
        $this->y_speed = 500;

        $this->rgba = $rgba;
    }

    public function update($dt) {
        $oldX = $this->x;
        $oldY = $this->y;

        $this->x += $this->x_speed*$dt;
        $this->y += $this->y_speed*$dt;

        if ($this->y <= 0 or $this->y >= Game::$config["height"] - $this->h) {
            $this->y = $oldY;
            $this->y_speed = -$this->y_speed;
            Game::play_sound("wall");
        }
    }

    public function draw() {
        Game::set_color($this->rgba);
        Game::rectangle("fill", $this->x, $this->y, $this->w, $this->h);
        Game::set_color([255,255,255,255]);
    }
}
