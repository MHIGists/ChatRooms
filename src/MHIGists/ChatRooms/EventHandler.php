<?php


namespace MHIGists\ChatRooms;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

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
        $event->setRecipients($this->main->chat_rooms[$player_chat]);
        $prefix = str_replace('<playerChat>', $player_chat, $this->main->config['prefix']);

        if ($this->main->pure_chat !== null) {
            $pure_chat_format = $this->main->pure_chat->getChatFormat($player, $event->getMessage());
            $prefix .= ' ' . $pure_chat_format;
            $event->setFormat($prefix);
            return;
        }
        $event->setFormat($prefix . ' ' . $player->getName() . ' >> ' . $event->getMessage());
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $this->main->changePlayerChat($event->getPlayer(), $this->main->config['default_chat_room']);
    }

    public function onLeave(PlayerQuitEvent $event)
    {
        $this->main->removePlayer($event->getPlayer());
    }
}