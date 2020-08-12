<?php
/**
 * Cloudflare DNS API
 *
 * Simple create dns records over api cloudflare
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, Mexious Media
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CloudflareDNSAPI
 * @author	Mexious Media
 * @copyright	Copyright (c) 2019, Mexious Media. (https://mexious.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://mexious.com/
 * @since	Version 1.0.0
 * @filesource
 */
class CloudflareDNSAPI{
    protected $ZoneID;
		public function __construct($ZoneID) {
			$this->zone_id  = $ZoneID;
			$this->url      = 'https://api.cloudflare.com/client/v4/';
			$this->email    = 'EMAIL_CLOUDFLARE_HERE';
			$this->auth_key = 'AUTH_KEY_CLOUDFLARE_HERE';
		}
		
    public function addARecods($domain, $content){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url."zones/".$this->zone_id."/dns_records/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $headers = [ 'X-Auth-Email:'.$this->email, 'X-Auth-Key:'.$this->auth_key, 'Content-Type: application/json'];
        $data = array( 'type' => 'A' , 'name' => $domain, 'content' => $content, 'TTL' => 1, 'priority' => 10 );
        $data_string = json_encode($data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $result = curl_exec($ch);
        $newarray = json_decode(trim($result), TRUE);
        $id_api = $newarray["result"]["id"];
        if($id_api == NULL){
            $error = array("result" => "error", "message" => $newarray["errors"][0]["message"]);
            $json = json_encode($error);
            return $json;
            header('Content-Type: application/json');
        } else {
            $success_result = array('result'  => 'success','message' => 'DNS Records was successfuly created','data' => array('id'=> $id_api, 'subdomain' => $newarray["result"]["name"], 'ip_address' => $ip ),);
            $json = json_encode($success_result, JSON_PRETTY_PRINT);
            return $json;
            header('Content-Type: application/json');
        }
    }
	
    public function addCNAMERecods($domain, $content){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url."zones/".$this->zone_id."/dns_records/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $headers = [ 'X-Auth-Email:'.$this->email, 'X-Auth-Key:'.$this->auth_key, 'Content-Type: application/json'];
        $data = array( 'type' => 'CNAME' , 'name' => $domain, 'content' => $ip, 'TTL' => 1, 'priority' => 10 );
        $data_string = json_encode($data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $result = curl_exec($ch);
        $newarray = json_decode(trim($result), TRUE);
        $id_api = $newarray["result"]["id"];
        if($id_api == NULL){
            $error = array("result" => "error", "message" => $newarray["errors"][0]["message"]);
            $json = json_encode($error);
            return $json;
            header('Content-Type: application/json');
        } else {
            $success_result = array('result'  => 'success','message' => 'DNS Records was successfuly created','data' => array('id'=> $id_api, 'subdomain' => $newarray["result"]["name"], 'content' => $content ),);
            $json = json_encode($success_result, JSON_PRETTY_PRINT);
            return $json;
            header('Content-Type: application/json');
        }
    }

}
?>
