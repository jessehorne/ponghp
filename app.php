<?php

include("./Billy/Game.php");

include("./objects/Ball.php");
include("./objects/Paddle.php");

class MyGame extends Game {
    protected $ball;
    protected $paddles = [];
    protected $waitForNewRound = true;
    protected $newRoundTimer = 0;
    protected $waitTime = 3;

    function __construct() {
        parent::__construct();

        // Ball
        $x = Game::$config["width"]/2-10;
        $y = Game::$config["height"]/2-10;
        $this->ball = new Ball($x, $y, [255,255,255,255]);

        // Paddle 1
        $width = 10;
        $height = 60;
        $x = 20;
        $y = Game::$config["height"]/2 - $height/2;

        $this->paddles[0] = new Paddle($x, $y, $width, $height, [255,255,255,255]);
        $this->paddles[0]->points_x = 300;
        $this->paddles[0]->points_y = 20;

        // Paddle 2
        $width = 10;
        $height = 60;
        $x = Game::$config["width"]-20-$width;
        $y = Game::$config["height"]/2 - $height/2;

        $this->paddles[1] = new Paddle($x, $y, $width, $height, [255,255,255,255]);
        $this->paddles[1]->points_x = 700;
        $this->paddles[1]->points_y = 20;

        Game::new_font("font1", "assets/font.ttf", 32);
        Game::set_font("font1");

        Game::new_sound("paddle", "assets/paddle.ogg", "static");
        Game::new_sound("wall", "assets/wall.ogg", "static");
        Game::new_sound("win", "assets/win.ogg", "static");

        $this->init();
    }

    protected function update($dt) {
        if ($this->waitForNewRound) {
            $this->newRoundTimer += $dt;
            if ($this->newRoundTimer >= $this->waitTime) {
                $this->newRoundTimer = 0;
                $this->waitForNewRound = false;
            } else {
                return;
            }
        }
        $this->ball->update($dt);

        foreach ($this->paddles as $paddle) {
            $paddle->update($dt);

            if (Helper::bbox($this->ball->x, $this->ball->y, $this->ball->w, $this->ball->h, $paddle->x, $paddle->y, $paddle->width, $paddle->height)) {
                $this->ball->x = $this->ball->oldX;
                $this->ball->y = $this->ball->oldY;
                $this->ball->x_speed = -$this->ball->x_speed;
                Game::play_sound("paddle");
            }
        }

        $this->ball->oldX = $this->ball->x;
        $this->ball->oldY = $this->ball->y;

        // get pointz
        if ($this->ball->x < 2) $this->win(1);
        if ($this->ball->x > ($this->paddles[1]->x+$this->paddles[1]->width)-2) $this->win(0);
    }

    protected function draw() {
        $this->ball->draw();

        foreach ($this->paddles as $paddle) {
            $paddle->draw();
        }
    }

    protected function key_pressed($key) {
        if ($key == "w") {
            $this->paddles[0]->movingUp = true;
            $this->paddles[0]->movingDown = false;
        } elseif ($key == "s") {
            $this->paddles[0]->movingUp = false;
            $this->paddles[0]->movingDown = true;
        }

        if ($key == "up") {
            $this->paddles[1]->movingUp = true;
            $this->paddles[1]->movingDown = false;
        } elseif ($key == "down") {
            $this->paddles[1]->movingUp = false;
            $this->paddles[1]->movingDown = true;
        }
    }

    protected function key_released($key) {
        if ($key == "w") {
            $this->paddles[0]->movingUp = false;
            $this->paddles[0]->movingDown = false;
        } elseif ($key == "s") {
            $this->paddles[0]->movingUp = false;
            $this->paddles[0]->movingDown = false;
        }

        if ($key == "up") {
            $this->paddles[1]->movingUp = false;
            $this->paddles[1]->movingDown = false;
        } elseif ($key == "down") {
            $this->paddles[1]->movingUp = false;
            $this->paddles[1]->movingDown = false;
        }
    }

    protected function win($winner) {
        // 0 = player 1
        // 1 = player 2
        $this->paddles[$winner]->points += 1;

        $this->ball->x = $this->ball->start_x;
        $this->ball->y = $this->ball->start_y;

        $this->ball->x_speed = -$this->ball->x_speed;

        $this->waitForNewRound = true;

        Game::play_sound("win");
    }
}

$game = new MyGame();
