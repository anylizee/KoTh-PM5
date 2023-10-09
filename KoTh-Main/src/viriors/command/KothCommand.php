<?php

namespace viriors\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

class KothCommand extends Command {
  /** @var KothSubCommand[] */
  private array $subCommands = [];
  
  /**
   * KothCommand Construct
   **/
   public function __construct() {
     parent::__construct("koth", TextFormat::RED."manage koths.");
     $this->setPermission('koth.command');
   }
   
   /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
      if ($sender instanceof Player) {
        if (!$sender->hasPermission('koth.manager')) {
          $sender->sendMessage(TextFormat::RED."Invalid Args #1!");
          return;
        }
        if ($sender->hasPermission('koth.manager')) {
        
        if (!isset($args[0])) {
          $sender->sendMessage(TextFormat::colorize("&l&4KOTH &fMANAGER&r\n&7Edit or create the arenas\n \n&e - /koth create (string:name)\n &e- /koth setpos\n&e - /koth delete (string:name)\n&e - /koth list\n&e - /koth start (string:arena)\n&e - /koth setspawn\n&e - /koth stop (string:arena)"));
          return;
          }
        }
      }
    }
}
?>