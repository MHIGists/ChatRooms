<?php


namespace MHIGists\ChatRooms;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;


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
                switch ($args[0])
                {
                    case 'create':
                        if (array_key_exists(1, $args))
                        {
                            if (!$sender->hasPermission('chatrooms.command.create'))
                            {
                                $sender->sendMessage(TextFormat::RED . ' You dont have permission to add chat rooms!');
                            }else{
                                $this->main->chat_rooms[$args[1]] = [];
                                $this->main->chat_rooms[$args[1]][] = new ConsoleCommandSender();
                                $sender->sendMessage("Chat " . $args[1] . " has been created!");
                            }
                        }else
                        {
                            $sender->sendMessage('To create a room use /chat create <chatRoomName>');
                        }

                        break;
                    case 'delete':
                        if (array_key_exists(1, $args))
                        {
                            if (!$sender->hasPermission('chatrooms.command.delete'))
                            {
                                $sender->sendMessage(TextFormat::RED . ' You dont have permission to add chat rooms!');

                            }else{
                                unset($this->main->chat_rooms[$args[1]]);
                                $sender->sendMessage('Chat ' . $args[1] . ' has been deleted!');
                            }
                        }else
                            {
                            $sender->sendMessage('To delete a chat room use /chat delete <chatRoomName>');
                        }
                        break;
                    case 'list':
                        $sender->sendMessage($this->main->getRooms());
                        break;
                    default:
                        if ($this->main->changePlayerChat($sender, $args[0]))
                        {
                            $sender->sendMessage('You are now in ' . $args[0]);
                        }else{
                            $sender->sendMessage('No such chat room.');
                        }
                }

            }else
            {
                $sender->sendMessage('Please specify at least 1 argument');
                $sender->sendMessage($this->getUsage());
            }

        }
    }
}