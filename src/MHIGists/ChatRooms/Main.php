<?php

declare(strict_types=1);

namespace MHIGists\ChatRooms;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public $chat_rooms = [];
    public $pure_chat;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->pure_chat = $this->getServer()->getPluginManager()->getPlugin('PureChat');
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
    }
    public function getPlayerChat(string $player_name)
    {
        foreach ($this->chat_rooms as $key => $chat_room) {
            $temp = array_search($player_name, $this->chat_rooms);
            if ($temp != false)
            {
                return $key;
            }
        }
    }

    public function changePlayerChat(Player $player, string $chat_room)
    {
        foreach ($this->chat_rooms as $key =>  $chat_room) {
            $temp = array_search($player->getName(), $this->chat_rooms);
            if ($temp != false)
            {
                unset($this->chat_rooms[$key][$temp]);
            }
        }
        if (array_key_exists($chat_room, $this->chat_rooms))
        {
            $this->chat_rooms[$chat_room][] = $player;
        }
        else{
            return false;
        }
    }
    public function getPrefix(string $chat_room)
    {
        $prefix = $this->getConfig()->getNested('prefix');
        if ($prefix == null || empty($prefix))
        {
            $prefix = "[$chat_room]";
        }
        return $prefix;
    }
}
