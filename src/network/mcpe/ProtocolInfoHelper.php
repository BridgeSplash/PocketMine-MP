<?php

declare(strict_types=1);

namespace pocketmine\network\mcpe;

use pocketmine\data\bedrock\ProtocolCompatData;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use function array_unshift;
use function count;
use function implode;
use function in_array;
use function is_int;
use function str_replace;
use function str_starts_with;
use function substr;

final class ProtocolInfoHelper{
	public const PROTOCOL_1_19_80 = 582;

	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private const LEGACY_PROTOCOL_VERSIONS = [
		self::PROTOCOL_1_19_80 => "1.19.80",
	];

	private function __construct(){
		//NOOP
	}

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public static function getAcceptedProtocols() : array{
		$accepted = ProtocolInfo::ACCEPTED_PROTOCOL;
		if(ProtocolCompatData::hasSupport(self::PROTOCOL_1_19_80)){
			array_unshift($accepted, self::PROTOCOL_1_19_80);
		}

		return $accepted;
	}

	public static function isAcceptedProtocol(int $protocolVersion) : bool{
		return in_array($protocolVersion, self::getAcceptedProtocols(), true);
	}

	public static function getMinecraftVersionFromProtocolId(int $protocolId) : string{
		return self::getProtocolVersionMap()[$protocolId] ?? (string) $protocolId;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public static function getAcceptedMinecraftVersions() : array{
		$versions = [];
		foreach(self::getAcceptedProtocols() as $protocolId){
			$versions[] = self::getMinecraftVersionFromProtocolId($protocolId);
		}

		return $versions;
	}

	public static function getAcceptedMinecraftVersionRange() : string{
		$versions = self::getAcceptedMinecraftVersions();
		if($versions === []){
			return ProtocolInfo::MINECRAFT_VERSION_NETWORK;
		}

		$first = $versions[0];
		$last = $versions[count($versions) - 1];

		return $first === $last ? $first : $first . " -> " . $last;
	}

	public static function getAcceptedMinecraftVersionsList() : string{
		return implode(", ", self::getAcceptedMinecraftVersions());
	}

	/**
	 * @return string[]
	 * @phpstan-return array<int, string>
	 */
	private static function getProtocolVersionMap() : array{
		static $map = null;
		if($map !== null){
			return $map;
		}

		$map = self::LEGACY_PROTOCOL_VERSIONS;

		foreach((new \ReflectionClass(ProtocolInfo::class))->getConstants() as $name => $value){
			if(!is_int($value) || !str_starts_with($name, "PROTOCOL_")){
				continue;
			}

			$map[$value] = str_replace("_", ".", substr($name, 9));
		}

		return $map;
	}
}
