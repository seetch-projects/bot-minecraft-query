<?php

require_once("vendor/autoload.php");

use DigitalStars\SimpleVK\LongPoll;
use jarne\querylibrary\QueryLibrary;

const TOKEN = "vk1.a.zoor9LmMwtSL_6kGopnVSygt_geT0z1SrJg36N7GoBUQm37HmieigSetEZxifuHl7znT5nFO8Ub62NYv8zZEl1fDjdvmu95-ec_87hEA3Cf4cy9mGGXi_U8LWomJMAxe3Cs63fkkR8UPpoKQb-RSLILhu6KBtQrfudIpOfoOO3B8-9WOlIOMkTFaweNSTcmuU8q4pozhLd5PrilPELmebQ";
const ID = 497129990;

$vk = LongPoll::create(TOKEN, '5.120');
$vk->setUserLogError(ID);

function clean(string $string, bool $removeFormat = true) : string{
	$string = preg_replace("/[\x{E000}-\x{F8FF}]/u", "", $string);
	if($removeFormat){
		$string = str_replace("\xc2\xa7", "", preg_replace("/" . "\xc2\xa7" . "[0-9a-gk-or]/u", "", $string));
	}
	return str_replace("\x1b", "", preg_replace("/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/u", "", $string));
}

$vk->listen(function() use ($vk){
	$data = $vk->initVars($peer_id, $user_id, $type, $message);

	if($type == "message_new"){
		if($peer_id >= 2000000000){
			return;
		}

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
	}
});