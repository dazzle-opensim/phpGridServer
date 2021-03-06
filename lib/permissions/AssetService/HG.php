<?php
/******************************************************************************
 * phpGridServer
 *
 * GNU LESSER GENERAL PUBLIC LICENSE
 * Version 2.1, February 1999
 *
 */

require_once("lib/interfaces/AssetServiceInterface.php");
require_once("lib/services.php");
class HGAssetServiceInterface implements AssetServiceInterface
{
	private $service;
	public function __construct($service)
	{
		$this->service = getService($service);
	}
	public function get($assetID)
	{
		return $this->service->get($assetID);
	}

	public function getMetadata($assetID)
	{
		return $this->service->getMetadata($assetID);
	}

	public function getData($assetID)
	{
		return $this->service->getData($assetID);
	}

	public function store($asset, $overwriteAlways = False)
	{
		return $this->service->store($asset, False);
	}

	public function delete($assetID)
	{
		throw new AssetPermissionsInsufficientException();
	}

	public function exists($assetID)
	{
		return $this->service->exists($assetID);
	}

	public function existsMultiple($assetIDsHash)
	{
		return $this->service->existsMultiple($assetIDsHash);
	}
}

return new HGAssetServiceInterface($_SERVICE_PARAMS["service"]);
