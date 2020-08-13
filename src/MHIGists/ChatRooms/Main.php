<?php

declare(strict_types=1);

namespace MHIGists\ChatRooms;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

    public $chat_rooms = [];
    public $config;
    public $pure_chat;

    public function onEnable()
    {
        $this->saveDefaultConfig();

        $this->pure_chat = $this->getServer()->getPluginManager()->getPlugin('PureChat');
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);

        $command = new ChatCommand($this);
        $this->getServer()->getCommandMap()->register('chatroom', $command);

        $this->get_config();
    }
    public function get_config() : void
    {
        $this->config = $this->getConfig()->getAll();
        $config =  $this->config['chatrooms'];
        foreach ($config as $chatroom) {
            $this->addChat($chatroom);
        }
        $this->check_duplicates();
    }
    public function addChat(string $chat_name) : void
    {
        $this->chat_rooms[$chat_name] = [];
        $this->chat_rooms[$chat_name][] = new ConsoleCommandSender();
    }
    public function deleteChat(string $chat_name) : void
    {
        unset($this->chat_rooms[$chat_name]);
        $this->getConfig()->setNested('chatrooms', $this->chat_rooms);
        $this->getConfig()->save();
    }
    public function check_duplicates() : void
    {
        foreach ($this->chat_rooms as $key => $chat_room) {
            $this->chat_rooms[$key] = array_unique($chat_room);
        }
    }


    /* @return false|string  */
    public function getPlayerChat(Player $player)
    {
        foreach ($this->chat_rooms as $key => $chat_room) {
                if (array_search($player, $chat_room)!= false)
                {
                    return $key;
                }
        }
        return false;
    }

    public function changePlayerChat(Player $player, string $chat_room_name) : bool
    {
        $this->removePlayer($player);
        if (array_key_exists($chat_room_name, $this->chat_rooms))
        {
            $this->chat_rooms[$chat_room_name][] = $player;
            return true;
        }
        else{
            return false;
        }
    }
    public function removePlayer(Player $player) : void
    {
        foreach ($this->chat_rooms as $key =>  $chat_room) {
            $temp = array_search($player, $chat_room);
            if ($temp != false)
            {
                unset($this->chat_rooms[$key][$temp]);
            }
        }
    }
    public function getRooms() : string
    {
        $list = TextFormat::GREEN . 'Available chat rooms: ' . TextFormat::EOL;
        foreach ($this->chat_rooms as $key => $value) {
            $list .= $key . ' | ' . (int)(count($value) - 1). ' players online.' . TextFormat::EOL;
        }
        return $list;
    }

    public function onDisable()
    {
        $chat_rooms = [];
        foreach ($this->chat_rooms as $key => $chat_room) {
            $chat_rooms[] = $key;
        }
        $this->getConfig()->setNested('chatrooms', $chat_rooms);
        $this->getConfig()->save();
    }
}
