<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Repositories\Message_Type_WaRepository;
use App\Repositories\Configs_RsRepository;

class WhatsappHelper
{

	public static function sendWaPerjanjian($data)
	{
		$baseUrl 	= self::getUrl();
		$url 		= $baseUrl . "api/send-message";

		$message 	= Message_Type_WaRepository::getMessageByType(9);

		$parsedMessage = str_replace('{VPASIEN}', $data->nama_pasien, $message);
		$parsedMessage = str_replace('{VDOKTER}', $data->nama_dokter, $parsedMessage);
		$parsedMessage = str_replace('{VTGL_JANJI}', $data->tgl_janji, $parsedMessage);
		$parsedMessage = str_replace('{VRM}', $data->rm, $parsedMessage);
		$parsedMessage = str_replace('{VBOOKING}', $data->kode_booking, $parsedMessage);
		$parsedMessage = str_replace('{VANTRIAN}', $data->no_urut, $parsedMessage);

		$payload = [
			'phone' 	=> $data->phone,
	        'message' 	=> $parsedMessage,
		];

		$result = self::sendRequest($url, $payload);
	}

	public static function sendWaPasienBaru($data)
	{
		$baseUrl 	= self::getUrl();
		$url 		= $baseUrl . "api/send-message";

		$message 	= Message_Type_WaRepository::getMessageByType(8);

		$parsedMessage = str_replace('{VRM}', $data->rm, $message);

		$payload = [
			'phone' 	=> $data->phone,
	        'message' 	=> $parsedMessage,
		];

		$result = self::sendRequest($url, $payload);
	}

	private static function getUrl()
	{
		return Configs_RsRepository::findByName('url_wablas')->data;
	}

	private static function getToken()
	{
		return Configs_RsRepository::findByName('token_wablas')->data;
	}

	private static function sendRequest($url, $payload)
	{
		$headers = [
			'Authorization' => self::getToken()
		];

		return Http::withHeaders($headers)
				->withOptions([
    				'verify' => false,
				])
				->post($url, $payload);
	}
}