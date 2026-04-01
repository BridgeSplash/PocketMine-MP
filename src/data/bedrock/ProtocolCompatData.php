<?php

declare(strict_types=1);

namespace pocketmine\data\bedrock;

use pocketmine\network\mcpe\ProtocolInfoHelper;
use function is_file;
use const pocketmine\RESOURCE_PATH;

final class ProtocolCompatData{
	private const BASE_PATH = RESOURCE_PATH . "bedrock-data-compat/";

	private function __construct(){
		//NOOP
	}

	public static function hasSupport(int $protocolId) : bool{
		$canonicalBlockStatesPath = self::getCanonicalBlockStatesPath($protocolId);
		$blockStateMetaMapPath = self::getBlockStateMetaMapPath($protocolId);
		$requiredItemListPath = self::getRequiredItemListPath($protocolId);
		$itemTagsPath = self::getItemTagsPath($protocolId);

		return $canonicalBlockStatesPath !== null && is_file($canonicalBlockStatesPath)
			&& $blockStateMetaMapPath !== null && is_file($blockStateMetaMapPath)
			&& $requiredItemListPath !== null && is_file($requiredItemListPath)
			&& $itemTagsPath !== null && is_file($itemTagsPath);
	}

	public static function getCanonicalBlockStatesPath(int $protocolId) : ?string{
		return match($protocolId){
			ProtocolInfoHelper::PROTOCOL_1_19_80 => self::BASE_PATH . "canonical_block_states-1.19.80.nbt",
			default => null,
		};
	}

	public static function getBlockStateMetaMapPath(int $protocolId) : ?string{
		return match($protocolId){
			ProtocolInfoHelper::PROTOCOL_1_19_80 => self::BASE_PATH . "block_state_meta_map-1.19.80.json",
			default => null,
		};
	}

	public static function getRequiredItemListPath(int $protocolId) : ?string{
		return match($protocolId){
			ProtocolInfoHelper::PROTOCOL_1_19_80 => self::BASE_PATH . "required_item_list-1.19.80.json",
			default => null,
		};
	}

	public static function getItemTagsPath(int $protocolId) : ?string{
		return match($protocolId){
			ProtocolInfoHelper::PROTOCOL_1_19_80 => self::BASE_PATH . "item_tags-1.19.80.json",
			default => null,
		};
	}
}
