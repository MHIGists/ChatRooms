<?php


namespace MHIGists\ChatRooms;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class EventHandler implements Listener
{
    private $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }
    /**
     * @param PlayerChatEvent $event
     * @priority HIGH
     */
    public function chat_event(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $player_chat = $this->main->getPlayerChat($player);
        if ($this->main->getPlayerChat($player) == false) {
            $this->main->changePlayerChat($player, $this->main->config['default_chat_room']);
            $player_chat = $this->main->config['default_chat_room'];
        }
       $event->setRecipients($this->main->chat_rooms[$player_chat]);
        $prefix = str_replace('<playerChat>', $player_chat, $this->main->config['prefix']);

        if ($this->main->pure_chat !== null)
        {
            $pure_chat_format = $this->main->pure_chat->getChatFormat($player, $event->getMessage());
            $prefix .=  ' ' . $pure_chat_format;
            $event->setFormat($prefix);
            return;
        }
        $event->setFormat($prefix . ' ' . $player->getName() . ' >> ' . $event->getMessage());
    }
}