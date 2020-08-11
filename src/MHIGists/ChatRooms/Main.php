<?php

declare(strict_types=1);

namespace MHIGists\ChatRooms;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

    public $chat_rooms = [
        'global' => []
    ];
    public $pure_chat;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->pure_chat = $this->getServer()->getPluginManager()->getPlugin('PureChat');
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $command = new ChatCommand($this);
        $this->getServer()->getCommandMap()->register('chat', $command);
        $this->chat_rooms['global'][] = new ConsoleCommandSender();
    }
    public function getPlayerChat(Player $player)
    {
        foreach ($this->chat_rooms as $key => $chat_room) {

                $temp = array_search($player, $chat_room);
                if ($temp != false)
                {
                    return $key;
                }

        }
        return false;
    }

    public function changePlayerChat(Player $player, string $chat_room_name)
    {
        foreach ($this->chat_rooms as $key =>  $chat_room) {
            $temp = array_search($player, $chat_room);
            if ($temp != false)
            {
                unset($this->chat_rooms[$key][$temp]);
            }
        }
        if (array_key_exists($chat_room_name, $this->chat_rooms))
        {
            $this->chat_rooms[$chat_room_name][] = $player;
            return true;
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
            $prefix = TextFormat::RED . "[$chat_room]";
        }
        return $prefix;
    }
    public function getRooms() : string
    {
        return implode(" ,", array_keys($this->chat_rooms));
    }
}
