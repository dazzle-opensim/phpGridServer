<?php
/******************************************************************************
 * phpGridServer
 *
 * GNU LESSER GENERAL PUBLIC LICENSE
 * Version 2.1, February 1999
 *
 */

require_once("lib/services.php");
require_once("lib/helpers/fetchInventory.php");

if($_SERVER["REQUEST_METHOD"] != "POST")
{
	http_response_code("405");
	exit;
}

$map = $_RPC_REQUEST->Params[0];

$pathcmps = explode("/", $_RPC_REQUEST->Method);
if(count($pathcmps) == 3 && $pathcmps[2] == "")
{
	/* this is a valid path too */
}
else if(count($pathcmps) != 2)
{
	http_response_code("400");
	header("Content-Type: text/plain");
	echo "Invalid path ".count($pathcmps);
	exit;
}

$sessionID = $pathcmps[1];
try
{
	$travelingdata = getHGTravelingData($sessionID);
}
catch(Exception $e)
{
	http_response_code("404");
	trigger_error("Invalid session $sessionID");
	exit;
}

if($travelingdata->ClientIPAddress != getRemoteIpAddr())
{
    /* same response as before intentionally */
	http_response_code("404");
	trigger_error("Invalid session $sessionID");
	exit;
}

$map = $_RPC_REQUEST->Params[0];

$res = new RPCSuccessResponse();
$outmap = new RPCStruct();
$res->Params[] = $outmap;

$inventoryService = getService("InventoryService");

if(!$inventoryService->isFolderOwnedByUUID($map->parent_id, $services->AgentID))
{
}
else
{
	try
	{
		$folder = new InventoryFolder();
		$folder->ID = $map->folder_id;
		$folder->OwnerID = $services->AgentID;
		$folder->Name = $map->name;
		$folder->Version = 1;
		$folder->Type = intval($map->type);
		$folder->ParentFolderID = $map->parent_id;

		$inventoryService->addFolder($folder);
		$outmap->folder_id = $map["folder_id"];
		$outmap->parent_id = $map["parent_id"];
		$outmap->type = $map["type"];
		$outmap->name = $map["name"];
	}
	catch(Exception $e)
	{
	}
}

return $res;
