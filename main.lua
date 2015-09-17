local json = require("cjson")
local socket = require("socket")

local host, port = "*", 5454
local udp = assert(socket.udp())
udp:settimeout(100)
udp:setsockname(host, port)

print("Started Bobby on port " .. port .. "!")

local queues = {}
local fonts = {}
local sounds = {}

local time = 0
local frame = 0

local current = 0

local shouldClear = false

local protocol = {}
protocol["rectangle"] = 1
protocol["setColor"] = 2
protocol["setTitle"] = 3
protocol["incoming"] = 4
protocol["config"] = 5
protocol["keyPressed"] = 6
protocol["keyReleased"] = 7
protocol["quit"] = 8
protocol["print"] = 9
protocol["newFont"] = 10
protocol["setFont"] = 11
protocol["newSound"] = 12
protocol["playSound"] = 13

function send(data)
	data = json.encode(data)
	udp:sendto(data, "127.0.0.1", 5555)
end

function love.run()

	if love.math then
		love.math.setRandomSeed(os.time())
		for i=1,3 do love.math.random() end
	end

	if love.event then
		love.event.pump()
	end

	if love.load then love.load(arg) end

	-- We don't want the first frame's dt to include time taken by love.load.
	if love.timer then love.timer.step() end

	local dt = 0

	-- Main loop time.
	while true do
		-- Process events.
		if love.event then
			love.event.pump()
			for e,a,b,c,d in love.event.poll() do
				if e == "quit" then
					if not love.quit or not love.quit() then
						if love.audio then
							love.audio.stop()
						end
						return
					end
				end
				love.handlers[e](a,b,c,d)
			end
		end

		-- Update dt, as we'll be passing it to update
		if love.timer then
			love.timer.step()
			dt = love.timer.getDelta()
		end

		-- Call update and draw
		if love.update then love.update(dt) end -- will pass 0 if love.timer is disabled

		if love.window and love.graphics and love.window.isCreated() then
			-- love.graphics.clear()
			love.graphics.origin()
			if love.draw then love.draw() end
			love.graphics.present()
		end

		if love.timer then love.timer.sleep(0.001) end
	end

end

function love.update(dt)
    time = time + dt
end

local counter = 0

function love.draw()
    --print(json.encode(queues))

    local data, ip = udp:receive()

    if data then
        data = json.decode(data)
        if data.c == protocol["incoming"] then
            for i=1, data.a do
                data, ip = udp:receive()

                if data then
                    data = json.decode(data)
                    current = data.f

                    table.insert(queues, data)

                    if oldFrame ~= current then
                        love.graphics.clear()

                        for i,v in ipairs(queues) do
                            for x,y in ipairs(v.q) do
                                if y.c == protocol["rectangle"] then
                                    love.graphics.rectangle(unpack(y.a))
                                elseif y.c == protocol["setColor"] then
                                    love.graphics.setColor(unpack(y.a))
                                elseif y.c == protocol["setTitle"] then
                                    love.window.setTitle(y.a)
								elseif y.c == protocol["print"] then
									love.graphics.print(unpack(y.a))
								elseif y.c == protocol["newFont"] then
									fonts[y.a[1]] = love.graphics.newFont(y.a[2], y.a[3])
								elseif y.c == protocol["setFont"] then
									love.graphics.setFont(fonts[y.a])
								elseif y.c == protocol["newSound"] then
									sounds[y.a[1]] = love.audio.newSource(y.a[2], y.a[3])
								elseif y.c == protocol["playSound"] then
									sounds[y.a]:play()
                                end
                            end
                        end
                        queues = {}
                    end

                    oldFrame = current
                end
            end
        end
        if data.c == protocol["config"] then
            if data.a.title then
                love.window.setTitle(data.a.title)
            end

            if data.a.width and data.a.height then
                love.window.setMode(data.a.width, data.a.height)
            end
        end
    end

    -- love.graphics.print(love.timer.getFPS(), 10, 10)

end

function love.keypressed(key)
	send({e=protocol["keyPressed"],k=key})
end

function love.keyreleased(key)
	send({e=protocol["keyReleased"],k=key})
end
