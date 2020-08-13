<?php


namespace MHIGists\ChatRooms;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;


class ChatCommand extends Command implements PluginIdentifiableCommand
{
    private $main;

    public function __construct(Main $main)
    {
        parent::__construct('chatroom', 'Changes your chat room', 'chat <chatName>');
        $this->setPermission('chatroom.command');
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!empty($args)) {
                switch ($args[0]) {
                    case 'list':
                        $sender->sendMessage($this->main->getRooms());
                        break;
                    default:
                        if ($this->main->changePlayerChat($sender, $args[0])) {
                            $sender->sendMessage('You are now in ' . $args[0]);
                        } else {
                            $sender->sendMessage('No such chat room.');
                        }
                }
            } else {
                $sender->sendMessage('Please specify at least 1 argument');
                $sender->sendMessage($this->getUsage());
            }
        }
        if ($sender instanceof ConsoleCommandSender) {
            if (!empty($args)) {
                switch ($args[0]) {
                    case 'list':
                        $sender->sendMessage($this->main->getRooms());
                        break;
                    case 'create':
                        if (array_key_exists(1, $args)) {
                            $this->main->addChat($args[1]);
                            $sender->sendMessage("Chat " . $args[1] . " has been created!");
                        }else{
                            $sender->sendMessage("To delete a chat room use: chatroom create <chatRoomName>");
                        }
                        break;
                    case 'delete':
                        if (array_key_exists(1, $args)) {
                            unset($this->main->chat_rooms[$args[1]]);
                            $sender->sendMessage('Chat ' . $args[1] . ' has been deleted!');
                        } else {
                            $sender->sendMessage('To delete a chat room use: chatroom delete <chatRoomName>');
                        }
                        break;
                    default:
                        $sender->sendMessage('You cant change chats you see them all');
                }

            }
        }

    }

    public function getPlugin(): Plugin
    {
        return $this->main;
    }
}