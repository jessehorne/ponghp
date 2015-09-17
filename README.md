Billy
=====

Billy is a 2D game framework written in PHP and Lua. It allows you to write 2D
games in pure PHP. It works by sending data to a client written in Lua, which
will handle things such as displaying graphics, playing sounds, or printing text
to the screen.

Please note that while this works, it can be buggy in some cases, it isn't
very efficient, and has yet to implement the entire [LÖVE](https://love2d.org/)
API. This was a project to help me learn PHP. So bare with me, and if you have
issues, please create an issue using the issue tracker.

## Prerequisites
1. [LÖVE](https://love2d.org/) 0.9.*
2. [PHP](http://php.net/) 5.5

## Running your game
I included [RunGame.sh]() to make this process simpler. The script itself is
simple, but it's better than having to type the commands each time you want to
run your game.

Be sure to set up your paths correctly.

#### Using RunGame.sh
```
./RunGame.sh run
```
#### Not using RunGame.sh
```
php app.php
love ./
```

## Learn Billy
I haven't written any documentation because of how much has yet to be
implemented in the framework. For now, just check out the game examples in
the [Show me some games](## Show me some games) section.

## Show me some games
[PongHP]()

## IRC (#billy-php on Freenode)
If you're having issues you don't feel belong in the issue tracker, or just want
to chat, feel free to join #billy on Freenode. Yes, it has it's own channel. I
have faith that I am not the only person who wants to create games using PHP. ;)
