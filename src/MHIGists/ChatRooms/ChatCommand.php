<?php


namespace MHIGists\ChatRooms;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;


class ChatCommand extends Command
{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct('chat', 'Changes your chat room', 'chat <chatName>');
        $this->setPermission('chatrooms.command');
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player && $sender->hasPermission($this->getPermission()))
        {
            if (!empty($args))
            {
                if ($this->main->changePlayerChat($sender, $args[0]))
                {
                    $this->main->chat_rooms[$args[0]][] = $sender;
                }
            }else
            {
                $sender->sendMessage('No such chat room.');
            }

        }
    }
}