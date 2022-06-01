<?php

error_reporting(E_ERROR | E_PARSE);

require_once("vendor/autoload.php");

use DigitalStar\vk_api\vk_api;
use DigitalStar\vk_api\LongPoll;
use DigitalStar\vk_api\Execute;
use jarne\querylibrary\QueryLibrary;

$vk = vk_api::create("2300b0173d8d344a99298cb077ebcccd84d1482cb428884c1b5054488e9dc85faeb4bad599e217266b27a", "5.95");
$vk = new Execute($vk);
$vk = new LongPoll($vk);

$vk->listen(function($data) use ($vk){
	$vk->initVars($id, $message, $payload, $user_id, $type);

	$args = explode(" ", $message);
	$command = strtolower(array_shift($args));

	switch($command){
		case "java":
		case "je":
		case "j":
			if(!isset($args[0])){
				$vk->reply("❌ Укажите адрес сервера");
				return;
			}

			$address = $args[0];
			$port = 25565;

			if(isset($args[1])){
				$port = $args[1];
			}

			if(!is_numeric($port)){
				$vk->reply("❌ Порт должен быть числом");
				return;
			}

			$result = json_decode(file_get_contents("https://api.mcsrvstat.us/2/{$address}:{$port}"));

			if(!$result->debug->ping){
				$vk->reply("❌ Сервер недоступен или у него отключен Query");
				return;
			}

			$vk->reply(implode("\n", [
				"ℹ Адрес — {$result->ip}, порт — {$result->port}",
				"⭐ MOTD — " . $result->motd->clean[0],
				"⚔ Версия — {$result->version}",
				"👥 Игроки — {$result->players->online} из {$result->players->max}",
				"📄 Протокол — {$result->protocol}"
			]));
			break;
		case "bedrock":
		case "be":
		case "b":
			if(!isset($args[0])){
				$vk->reply("❌ Укажите адрес сервера");
				return;
			}

			$address = $args[0];
			$port = 19132;

			if(isset($args[1])){
				$port = $args[1];
			}

			if(!is_numeric($port)){
				$vk->reply("❌ Порт должен быть числом");
				return;
			}

			$queryLib = new QueryLibrary();
			$result = $queryLib->fetch($address, $port);

			if(!$result->isStatus()){
				$vk->reply("❌ Сервер недоступен или у него отключен Query");
				return;
			}

			$vk->reply(implode("\n", [
				"ℹ Адрес — " . gethostbyname($address) . ", порт — {$result->getPort()}",
				"⭐ MOTD — " . clean($result->getMotd()),
				"⚔ Версия — {$result->getVersion()}",
				"🏐 Ядро сервера — {$result->getServerEngine()}",
				"📄 Вайт-лист — " . ($result->isWhitelist() ? "включен" : "отключен"),
				"👥 Игроки — {$result->getOnlinePlayers()} из {$result->getMaxPlayers()}",
				"🗺 Карта (по умолчанию) — {$result->getDefaultLevelName()}",
				"💎 Плагины — " . ((empty($result->getPlugins()) ? "скрыты" : $result->getPlugins())),
				"",
				"🎭 Список игроков (может не совпадать с количеством, если это лобби) —",
				implode(", ", $names = $result->getPlayerNames()) . (count($names) > 0) ? " (" . count($names) . ")" : "отсутствуют"
			]));
			break;
		default:
			$vk->reply("🔎 Актуальная информация о состоянии сервера Minecraft\n\n🕷️ j <адрес> <порт: 25565> — ᴊᴀᴠᴀ ᴇᴅɪᴛɪᴏɴ\n🐍 b <адрес> <порт: 19132> — ʙᴇᴅʀᴏᴄᴋ ᴇᴅɪᴛɪᴏɴ");
	}
});

function clean(string $string, bool $removeFormat = true) : string{
	$string = preg_replace("/[\x{E000}-\x{F8FF}]/u", "", $string); //remove unicode private-use-area characters (they might break the console)
	if($removeFormat){
		$string = str_replace("\xc2\xa7", "", preg_replace("/" . "\xc2\xa7" . "[0-9a-gk-or]/u", "", $string));
	}
	return str_replace("\x1b", "", preg_replace("/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/u", "", $string));
}